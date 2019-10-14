<?php declare(strict_types=1);

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Installer\Script\Install\Yarn;

use Eureka\Component\Installer\Common\AbstractInstallerScript;
use Eureka\Component\Installer\Enumerator\Platform;

/**
 * Class EncoreBuild
 *
 * @author Romain Cottard
 */
class EncoreBuild extends AbstractInstallerScript
{
    /**
     * EncoreBuild constructor.
     */
    public function __construct()
    {
        $this->setDescription('Yarn build dependencies with Encore');
        $this->setExecutable(true);
    }

    /**
     * @return void
     */
    public function run(): void
    {
        passthru('yarn encore ' . ($this->getAppPlatform() === Platform::PROD ? 'production' : 'dev'), $status);

        if ($status !== 0) {
            $this->throw('Error with yarn build with Encore');
        }
    }
}
