=== Repository Updater ===

Contributors:
Donate link:
Tags: plugin update, repository, github, bitbucket
Requires at least:
Tested up to:
Stable tag:
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Update plugins directly from GitHub.

== Description ==

Keep plugins, which are not listed on https://wordpress.org/plugins/, always in sync ... **the WordPress way**.

== Installation ==

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

== Usage ==

Once activated you'll find the 'Repository Connect' settings page listed in the submenu of 'Plugins'.

1. Choose the plugin which is hosted on GitHub.
2. Enter (at least) the GitHub _Repository URL_.
  * Optionally enter the _Branch or Release_ to use (default is 'master').
  * In case the WordPress files in the repository are not located in the root directory, enter the _Subfolder_ where to find them.
3. Click 'Save Changes'. Your data will be validated.
4. Go to the 'Plugins' screen, look for updates and proceed as known from WordPress.

== Support of repositories hosted on ==

* [GitHub](https://github.com) and (coming soon) [Bitbucket](https://bitbucket.com)

== Plugin Updates ==

Although the plugin is not listed on https://wordpress.org/plugins/, you can ... wait! What is this plugin about!? ;)

_We test our plugin through its paces, but we advise you to take all safety precautions before the update. Just in case of the unexpected._

== Changelog ==

= Unreleased =

* Add https://bitbucket.com support

= 1.0.1 - 2016-03-10 =
**Added**

* build/

= 1.0.0 - 2016-03-10 =
**Added**

* Initial file commit
