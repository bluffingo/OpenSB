<h1 align="center">squareBracket Beta 2.0.2</h1>

<p align="center">
<img src="https://user-images.githubusercontent.com/45898787/140172623-b471f76c-05d0-4087-99e0-21712176846d.png"><br>
<img src="https://img.shields.io/discord/853036368712040498?style=flat">
<img src="https://img.shields.io/github/v/release/chazizsquarebracket/squarebracket?include_prereleases&label=lastest%20released&style=flat">
<img src="https://img.shields.io/github/release-date-pre/chazizsquarebracket/squarebracket?label=released&style=flat">
<img src="https://img.shields.io/github/commits-since/chazizsquarebracket/squarebracket/beta-2.0.1?include_prereleases&style=flat">
<img src="https://img.shields.io/github/repo-size/chazizsquarebracket/squarebracket?style=flat">
<a title="Crowdin" target="_blank" href="https://crowdin.com/project/squarebracket"><img src="https://badges.crowdin.net/squarebracket/localized.svg"></a>
<br><br>
<a href="https://www.youtube.com/channel/UCMnG3eA5QcSgIPsavuW4ubA">
<img src="https://img.shields.io/youtube/channel/subscribers/UCMnG3eA5QcSgIPsavuW4ubA?style=social">
</a>
<br>
</p>

<!--<h3 align="center"><a href="https://squarebracket.veselcraft.ru/">squareBracket's live website</a></h3>-->

## How to setup squareBracket.
1. Get a web server (Apache/NGINX) with PHP and MariaDB up and running, including Composer.
1. Run `composer update` from the terminal.
1. Copy `config.sample.php`, rename it to `config.php` and fill in your database credentials.
1. Import the database dump found in `sql/` into the database you want to use.
1. Either run the `compile-scss-sassc` or `compile-scss-pscss` script available in the tools directory to generate CSS.
1. (Optional for Discord webhook functionality) Enable the cURL module in PHP.

### Production specific
1. Instead of installing dependencies using `composer update` you do `composer update --no-dev`
1. Make the `videos/`, `templates/cache/` and `assets/thumb/` directories writable by your web server.

### Development specific
1. Disable Twig's template caching by setting `$tplNoCache` to true.
1. Enable debugging features by setting `$isDebug` to true.
1. If you want to be able to upload videos during development, make the `videos/` and `assets/thumb/` directory writable by your web server.

## Questions

### How do I translate squareBracket?
squareBracket Translations: https://crowdin.com/project/squarebracket

RelativeTime Repo (used for dates): https://github.com/mpratt/RelativeTime

### Why use Twig? Why not just PHP?
Twig literally makes HTML injection attacks a thing of the past. It's more short and concise than PHP's "templating" syntax, it supports layout inheritance and it allows for more code reuse and it's versatile for creating more frontends in the future. It's secure (it treats all variables as "unsafe" and automatically escapes them unless you explicitly mark them as safe), concise (its liquid-like syntax is shorter and way more appropriate for the context of templating) and fast (with caching enabled there's basically no overhead compared to not using Twig).

### Why ditch Bootstrap?
We had problems with it, hense we're writing our own custom SCSS for squareBracket's new layout, which is sbNext.
