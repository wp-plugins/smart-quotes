=== Smart Quotes ===
Contributors: tfnab
Donate link: http://ten-fingers-and-a-brain.com/donate/
Tags: smart quotes, curly quotes, curly, quotes, wptexturize
Requires at least: 2.9
Tested up to: 3.3
Stable tag: trunk

Change the quotation marks that are automatically rendered as smart or curly quotes inside your content.

== Description ==

Change the quotation marks, that are automatically rendered as smart or curly quotes inside your content, from the default English style (&#8220;&#8230;&#8221;) to anything you like, e.g. to Croatian/Hungarian/Polish/Romanian style quotation marks (&#8222;&#8230;&#8221;), Czech or German style (&#8222;&#8230;&#8220;), Danish style (&#187;&#8230;&#171;), Finnish or Swedish style (&#8221;&#8230;&#8221;), French style (&#171;&nbsp;&#8230;&nbsp;&#187; &ndash; with spaces), Greek/Italian/Norwegian/Portuguese/Russian/Spanish/Swiss style (&#171;&#8230;&#187; &ndash; without spaces), Japanese or Traditional Chinese style (&#12300;&#8943;&#12301;), or actually to any arbitrary character combination of your choice. Of course you can turn off curly quotes entirely by picking the so-called &quot;dumb&quot; quotes (&quot;&#8230;&quot;).

== Installation ==

1. Upload the entire `smart-quotes` folder to the wp-content/plugins directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to the 'Writing Settings' screen where you will be able to configure your 'Smart Quotes'

== Changelog ==

= 0.3 =
* fixed bug where CSS styles for &lt;q> element were styled incorrectly when user hadn't picked quotation marks
* i18n
* L10n for German (de_DE)
* tested with older WordPress versions: now tagged to require 2.9 (instead of 3.2)

= 0.2 =
* added support for the &lt;q> element
* admin stylesheet cleanup (no longer using unit "px")
* link to "Writing Settings" page on "Plugins" page

= 0.1 =
* initial public release

== Upgrade Notice ==

= 0.3 =
bugfix release; German language file added (Deutsche Übersetzung)

= 0.2 =
added support for the &lt;q> element
