<?php

namespace Queensbridge\Composer\Installers;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

/**
 * Installer for installing chef cookbooks.
 */
class CookbookInstaller extends LibraryInstaller
{
    /**
     * Default type for installation packages.
     */
    const TYPE = 'chef-cookbook';

    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        $prettyName = $package->getPrettyName();
        if (strpos($prettyName, '/') !== false) {
            list($vendor, $name) = explode('/', $prettyName);
        } else {
            $vendor = '';
            $name = $prettyName;
        }

        $path = 'cookbooks/'.$name.'/';

        if ($this->composer->getPackage()) {
            $extra = $this->composer->getPackage()->getExtra();

            if (!empty($extra['cookbooks-dir'])) {
                $path = trim($extra['cookbooks-dir'], '/').'/'.$name.'/';
            }
        }

        return $path;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return $packageType === self::TYPE;
    }
}
