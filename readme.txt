=== Ar Design YayMail Payment QR ===
Contributors: arpad70
Requires at least: 6.7
Tested up to: 6.9.4
Requires PHP: 8.0
Stable tag: 0.1.5
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.txt

Shortcode pro YayMail s platebnimi udaji a QR kodem pro WooCommerce objednavky.

== Description ==

Plugin pridava shortcode blok pro YayMail, ktery zobrazuje platebni udaje k objednavce a QR kod pro rychlou platbu.
Plugin je urceny pro WooCommerce a deklaruje kompatibilitu s HPOS.
QR kod je generovan server-side uvnitr pluginu, bez zavislosti na externi QR sluzbe tretich stran.

== Installation ==

1. Nahrajte plugin do adresare `/wp-content/plugins/`.
2. Aktivujte plugin v administraci WordPressu.
3. Pouzijte shortcode/blok v YayMail sablone objednavkovych e-mailu.

== Changelog ==

= 0.1.2 =
* QR kod je generovany plne server-side cez interni podpisany REST endpoint a lokalnu PHP kniznicu namiesto externej QR sluzby.
* Release pipeline je zjednotena s ostatnymi AR pluginmi vratane Composer-aware build kroku.

= 0.1.1 =
* Doplnen WordPress-standard `readme.txt`.
* Zachovana deklarace WooCommerce HPOS kompatibility.
* QR kod uz je generovan server-side pres interni REST endpoint a lokalni PHP knihovnu.

= 0.1.0 =
* Prvni verejna verze pluginu pro YayMail payment QR blok.
