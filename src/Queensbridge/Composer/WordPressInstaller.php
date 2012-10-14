<?php

namespace Queensbridge\Composer;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

/**
 * Installer for installing WordPress Core, Themes and Plugins.
 */
class WordPressInstaller extends LibraryInstaller
{
    /**
     * Default prefix for installation packages.
     */
    protected const PREFIX = 'wordpress-';

    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        $type = substr($package->getType(), 0, strlen(self::PREFIX));

        switch ($type) {
            case 'core':
                return $package->getPrettyName();
            case 'theme':
                return 'content/themes/'.$package->getPrettyName();
            case 'plugin':
                return 'content/plugins/'.$package->getPrettyName();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        $type = substr($packageType, 0, strlen(self::PREFIX));

        return in_array($type, array(
            'core',
            'theme',
            'plugin'
        ));
    }
}