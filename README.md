wp-bootstrap
===================

**A library of action hooks, filters and callback functions for theme developers building Bootstrap compatible themes for WordPress.**

WordPress Compatibility
-----------------------
WP-Bootstrap utilizes action hooks and filters introduced as recently as WordPress 4.0. Older versions of WordPress will ignore hooks it doesn't recognize. This section of the README will be updated when regression tests have been performed to determine version specific compatibility.

Bootstrap Compatibility
---------------------------
WP-Bootstrap assumes the presence of Bootstrap 3.x in your theme and that all assets (CSS, JS and Fonts) are loaded and available at runtime.

**Warning:** If you attempt to add WP-Bootstrap to a Bootstrap 2.x theme, you're going to have a bad time.

Notes
-----
**Last Updated: October 9, 2014**
WP-Bootstrap is under active development as part of a new Bootstrap based WordPress theme. It is far from complete. In short **there will be bugs**. Contributions are welcome.

The excellent **wp_bootstrap_navwalker** class by **Edward McIntyre (@twittem)** is built into WP-Bootstrap for convenience. The standalone project is available at https://github.com/twittem/wp-bootstrap-navwalker. I am not an active contributor on that project and take no credit for it.

WP-Bootstrap **does not** make any permanent changes to your code, content or raw MySQL data. If your theme doesn't look right after installing WP-Bootstrap, **do not panic**. Simply remove WP-Bootstrap from your theme and everything will fall back into place.

Installation
------------
1. Place the **wp-boostrap.php** file in your WordPress theme folder `/wp-content/theme-name/`

2. Open the **functions.php** file in your WordPress theme `/wp-content/theme-name/functions.php` and add the following code:

```php
// Load WP-Boostrap
require_once('wp-bootstrap.php');
```

3. Save the file and load your site in a browser

Changelog
---------
**0.3.1**
Initial commit includes basic handling of NAV, CONTENT and COMMENTS and improved documentation.
