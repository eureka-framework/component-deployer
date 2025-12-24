<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Deployer\Tests\Unit;

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
    public function testICanBuildPathAndLinkForDev(): void
    {
        $builder  = new PathBuilder('/var/src', '/var/www');
        $pathSrc  = $builder->buildPathSource(Platform::Dev, 'example', 'www.example.com', '1.0.0');
        $pathLink = $builder->buildPathLink(Platform::Dev, 'www.example.com');

        $this->assertSame('/var/src/example/dev-www.example.com_v1.0.0', $pathSrc);
        $this->assertSame('/var/www/dev-www.example.com', $pathLink);
    }

    public function testICanBuildPathAndLinkForLocal(): void
    {
        $builder  = new PathBuilder('/var/src', '/var/www');
        $pathSrc  = $builder->buildPathSource(Platform::Local, 'example', 'www.example.com', '1.0.0', false);
        $pathLink = $builder->buildPathLink(Platform::Local, 'www.example.com');

        $this->assertSame('/var/src/example/local-www.example.com', $pathSrc);
        $this->assertSame('/var/www/local-www.example.com', $pathLink);
    }

    public function testICanBuildPathAndLinkForDocker(): void
    {
        $builder  = new PathBuilder('/var/src', '/var/www');
        $pathSrc  = $builder->buildPathSource(Platform::Docker, 'example', 'www.example.com', '1.0.0');
        $pathLink = $builder->buildPathLink(Platform::Docker, 'www.example.com');

        $this->assertSame('/var/src/example/docker-www.example.com_v1.0.0', $pathSrc);
        $this->assertSame('/var/www/docker-www.example.com', $pathLink);
    }

    public function testICanBuildPathAndLinkForStaging(): void
    {
        $builder  = new PathBuilder('/var/src', '/var/www');
        $pathSrc  = $builder->buildPathSource(Platform::Staging, 'example', 'www.example.com', '1.0.0');
        $pathLink = $builder->buildPathLink(Platform::Staging, 'www.example.com');

        $this->assertSame('/var/src/example/staging-www.example.com_v1.0.0', $pathSrc);
        $this->assertSame('/var/www/staging-www.example.com', $pathLink);
    }

    public function testICanBuildPathAndLinkForTest(): void
    {
        $builder  = new PathBuilder('/var/src', '/var/www');
        $pathSrc  = $builder->buildPathSource(Platform::Test, 'example', 'www.example.com', '1.0.0');
        $pathLink = $builder->buildPathLink(Platform::Test, 'www.example.com');

        $this->assertSame('/var/src/example/test-www.example.com_v1.0.0', $pathSrc);
        $this->assertSame('/var/www/test-www.example.com', $pathLink);
    }

    public function testICanBuildPathAndLinkForDemo(): void
    {
        $builder  = new PathBuilder('/var/src', '/var/www');
        $pathSrc  = $builder->buildPathSource(Platform::Demo, 'example', 'www.example.com', '1.0.0');
        $pathLink = $builder->buildPathLink(Platform::Demo, 'www.example.com');

        $this->assertSame('/var/src/example/demo-www.example.com_v1.0.0', $pathSrc);
        $this->assertSame('/var/www/demo-www.example.com', $pathLink);
    }

    public function testICanBuildPathAndLinkForPreprod(): void
    {
        $builder  = new PathBuilder('/var/src', '/var/www');
        $pathSrc  = $builder->buildPathSource(Platform::Preprod, 'example', 'www.example.com', '1.0.0', false);
        $pathLink = $builder->buildPathLink(Platform::Preprod, 'www.example.com');

        $this->assertSame('/var/src/example/preprod-www.example.com_v1.0.0', $pathSrc);
        $this->assertSame('/var/www/preprod-www.example.com', $pathLink);
    }

    public function testICanBuildPathAndLinkForProd(): void
    {
        $builder  = new PathBuilder('/var/src', '/var/www');
        $pathSrc  = $builder->buildPathSource(Platform::Prod, 'example', 'www.example.com', '1.0.0', false);
        $pathLink = $builder->buildPathLink(Platform::Prod, 'www.example.com');

        $this->assertSame('/var/src/example/www.example.com_v1.0.0', $pathSrc);
        $this->assertSame('/var/www/www.example.com', $pathLink);
    }
}
