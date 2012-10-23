<?php

namespace Queensbridge\Composer;

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
        $type = $package->getType();

        $prettyName = $package->getPrettyName();
        if (strpos($prettyName, '/') !== false) {
            list($vendor, $name) = explode('/', $prettyName);
        } else {
            $vendor = '';
            $name = $prettyName;
        }

        $path = '';

        if ($type === self::TYPE) {
            $path = 'cookbooks/'.$name.'/';
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
