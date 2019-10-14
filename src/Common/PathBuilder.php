<?php declare(strict_types=1);

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    private $pathSource = '';

    /** @var string $pathLink */
    private $pathLink = '';

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

        return $this->pathSource . DIRECTORY_SEPARATOR . $app . DIRECTORY_SEPARATOR . $prefix . $suffix;
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
     * @param string $domain
     * @return string
     */
    public function buildPathLink(string $platform, string $domain): string
    {
        $suffix = $domain;
        $prefix = $this->getPrefix($platform);

        return $this->pathLink . DIRECTORY_SEPARATOR . $prefix . $suffix;
    }

    /**
     * @param string $platform
     * @return string
     */
    private function getPrefix(string $platform): string
    {
        switch ($platform) {
            case Platform::LOCAL:
                $prefix = 'local-';
                break;
            case Platform::DOCKER:
                $prefix = 'docker-';
                break;
            case Platform::DEV:
                $prefix = 'dev-';
                break;
            case Platform::TEST:
                $prefix = 'test-';
                break;
            case Platform::STAGING:
                $prefix = 'staging-';
                break;
            case Platform::PREPROD:
                $prefix = 'preprod-';
                break;
            case Platform::DEMO:
                $prefix = 'demo-';
                break;
            case Platform::PROD:
            default:
                $prefix = '';
        }

        return $prefix;
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

        switch ($platform) {
            case Platform::LOCAL:
            case Platform::DOCKER:
            case Platform::DEV:
            case Platform::STAGING:
                $suffix = $domain;
                break;
            case Platform::TEST:
            case Platform::PREPROD:
            case Platform::DEMO:
            case Platform::PROD:
            default:
                $suffix = $domain . '_v' . $tag;
        }

        return $suffix;
    }
}
