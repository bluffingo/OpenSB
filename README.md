# squareBracket 2.0
An attempt at rewriting squareBracket, with a different vision.

## How to set this up
1. Install [composer](https://getcomposer.org/).
2. From the command prompt/terminal run `composer i`.
3. Copy `config.sample.php` and name it as `config.php`
4. Edit the config file, change the database auth details to the ones you use.

## Not-so frequently asked questions

### Will my old stuff on squareBracket 1.0 be on squareBracket 2.0?

Yes, but bulletins and empty accounts will not be imported.

### Why is this no longer a website based on a old version of YouTube?
If it's an old youtube clone, then it's going to be a a circlejerk of young people nostalgizing about some version of a popular website from years ago. Addtionally, these sorts of website are infamous of having a lot of heat and immature drama because 1 person isn't agreeing with someone else's opinion.

### Why not keep the old codebase (squareBracket 1.0) and make it secure?
1.0 suffered from over-engineering and garbage programming.

1. There were features no one really used (half-assed profile customization, bulletins, etc)
2. Tons of vunerbilities that were only patched up by making the SQL user not be able to use commands it wouldn't naturally use (so basically just by putting duct tape)
3. It was just using the remains of a project that died a few weeks before squareBracket's developement started.

### Why use Bootstrap 3? Why not Bootstrap 4/5 or *insert name of other CSS framework*?

Bootstrap 3 looked the best in my opinion. We had tried Bootstrap 5 and Semantic UI in the fact, but they didn't work.

### Why TWIG?
Twig literally makes HTML injection attacks a thing of the past. It's more short and concise than PHP's "templating" syntax, it supports layout inheritance and it allows for more code reuse and it's versatile for creating more frontends in the future if you'd ever want that.

It wouldn't look better if we removed Twig, it'd just look like old squareBracket. Probably just as insecure as old squareBracket as well.

It's secure (it treats all variables as "unsafe" and automatically escapes them unless you explicitly mark them as safe), concise (its liquid-like syntax is shorter and way more appropriate for the context of templating) and fast (with caching enabled there's basically no overhead compared to not using Twig). At least 150 million PHP projects use Twig, based on Packagist's stats. It's not some obscure little thing, it's a major part of modern PHP.

note by chaziz: fucktube, sniped's youtube clone using the same framebit codebase that squarebracket 1.0 did, got exploited to death.
