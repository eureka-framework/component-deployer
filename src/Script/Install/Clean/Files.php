<?php declare(strict_types=1);

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Deployer\Script\Install\Clean;

use Eureka\Component\Deployer\Common\AbstractCommonScript;
use Eureka\Eurekon;
use Eureka\Eurekon\Argument\Argument;

/**
 * Class Files
 *
 * @author Romain Cottard
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

        foreach ($files as $file) {
            $file = $this->rootDir . DIRECTORY_SEPARATOR . $file;

            $this->displayInfo('Removing ' . $file . '...');

            /*if (!unlink($file)) {
                $this->displayInfoFailed();
                $this->throw('Cannot remove file "' . $file . '"!');
            }*/
            $this->displayInfoDone();
        }
    }

    /**
     * @param array $directories
     * @return void
     */
    protected function cleanDirectories(array $directories): void
    {
        $status = 0;
        foreach ($directories as $dir) {
            $dir = $this->rootDir . DIRECTORY_SEPARATOR . $dir;

            $this->displayInfo('Removing ' . $dir . '...');

            //passthru('rm -r ' . escapeshellarg($dir), $status);

            if ($status !== 0) {
                $this->displayInfoFailed();
                $this->throw('Cannot remove directory "' . $dir . '"!');
            }

            $this->displayInfoDone();
        }
    }
}
