# Custom Composer Installers [![Build Status](https://secure.travis-ci.org/ckhampus/composer-installers.png)](http://travis-ci.org/ckhampus/composer-installers)
This repository contains at the moment two very specific package installer for Chef cookbooks and WordPress Themes, Plugins and the Core itself.

## Cookbook Installer
The cookbook installer is for handling Chef cookbooks. By default cookbooks are installed in `cookbook/`, but you can change that with the `cookbooks-dir` option.

```json
{
  "extra": {
    "cookbooks-dir": "path/to/cookbooks/"
  }
}
```

## WordPress Installer
This installer is intended for WordPress themes and plugins, but also for WordPress itself. Plugins and themes are installed to `content/plugins/` and `content/themes/`, while WordPress is installed to `wordpress/`. This can be changed with the `wordpress-dir`and `content-dir` options.

```json
{
  "extra": {
    "wordpress-dir": "wp/",
    "content-dir": "wp/wp-content/"
  }
}
```