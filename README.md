# Php-biere-library
This is a PHP installation for The Biere Library restaurant's website.  The Biere Library is a Belgian restaurant located in Corvallis, Oregon.

## Dependencies
* Apache >= 2.4
* PHP > 7.3
* Composer (PHP package manager)
** For installation on Windows see [the documentation](https://getcomposer.org/doc/00-intro.md#installation-windows).

## Installation
Php-biere-library should be installed in a working sub-directory of an Apache2 web server.
Additionally, the <code>sites/</code> and <code>themes/</code> directories should be populated.  

```bash
cd my-document-root
git clone https://github.com/ocdladefense/php-biere-library.git
git submodule update --init --recursive
cp sites/example-sites.php sites/sites.php
composer update
npm update
cp .htaccess-example .htaccess
```

### Theme installation
The <code>[biere-library](https://github.com/ocdladefense/theme-biere-library)</code> theme should be installed in the <code>themes/</code> directory.
```bash
cd themes/
git clone git@github.com:ocdladefense/theme-biere-library.git
```

### .htaccess
The repository includes an example .htaccess file, <code>.htaccess-example</code>.

### Validating the installation
After performing the above installation steps, you should then be able to open a web browser and navigate to http://localhost/my-document-root.

## More information
For more information see the [server outline](https://docs.google.com/drawings/d/1eHy1dVjZhxTji9msrA00NKTfgfw7kxzlPjal8Utf40M/edit?usp=sharing)
=======

# The Biere Library Instagram Integration

## Possible Tags
* #bl-home
* #bl-event
* #bl-drink-The-Drink-Name
* #bl-food-The-Food-Name
* #bl-event-The-Event-Name


## Reference Links

### Retrieve recent posts by tag name
```
// Retrieve posts by tag name
https://api.instagram.com/v1/tags/{tag-name}/media/recent?access_token={token}
```

### Retrieve recent posts
```
// Retrieve recent posts for the specified user (by user id)
https://api.instagram.com/v1/users/45951573/media/recent/?access_token={token}
```
