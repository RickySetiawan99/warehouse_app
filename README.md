# Grosera Warehouse App (`warehouse_app`)

Aplikasi client operasional gudang dan pemindai stok mobile (**Laravel 12 + NativePHP Mobile**) untuk ekosistem **ModernGrosir / Grosera**. Terhubung langsung dengan REST API backend `moderngrosir_laravel` melalui endpoint `/api/store` dan `/api/warehouse/*`.

---

## Arsitektur & Fitur Utama

* **Laravel 12 + NativePHP Mobile (`com.moderngrosir.warehouse`)**: Berjalan tanpa database lokal bisnis (stateless session client via Sanctum bearer token di `WarehouseApiService`).
* **Kontrol Akses & Gudang Aktif**: Middleware `EnsureApiSession` menjaga seluruh rute operasional, sementara `ApiSessionExpired` otomatis menghapus sesi dan mengarahkan ulang ke halaman login saat token kedaluwarsa (`401`). Pengelola multi-gudang dapat mengganti lokasi gudang aktif secara langsung dari app bar (`POST /warehouse/select`).
* **Antarmuka Flat Design**: Palet kontras tinggi (Dark mode `#0F172A` default & Light mode `#F1F5F9`), aksen Grosera Orange (`#FF783F`), status Emerald (`#10B981`), tipografi `Plus Jakarta Sans` + `JetBrains Mono`, navigasi adaptif (bottom dock mobile, tab horizontal tablet, rail kiri desktop), umpan balik suara Web Audio (`playBeep`), dan shortcut keyboard `/` atau `Ctrl+K` ke kolom pemindai barcode.

---

## Pemetaan Modul, Rute Web, & Endpoint API

| Modul | Rute Web (`warehouse_app`) | Endpoint Backend (`moderngrosir_laravel`) | Fungsi Utama |
| :--- | :--- | :--- | :--- |
| **Autentikasi & Sesi** | `GET/POST /login`, `POST /logout`, `POST /warehouse/select` | `GET /api/store`, `POST /api/warehouse/login`, `GET /api/warehouse/profile`, `POST /api/warehouse/logout` | Login staf gudang (`warehouse` / `manage inventory` / `view inventory`), pemilih gudang aktif |
| **Dashboard Shift** | `GET /` | `/inbound/po`, `/outbound/orders`, `/transfers/in-transit`, `/opname/history` | KPI ringkasan shift aktif dan antrean kerja langsung |
| **Quick Scanner** | `GET /scanner` | `GET /api/warehouse/scan` | Deteksi SKU/barcode multi-satuan, stok lintas gudang, serta status kadaluarsa batch FEFO |
| **Stock Opname** | `GET /opname`, `POST /opname` | `GET /api/warehouse/opname/history`, `POST /api/warehouse/opname/count` | Hitung fisik stok per satuan (Dus/Pcs), kalkulasi selisih live, dan ajukan approval admin |
| **Inbound PO** | `GET /inbound`, `GET /inbound/{id}`, `POST /inbound/{id}/receive` | `GET /api/warehouse/inbound/po`, `GET /api/warehouse/inbound/po/{id}`, `POST /api/warehouse/inbound/po/{id}/receive` | Sorot SKU PO, input qty terima, nomor batch & kadaluarsa, serta notifikasi perubahan harga |
| **Outbound Picking** | `GET /picking`, `GET /picking/{id}`, `POST /picking/{id}/verify`, `POST /picking/{id}/complete` | `GET /api/warehouse/outbound/orders`, `GET /api/warehouse/outbound/orders/{id}`, `POST /api/warehouse/outbound/orders/{id}/verify-item`, `POST /api/warehouse/outbound/orders/{id}/complete` | Cek kecocokan barcode item sebelum muat dan selesaikan picking menjadi status `packing` (*Siap Muat*) |
| **Mutasi Gudang** | `GET /transfers`, `GET /transfers/{id}`, `POST /transfers/{id}/receive` | `GET /api/warehouse/transfers/in-transit`, `GET /api/warehouse/transfers/{id}`, `POST /api/warehouse/transfers/{id}/receive` | Konfirmasi penerimaan barang transfer in-transit dan catatan selisih |

---

## Menjalankan & Menguji Aplikasi

1. Pastikan backend `moderngrosir_laravel` berjalan, lalu sesuaikan `MODERNGROSIR_API_URL` di `.env` (default `http://127.0.0.1:8000/api`).
2. Jalankan aplikasi di server lokal:
   ```bash
   php artisan serve --port=8002
   ```
3. Jalankan suite tes fitur (`Http::fake` untuk seluruh alur autentikasi & 6 modul `/api/warehouse/*`):
   ```bash
   php vendor/bin/phpunit
   ```
   *(Verifikasi terakhir 13 Nov 2026: `11 tests, 74 assertions` lolos 100%).*

---

## Mockup Interaktif UI/UX

Prototipe HTML mandiri (*self-contained*) awal tetap disimpan di [`mockups/index.html`](mockups/index.html) sebagai referensi desain sistem dan animasi antarmuka:

```bash
open mockups/index.html
```
