<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Deployer\Script\Install\Clean;

use Eureka\Component\Deployer\Common\AbstractCommonScript;

/**
 * Class Files
 *
 * @author Romain Cottard
 * @codeCoverageIgnore
 */
class Files extends AbstractCommonScript
{
    /**
     * Files constructor.
     */
    public function __construct()
    {
        $this->setDescription('Cleaning files & directories');
        $this->setExecutable(true);
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $this->cleanFiles($this->config['install']['clean']['files']);
        $this->cleanDirectories($this->config['install']['clean']['directories']);
    }

    /**
     * @param array $files
     * @return void
     */
    protected function cleanFiles(array $files): void
    {
        $this->chdirSource();

        $rootDir = $this->getRootDirSource();

        foreach ($files as $file) {
            $file = $rootDir . DIRECTORY_SEPARATOR . $file;

            $this->displayInfo('Removing ' . $file . '...');

            if (is_file($file) && !unlink($file)) {
                $this->displayInfoFailed();
            }
            $this->displayInfoDone();
        }
    }

    /**
     * @param array $directories
     * @return void
     */
    protected function cleanDirectories(array $directories): void
    {
        $rootDir = $this->getRootDirSource();

        $status = 0;
        foreach ($directories as $dir) {
            $dir = $rootDir . DIRECTORY_SEPARATOR . $dir;

            $this->displayInfo('Removing ' . $dir . '...');

            if (is_dir($dir)) {
                passthru('rm -rf ' . escapeshellarg($dir), $status);
            }

            if ($status !== 0) {
                $this->displayInfoFailed();
            }

            $this->displayInfoDone();
        }
    }
}
