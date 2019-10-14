<?php declare(strict_types=1);

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Installer\Script;

use Eureka\Component\Installer\Common\AbstractInstallerScript;
use Eureka\Component\Installer\Common\InstallerPathBuilder;

/**
 * Class Export
 *
 * @author Romain Cottard
 */
class Export extends AbstractInstallerScript
{
    /**
     * Export constructor.
     */
    public function __construct()
    {
        $this->setDescription('Eureka Exporter');
        $this->setExecutable(true);

        $this->startTimer();
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $this->displayStep('   ', 'Export project source');

        try {
            $pathApplication = $this->getPathBuilder()->buildPathSource(
                $this->getAppPlatform(),
                $this->getAppName(),
                $this->getAppDomain(),
                $this->getAppTag(),
                true
            );

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
        passthru("git archive -o ${fileArg} --prefix=${prefixArg} ${tagArg}", $status);

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
        $pathSource     = dirname($pathApplication) ;
        $fileArchiveArg = escapeshellarg($pathApplication . '.zip');

        $currentLocation = getcwd();
        chdir($pathSource);

        $this->displayInfo(' Uncompressing archive file...');
        exec("unzip ${fileArchiveArg}", $output, $status);

        if ($status !== 0) {
            chdir($currentLocation);
            $this->displayInfoFailed();
            $this->throw('Cannot uncompress archive file!');
        }

        chdir($currentLocation);
        $this->displayInfoDone();

        $this->displayInfo(' Removing archive file...');
        system("rm ${fileArchiveArg}", $status);

        if ($status !== 0) {
            $this->displayInfoFailed();
            $this->throw('Cannot remove file archive!');
        }

        $this->displayInfoDone();
    }
}
