<?php declare(strict_types=1);

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Installer\Script\Install;

use Eureka\Component\Installer\Common\AbstractInstallerScript;

/**
 * Class Done
 *
 * @author Romain Cottard
 */
class Done extends AbstractInstallerScript
{
    /**
     * Done constructor.
     */
    public function __construct()
    {
        $this->setDescription('Installation done');
        $this->setExecutable(true);
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $this->displayInfo('Finishing installation...');
        $this->displayInfoDone();
    }
}
