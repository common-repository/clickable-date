=== Clickable Date ===
Contributors: iacons
Donate link: http://wabbieworks.com/
Tags: date, seo, archive
Requires at least: 1.5
Tested up to: 2.7
Stable tag: 1.0.1

Makes your posts' publication dates clickable

== Description ==

Converts your posts publication dates into clickable links which point back to the archive. 

== Installation ==

1. Upload `clickable-date.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

That's all. Congratulations, you've just installed and enable clickable date.


== Frequently Asked Questions ==

= Does this work with ugly permalinks? (?p=123) =

Of course! :)

= I've installed and enabled the plugin but nothing changed! What's wrong? =

Unfortunately, the dates are not converted automatically in WordPress versions prior to 2.x. Don't worry though, the plugin is still working but you have to replace some function calls on your own. In most of the cases, you should replace `the_time();` with `clickable_time();` and `the_date();` with `clickable_date();`. Just have a look at your themes files `index.php`, `single.php`, `category.php` and `archive.php` and you will find what are you looking for!

