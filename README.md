# squareBracket 2.0
An attempt at rewriting squareBracket, with a different vision.

## How to set this up
1. Install [composer](https://getcomposer.org/).
2. From the command prompt/terminal run `composer i`.
3. Copy `config.sample.php` and name it as `config.php`
4. Edit the config file, change the database auth details to the ones you use.

## Not-so frequently asked questions

### Why is this no longer an old youtube clone?
If it's an old youtube clone, then people are going to think it's a circlejerk of special-ed children nostalgizing about some version of a popular website from years ago.

If it's some generic video sharing site and if it's good, Then hey! People could start using squareBracket!

### Why Bootstrap 3? Why not Bootstrap 4/5 or *insert name of other CSS framework*

Bootstrap 3 looked the best in my opinion. There were [2](https://cdn.discordapp.com/attachments/832695674662420500/832704559893708810/unknown.png) [attempts](https://cdn.discordapp.com/attachments/832695674662420500/832718470068043807/unknown.png) at converting the old codebase to use Bootstrap 5, but they all failed. Addtionally, we had [used Semantic UI](https://web.archive.org/web/20210301000232/https://squarebracket.me/) at one point, to lukewarm reception.

### Why TWIG?
Twig literally makes HTML injection attacks a thing of the past (and I know you like security), it's more short and concise than PHP's "templating" syntax, it supports layout inheritance and it allows for more code reuse and it's versatile for creating more frontends in the future if you'd ever want that.

It wouldn't look better if we removed Twig, it'd just look like old squareBracket. Probably just as insecure as old squareBracket as well.

It's secure (it treats all variables as "unsafe" and automatically escapes them unless you explicitly mark them as safe), concise (its liquid-like syntax is shorter and way more appropriate for the context of templating) and fast (with caching enabled there's basically no overhead compared to not using Twig). At least 150 million PHP projects use Twig, based on Packagist's stats. It's not some obscure little thing, it's a major part of modern PHP.
