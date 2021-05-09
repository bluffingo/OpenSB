<h1 align="center">squareBracket</h1>

<p align="center">

<img src="https://img.shields.io/discord/737791548435071037?style=plastic">
<img src="https://img.shields.io/github/v/release/chazizsquarebracket/squarebracket?include_prereleases&label=lastest%20released&style=plastic">
<img src="https://img.shields.io/github/release-date-pre/chazizsquarebracket/squarebracket?label=released&style=plastic">
</p>

<h3 align="center"><a href="https://185.86.231.49/">Live website here ></a></h3>

## How to setup squareBracket
1. Install [composer](https://getcomposer.org/).
2. From the command prompt/terminal run `composer i`.
3. Copy `config.sample.php` and name it as `config.php`
4. Edit the config file, change the database auth details to the ones you use.

## Questions

### Will my old stuff on squareBracket 1.0 (PokTube) be on squareBracket 2.0?

Yes, but bulletins will not be imported.

### Why not keep the old PokTube codebase and make it secure?
It suffered from over-engineering and garbage programming.

1. There were features no one really used (half-assed profile customization, bulletins, etc)
2. Tons of vunerbilities that were only patched up by making the SQL user not be able to use commands it wouldn't naturally use (so basically just by putting duct tape)
3. It was just using the remains of a project that died a few weeks before squareBracket's developement started.

### Why use Twig?
Twig literally makes HTML injection attacks a thing of the past. It's more short and concise than PHP's "templating" syntax, it supports layout inheritance and it allows for more code reuse and it's versatile for creating more frontends in the future if you'd ever want that.

It wouldn't look better if we removed Twig, It'd just be insecure as old PokTube as well.

It's secure (it treats all variables as "unsafe" and automatically escapes them unless you explicitly mark them as safe), concise (its liquid-like syntax is shorter and way more appropriate for the context of templating) and fast (with caching enabled there's basically no overhead compared to not using Twig). At least 150 million PHP projects use Twig, based on Packagist's stats. It's not some obscure little thing, it's a major part of modern PHP.
