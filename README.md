<h1 align="center">squareBracket Beta 3.0.0</h1>

<p align="center">
<img src="https://user-images.githubusercontent.com/45898787/173667230-5cdb4b50-75ac-4bc6-ad61-873e4466f855.png">
</p>

<h3 align="center"><a href="https://pok.byteemail.com/">squareBracket's live website</a></h3>

## How to setup an instance squareBracket.
1. Get an Apache (NGINX is untested) with PHP and MariaDB up and running, including Composer.
1. Setup some virtual host shit, look below.
1. Run `composer update` from the terminal.
1. Copy `config.sample.php`, rename it to `config.php` and fill in your database credentials.
1. Import the database dump found in `sql/` into the database you want to use.
1. Either run the `compile-scss-sassc` or `compile-scss-dartsass` script available in the tools directory to generate CSS. This will be a bit more complicated for Windows users.

### Production specific
1. Instead of installing dependencies using `composer update` you do `composer update --no-dev`
1. Make the `videos/`, `templates/cache/` and `assets/thumb/` directories writable by your web server.

### Development specific

1. Disable Twig's template caching by setting `$tplNoCache` to true.
1. Enable debugging features by setting `$isDebug` to true.
1. If you want to be able to upload videos during development, make the `videos/` and `assets/thumb/` directory writable by your web server.

### Virtual Host Example
You will have to modify the directories to match your instance's location.
```
<VirtualHost *> 
    ServerName localhost
    DocumentRoot "C:/xampp/squarebracket/public"

    Alias /dynamic "C:/xampp/squarebracket/dynamic"
    Alias /bulmajs "C:/xampp/squarebracket/vendor/npm-asset/vizuaalog--bulmajs/dist/"

    <Directory "C:/xampp/squarebracket">
        Options Indexes FollowSymLinks MultiViews
	Require all granted
    </Directory>
</VirtualHost>
```

## Questions

### How do I translate squareBracket?

squareBracket Translations (MIGHT BE BROKEN): https://crowdin.com/project/squarebracket

RelativeTime Repo (used for dates): https://github.com/mpratt/RelativeTime

### Why ditch Bootstrap?
We had problems with it, hense we're writing our own custom SCSS named Finalium for squareBracket's new layout, codenamed sbNext. However, Finalium uses parts of the Bootstrap 3 grid system for better compatibility.
