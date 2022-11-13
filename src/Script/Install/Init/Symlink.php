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
 * Class Link
 *
 * @author Romain Cottard
 * @codeCoverageIgnore
 */
class Symlink extends AbstractCommonScript
{
    /**
     * Directories constructor.
     */
    public function __construct()
    {
        $this->setDescription('Initializing links (symlinks)');
        $this->setExecutable();
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $this->chdirSource();

        $this->createSymlinks();
    }

    /**
     * @return void
     */
    private function createSymlinks(): void
    {
        $rootDir = $this->getRootDirSource();

        $this->displayInfo('Create symlinks');
        foreach ($this->config['install']['init']['symlinks'] as $source => $destination) {
            $destination = str_replace('//', '/', $rootDir . DIRECTORY_SEPARATOR . $destination);

            if (!symlink($source, $destination)) {
                $this->displayInfoFailed();
                $this->throw("Could not create symlink from $source to $destination");
            }
        }

        $this->displayInfoDone();
    }
}
