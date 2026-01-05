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

class PathBuilder
{
    public function __construct(
        private string $pathSource,
        private string $pathLink,
    ) {}

    public function buildPathSource(
        Platform $platform,
        string $app,
        string $domain,
        string $tag,
        bool $forceAppendTag = true,
    ): string {
        $suffix = $this->getSuffix($platform, $domain, $tag, $forceAppendTag);
        $prefix = $this->getPrefix($platform);

        return $this->getPathSource() . DIRECTORY_SEPARATOR . $app . DIRECTORY_SEPARATOR . $prefix . $suffix;
    }

    public function buildPathLink(Platform $platform, string $domain): string
    {
        $suffix = $domain;
        $prefix = $this->getPrefix($platform);

        return $this->getPathLink() . DIRECTORY_SEPARATOR . $prefix . $suffix;
    }

    public function getPathSource(): string
    {
        return $this->pathSource;
    }

    public function getPathLink(): string
    {
        return $this->pathLink;
    }

    private function getPrefix(Platform $platform): string
    {
        if ($platform === Platform::Prod) {
            return '';
        }

        return $platform->value . '-';
    }

    /**
     * By default, adding tag only for test, preprod & prod platform.
     * With forceAppendTag option, tag is append for all platform.
     */
    private function getSuffix(Platform $platform, string $domain, string $tag, bool $forceAppendTag): string
    {
        if ($forceAppendTag) {
            return $domain . '_v' . $tag;
        }

        return match ($platform) {
            Platform::Local, Platform::Docker, Platform::Dev, Platform::Staging => $domain,
            default => $domain . '_v' . $tag,
        };
    }
}
