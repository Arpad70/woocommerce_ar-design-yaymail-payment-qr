# Ar Design YayMail Payment QR

Malý samostatný WordPress plugin pre ar-design.sk.

## Čo robí
- registruje shortcode `[ard_yaymail_payment_qr_block]` pre YayMail / WooCommerce e-maily,
- vykreslí dvojstĺpcový blok s platobnými údajmi a QR kódom,
- skladá QR payload serverovo v PHP, takže nerozbíja HTML `img src` atribút,
- podporuje aktualizácie z GitHub Releases priamo vo WordPresse.

## Použitie v YayMail
Do HTML/Text bloku vlož:

`[ard_yaymail_payment_qr_block]`

Voliteľne pre preview:

`[ard_yaymail_payment_qr_block preview_order_number="14092" preview_amount="20.03"]`

## Predvolené platobné údaje
- Firma: AR DESIGN s.r.o.
- Banka: Všeobecná úverová banka, a.s. Poprad
- IBAN: SK04 0200 0000 0038 7078 8755
- BIC: SUBASKBX
- Mena: EUR

## GitHub release workflow
Aby fungovali aktualizácie vo WordPresse, publikovaný release musí obsahovať ZIP asset s názvom:

- `ar-design-yaymail-payment-qr.zip`

Repozitár očakávaný v hlavičke pluginu:

- `Arpad70/woocommerce_ar-design-yaymail-payment-qr`
