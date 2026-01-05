<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Deployer\Script\Install\Init;

use Eureka\Component\Deployer\Common\AbstractCommonScript;

/**
 * @codeCoverageIgnore
 */
class Directories extends AbstractCommonScript
{
    public function __construct()
    {
        $this->setDescription('Initializing / Fixing directories');
        $this->setExecutable();
    }

    public function run(): void
    {
        $this->chdirSource();

        $this->createDirectories();
        $this->fixPermissions();
    }

    private function createDirectories(): void
    {
        $rootDir = $this->getRootDirSource();

        $this->displayInfo('Create missing directories... ');
        foreach ($this->config['install']['init']['directories'] as $directory => $perms) {
            $path = $rootDir . DIRECTORY_SEPARATOR . $directory;

            if (!is_dir($path) && !mkdir($path, 0755, true)) {
                $this->displayInfoFailed();
                $this->throw('Cannot create directory: ' . $directory);
            }
        }

        $this->displayInfoDone();
    }

    private function fixPermissions(): void
    {
        $rootDir = $this->getRootDirSource();

        $this->displayInfo('Fixing permissions... ');

        foreach ($this->config['install']['init']['directories'] as $directory => $perms) {
            $path = $rootDir . DIRECTORY_SEPARATOR . $directory;

            system('chmod -R 0' . $perms . ' ' . escapeshellarg($path), $status);

            if ($status !== 0) {
                $this->displayInfoFailed();
                $this->throw('Cannot fix permissions on directory: ' . $directory);
            }
        }

        $this->displayInfoDone();
    }
}
