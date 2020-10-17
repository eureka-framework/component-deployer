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
 * Class Install
 *
 * @author Romain Cottard
 * @codeCoverageIgnore
 */
class Install extends AbstractCommonScript
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
        $this->chdirSource();

        $args = ' --no-interaction --no-dev';
        if ($this->getAppPlatform() === Platform::PROD) {
            $args .= ' --optimize-autoloader';
        }

        passthru("composer install ${args}", $status);

        if ($status !== 0) {
            $this->throw('Error with composer installation');
        }
    }
}
