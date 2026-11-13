# GroseraID — Paket Aset Lengkap

Gaya: monogram G dengan kubus, palet oranye (#FF783F) dan navy (#0F172A), font Poppins. Semua aset tersedia untuk mode terang dan gelap.

## Struktur dan isi

| Folder | Isi | Mode |
|---|---|---|
| `light/` | Logo utama, horizontal, wordmark, ikon, mono navy, PNG 24 sampai 1024 px | Latar terang |
| `dark/` | Sama dengan `light/`, teks putih | Latar gelap |
| `shared/` | App icon (orange, soft, navy), favicon, avatar sosial, banner OG, token CSS | Keduanya |
| `icons/` | 33 ikon garis, varian orange, navy, white, currentColor. SVG, PNG 24/48/96 px, sprite.svg, preview | Keduanya |
| `chatbot/` | Avatar bot (terang dan gelap), tombol launcher, avatar pengguna, indikator mengetik, ilustrasi empty state | Keduanya |
| `web/` | `landing-page.html` (dengan widget chatbot), `chatbot-widget-demo.html` | Terang dan gelap |
| `social/` | Instagram post dan story (terang dan gelap), LinkedIn banner, header X, header email, Google Play feature graphic | Terang dan gelap |
| `print/` | Kartu nama 90×55 mm, 300 dpi (depan, belakang, PDF) | Terang dan gelap |
| `email/` | `signature.html` dan logo tanda tangan (terang dan gelap) | Terang dan gelap |
| `pwa/` | Ikon 192 dan 512 px, versi maskable, `manifest.webmanifest` | Keduanya |
| `ref/` | Potongan mockup dari brand sheet, hanya untuk referensi visual | — |

## Penggunaan singkat
- **Ikon di web**: pakai `icons/sprite.svg` dengan `<use href="icons/sprite.svg#gr-send"/>`, atau varian `svg/currentColor/` untuk mengikuti tema.
- **Logo**: pakai `light/` di latar terang dan `dark/` di latar gelap. Di HTML, `<picture>` dengan `prefers-color-scheme` bisa memilih otomatis.
- **Landing page**: buka `web/landing-page.html`. Jika dipasang di server, pertahankan struktur folder agar path relatif tetap berfungsi.
- **Cetak**: file cetak belum memiliki bleed. Tambahkan bleed 3 mm sebelum produksi.

## Catatan
- Teks di semua desain (headline, CTA, fitur, balasan chatbot) adalah contoh. Sesuaikan dengan copy final.
- Asisten di widget masih menjawab dengan balasan tetap. Sambungkan `userSay()` ke backend chatbot untuk produksi.
- Nomor telepon, email, dan alamat di kartu nama serta tanda tangan email adalah placeholder.
- Landing page sudah dicek visualnya dengan render lama. Sebaiknya diuji di Chrome, Safari, dan Firefox sebelum rilis.
- Foto mockup di `ref/` dihasilkan AI dan resolusinya rendah, sehingga tidak untuk produksi.
