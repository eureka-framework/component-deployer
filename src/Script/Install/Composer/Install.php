<?php declare(strict_types=1);

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Installer\Script\Install\Composer;

use Eureka\Component\Installer\Common\AbstractInstallerScript;
use Eureka\Component\Installer\Enumerator\Platform;
use Eureka\Eurekon;
use Eureka\Eurekon\Argument\Argument;

/**
 * Class Install
 *
 * @author Romain Cottard
 */
class Install extends AbstractInstallerScript
{
    /**
     * Install constructor.
     */
    public function __construct()
    {
        $this->setDescription('Composer install dependencies');
        $this->setExecutable(true);
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $args = ' --no-interaction';
        if ($this->getAppPlatform() === Platform::PROD) {
            $args .= ' --optimize-autoloader --no-dev';
        }

        passthru("composer install ${args}", $status);

        if ($status !== 0) {
            $this->throw('Error with composer installation');
        }
    }
}
