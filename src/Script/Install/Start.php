<?php declare(strict_types=1);

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Installer\Script\Install;

use Eureka\Component\Installer\Common\AbstractInstallerScript;
use Eureka\Eurekon\IO\Out;

/**
 * Class Start
 *
 * @author Romain Cottard
 */
class Start extends AbstractInstallerScript
{
    /**
     * Start constructor.
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
        Out::std(' Platform:    ' . $this->getAppPlatform());
        Out::std(' Application: ' . $this->getAppName());
        Out::std(' Domain:      ' . $this->getAppDomain());
        Out::std(' Tag:         ' . $this->getAppTag());
    }
}
