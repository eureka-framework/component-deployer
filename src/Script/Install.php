<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Deployer\Script;

use Eureka\Component\Deployer\Common\AbstractCommonScript;
use Eureka\Component\Console\IO\Out;

/**
 * Class Install
 *
 * @author Romain Cottard
 * @codeCoverageIgnore
 */
class Install extends AbstractCommonScript
{
    /**
     * Install constructor.
     */
    public function __construct()
    {
        $this->setDescription('Eureka Installer');
        $this->setExecutable(true);
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $this->stepStart();

        $stepStart = $this->config['install']['step.start'] ?? 0;
        $stepEnd   = $this->config['install']['step.end'] ?? 100;

        foreach ($this->config['install']['step.list'] as $step => $script) {
            if ($step < $stepStart) {
                continue;
            }

            if ($step > $stepEnd) {
                break;
            }

            $stringStep = str_pad((string) $step, 3, '0', STR_PAD_LEFT);
            $this->runStep($stringStep, $script);
        }

        $this->stepEnd();
    }

    /**
     * @param string $step
     * @param string $script
     * @return void
     */
    private function runStep(
        string $step,
        string $script
    ): void {
        $this->startTimer();

        $scriptArg   = escapeshellarg($script);
        $stepArg     = '--step=' . escapeshellarg($step);
        $platformArg = '--platform=' . escapeshellarg($this->getAppPlatform());
        $tagArg      = '--tag=' . escapeshellarg($this->getAppTag());
        $nameArg     = '--app=' . escapeshellarg($this->getAppName());
        $domainArg   = '--domain=' . escapeshellarg($this->getAppDomain());

        $pathSource = $this->getPathBuilder()->buildPathSource(
            $this->getAppPlatform(),
            $this->getAppName(),
            $this->getAppDomain(),
            $this->getAppTag(),
            true
        );

        passthru("${pathSource}/bin/console ${scriptArg} ${stepArg} ${platformArg} ${tagArg} ${nameArg} ${domainArg}", $status);

        if ($status !== 0) {
            $this->displayError($script);
        } else {
            $this->displaySuccess($script);
        }
    }

    /**
     * @return void
     */
    private function stepStart(): void
    {
        $this->displayStep('000', 'Starting install');

        Out::std(' Platform:    ' . $this->getAppPlatform());
        Out::std(' Application: ' . $this->getAppName());
        Out::std(' Domain:      ' . $this->getAppDomain());
        Out::std(' Tag:         ' . $this->getAppTag());
    }

    /**
     * @return void
     */
    private function stepEnd(): void
    {
        $this->displayStep('100', 'Ending install');
        $this->displayInfo('Finishing installation...');
        $this->displayInfoDone();
        $this->displaySuccess('Ending install');
    }
}
