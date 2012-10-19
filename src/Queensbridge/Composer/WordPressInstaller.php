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
    const PREFIX = 'wordpress-';

    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        $type = substr($package->getType(), strlen(self::PREFIX));

        $prettyName = $package->getPrettyName();
        if (strpos($prettyName, '/') !== false) {
            list($vendor, $name) = explode('/', $prettyName);
        } else {
            $vendor = '';
            $name = $prettyName;
        }

        switch ($type) {
            case 'core':
                $path = $name.'/';
                break;
            case 'theme':
                $path = 'content/themes/'.$name.'/';
                break;
            case 'plugin':
                $path = 'content/plugins/'.$name.'/';
                break;
        }

        return $path;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        $type = substr($packageType, strlen(self::PREFIX));

        return in_array($type, array(
            'core',
            'theme',
            'plugin'
        ));
    }
}
