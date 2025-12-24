<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Deployer\Enumerator;

/**
 * Class Platform
 *
 * @author Romain Cottard
 */
enum Platform: string
{
    case Local   = 'local'; // Usually user pc (but NOT IN docker)
    case Docker  = 'docker'; // Usually user pc (but IN docker)
    case Dev     = 'dev'; // Usually on "distant" dev server
    case Test    = 'test'; // Usually on "distant" testing server
    case Staging = 'staging'; // Usually on distant staging server
    case Preprod = 'preprod'; // Usually on distant pre-production server
    case Demo    = 'demo'; // Usually on distant "demonstration" server
    case Prod    = 'prod'; // Usually on distant production server
}
