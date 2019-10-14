<?php declare(strict_types=1);

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Installer\Script;

use Eureka\Component\Installer\Common\AbstractInstallerScript;
use Eureka\Component\Installer\Common\InstallerPathBuilder;

/**
 * Class Deploy
 *
 * @author Romain Cottard
 */
class Deploy extends AbstractInstallerScript
{
    /**
     * Deploy constructor.
     */
    public function __construct()
    {
        $this->setDescription('Eureka Deployer');
        $this->setExecutable(true);
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $platformArg = '--platform=' . escapeshellarg($this->getAppPlatform());
        $tagArg      = '--tag=' . escapeshellarg($this->getAppTag());
        $nameArg     = '--app=' . escapeshellarg($this->getAppName());
        $domainArg   = '--domain=' . escapeshellarg($this->getAppDomain());

        $this->exec('export', $platformArg, $tagArg, $nameArg, $domainArg);
        $this->exec('install', $platformArg, $tagArg, $nameArg, $domainArg);
        $this->exec('link', $platformArg, $tagArg, $nameArg, $domainArg);
    }

    /**
     * @param string $cmd
     * @param string $platformArg
     * @param string $tagArg
     * @param string $nameArg
     * @param string $domainArg
     * @return void
     */
    private function exec(string $cmd, string $platformArg, string $tagArg, string $nameArg, string $domainArg): void
    {
        passthru("bin/console ${cmd} ${platformArg} ${tagArg} ${nameArg} ${domainArg}", $status);

        if ($status !== 0) {
            throw new \RuntimeException('An error has occurred. Cannot deploy this application!');
        }
    }
}
