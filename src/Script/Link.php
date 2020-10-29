<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Deployer\Script;

use Eureka\Component\Deployer\Common\AbstractCommonScript;

/**
 * Class Link
 *
 * @author Romain Cottard
 * @codeCoverageIgnore
 */
class Link extends AbstractCommonScript
{
    /**
     * Link constructor.
     */
    public function __construct()
    {
        $this->setDescription('Eureka Link');
        $this->setExecutable(true);

        $this->startTimer();
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $this->displayStep('   ', 'Link project source to www apache domain directory');

        try {
            $pathSource = $this->getRootDirSource();
            $pathLink   = $this->getPathBuilder()->buildPathLink(
                $this->getAppPlatform(),
                $this->getAppDomain()
            );
            $this->cleanLink($pathLink);
            $this->linkToWebServer($pathSource, $pathLink);
        } catch (\RuntimeException $exception) {
            $this->displayError($exception->getMessage());
            return;
        }

        $this->displaySuccess('Linked with success');
    }

    /**
     * @param string $pathLink
     * @return void
     */
    private function cleanLink(string $pathLink): void
    {
        if (!file_exists($pathLink)) {
            return;
        }

        $this->displayInfo('Cleaning previous link to source...');
        if (!is_link($pathLink)) {
            $this->displayInfoFailed();
            $this->throw('Previous link is not a symlink, cannot remove it!');
        }

        if (!unlink($pathLink)) {
            $this->displayInfoFailed();
            $this->throw('Cannot remove previous link!');
        }

        $this->displayInfoDone();
    }

    /**
     * @param string $pathSource
     * @param string $pathLink
     * @return void
     */
    private function linkToWebServer(string $pathSource, string $pathLink): void
    {
        $this->displayInfo('Creating link to source...');
        $status = symlink($pathSource, $pathLink);

        if ($status === false) {
            $this->displayInfoFailed();
            $this->throw('Link error. Cannot link source path!');
        }

        $this->displayInfoDone();
    }
}
