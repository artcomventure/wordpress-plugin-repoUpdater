# WordPress Repository Plugin Updater

Keep your plugins, which are not listed on https://wordpress.org/plugins/, always in sync with the corresponding repository ... and update them **the WordPress way**.

* No programming skills needed! All settings are made in the backend.
* Works with _all_ repositories plugins. Even if sourcecode is in some subfolder (e.g. /build/).

## Installation

1. Upload files to the `/wp-content/plugins/` directory of your WordPress installation.
  * Either [download the latest files](https://github.com/artcomventure/wordpress-plugin-repoUpdater/archive/master.zip) and extract zip (optionally rename folder)
  * ... or clone repository:
  ```
  $ cd /PATH/TO/WORDPRESS/wp-content/plugins/
  $ git clone https://github.com/artcomventure/wordpress-plugin-repoUpdater.git
  ```
  If you want a different folder name than `wordpress-plugin-repoUpdater` extend the clone command by ` 'FOLDERNAME'` (replace the word `'FOLDERNAME'` by your chosen one):
  ```
  $ git clone https://github.com/artcomventure/wordpress-plugin-repoUpdater.git 'FOLDERNAME'
  ```
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. **Enjoy**

## Usage

Once activated you'll find the 'Repository Connect' settings page listed in the submenu of 'Plugins'.

![image](assets/screenshot-1.png)

1. Choose the plugin which is hosted on GitHub or Bitbucket.
2. Enter (at least) the GitHub or Bitbucket _Repository URL_.
  * Optionally enter the _Branch or Release_ to use (default is 'master' (GitHub) or 'tip' (Bitbucket)).
  * In case the WordPress files in the repository are not located in the root directory, enter the _Subfolder_ where to find them.
3. Click 'Save Changes'. Your data will be validated.
4. Go to the 'Plugins' screen, look for updates and proceed as known from WordPress.

## Support of ...

* public and (coming soon) privat repositories hosted on [**GitHub**](https://github.com) and [**Bitbucket**](https://bitbucket.org).

## Plugin Updates

Although the plugin is not listed on https://wordpress.org/plugins/, you can ... wait! What is this plugin about!? ;)

_We test our plugin through its paces, but we advise you to take all safety precautions before the update. Just in case of the unexpected._

## Questions, concerns, needs, suggestions?

Don't hesitate! [Issues](https://github.com/artcomventure/wordpress-plugin-repoUpdater/issues) welcome.
