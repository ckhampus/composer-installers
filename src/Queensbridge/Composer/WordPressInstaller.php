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

        $path = 'content/';
        $wp_path = '';

        if ($this->composer->getPackage()) {
            $extra = $this->composer->getPackage()->getExtra();

            if (!empty($extra['content-dir'])) {
                $path = $extra['content-dir'];
            }

            if (!empty($extra['wordpress-dir'])) {
                $wp_path = $extra['wordpress-dir'];
            }
        }

        switch ($type) {
            case 'core':
                $path = empty($wp_path) ? $name.'/' : $wp_path;
                break;
            case 'theme':
                $path .= 'themes/'.$name.'/';
                break;
            case 'plugin':
                $path .= 'plugins/'.$name.'/';
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
