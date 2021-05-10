<h1 align="center">squareBracket</h1>

<p align="center">

<img src="https://img.shields.io/discord/737791548435071037?style=plastic">
<img src="https://img.shields.io/github/v/release/chazizsquarebracket/squarebracket?include_prereleases&label=lastest%20released&style=plastic">
<img src="https://img.shields.io/github/release-date-pre/chazizsquarebracket/squarebracket?label=released&style=plastic">
<img src="https://img.shields.io/github/commits-since/chazizsquarebracket/squarebracket/milestone-1?include_prereleases&style=plastic">
<img src="https://img.shields.io/github/repo-size/chazizsquarebracket/squarebracket&style=plastic"><br><br>
<a href="https://www.youtube.com/channel/UCMnG3eA5QcSgIPsavuW4ubA">
<img src="https://img.shields.io/youtube/channel/subscribers/UCMnG3eA5QcSgIPsavuW4ubA?style=social">
</a>
</p>

<h3 align="center"><a href="https://185.86.231.49/">Live website here ></a></h3>

## How to setup your squareBracket environment.
1. Install [composer](https://getcomposer.org/).
2. Run `composer i` from the terminal.
3. Copy `config.sample.php` and name it as `config.php`
4. Edit the config file, change the database auth details to the ones you use.

## Questions

### Will my videos/comments on PokTube be on squareBracket?
Yes, but note the following:
* Bulletins are not going to be imported. They were barely used by anyone.
* It is unknown if comment channels should be imported, along with some level of channel customization.

### Why not  still use the old PokTube codebase?
A lot of the code was garbage, and around the time the codebase was abandonned in favor of squareBracket, spaghetti code problems had started appearing.

### Why is the live website still running an old version of Milestone 1?
Near the end of Milestone 1, we made the switch to Bootstrap 5 as it recently came out. However, the SCSS renderer PHP code used had some security vulnerabilities. This issue is not currently fixed, so Milestone 1 is still in use.

### Why Twig?
Twig literally makes HTML injection attacks a thing of the past. It's more short and concise than PHP's "templating" syntax, it supports layout inheritance and it allows for more code reuse and it's versatile for creating more frontends in the future. It's secure (it treats all variables as "unsafe" and automatically escapes them unless you explicitly mark them as safe), concise (its liquid-like syntax is shorter and way more appropriate for the context of templating) and fast (with caching enabled there's basically no overhead compared to not using Twig).
