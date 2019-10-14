<?php declare(strict_types=1);

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Installer\Script\Install\Composer;

use Eureka\Component\Installer\Common\AbstractInstallerScript;
use Eureka\Eurekon;
use Eureka\Eurekon\Argument\Argument;

/**
 * Class ComposerClean
 *
 * @author Romain Cottard
 */
class Clean extends AbstractInstallerScript
{
    /**
     * ComposerClean constructor.
     */
    public function __construct()
    {
        $this->setDescription('Composer clean previous dependencies');
        $this->setExecutable(true);
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $vendor = $this->rootDir . '/vendor';

        if (!is_dir($vendor)) {
            return;
        }

        $this->displayInfo('Removing "vendor/" directory...');

        passthru('rm -r ' . $vendor, $status);

        if ($status !== 0) {
            $this->displayInfoFailed();
            $this->throw('Could not clean "vendor/" directory');
        }

        $this->displayInfoDone();
    }
}
