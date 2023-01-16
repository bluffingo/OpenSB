<h1 align="center">openSB Beta 3.0.0</h1>
<p align="center">
<img src="https://user-images.githubusercontent.com/45898787/202602835-5e178385-4fee-4b31-9c25-7337e242a748.png">
</p>

## How to setup an openSB instance.

It should be noted that this codebase is a bit clunky and has not aged that well. Do not expect everything to work, especially uploading.

1. Get an Apache server with PHP and MariaDB up and running, including Composer and the PHP GD library extension. Please note that NGINX is currently unsupported.
1. Setup a virtual host. Look below the steps for an example.
1. Run `composer update` from the terminal.
1. Copy `config.sample.php`, rename it to `config.php` and fill in your database credentials.
1. Import the database template found in `sql/` into the database you want to use.
1. Run the `compile-scss-dartsass` script available in the tools directory to generate CSS. This will be a bit more complicated for Windows users.

### Production specific

1. Instead of installing dependencies using `composer update` you do `composer update --no-dev`
1. Make the `dynamic/` and `templates/cache/` directories writable by your web server.
1. Modify `$branding` to replace openSB branding with your custom branding. Check the `public/assets/placeholder` directory for reference.

### Development specific

1. Disable Twig's template caching by setting `$tplNoCache` to true.
1. Enable debugging features by setting `$isDebug` to true.
1. If you want to be able to upload during development, make the `dynamic/` directory and the directories inside it writable by your web server.

### Virtual host example
You will have to modify the directories to match your instance's location.
```
<VirtualHost *> 
    ServerName localhost
    DocumentRoot "C:/xampp/openSB/public"

    Alias /dynamic "C:/xampp/openSB/dynamic"

    <Directory "C:/xampp/openSB">
        Options Indexes FollowSymLinks
	Require all granted
	AllowOverride All
    </Directory>
</VirtualHost>
```

## Questions

### Where can I translate openSB?

openSB translations: https://crowdin.com/project/squarebracket

Relative time translations: https://github.com/mpratt/RelativeTime

### Why do I get 404 errors when I click on thumbnails?

Assuming you use Apache and have the rewrite module installed, this is because AllowOverride is turned off. See the virtual host example above for a quick fix.
