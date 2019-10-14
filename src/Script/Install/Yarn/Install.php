<?php declare(strict_types=1);

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Installer\Script\Install\Yarn;

use Eureka\Component\Installer\Common\AbstractInstallerScript;

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
        $this->setDescription('Yarn install dependencies');
        $this->setExecutable(true);
    }

    /**
     * @return void
     */
    public function run(): void
    {
        passthru('yarn install', $status);

        if ($status !== 0) {
            $this->throw('Error with yarn installation');
        }
    }
}
