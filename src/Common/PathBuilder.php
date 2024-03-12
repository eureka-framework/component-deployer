<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Deployer\Common;

use Eureka\Component\Deployer\Enumerator\Platform;

/**
 * Class PathBuilder
 *
 * @author Romain Cottard
 */
class PathBuilder
{
    /** @var string $pathSource */
    private string $pathSource;

    /** @var string $pathLink */
    private string $pathLink;

    /**
     * PathBuilder constructor.
     *
     * @param string $pathSource
     * @param string $pathLink
     */
    public function __construct(string $pathSource, string $pathLink)
    {
        $this->pathSource = $pathSource;
        $this->pathLink   = $pathLink;
    }

    /**
     * @param string $platform
     * @param string $app
     * @param string $domain
     * @param string $tag
     * @param bool $forceAppendTag
     * @return string
     */
    public function buildPathSource(
        string $platform,
        string $app,
        string $domain,
        string $tag,
        bool $forceAppendTag = true
    ): string {
        $suffix = $this->getSuffix($platform, $domain, $tag, $forceAppendTag);
        $prefix = $this->getPrefix($platform);

        return $this->getPathSource() . DIRECTORY_SEPARATOR . $app . DIRECTORY_SEPARATOR . $prefix . $suffix;
    }

    /**
     * @param string $platform
     * @param string $domain
     * @return string
     */
    public function buildPathLink(string $platform, string $domain): string
    {
        $suffix = $domain;
        $prefix = $this->getPrefix($platform);

        return $this->getPathLink() . DIRECTORY_SEPARATOR . $prefix . $suffix;
    }

    /**
     * @return string
     */
    public function getPathSource(): string
    {
        return $this->pathSource;
    }

    /**
     * @return string
     */
    public function getPathLink(): string
    {
        return $this->pathLink;
    }

    /**
     * @param string $platform
     * @return string
     */
    private function getPrefix(string $platform): string
    {
        return match ($platform) {
            Platform::LOCAL => 'local-',
            Platform::DOCKER => 'docker-',
            Platform::DEV => 'dev-',
            Platform::TEST => 'test-',
            Platform::STAGING => 'staging-',
            Platform::PREPROD => 'preprod-',
            Platform::DEMO => 'demo-',
            default => '',
        };
    }

    /**
     * By default, adding tag only for test, preprod & prod platform.
     * With forceAppendTag option, tag is append for all platform.
     *
     * @param string $platform
     * @param string $domain
     * @param string $tag
     * @param bool $forceAppendTag
     * @return string
     */
    private function getSuffix(string $platform, string $domain, string $tag, bool $forceAppendTag): string
    {
        if ($forceAppendTag) {
            return $domain . '_v' . $tag;
        }

        return match ($platform) {
            Platform::LOCAL, Platform::DOCKER, Platform::DEV, Platform::STAGING => $domain,
            default => $domain . '_v' . $tag,
        };
    }
}
