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
    /** @var string $rootDir */
    protected string $rootDir;

    /** @var array $config */
    protected array $config;

    /** @var float $timer */
    protected float $time;

    /** @var string $appPlatform */
    private string $appPlatform;

    /** @var string $appTag */
    private string $appTag;

    /** @var string|null $appName */
    private ?string $appName;

    /** @var string|null $appDomain */
    private ?string $appDomain;

    /** @var PathBuilder $pathBuilder */
    private PathBuilder $pathBuilder;

    /**
     * @param PathBuilder $pathBuilder
     * @return $this
     */
    public function setPathBuilder(PathBuilder $pathBuilder): self
    {
        $this->pathBuilder = $pathBuilder;

        return $this;
    }

    /**
     * @return void
     */
    public function before(): void
    {
        parent::before();

        $arguments = Argument::getInstance();

        //~ Load configuration
        $this->loadConfiguration();

        //~ Add color & hide base header/footer script display
        $arguments
            ->add('script-no-header', true)
            ->add('color', true)
        ;

        //~ Init installer main vars
        $this->appPlatform = (string) $arguments->get('platform', 'p', Platform::PROD);
        $this->appTag      = (string) $arguments->get('tag', 't', $this->config['app.tag'] ?? '1.0.0');
        $this->appName     = $arguments->get('name', 'n', $this->config['app.name'] ?? null);
        $this->appDomain   = $arguments->get('domain', 'd', $this->config['app.domain'] ?? null);

        //~ Display Step title
        if ($arguments->has('step')) {
            $this->displayStep(
                $arguments->get('step'),
                $this->getDescription()
            );
        }

        $this->rootDir = $this->getContainer()->getParameter('kernel.directory.root');
    }

    /**
     * Display help.
     *
     * @return void
     */
    public function help(): void
    {
        (new Help(static::class))
            ->addArgument('p', 'platform', 'Platform where installation is executed (default from config)', true, false)
            ->addArgument('t', 'tag', 'Tag version to install (default from config)', true, false)
            ->addArgument('d', 'domain', 'Application domain (ie: www.my-app.com) (default from config)', true, false)
            ->addArgument('n', 'name', 'Application name, used to retrieve config (default from config)', true, false)
            ->display()
        ;
    }

    /**
     * @return PathBuilder
     */
    protected function getPathBuilder(): PathBuilder
    {
        return $this->pathBuilder;
    }

    /**
     * @return string
     */
    protected function getAppDomain(): string
    {
        return $this->appDomain;
    }

    /**
     * @return string
     */
    protected function getAppPlatform(): string
    {
        return $this->appPlatform;
    }

    /**
     * @return string
     */
    protected function getAppName(): string
    {
        return $this->appName;
    }

    /**
     * @return string
     */
    protected function getAppTag(): string
    {
        return $this->appTag;
    }

    /**
     * AbstractCommonScript constructor.
     */
    protected function startTimer()
    {
        $this->time = -microtime(true);
    }

    /**
     * @return void
     */
    protected function loadConfiguration(): void
    {
        $this->config = $this->getContainer()->getParameter('eureka.deployer.config');

        if (!isset($this->config['install'])) {
            throw new \RuntimeException('Invalid installer configuration file');
        }
    }

    /**
     * @param string $title
     * @return void
     */
    protected function displayHeader(string $title): void
    {
        $text = (string) (new Style($title))
            ->colorBackground(Color::BLUE)
            ->colorForeground(Color::WHITE)
            //->bold()
        ;

        Out::std((string) $text, PHP_EOL);
    }

    /**
     * @param string $text
     * @return void
     */
    protected function displayInfo(string $text): void
    {
        Out::std(' ' . $text, '');
    }

    /**
     * @return void
     */
    protected function displayInfoDone(): void
    {
        Out::std((string) (new Style(' done!'))->colorForeground(Color::GREEN)->highlightForeground());
    }

    /**
     * @return void
     */
    protected function displayInfoFailed(): void
    {
        Out::std((string) (new Style(' failed!'))->colorForeground(Color::RED)->highlightForeground());
    }

    /**
     * @param string $step
     * @param string $title
     * @return void
     */
    protected function displayStep(string $step, string $title): void
    {
        $text = PHP_EOL . (string) (new Style(" $step "))
            ->colorForeground(Color::WHITE)
            ->bold()
            ->colorBackground(Color::BLACK)
            ->highlightBackground()
        ;

        $text .= (string) (new Style(" $title "))
            ->colorBackground(Color::CYAN)
            ->colorForeground(Color::WHITE)
            ->highlightForeground()
        ;

        Out::std($text, PHP_EOL . PHP_EOL);
    }

    /**
     * @param string $title
     * @return void
     */
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

        Out::std(" ✓ ${status} ${text} in ${time}s", PHP_EOL . PHP_EOL);
    }

    /**
     * @param string $title
     * @return void
     */
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

        Out::std(" ✗ ${status} ${text} in ${time}s", PHP_EOL . PHP_EOL);
    }

    /**
     * @param string $message
     * @param int $code
     * @return void
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

    /**
     * @return void
     */
    protected function chdirSource(): void
    {
        $pathSource = $this->getRootDirSource();

        if (!chdir($pathSource)) {
            $this->throw('Cannot change directory to "' . $pathSource . '"');
        }
    }

    /**
     * @param bool $forceAppendTag
     * @return string
     */
    protected function getRootDirSource(bool $forceAppendTag = false): string
    {
        return $this->pathBuilder->buildPathSource(
            $this->getAppPlatform(),
            $this->getAppName(),
            $this->getAppDomain(),
            $this->getAppTag(),
            $forceAppendTag
        );
    }

    /**
     * @return float
     */
    private function getTime(): float
    {
        return round($this->time + microtime(true), 1);
    }
}
