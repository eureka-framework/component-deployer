<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Deployer\Tests;

use Eureka\Component\Deployer\Common\PathBuilder;
use Eureka\Component\Deployer\Enumerator\Platform;
use PHPUnit\Framework\TestCase;

/**
 * Class PathBuilderTest
 *
 * @author Romain Cottard
 */
class PathBuilderTest extends TestCase
{
    /**
     * @return void
     */
    public function testICanInitializePathBuilder(): void
    {
        $builder = new PathBuilder('/var/src', '/var/www');

        $this->assertInstanceOf(PathBuilder::class, $builder);
    }

    public function testICanBuildPathAndLinkForDev(): void
    {
        $builder  = new PathBuilder('/var/src', '/var/www');
        $pathSrc  = $builder->buildPathSource(Platform::DEV, 'example', 'www.example.com', '1.0.0');
        $pathLink = $builder->buildPathLink(Platform::DEV, 'www.example.com');

        $this->assertSame('/var/src/example/dev-www.example.com_v1.0.0', $pathSrc);
        $this->assertSame('/var/www/dev-www.example.com', $pathLink);
    }

    public function testICanBuildPathAndLinkForProd(): void
    {
        $builder  = new PathBuilder('/var/src', '/var/www');
        $pathSrc  = $builder->buildPathSource(Platform::PROD, 'example', 'www.example.com', '1.0.0');
        $pathLink = $builder->buildPathLink(Platform::PROD, 'www.example.com');

        $this->assertSame('/var/src/example/www.example.com_v1.0.0', $pathSrc);
        $this->assertSame('/var/www/www.example.com', $pathLink);
    }
}
