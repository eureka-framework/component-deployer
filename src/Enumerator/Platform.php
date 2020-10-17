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
final class Platform
{
    /** @var string LOCAL Usually user pc (but NOT IN docker) */
    public const LOCAL   = 'local';

    /** @var string DOCKER Usually user pc (but IN docker)  */
    public const DOCKER  = 'docker';

    /** @var string DEV Usually on "distant" dev server */
    public const DEV     = 'dev';

    /** @var string TEST Usually on "distant" testing server */
    public const TEST    = 'test';

    /** @var string STAGING Usually on distant staging server */
    public const STAGING = 'staging';

    /** @var string PREPROD Usually on distant pre-production server */
    public const PREPROD = 'preprod';

    /** @var string DEMO Usually on distant "demonstration" server */
    public const DEMO    = 'demo';

    /** @var string PROD Usually on distant production server */
    public const PROD    = 'prod';
}
