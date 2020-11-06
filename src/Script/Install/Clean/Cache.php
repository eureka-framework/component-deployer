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
class Cache extends AbstractCommonScript
{
    /**
     * Files constructor.
     */
    public function __construct()
    {
        $this->setDescription('Cleaning cache app directory');
        $this->setExecutable(true);
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $this->cleanDirectories(['var/cache/', 'var/log/']);
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
