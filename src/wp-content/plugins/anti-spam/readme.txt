=== Anti-spam ===
Contributors: webvitaly
Donate link: http://web-profile.com.ua/donate/
Tags: spam, spammer, spammers, comment, comments, antispam, anti-spam, block-spam, spamfree, spam-free, spambot, spam-bot, bot
Requires at least: 3.0
Tested up to: 4.0
Stable tag: 3.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

No spam in comments. No captcha.

== Description ==

**[WordPress Pro plugins](http://codecanyon.net/popular_item/by_category?category=wordpress&ref=webvitaly)** |
**[Anti-spam Pro](http://codecanyon.net/item/antispam-pro/6491169?ref=webvitaly "Upgrade to Pro")** |
**[Anti-spam](http://web-profile.com.ua/wordpress/plugins/anti-spam/ "Plugin page")** |
**[Donate](http://web-profile.com.ua/donate/ "Support the development")**

**Why humans should prove that they are humans by filling captchas? Lets bots prove that they are not bots with adding javascript to their user-agents!**

Anti-spam plugin blocks spam in comments automatically, invisibly for users and for admins.

* **no captcha**, because spam is not users' problem
* **no moderation queues**, because spam is not administrators' problem
* **no options**, because it is great to forget about spam completely

If you need more powerful and extended version with options you may try [Anti-spam Pro](http://codecanyon.net/item/antispam-pro/6491169?ref=webvitaly "Upgrade to Pro").

Plugin is easy to use: just install it and it just works.
Need [more info about the plugin](http://wordpress.org/plugins/anti-spam/faq/)?

After installing the Anti-spam plugin **try to submit a comment on your site being logged out**.
If you get an error - you may check the solution in the [Support section](http://wordpress.org/support/plugin/anti-spam) or submit a new topic with detailed description of your problem.

= Useful: =
* [Anti-spam Pro](http://codecanyon.net/item/antispam-pro/6491169?ref=webvitaly "Upgrade to Pro")
* ["Security-protection" - blocks brute-force attacks](http://wordpress.org/plugins/security-protection/ "stops brute-force attacks")
* [WordPress Pro plugins](http://codecanyon.net/popular_item/by_category?category=wordpress&ref=webvitaly)

== Installation ==

1. install and activate the plugin on the Plugins page
2. enjoy life without spam in comments

== Frequently Asked Questions ==

= How does Anti-spam plugin work? =

The blocking algorithm is based on 3 methods: 'invisible js-captcha', 'invisible input trap' and 'spam points'.

= How does 'invisible js-captcha' method work? =

The 'invisible js-captcha' method is based on fact that bots does not have javascript on their user-agents.
Extra hidden field is added to comments form.
It is the question about the current year.
If the user visits site, than this field is answered automatically with javascript, is hidden by javascript and css and invisible for the user.
If the spammer will fill year-field incorrectly - the comment will be blocked because it is spam.

= How does 'invisible input trap' method work? =

The 'invisible input trap' method is based on fact that almost all the bots will fill inputs with name 'email' or 'url'.
Extra hidden field is added to comments form.
This field is hidden for the user and user will not fill it.
But this field is visible for the spammer.
If the spammer will fill this trap-field with anything - the comment will be blocked because it is spam.

= How does 'spam points' method work? =

Every comment will have +1 spam point for each truthy statement below:

* empty COOKIE array
* URL field is not empty
* comment contains too many links (3 or more)
* comment contains html
* comment is too long (more than 2000 chars)
* comment contains rel="nofollow" code
* comment contains [/url] code

If the comment will have more than 3 spam points - the comment will be blocked because it is spam.

= How to test what spam comments are rejected? =

You may enable sending all rejected spam comments to admin email.
Edit [anti-spam.php](http://plugins.trac.wordpress.org/browser/anti-spam/trunk/anti-spam.php) file and find "$antispam_send_spam_comment_to_admin" and make it "true".

= Does Anti-spam plugin work with Jetpack Comments? =

Anti-spam plugin does not work with [Jetpack Comments](http://jetpack.me/support/comments/).
Jetpack Comments use iframe to insert comment form and it is impossible to access it via javascript because of security reasons.
If you use Jetpack Comments - you should find some other plugin to block spam.
You could try [Akismet](http://wordpress.org/plugins/akismet/), it is compatible with Jetpack Comments.

= Does Anti-spam plugin block spam from Contact or other forms? =

Anti-spam plugin blocks spam only in comments form section and does not block spam from any other forms on site.

= Does Anti-spam plugin work with bbPress? =

No.

= Does Anti-spam plugin work with ajax comments forms? =

Some themes, for example [p2](http://wordpress.org/themes/p2), use ajax to submit comment.
But if the script of the theme will not submit extra Anti-spam fields - so Anti-spam plugin will not work.

= What is the percentage of spam blocked? =

Anti-spam plugin blocks more than 99.5% of spam messages.

= What about trackback spam? =

Users rarely use trackbacks because it is manual and requires extra input. Spammers uses trackbacks because it is easy to cheat here.
Users use pingbacks very often because they work automatically. Spammers does not use pingbacks because backlinks are checked.
So trackbacks are blocked by default but pingbacks are enabled. You may enable trackbacks if you use it.
Edit [anti-spam.php](http://plugins.trac.wordpress.org/browser/anti-spam/trunk/anti-spam.php) file and find "$antispam_allow_trackbacks" and make it "true".
You may read more about the [difference between trackbacks and pingbacks](http://web-profile.com.ua/web/trackback-vs-pingback/).

= And one more extra note... =

If site has caching plugin enabled and cache is not cleared or if theme does not use 'comment_form' action - Anti-spam plugin does not worked.
So in new version of the plugin now whole input added via javascript if it does not exist in html of the comments form.

= Not enough information about the plugin? =

You may check out the [source code of the plugin](http://plugins.trac.wordpress.org/browser/anti-spam/trunk/anti-spam.php).
The plugin is pretty small and easy to read. I was trying my best to make plugin's code clean.
Plugin is lightweight but it makes all the dirty work against spam pretty good. You may give it a try.

= How to reduce the amount of spam? =

Do not order spam-newsletters because people hate spam and people will not like products received from spam.
Do not order products from spam. If spam will be less effective than spammers will stop sending it.


== Changelog ==
= 3.1 - 2014-12-04 =
* remove log notices

= 3.0 - 2014-12-02 =
* added 'spam points' algorithm
* bugfixing
* enqueue script only for pages with comments form and in the footer (thanks to dougvdotcom)
* refactor code structure

= 2.6 - 2014-11-30 =
* reverting to ver.2.2 state (enqueue script using 'init' hook and into the header) because users start receiving spam messages

= 2.5 - 2014-11-26 =
* update input names

= 2.4 - 2014-11-25 =
* update input names

= 2.3 - 2014-11-23 =
* enqueue script only for pages with comments form and in the footer (thanks to dougvdotcom)
* clean up code

= 2.2 - 2014-08-03 =
* clear value of the empty input because some themes are adding some value for all inputs
* updated FAQ section

= 2.1 - 2014-02-15 =
* add support for comments forms loaded via ajax

= 2.0 - 2014-01-04 =
* bug fixing
* updating info

= 1.9 - 2013-10-23 =
* change the html structure

= 1.8 - 2013-07-19 =
* removed labels from plugin markup because some themes try to get text from labels and insert it into inputs like placeholders (what cause an error)
* added info to FAQ section that Anti-spam plugin does not work with Jetpack Comments

= 1.7 - 2013-05-31 =
* if site has caching plugin enabled and cache is not cleared or if theme does not use 'comment_form' action - Anti-spam plugin does not worked; so now whole input added via javascript if it does not exist in html

= 1.6 - 2013-05-05 =
* add some more debug info in errors text

= 1.5 - 2013-04-15 =
* disable trackbacks because of spam (pingbacks are enabled)

= 1.4 - 2013-04-13 =
* code refactor
* renaming empty field to "*-email-url" to trap more spam

= 1.3 - 2013-04-10 =
* changing the input names and add some more traps because some spammers are passing the plugin

= 1.2 - 2012-10-28 =
* minor changes

= 1.1 - 2012-10-14 =
* sending answer from server to client into hidden field (because client year and server year could mismatch)

= 1.0 - 2012-09-06 =
* initial release