<?php declare(strict_types=1);

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Installer\Script\Install\Copy;

use Eureka\Component\Installer\Common\AbstractInstallerScript;
use Eureka\Eurekon;
use Eureka\Eurekon\Argument\Argument;

/**
 * Class Config
 *
 * @author Romain Cottard
 */
class Config extends AbstractInstallerScript
{
    /**
     * Config constructor.
     */
    public function __construct()
    {
        $this->setDescription('Start installation');
        $this->setExecutable(true);
    }

    /**
     * @return void
     */
    public function run(): void
    {
        foreach ($this->config['install']['copy']['files'] as $file => $destination) {

            $source = str_replace(['{platform}', '{domain}'], [$this->getAppPlatform(), $this->getAppDomain()], $file);
            $this->displayInfo("Copying ${source} to ${destination}");

            if (!copy($source, $destination)) {
                $this->displayInfoFailed();
                $this->throw("Could not copy ${source} to ${destination}");
            }

            $this->displayInfoDone();
        }
    }
}
