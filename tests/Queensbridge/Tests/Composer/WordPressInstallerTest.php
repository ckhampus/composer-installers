<?php
namespace Queensbridge\Tests\Composer;

use Composer\Installers\Installer;
use Composer\Util\Filesystem;
use Composer\Package\Package;
use Composer\Package\RootPackage;
use Composer\Composer;
use Composer\Config;

use Queensbridge\Composer\WordPressInstaller;

class WordPressInstallerTest extends TestCase
{
    private $composer;
    private $config;
    private $vendorDir;
    private $binDir;
    private $dm;
    private $repository;
    private $io;
    private $fs;

    /**
     * setUp
     *
     * @return void
     */
    public function setUp()
    {
        $this->fs = new Filesystem;

        $this->composer = new Composer();
        $this->config = new Config();
        $this->composer->setConfig($this->config);

        $this->vendorDir = realpath(sys_get_temp_dir()) . DIRECTORY_SEPARATOR . 'baton-test-vendor';
        $this->ensureDirectoryExistsAndClear($this->vendorDir);

        $this->binDir = realpath(sys_get_temp_dir()) . DIRECTORY_SEPARATOR . 'baton-test-bin';
        $this->ensureDirectoryExistsAndClear($this->binDir);

        $this->config->merge(array(
            'config' => array(
                'vendor-dir' => $this->vendorDir,
                'bin-dir' => $this->binDir,
            ),
        ));

        $this->dm = $this->getMockBuilder('Composer\Downloader\DownloadManager')
            ->disableOriginalConstructor()
            ->getMock();
        $this->composer->setDownloadManager($this->dm);

        $this->repository = $this->getMock('Composer\Repository\InstalledRepositoryInterface');
        $this->io = $this->getMock('Composer\IO\IOInterface');
    }

    /**
     * tearDown
     *
     * @return void
     */
    public function tearDown()
    {
        $this->fs->removeDirectory($this->vendorDir);
        $this->fs->removeDirectory($this->binDir);
    }

    /**
     * testSupports
     *
     * @return void
     *
     * @dataProvider dataForTestSupport
     */
    public function testSupports($type, $expected)
    {
        $installer = new WordPressInstaller($this->io, $this->composer);
        $this->assertSame($expected, $installer->supports($type), sprintf('Failed to show support for %s', $type));
    }

    /**
     * dataForTestSupport
     */
    public function dataForTestSupport()
    {
        return array(
            array('wordpress', false),
            array('wordpress-core', true),
            array('wordpress-theme', true),
            array('wordpress-plugin', true)
        );
    }

    /**
     * testInstallPath
     *
     * @dataProvider dataForTestInstallPath
     */
    public function testInstallPath($type, $path, $name)
    {
        $installer = new WordPressInstaller($this->io, $this->composer);
        $package = new Package($name, '1.0.0', '1.0.0');
        $package->setType($type);

        $result = $installer->getInstallPath($package);
        $this->assertEquals($path, $result);
    }

    /**
     * testCustomInstallPath
     *
     * @dataProvider dataForTestCustomInstallPath
     */
    public function testCustomInstallPath($type, $path, $name)
    {
        $installer = new WordPressInstaller($this->io, $this->composer);
        $package = new Package($name, '1.0.0', '1.0.0');
        $package->setType($type);

        $consumerPackage = new RootPackage('foo/bar', '1.0.0', '1.0.0');
        $this->composer->setPackage($consumerPackage);
        $consumerPackage->setExtra(array(
            'wordpress-dir' => 'wp/',
            'content-dir' => 'wordpress/wp-content/',
        ));

        $result = $installer->getInstallPath($package);
        $this->assertEquals($path, $result);
    }

    /**
     * dataForTestInstallPath
     */
    public function dataForTestInstallPath()
    {
        return array(
            array('wordpress-core', 'wordpress/', 'queensbridge/wordpress'),
            array('wordpress-theme', 'content/themes/custom-theme/', 'queensbridge/custom-theme'),
            array('wordpress-plugin', 'content/plugins/custom-plugin/', 'queensbridge/custom-plugin'),
            array('wordpress-plugin', 'content/plugins/no-vendor-plugin/', 'no-vendor-plugin')
        );
    }

    /**
     * dataForTestCustomInstallPath
     */
    public function dataForTestCustomInstallPath()
    {
        return array(
            array('wordpress-core', 'wp/', 'queensbridge/wordpress'),
            array('wordpress-theme', 'wordpress/wp-content/themes/custom-theme/', 'queensbridge/custom-theme'),
            array('wordpress-plugin', 'wordpress/wp-content/plugins/custom-plugin/', 'queensbridge/custom-plugin'),
            array('wordpress-plugin', 'wordpress/wp-content/plugins/no-vendor-plugin/', 'no-vendor-plugin')
        );
    }

}
