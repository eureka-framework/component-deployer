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
use Eureka\Component\Console\Color\Bit4Color;
use Eureka\Component\Console\Color\Bit8HighColor;
use Eureka\Component\Console\Color\Bit8RGBColor;
use Eureka\Component\Console\Color\Bit8StandardColor;
use Eureka\Component\Console\Help;
use Eureka\Component\Console\Option\Option;
use Eureka\Component\Console\Option\Options;
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

        $this->initOptions(
            (new Options())
                ->add(
                    new Option(
                        shortName:   'p',
                        longName:    'platform',
                        description: 'Platform where installation is executed (default from config)',
                        hasArgument: true,
                        default:     Platform::PROD,
                    )
                )
                ->add(
                    new Option(
                        shortName:   't',
                        longName:    'tag',
                        description: 'Tag version to install (default from config)',
                        hasArgument: true,
                        default:     $this->config['app.tag'] ?? '1.0.0',
                    )
                )
                ->add(
                    new Option(
                        shortName:   'd',
                        longName:    'domain',
                        description: 'Application domain (ie: www.my-app.com) (default from config)',
                        hasArgument: true,
                        default:     $this->config['app.domain'] ?? '',
                    )
                )
                ->add(
                    new Option(
                        shortName:   'n',
                        longName:    'name',
                        description: 'Application name, used to retrieve config (default from config)',
                        hasArgument: true,
                        default:     $this->config['app.domain'] ?? '',
                    )
                )
                ->add(
                    new Option(
                        shortName:   's',
                        longName:    'step',
                        description: 'From which step the deployer should start (default is from beginning)',
                        hasArgument: true,
                    )
                )
        );

        //~ Init installer main vars
        $this->appPlatform = (string) $this->options()->value('platform', 'p');
        $this->appTag      = (string) $this->options()->value('tag', 't');
        $this->appName     = (string) $this->options()->value('name', 'n');
        $this->appDomain   = (string) $this->options()->value('domain', 'd');

        //~ Display Step title
        if ($this->options()->value('step') !== null) {
            $this->displayStep(
                (string) $this->options()->value('step'),
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
        (new Help(self::class, $this->declaredOptions(), $this->output(), $this->options()))
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
        $text = (new Style($this->options()))
            ->background(Bit8StandardColor::Blue)
            ->apply($title)
        ;

        $this->output()->writeln($text);
    }

    protected function displayInfo(string $text): void
    {
        $this->output()->write(" $text");
    }

    protected function displayInfoDone(): void
    {
        $text = (new Style($this->options()))
            ->color(Bit8HighColor::Green)
            ->apply(' done!')
        ;

        $this->output()->writeln($text);
    }

    protected function displayInfoFailed(): void
    {
        $text = (new Style($this->options()))
            ->color(Bit8HighColor::Red)
            ->apply(' failed!')
        ;

        $this->output()->writeln($text);
    }

    protected function displayStep(string $step, string $title): void
    {
        $text = PHP_EOL . (
            (new Style($this->options()))
                ->bold()
                ->background(Bit8HighColor::Black)
                ->apply(" $step ")
        );

        $text .= (new Style($this->options()))
            ->background(Bit8HighColor::Cyan)
            ->color(Bit8HighColor::White)
            ->apply(" $title ")
        ;

        $this->output()->writeln($text . PHP_EOL);
    }

    protected function displaySuccess(string $title): void
    {
        $status = (new Style($this->options()))
            ->color(Bit8HighColor::Green)
            ->apply('[OK]')
        ;

        $text = (new Style($this->options()))
            ->color(Bit8StandardColor::Cyan)
            ->apply($title)
        ;

        $time = (string) $this->getTime();

        $this->output()->writeln(" ✓ $status $text in {$time}s" . PHP_EOL);
    }

    protected function displayError(string $title): void
    {
        $status = (new Style($this->options()))
            ->color(Bit8HighColor::Red)
            ->apply('[ERROR]')
        ;

        $text = (new Style($this->options()))
            ->color(Bit8StandardColor::Cyan)
            ->apply($title)
        ;

        $time = (string) $this->getTime();

        $this->output()->writeln(" ✗ $status $text in {$time}s" . PHP_EOL);
    }

    /**
     * @param string $message
     * @param int $code
     * @return never
     */
    protected function throw(string $message, int $code = 1): void
    {
        $text = (new Style($this->options()))
            ->color(Bit8StandardColor::Red)
            ->apply('  > ' . $message)
        ;

        $this->output()->writeln($text . PHP_EOL);

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
