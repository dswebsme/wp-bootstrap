wp-bootstrap
===================

**A library of action hooks, filters and callback functions for theme developers building Bootstrap 3.x compatible themes for WordPress.**

WordPress Compatibility
-----------------------
The WP-Bootstrap library utilizes action hooks and filters introduced as recently as WordPress 4.0. Older versions of WordPress will simply ignore hooks it doesn't recognize. This section of the README will be updated when regression tests have been performed to determine version specific compatibility.

Bootstrap 3.x Compatibility
---------------------------
The WP-Bootstrap assumes the presence of Bootstrap 3.x (CSS, JS, Fonts) in your theme. The location of the Bootstrap 3.x framework in your theme doesn't matter. If you attempt to load this library into a Bootstrap 2.x based theme, you're going to have a bad time.

Notes
-----
**Last Updated: October 7, 2014**
This library is under active development as part of a new Bootstrap 3.x based WordPress theme. It is far from complete. In short **there will be bugs**. Contributions are welcome.

The excellent **wp_bootstrap_navwalker** class by **Edward McIntyre (@twittem)** is built into this library for convenience purposes. The standalone project is available at https://github.com/twittem/wp-bootstrap-navwalker. I am neither an owner or contributor on that project and take no credit for it.

This library **does not** make any permanent changes to your code, content or raw MySQL data. If your theme doesn't look right after installing the library **do not panic**. Simply remove the library from your theme to find your happy place.

Installation
------------
1. Place the **wp-boostrap.php** file in your WordPress theme folder `/wp-content/theme-name/`

2. Open the **functions.php** file in your WordPress theme `/wp-content/theme-name/functions.php` and add the following code:

```php
// Load the WordPress Boostrap library
require_once('wp-bootstrap.php');
```

3. Save the file and load your site in a browser

Changelog
---------
**0.3.1**
Initial commit includes basic handling of NAV, CONTENT and COMMENTS and improved documentation.
