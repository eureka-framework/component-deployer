<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Deployer\Script\Install\Yarn;

use Eureka\Component\Deployer\Common\AbstractCommonScript;
use Eureka\Component\Deployer\Enumerator\Platform;

/**
 * @codeCoverageIgnore
 */
class EncoreBuild extends AbstractCommonScript
{
    public function __construct()
    {
        $this->setDescription('Yarn build dependencies with Encore');
        $this->setExecutable();
    }

    public function run(): void
    {
        $this->chdirSource();

        \passthru('yarn encore ' . ($this->getAppPlatform() === Platform::Prod ? 'production' : 'dev'), $status);

        if ($status !== 0) {
            $this->throw('Error with yarn build with Encore');
        }
    }
}
