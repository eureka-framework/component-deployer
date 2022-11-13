<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Deployer\Common;

use Eureka\Component\Console\Argument\Argument;
use Eureka\Component\Console\Help;
use Eureka\Component\Deployer\Enumerator\Platform;
use Eureka\Component\Console\AbstractScript;
use Eureka\Component\Console\IO\Out;
use Eureka\Component\Console\Style\Color;
use Eureka\Component\Console\Style\Style;

/**
 * Class AbstractCommonScript
 *
 * @author Romain Cottard
 * @codeCoverageIgnore
 */
abstract class AbstractCommonScript extends AbstractScript
{
    /** @var array $config */
    protected array $config;

    protected string $rootDir;
    protected float $time;
    private string $appPlatform;
    private string $appTag;
    private string $appName;
    private string $appDomain;
    private PathBuilder $pathBuilder;

    public function setRootDir(string $rootDir): self
    {
        $this->rootDir = $rootDir;

        return $this;
    }

    /**
     * @param array $config
     * @return $this
     */
    public function setConfig(array $config): self
    {
        $this->config = $config;

        if (!isset($this->config['install'])) {
            throw new \RuntimeException('Invalid installer configuration file');
        }

        return $this;
    }

    public function setPathBuilder(PathBuilder $pathBuilder): self
    {
        $this->pathBuilder = $pathBuilder;

        return $this;
    }

    public function before(): void
    {
        parent::before();

        $arguments = Argument::getInstance();

        //~ Add color & hide base header/footer script display
        $arguments
            ->add('script-no-header', true)
            ->add('color', true)
        ;

        //~ Init installer main vars
        $this->appPlatform = (string) $arguments->get('platform', 'p', Platform::PROD);
        $this->appTag      = (string) $arguments->get('tag', 't', $this->config['app.tag'] ?? '1.0.0');
        $this->appName     = (string) $arguments->get('name', 'n', $this->config['app.name'] ?? '');
        $this->appDomain   = (string) $arguments->get('domain', 'd', $this->config['app.domain'] ?? '');

        //~ Display Step title
        if ($arguments->has('step')) {
            $this->displayStep(
                (string) $arguments->get('step'),
                $this->getDescription()
            );
        }
    }

    /**
     * Display help.
     *
     * @return void
     */
    public function help(): void
    {
        (new Help(static::class))
            ->addArgument('p', 'platform', 'Platform where installation is executed (default from config)', true)
            ->addArgument('t', 'tag', 'Tag version to install (default from config)', true)
            ->addArgument('d', 'domain', 'Application domain (ie: www.my-app.com) (default from config)', true)
            ->addArgument('n', 'name', 'Application name, used to retrieve config (default from config)', true)
            ->display()
        ;
    }

    protected function getPathBuilder(): PathBuilder
    {
        return $this->pathBuilder;
    }

    protected function getAppDomain(): string
    {
        return $this->appDomain;
    }

    protected function getAppPlatform(): string
    {
        return $this->appPlatform;
    }

    protected function getAppName(): string
    {
        return $this->appName;
    }

    protected function getAppTag(): string
    {
        return $this->appTag;
    }

    protected function startTimer(): void
    {
        $this->time = -microtime(true);
    }

    protected function displayHeader(string $title): void
    {
        $text = (string) (new Style($title))
            ->colorBackground(Color::BLUE)
        ;

        Out::std($text);
    }

    protected function displayInfo(string $text): void
    {
        Out::std(' ' . $text, '');
    }

    protected function displayInfoDone(): void
    {
        Out::std((string) (new Style(' done!'))->colorForeground(Color::GREEN)->highlightForeground());
    }

    protected function displayInfoFailed(): void
    {
        Out::std((string) (new Style(' failed!'))->colorForeground(Color::RED)->highlightForeground());
    }

    protected function displayStep(string $step, string $title): void
    {
        $text = PHP_EOL . (new Style(" $step "))
            ->bold()
            ->colorBackground(Color::BLACK)
            ->highlightBackground()
        ;

        $text .= (new Style(" $title "))
            ->colorBackground(Color::CYAN)
            ->highlightForeground()
        ;

        Out::std($text, PHP_EOL . PHP_EOL);
    }

    protected function displaySuccess(string $title): void
    {
        $status = (string) (new Style('[OK]'))
            ->colorForeground(Color::GREEN)
            ->highlightForeground()
        ;

        $text = (string) (new Style($title))
            ->colorForeground(Color::CYAN)
        ;

        $time = (string) $this->getTime();

        Out::std(" ✓ $status $text in {$time}s", PHP_EOL . PHP_EOL);
    }

    protected function displayError(string $title): void
    {
        $status = (string) (new Style('[ERROR]'))
            ->colorForeground(Color::RED)
            ->highlightForeground()
        ;

        $text = (string) (new Style($title))
            ->colorForeground(Color::CYAN)
        ;

        $time = (string) $this->getTime();

        Out::std(" ✗ $status $text in {$time}s", PHP_EOL . PHP_EOL);
    }

    /**
     * @param string $message
     * @param int $code
     * @return never
     */
    protected function throw(string $message, int $code = 1): void
    {
        $text = (string) (new Style('  > ' . $message))
            ->colorForeground(Color::RED)
            ->bold()
        ;

        Out::std($text, PHP_EOL . PHP_EOL);

        throw new \RuntimeException($message, $code);
    }

    protected function chdirSource(): void
    {
        $pathSource = $this->getRootDirSource();

        if (!chdir($pathSource)) {
            $this->throw('Cannot change directory to "' . $pathSource . '"');
        }
    }

    protected function getRootDirSource(bool $forceAppendTag = true): string
    {
        return $this->pathBuilder->buildPathSource(
            $this->getAppPlatform(),
            $this->getAppName(),
            $this->getAppDomain(),
            $this->getAppTag(),
            $forceAppendTag
        );
    }

    private function getTime(): float
    {
        return round($this->time + microtime(true), 1);
    }
}
