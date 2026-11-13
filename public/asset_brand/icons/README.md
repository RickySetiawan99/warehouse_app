# GroseraID — Icon Set (33 ikon)

Satu set ikon garis (stroke 1.8 pada grid 24×24, ujung dan sambungan membulat) untuk UI, chatbot, dan landing page.

## Varian warna
- `orange` (#FF783F): ikon aksen, untuk light maupun dark
- `navy` (#0F172A): untuk latar terang
- `white` (#FFFFFF): untuk latar gelap
- `currentColor`: mengikuti `color` CSS, cocok untuk pengaturan tema otomatis

## Format
- `svg/<varian>/<nama>.svg`: vektor, bisa diskalakan tanpa batas
- `png/<varian>/<ukuran>px/<nama>.png`: ukuran 24, 48, dan 96 px, transparan
- `sprite.svg`: semua ikon dalam satu file. Pakai dengan `<svg><use href="sprite.svg#gr-bot"/></svg>`

## Daftar ikon
- `produk-grosir`
- `toko-supplier`
- `transaksi-aman`
- `pengiriman-cepat`
- `dukungan-24-7`
- `cart`
- `wallet`
- `tag`
- `invoice`
- `chart`
- `trending-up`
- `star`
- `check-circle`
- `check`
- `map-pin`
- `user`
- `users`
- `bell`
- `search`
- `menu`
- `plus`
- `arrow-right`
- `close`
- `refresh`
- `clock`
- `mail`
- `phone`
- `moon`
- `send`
- `attach`
- `mic`
- `bot`
- `message-circle`

## Pemakaian di web
```html
<svg class="icon" width="24" height="24"><use href="icons/sprite.svg#gr-send"/></svg>
```
Gunakan `color` pada elemen induk untuk mengatur warnanya, lalu pakai varian `currentColor`.
