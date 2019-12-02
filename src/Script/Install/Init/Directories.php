<?php declare(strict_types=1);

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Deployer\Script\Install\Init;

use Eureka\Component\Deployer\Common\AbstractCommonScript;

/**
 * Class Directories
 *
 * @author Romain Cottard
 */
class Directories extends AbstractCommonScript
{
    /**
     * Directories constructor.
     */
    public function __construct()
    {
        $this->setDescription('Initializing / Fixing directories');
        $this->setExecutable(true);
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $this->chdirSource();

        $this->createDirectories();
        $this->fixPermissions();
    }

    /**
     * @return void
     */
    private function createDirectories(): void
    {
        $this->displayInfo('Create missing directories... ');
        foreach ($this->config['install']['init']['directories'] as $directory => $perms) {
            $path = $this->rootDir . DIRECTORY_SEPARATOR . $directory;

            if (!is_dir($path) && !mkdir($path, $perms, true)) {
                $this->displayInfoFailed();
                $this->throw('Cannot create directory: ' . $directory);
            }
        }

        $this->displayInfoDone();
    }

    /**
     * @return void
     */
    private function fixPermissions(): void
    {
        $this->displayInfo('Fixing permissions... ');

        foreach ($this->config['install']['init']['directories'] as $directory => $perms) {
            $path = $this->rootDir . DIRECTORY_SEPARATOR . $directory;

            system('chmod -R ' . $perms . ' ' . escapeshellarg($path), $status);

            if ($status !== 0) {
                $this->displayInfoFailed();
                $this->throw('Cannot fix permissions on directory: ' . $directory);
            }
            /*if (!chmod($path, $perms)) {
                $this->throw('Cannot fix permissions on directory: ' . $directory);
            }*/
        }

        $this->displayInfoDone();
    }
}
