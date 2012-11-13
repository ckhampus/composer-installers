<?php

namespace Queensbridge\Composer\Scripts;

use Composer\Script\Event;

class WordPressScriptHandler
{
    public static function moveIndexFile(Event $event)
    {
        $package = $event->getOperation()->getPackage();

        if ($package->getType() === 'wordpress-core') {
            $extra = self::getOptions($event);
            $wpDir = trim($extra['wordpress-dir'], '/');
            $file = $wpDir.'/index.php';
            $newFile = $wpDir.'/../index.php';

            if (file_exists($file)) {
                if (file_exists($newFile)) {
                    unlink($newFile);
                }

                $content = file_get_contents($file);
                $content = preg_replace("/require\('\.(\/wp-blog-header\.php')\);/", "require(__DIR__.'/{$wpDir}$1);", $content);
                file_put_contents($newFile, $content);
            }
        }
    }

    public static function addPathToIgnore(Event $event)
    {
        $package = $event->getOperation()->getPackage();
        $installer = new WordPressInstaller($event->getIO(), $event->getComposer());

        if (file_exists('.gitignore') && $installer->supports($package->getType())) {
            $path = $installer->getInstallPath($package);

            $content = file_get_contents('.gitignore');
            $content .= PHP_EOL.$path;
            file_put_contents('.gitignore', $content);
        }
    }

    public static function removePathToIgnore(Event $event)
    {
        $package = $event->getOperation()->getPackage();
        $installer = new WordPressInstaller($event->getIO(), $event->getComposer());

        if (file_exists('.gitignore') && $installer->supports($package->getType())) {
            $path = $installer->getInstallPath($package);

            $content = file_get_contents('.gitignore');
            $content = str_replace(PHP_EOL.$path, '', $content);
            file_put_contents('.gitignore', $content);
        }
    }

    protected static function getOptions($event)
    {
        $options = array_merge(array(
            'content-dir' => 'content',
            'wordpress-dir' => 'wordpress'
        ), $event->getComposer()->getPackage()->getExtra());

        return $options;
    }
}
