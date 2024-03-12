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
 * Class Export
 *
 * @author Romain Cottard
 * @codeCoverageIgnore
 */
class Export extends AbstractCommonScript
{
    /**
     * Export constructor.
     */
    public function __construct()
    {
        $this->setDescription('Eureka Exporter');
        $this->setExecutable();

        $this->startTimer();
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $this->displayStep('   ', 'Export project source');

        try {
            $pathApplication = $this->getRootDirSource();

            $this->gitArchive($pathApplication);
            $this->unzip($pathApplication);
        } catch (\RuntimeException $exception) {
            $this->displayError($exception->getMessage());
            return;
        }

        $this->displaySuccess('Exported with success');
    }

    /**
     * @param string $pathApplication
     * @return void
     */
    private function gitArchive(string $pathApplication): void
    {
        $prefixArg = escapeshellarg(basename($pathApplication) . DIRECTORY_SEPARATOR);
        $fileArg   = escapeshellarg($pathApplication . '.zip');
        $tagArg    = escapeshellarg($this->getAppTag());

        $this->displayInfo('Creating git archive...');
        echo PHP_EOL;

        passthru("git archive -o $fileArg --prefix=$prefixArg $tagArg", $status);

        if ($status !== 0) {
            $this->displayInfoFailed();
            $this->throw('Export error. Cannot export the source files!');
        }

        $this->displayInfoDone();
    }

    /**
     * @param string $pathApplication
     * @return void
     */
    private function unzip(string $pathApplication): void
    {
        $pathSource     = dirname($pathApplication);
        $fileArchiveArg = escapeshellarg($pathApplication . '.zip');

        $currentLocation = getcwd();
        if ($currentLocation === false) {
            $this->throw('Cannot get current directory location!');
        }

        chdir($pathSource);

        $this->displayInfo(' Decompressing archive file...');
        exec("unzip $fileArchiveArg", $output, $status);

        if ($status !== 0) {
            chdir($currentLocation);
            $this->displayInfoFailed();
            $this->throw('Cannot decompress archive file!');
        }

        chdir($currentLocation);
        $this->displayInfoDone();

        $this->displayInfo(' Removing archive file...');
        system("rm $fileArchiveArg", $status);

        if ($status !== 0) {
            $this->displayInfoFailed();
            $this->throw('Cannot remove file archive!');
        }

        $this->displayInfoDone();
    }
}
