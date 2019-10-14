<?php declare(strict_types=1);

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Deployer\Script\Install\Yarn;

use Eureka\Component\Deployer\Common\AbstractCommonScript;

/**
 * Class Install
 *
 * @author Romain Cottard
 */
class Install extends AbstractCommonScript
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
        $this->chdirSource();

        passthru('yarn install', $status);

        if ($status !== 0) {
            $this->throw('Error with yarn installation');
        }
    }
}
