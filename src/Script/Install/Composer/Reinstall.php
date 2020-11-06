<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Deployer\Script\Install\Composer;

use Eureka\Component\Deployer\Common\AbstractCommonScript;
use Eureka\Component\Deployer\Enumerator\Platform;

/**
 * Class Reinstall
 *
 * @author Romain Cottard
 * @codeCoverageIgnore
 */
class Reinstall extends AbstractCommonScript
{
    /**
     * ComposerClean constructor.
     */
    public function __construct()
    {
        $this->setDescription('Composer clean reinstall (no dev)');
        $this->setExecutable(true);
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $this->chdirSource();

        $this->clean();
        $this->install();
    }

    /**
     * @return void
     */
    private function clean(): void
    {
        $vendor = $this->getRootDirSource() . '/vendor';

        if (!is_dir($vendor)) {
            return;
        }

        $this->displayInfo('Removing "vendor/" directory...');

        passthru('rm -rf ' . $vendor, $status);

        if ($status !== 0) {
            $this->displayInfoFailed();
            $this->throw('Could not clean "vendor/" directory');
        }

        $this->displayInfoDone();
    }

    public function install(): void
    {
        $this->displayInfo('Installing composer without dev dependencies...');

        $args = ' --no-interaction --no-dev';
        if ($this->getAppPlatform() === Platform::PROD) {
            $args .= ' --optimize-autoloader';
        }

        passthru("composer install ${args}", $status);

        if ($status !== 0) {
            $this->displayInfoFailed();
            $this->throw('Error with composer installation');
        }

        $this->displayInfoDone();
    }
}
