# 📱 Grosera Warehouse App (WMS Mobile Scanner)

> **Antarmuka Mobile & Scanner Staf Gudang untuk Ekosistem Grosera**  
> Dibuat: 8 November 2026  
> Status: **Tahap Desain & Mockup UI/UX Pro Max**

---

## 🎨 1. Filosofi & Ergonomi Desain (UI/UX Guidelines)

Aplikasi mobile staf gudang dirancang dengan standar ergonomi tinggi yang disesuaikan dengan kondisi kerja nyata di gudang fisik:
1. **Target Sentuh Besar (Min 48px):**  
   Tombol aksi (stepper `+` / `−`, konfirmasi item, pemicu scanner) dibuat besar agar mudah ditekan dengan satu ibu jari atau saat staf menggunakan sarung tangan kerja.
2. **Kontras Tinggi (Dark Mode Default):**  
   Menggunakan latar gelap kontras tinggi (`#0F172A` / `#020617`) dengan aksen **Grosera Orange** (`#FF783F`) dan **Emerald Green** (`#10B981`) agar teks dan angka stok tetap terbaca jelas di bawah lampu gudang yang temaram maupun saat silau di area bongkar muat luar.
3. **Penyelarasan Multi-Satuan (Multi-UOM Clarity):**  
   Secara visual membedakan label kemasan **DUS (Isi 24)** vs **PCS (Satuan Dasar)** dengan badge warna kontras, mencegah salah hitung saat bongkar muat.
4. **Indikator FEFO & Status Kritis:**  
   Peringatan tanggal kadaluarsa menggunakan warna semantik (Merah = < 30 hari, Kuning = < 90 hari, Hijau = aman).
5. **Umpan Balik Audio & Getar (Sound & Haptic Feedback):**  
   Menyediakan bunyi *beep* berfrekuensi tinggi saat scan barcode berhasil dan bunyi nada rendah saat scan salah/gagal.

---

## 📐 2. Alur 6 Layar Utama (Interactive Screens)

| Layar | Nama Modul | Fungsi Utama di Lapangan |
| :--- | :--- | :--- |
| **Layar 1** | **Dashboard Shift** | Ringkasan tugas shift aktif (truk PO masuk, opname pending, pesanan siap pick), pemilih gudang aktif. |
| **Layar 2** | **Quick Scanner** | Kamera viewfinder dengan garis laser, senter toggle, deteksi barcode instan, popup stok multi-gudang & batch FEFO. |
| **Layar 3** | **Stock Opname** | Audit rak tanpa kertas: pilihan satuan Dus vs Pcs, stepper hitung cepat, kalkulasi selisih otomatis, submit status `pending`. |
| **Layar 4** | **Inbound PO Supplier** | Pembongkaran truk kiriman pabrik/supplier: checklist barang, input nomor batch fisik & tanggal kadaluarsa, progress bar. |
| **Layar 5** | **Outbound Picking** | Verifikasi surat jalan reseller: scan barcode barang sebelum naik ke mobil box, proteksi salah ambil barang. |
| **Layar 6** | **Mutasi Antar-Gudang** | Konfirmasi barang transfer in-transit yang tiba dari gudang cabang/pusat. |

---

## 🌐 3. Cara Membuka Mockup Interaktif

File mockup interaktif bersifat mandiri (*self-contained*) dan dapat dibuka langsung di browser:

```bash
# Buka file mockup di browser favorit Anda:
open /Users/ricky/Documents/project/latihan/laravel/moderngrosir_app/warehouse_app/mockups/index.html
```

Di dalam mockup, Anda dapat:
- Menekan tombol navigasi atas atau bottom nav untuk berpindah ke 6 layar berbeda.
- Menekan tombol **"Simulasi Scan Barcode"** untuk mendengar bunyi *beep scanner* dan melihat hasil deteksi instan.
- Menekan tombol `+` dan `−` pada kalkulasi Opname untuk melihat perubahan angka selisih secara otomatis.
- Menekan tombol **"Flash"** untuk menyalakan simulasi lampu senter ponsel.

