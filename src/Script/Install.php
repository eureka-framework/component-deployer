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
        $this->setExecutable();
    }

    /**
     * @return void
     */
    public function run(): void
    {
        //~ Step 0 - 9: Reserved by deployer
        $this->stepStart();

        $this->step001(); // Composer install
        $this->step002(); // Copy secrets

        //~ Start app step
        $stepStart = (int) ($this->config['install']['step.start'] ?? 10);
        $stepEnd   = (int) ($this->config['install']['step.end'] ?? 100);

        $pathSource = $this->getRootDirSource();

        foreach ($this->config['install']['step.list'] as $step => $script) {
            if ((int) $step < $stepStart) {
                continue;
            }

            if ((int) $step > $stepEnd) {
                break;
            }

            $stringStep = str_pad((string) $step, 3, '0', STR_PAD_LEFT);
            $this->runStep($stringStep, $script, $pathSource);
        }

        //~ Step 90 - 100: Reserved by deployer
        $this->step098(); // Clear symfony cache
        $this->step099(); // Init directories & fix perms
        $this->stepEnd();
    }

    /**
     * @param string $step
     * @param string $script
     * @param string $pathSource
     * @return void
     */
    private function runStep(
        string $step,
        string $script,
        string $pathSource
    ): void {
        $this->startTimer();

        $scriptArg   = escapeshellarg($script);
        $stepArg     = '--step=' . escapeshellarg($step);
        $platformArg = '--platform=' . escapeshellarg($this->getAppPlatform());
        $tagArg      = '--tag=' . escapeshellarg($this->getAppTag());
        $nameArg     = '--app=' . escapeshellarg($this->getAppName());
        $domainArg   = '--domain=' . escapeshellarg($this->getAppDomain());

        passthru(
            "$pathSource/bin/console $scriptArg $stepArg $platformArg $tagArg $nameArg $domainArg",
            $status
        );

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

        $this->output()->writeln(' Platform:    ' . $this->getAppPlatform());
        $this->output()->writeln(' Application: ' . $this->getAppName());
        $this->output()->writeln(' Domain:      ' . $this->getAppDomain());
        $this->output()->writeln(' Tag:         ' . $this->getAppTag());
    }

    /**
     * @return void
     */
    private function step001(): void
    {
        $this->runStep('001', 'Install/Composer/Reinstall', $this->rootDir);
    }

    /**
     * @return void
     */
    private function step002(): void
    {
        $this->runStep('002', 'Install/Copy/Config', $this->rootDir);
    }

    /**
     * @return void
     */
    private function step098(): void
    {
        $this->runStep('098', 'Install/Clean/Cache', $this->rootDir);
    }

    /**
     * @return void
     */
    private function step099(): void
    {
        $this->runStep('099', 'Install/Init/Directories', $this->rootDir);
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
