-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Waktu pembuatan: 04 Apr 2026 pada 03.35
-- Versi server: 5.7.44
-- Versi PHP: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `db_tecno_spp`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tingkat` enum('X','XI','XII') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun_ajaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`id`, `nama_kelas`, `tingkat`, `tahun_ajaran`, `created_at`, `updated_at`) VALUES
(1, 'TKJ-1', 'XII', '2025/2026', '2026-02-28 17:46:18', '2026-02-28 17:46:18'),
(2, 'X-A', 'X', '2025/2026', '2026-03-01 06:07:35', '2026-03-01 06:07:35'),
(3, 'X-B', 'X', '2025/2026', '2026-03-01 06:07:35', '2026-03-01 06:07:35'),
(4, 'XI-IPA', 'XI', '2025/2026', '2026-03-01 06:07:35', '2026-03-01 06:07:35'),
(5, 'XI-IPS', 'XI', '2025/2026', '2026-03-01 06:07:35', '2026-03-01 06:07:35'),
(6, 'XII-IPA', 'XII', '2025/2026', '2026-03-01 06:07:35', '2026-03-01 06:07:35'),
(7, 'XII-IPS', 'XII', '2025/2026', '2026-03-01 06:07:35', '2026-03-01 06:07:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `aksi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id`, `user_id`, `aksi`, `detail`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 1, 'login', 'Login berhasil sebagai admin', '127.0.0.1', '2026-02-28 17:45:55', '2026-02-28 17:45:55'),
(2, 1, 'tambah_kelas', 'Menambah kelas: TKJ-1 (XII)', '127.0.0.1', '2026-02-28 17:46:18', '2026-02-28 17:46:18'),
(3, 1, 'tambah_spp', 'Menambah nominal SPP: TKJ-1 - Rp 125.000', '127.0.0.1', '2026-02-28 17:46:53', '2026-02-28 17:46:53'),
(4, 1, 'tambah_siswa', 'Menambah siswa: Budi Suntana (NIS: 123456)', '127.0.0.1', '2026-02-28 17:48:03', '2026-02-28 17:48:03'),
(5, 1, 'generate_tagihan_manual', 'Generate tagihan manual bulan 3/2026. Total: 1 tagihan dibuat.', '127.0.0.1', '2026-02-28 17:48:22', '2026-02-28 17:48:22'),
(6, 2, 'login', 'Login berhasil sebagai siswa', '127.0.0.1', '2026-02-28 17:49:10', '2026-02-28 17:49:10'),
(7, 2, 'ganti_password', 'Password berhasil diubah', '127.0.0.1', '2026-02-28 17:49:21', '2026-02-28 17:49:21'),
(8, 2, 'upload_bukti', 'Siswa Budi Suntana upload bukti pembayaran bulan Maret 2026', '127.0.0.1', '2026-02-28 17:49:45', '2026-02-28 17:49:45'),
(9, 1, 'verifikasi_pembayaran', 'Memverifikasi pembayaran Budi Suntana bulan Maret 2026 - Rp 125.000', '127.0.0.1', '2026-02-28 17:50:05', '2026-02-28 17:50:05'),
(10, 1, 'logout', 'Logout', '127.0.0.1', '2026-02-28 17:51:16', '2026-02-28 17:51:16'),
(11, 1, 'login', 'Login berhasil sebagai admin', '127.0.0.1', '2026-02-28 17:51:21', '2026-02-28 17:51:21'),
(12, 1, 'login', 'Login berhasil sebagai admin', '127.0.0.1', '2026-03-01 05:55:32', '2026-03-01 05:55:32'),
(13, 1, 'login', 'Login berhasil sebagai admin', '127.0.0.1', '2026-03-01 05:56:27', '2026-03-01 05:56:27'),
(14, 1, 'generate_tagihan_manual', 'Generate tagihan Januari - Desember 2026 untuk 1 siswa terpilih. Total: 9 tagihan dibuat.', '127.0.0.1', '2026-03-01 05:59:42', '2026-03-01 05:59:42'),
(15, 1, 'logout', 'Logout', '127.0.0.1', '2026-03-01 05:59:57', '2026-03-01 05:59:57'),
(16, 2, 'login', 'Login berhasil sebagai siswa', '127.0.0.1', '2026-03-01 06:01:07', '2026-03-01 06:01:07'),
(17, 1, 'auto_generate_tagihan', 'Auto-generate tunggakan: 62 tagihan dibuat. Detail: Budi Suntana (TKJ-1): 8 tagihan, Ahmad Fadilah (X-A): 9 tagihan, Siti Nurhaliza (X-A): 9 tagihan, Budi Santoso (X-B): 9 tagihan, Dewi Lestari (XI-IPA): 9 tagihan, Rizky Pratama (XI-IPS): 9 tagihan, Putri Anggraini (XII-IPA): 9 tagihan', '127.0.0.1', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(18, 6, 'login', 'Login berhasil sebagai siswa', '127.0.0.1', '2026-03-03 04:30:25', '2026-03-03 04:30:25'),
(19, 6, 'ganti_password', 'Password berhasil diubah', '127.0.0.1', '2026-03-03 04:33:00', '2026-03-03 04:33:00'),
(20, 6, 'upload_bukti_multi', 'Siswa Ahmad Fadilah upload 3 bukti untuk 2 bulan: Juli 2025, Agustus 2025', '127.0.0.1', '2026-03-03 04:49:00', '2026-03-03 04:49:00'),
(21, 6, 'logout', 'Logout', '127.0.0.1', '2026-03-03 04:49:23', '2026-03-03 04:49:23'),
(22, 1, 'login', 'Login berhasil sebagai admin', '127.0.0.1', '2026-03-03 04:49:31', '2026-03-03 04:49:31'),
(23, 1, 'generate_tagihan_manual', 'Generate tagihan Januari - Desember 2027 untuk semua siswa. Total: 84 tagihan dibuat.', '127.0.0.1', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(24, NULL, 'login_gagal', 'Login gagal untuk username: keuangan@gmail.com', '127.0.0.1', '2026-04-03 18:13:01', '2026-04-03 18:13:01'),
(25, NULL, 'login_gagal', 'Login gagal untuk username: admin@gmail.com', '127.0.0.1', '2026-04-03 18:13:20', '2026-04-03 18:13:20'),
(26, 12, 'login', 'Login berhasil sebagai admin', '127.0.0.1', '2026-04-03 18:14:11', '2026-04-03 18:14:11'),
(27, 12, 'reset_password', 'Mereset password siswa: Ahmad Fadilah (NIS: 10001)', '127.0.0.1', '2026-04-03 18:54:54', '2026-04-03 18:54:54'),
(28, 6, 'login', 'Login berhasil sebagai siswa', '127.0.0.1', '2026-04-03 18:55:11', '2026-04-03 18:55:11'),
(29, 6, 'ganti_password', 'Password berhasil diubah', '127.0.0.1', '2026-04-03 18:55:22', '2026-04-03 18:55:22'),
(30, 6, 'sandbox_webhook', 'Simulasi pembayaran sukses untuk Order ID TRX-1775242545-IVVWH sejumlah Rp 1500000.00', '127.0.0.1', '2026-04-03 18:59:35', '2026-04-03 18:59:35'),
(31, 6, 'sandbox_webhook', 'Simulasi pembayaran sukses untuk Order ID TRX-1775242927-MMZ9S sejumlah Rp 250000.00', '127.0.0.1', '2026-04-03 19:02:41', '2026-04-03 19:02:41'),
(32, 6, 'sandbox_webhook', 'Simulasi pembayaran sukses untuk Order ID TRX-1775243582-ZWXJN sejumlah Rp 250000.00', '127.0.0.1', '2026-04-03 19:13:20', '2026-04-03 19:13:20'),
(33, 6, 'sandbox_webhook', 'Simulasi pembayaran sukses untuk Order ID TRX-1775243627-4MYX4 sejumlah Rp 2500000.00', '127.0.0.1', '2026-04-03 19:13:53', '2026-04-03 19:13:53'),
(34, 12, 'tolak_pembayaran', 'Menolak pembayaran Ahmad Fadilah bulan Juli 2025', '127.0.0.1', '2026-04-03 19:15:19', '2026-04-03 19:15:19'),
(35, 12, 'tolak_pembayaran', 'Menolak pembayaran Ahmad Fadilah bulan Agustus 2025', '127.0.0.1', '2026-04-03 19:15:24', '2026-04-03 19:15:24'),
(36, 6, 'sandbox_webhook', 'Simulasi pembayaran sukses untuk Order ID TRX-1775243787-X6A0E sejumlah Rp 500000.00', '127.0.0.1', '2026-04-03 19:16:32', '2026-04-03 19:16:32'),
(37, 6, 'sandbox_webhook', 'Simulasi pembayaran sukses untuk Order ID TRX-1775243806-IMGQC sejumlah Rp 250000.00', '127.0.0.1', '2026-04-03 19:17:04', '2026-04-03 19:17:04'),
(38, 12, 'auto_generate_tagihan', 'Auto-generate tunggakan: 6 tagihan dibuat. Detail: Ahmad Fadilah (X-A): 1 tagihan, Siti Nurhaliza (X-A): 1 tagihan, Budi Santoso (X-B): 1 tagihan, Dewi Lestari (XI-IPA): 1 tagihan, Rizky Pratama (XI-IPS): 1 tagihan, Putri Anggraini (XII-IPA): 1 tagihan', '127.0.0.1', '2026-04-03 19:21:51', '2026-04-03 19:21:51'),
(39, 6, 'sandbox_webhook', 'Simulasi pembayaran sukses untuk Order ID TRX-1775244766-ODASJ sejumlah Rp 250000.00', '127.0.0.1', '2026-04-03 19:38:11', '2026-04-03 19:38:11'),
(40, 6, 'login', 'Login berhasil sebagai siswa', '127.0.0.1', '2026-04-04 03:06:15', '2026-04-04 03:06:15'),
(41, NULL, 'login_gagal', 'Login gagal untuk username: admin@gmail.com', '127.0.0.1', '2026-04-04 03:17:41', '2026-04-04 03:17:41'),
(42, 12, 'login', 'Login berhasil sebagai admin', '127.0.0.1', '2026-04-04 03:17:48', '2026-04-04 03:17:48'),
(43, 12, 'generate_tagihan_manual', 'Generate tagihan Januari - Desember 2028 untuk 1 siswa terpilih. Total: 12 tagihan dibuat.', '127.0.0.1', '2026-04-04 03:18:27', '2026-04-04 03:18:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '0001_01_01_000003_create_kelas_table', 1),
(5, '0001_01_01_000004_create_siswa_table', 1),
(6, '0001_01_01_000005_create_spp_table', 1),
(7, '0001_01_01_000006_create_tagihan_table', 1),
(8, '0001_01_01_000007_create_pembayaran_table', 1),
(9, '0001_01_01_000008_create_notifikasi_table', 1),
(10, '0001_01_01_000009_create_log_aktivitas_table', 1),
(11, '2026_04_04_014743_create_transaksi_sandboxes_table', 2),
(12, '2026_04_04_014938_add_transaksi_id_to_pembayaran_table', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `pesan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `notifikasi`
--

INSERT INTO `notifikasi` (`id`, `user_id`, `pesan`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 2, 'Tagihan bulan Maret 2026 telah tersedia. Nominal: Rp 125.000', 1, '2026-02-28 17:48:22', '2026-02-28 17:49:28'),
(2, 2, 'Pembayaran bulan Maret 2026 telah dikonfirmasi. Status: Lunas. ✅', 1, '2026-02-28 17:50:05', '2026-03-01 06:01:18'),
(3, 2, 'Tagihan bulan April 2026 telah tersedia. Nominal: Rp 125.000', 1, '2026-03-01 05:59:42', '2026-03-01 06:01:18'),
(4, 2, 'Tagihan bulan Mei 2026 telah tersedia. Nominal: Rp 125.000', 1, '2026-03-01 05:59:42', '2026-03-01 06:01:18'),
(5, 2, 'Tagihan bulan Juni 2026 telah tersedia. Nominal: Rp 125.000', 1, '2026-03-01 05:59:42', '2026-03-01 06:01:18'),
(6, 2, 'Tagihan bulan Juli 2026 telah tersedia. Nominal: Rp 125.000', 1, '2026-03-01 05:59:42', '2026-03-01 06:01:18'),
(7, 2, 'Tagihan bulan Agustus 2026 telah tersedia. Nominal: Rp 125.000', 1, '2026-03-01 05:59:42', '2026-03-01 06:01:18'),
(8, 2, 'Tagihan bulan September 2026 telah tersedia. Nominal: Rp 125.000', 1, '2026-03-01 05:59:42', '2026-03-01 06:01:18'),
(9, 2, 'Tagihan bulan Oktober 2026 telah tersedia. Nominal: Rp 125.000', 1, '2026-03-01 05:59:42', '2026-03-01 06:01:18'),
(10, 2, 'Tagihan bulan November 2026 telah tersedia. Nominal: Rp 125.000', 1, '2026-03-01 05:59:42', '2026-03-01 06:01:18'),
(11, 2, 'Tagihan bulan Desember 2026 telah tersedia. Nominal: Rp 125.000', 1, '2026-03-01 05:59:42', '2026-03-01 06:01:18'),
(12, 2, 'Tagihan bulan Juli 2025 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(13, 2, 'Tagihan bulan Agustus 2025 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(14, 2, 'Tagihan bulan September 2025 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(15, 2, 'Tagihan bulan Oktober 2025 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(16, 2, 'Tagihan bulan November 2025 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(17, 2, 'Tagihan bulan Desember 2025 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(18, 2, 'Tagihan bulan Januari 2026 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(19, 2, 'Tagihan bulan Februari 2026 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(20, 6, 'Tagihan bulan Juli 2025 telah tersedia. Nominal: Rp 250.000', 1, '2026-03-01 06:10:29', '2026-04-03 19:12:07'),
(21, 6, 'Tagihan bulan Agustus 2025 telah tersedia. Nominal: Rp 250.000', 1, '2026-03-01 06:10:29', '2026-04-03 19:12:07'),
(22, 6, 'Tagihan bulan September 2025 telah tersedia. Nominal: Rp 250.000', 1, '2026-03-01 06:10:29', '2026-04-03 19:12:07'),
(23, 6, 'Tagihan bulan Oktober 2025 telah tersedia. Nominal: Rp 250.000', 1, '2026-03-01 06:10:29', '2026-04-03 19:12:07'),
(24, 6, 'Tagihan bulan November 2025 telah tersedia. Nominal: Rp 250.000', 1, '2026-03-01 06:10:29', '2026-04-03 19:12:07'),
(25, 6, 'Tagihan bulan Desember 2025 telah tersedia. Nominal: Rp 250.000', 1, '2026-03-01 06:10:29', '2026-04-03 19:12:07'),
(26, 6, 'Tagihan bulan Januari 2026 telah tersedia. Nominal: Rp 250.000', 1, '2026-03-01 06:10:29', '2026-04-03 19:12:07'),
(27, 6, 'Tagihan bulan Februari 2026 telah tersedia. Nominal: Rp 250.000', 1, '2026-03-01 06:10:29', '2026-04-03 19:12:07'),
(28, 6, 'Tagihan bulan Maret 2026 telah tersedia. Nominal: Rp 250.000', 1, '2026-03-01 06:10:29', '2026-04-03 19:12:07'),
(29, 7, 'Tagihan bulan Juli 2025 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(30, 7, 'Tagihan bulan Agustus 2025 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(31, 7, 'Tagihan bulan September 2025 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(32, 7, 'Tagihan bulan Oktober 2025 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(33, 7, 'Tagihan bulan November 2025 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(34, 7, 'Tagihan bulan Desember 2025 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(35, 7, 'Tagihan bulan Januari 2026 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(36, 7, 'Tagihan bulan Februari 2026 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(37, 7, 'Tagihan bulan Maret 2026 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(38, 8, 'Tagihan bulan Juli 2025 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(39, 8, 'Tagihan bulan Agustus 2025 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(40, 8, 'Tagihan bulan September 2025 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(41, 8, 'Tagihan bulan Oktober 2025 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(42, 8, 'Tagihan bulan November 2025 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(43, 8, 'Tagihan bulan Desember 2025 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(44, 8, 'Tagihan bulan Januari 2026 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(45, 8, 'Tagihan bulan Februari 2026 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(46, 8, 'Tagihan bulan Maret 2026 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(47, 9, 'Tagihan bulan Juli 2025 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(48, 9, 'Tagihan bulan Agustus 2025 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(49, 9, 'Tagihan bulan September 2025 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(50, 9, 'Tagihan bulan Oktober 2025 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(51, 9, 'Tagihan bulan November 2025 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(52, 9, 'Tagihan bulan Desember 2025 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(53, 9, 'Tagihan bulan Januari 2026 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(54, 9, 'Tagihan bulan Februari 2026 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(55, 9, 'Tagihan bulan Maret 2026 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(56, 10, 'Tagihan bulan Juli 2025 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(57, 10, 'Tagihan bulan Agustus 2025 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(58, 10, 'Tagihan bulan September 2025 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(59, 10, 'Tagihan bulan Oktober 2025 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(60, 10, 'Tagihan bulan November 2025 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(61, 10, 'Tagihan bulan Desember 2025 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(62, 10, 'Tagihan bulan Januari 2026 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(63, 10, 'Tagihan bulan Februari 2026 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(64, 10, 'Tagihan bulan Maret 2026 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(65, 11, 'Tagihan bulan Juli 2025 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(66, 11, 'Tagihan bulan Agustus 2025 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(67, 11, 'Tagihan bulan September 2025 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(68, 11, 'Tagihan bulan Oktober 2025 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(69, 11, 'Tagihan bulan November 2025 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(70, 11, 'Tagihan bulan Desember 2025 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(71, 11, 'Tagihan bulan Januari 2026 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(72, 11, 'Tagihan bulan Februari 2026 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(73, 11, 'Tagihan bulan Maret 2026 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(74, 2, 'Tagihan bulan Januari 2027 telah tersedia. Nominal: Rp 125.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(75, 6, 'Tagihan bulan Januari 2027 telah tersedia. Nominal: Rp 250.000', 1, '2026-03-03 04:49:59', '2026-04-03 19:12:07'),
(76, 7, 'Tagihan bulan Januari 2027 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(77, 8, 'Tagihan bulan Januari 2027 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(78, 9, 'Tagihan bulan Januari 2027 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(79, 10, 'Tagihan bulan Januari 2027 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(80, 11, 'Tagihan bulan Januari 2027 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(81, 2, 'Tagihan bulan Februari 2027 telah tersedia. Nominal: Rp 125.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(82, 6, 'Tagihan bulan Februari 2027 telah tersedia. Nominal: Rp 250.000', 1, '2026-03-03 04:49:59', '2026-04-03 19:12:07'),
(83, 7, 'Tagihan bulan Februari 2027 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(84, 8, 'Tagihan bulan Februari 2027 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(85, 9, 'Tagihan bulan Februari 2027 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(86, 10, 'Tagihan bulan Februari 2027 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(87, 11, 'Tagihan bulan Februari 2027 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(88, 2, 'Tagihan bulan Maret 2027 telah tersedia. Nominal: Rp 125.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(89, 6, 'Tagihan bulan Maret 2027 telah tersedia. Nominal: Rp 250.000', 1, '2026-03-03 04:49:59', '2026-04-03 19:12:07'),
(90, 7, 'Tagihan bulan Maret 2027 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(91, 8, 'Tagihan bulan Maret 2027 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(92, 9, 'Tagihan bulan Maret 2027 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(93, 10, 'Tagihan bulan Maret 2027 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(94, 11, 'Tagihan bulan Maret 2027 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(95, 2, 'Tagihan bulan April 2027 telah tersedia. Nominal: Rp 125.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(96, 6, 'Tagihan bulan April 2027 telah tersedia. Nominal: Rp 250.000', 1, '2026-03-03 04:49:59', '2026-04-03 19:12:07'),
(97, 7, 'Tagihan bulan April 2027 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(98, 8, 'Tagihan bulan April 2027 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(99, 9, 'Tagihan bulan April 2027 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(100, 10, 'Tagihan bulan April 2027 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(101, 11, 'Tagihan bulan April 2027 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(102, 2, 'Tagihan bulan Mei 2027 telah tersedia. Nominal: Rp 125.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(103, 6, 'Tagihan bulan Mei 2027 telah tersedia. Nominal: Rp 250.000', 1, '2026-03-03 04:49:59', '2026-04-03 19:12:07'),
(104, 7, 'Tagihan bulan Mei 2027 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(105, 8, 'Tagihan bulan Mei 2027 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(106, 9, 'Tagihan bulan Mei 2027 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(107, 10, 'Tagihan bulan Mei 2027 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(108, 11, 'Tagihan bulan Mei 2027 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(109, 2, 'Tagihan bulan Juni 2027 telah tersedia. Nominal: Rp 125.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(110, 6, 'Tagihan bulan Juni 2027 telah tersedia. Nominal: Rp 250.000', 1, '2026-03-03 04:49:59', '2026-04-03 19:12:07'),
(111, 7, 'Tagihan bulan Juni 2027 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(112, 8, 'Tagihan bulan Juni 2027 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(113, 9, 'Tagihan bulan Juni 2027 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(114, 10, 'Tagihan bulan Juni 2027 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(115, 11, 'Tagihan bulan Juni 2027 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(116, 2, 'Tagihan bulan Juli 2027 telah tersedia. Nominal: Rp 125.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(117, 6, 'Tagihan bulan Juli 2027 telah tersedia. Nominal: Rp 250.000', 1, '2026-03-03 04:49:59', '2026-04-03 19:12:07'),
(118, 7, 'Tagihan bulan Juli 2027 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(119, 8, 'Tagihan bulan Juli 2027 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(120, 9, 'Tagihan bulan Juli 2027 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(121, 10, 'Tagihan bulan Juli 2027 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(122, 11, 'Tagihan bulan Juli 2027 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(123, 2, 'Tagihan bulan Agustus 2027 telah tersedia. Nominal: Rp 125.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(124, 6, 'Tagihan bulan Agustus 2027 telah tersedia. Nominal: Rp 250.000', 1, '2026-03-03 04:49:59', '2026-04-03 19:12:07'),
(125, 7, 'Tagihan bulan Agustus 2027 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(126, 8, 'Tagihan bulan Agustus 2027 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(127, 9, 'Tagihan bulan Agustus 2027 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(128, 10, 'Tagihan bulan Agustus 2027 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(129, 11, 'Tagihan bulan Agustus 2027 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(130, 2, 'Tagihan bulan September 2027 telah tersedia. Nominal: Rp 125.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(131, 6, 'Tagihan bulan September 2027 telah tersedia. Nominal: Rp 250.000', 1, '2026-03-03 04:49:59', '2026-04-03 19:12:07'),
(132, 7, 'Tagihan bulan September 2027 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(133, 8, 'Tagihan bulan September 2027 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(134, 9, 'Tagihan bulan September 2027 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(135, 10, 'Tagihan bulan September 2027 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(136, 11, 'Tagihan bulan September 2027 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(137, 2, 'Tagihan bulan Oktober 2027 telah tersedia. Nominal: Rp 125.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(138, 6, 'Tagihan bulan Oktober 2027 telah tersedia. Nominal: Rp 250.000', 1, '2026-03-03 04:49:59', '2026-04-03 19:12:07'),
(139, 7, 'Tagihan bulan Oktober 2027 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(140, 8, 'Tagihan bulan Oktober 2027 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(141, 9, 'Tagihan bulan Oktober 2027 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(142, 10, 'Tagihan bulan Oktober 2027 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(143, 11, 'Tagihan bulan Oktober 2027 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(144, 2, 'Tagihan bulan November 2027 telah tersedia. Nominal: Rp 125.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(145, 6, 'Tagihan bulan November 2027 telah tersedia. Nominal: Rp 250.000', 1, '2026-03-03 04:49:59', '2026-04-03 19:12:07'),
(146, 7, 'Tagihan bulan November 2027 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(147, 8, 'Tagihan bulan November 2027 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(148, 9, 'Tagihan bulan November 2027 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(149, 10, 'Tagihan bulan November 2027 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(150, 11, 'Tagihan bulan November 2027 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(151, 2, 'Tagihan bulan Desember 2027 telah tersedia. Nominal: Rp 125.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(152, 6, 'Tagihan bulan Desember 2027 telah tersedia. Nominal: Rp 250.000', 1, '2026-03-03 04:49:59', '2026-04-03 19:12:07'),
(153, 7, 'Tagihan bulan Desember 2027 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(154, 8, 'Tagihan bulan Desember 2027 telah tersedia. Nominal: Rp 250.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(155, 9, 'Tagihan bulan Desember 2027 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(156, 10, 'Tagihan bulan Desember 2027 telah tersedia. Nominal: Rp 275.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(157, 11, 'Tagihan bulan Desember 2027 telah tersedia. Nominal: Rp 300.000', 0, '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(158, 6, 'Pembayaran Anda sebesar Rp 1.500.000 untuk tagihan (September 2025, Oktober 2025, November 2025, Desember 2025, Januari 2026, Februari 2026) berhasil diverifikasi Gateway Sandbox.', 1, '2026-04-03 18:59:35', '2026-04-03 19:12:07'),
(159, 6, 'Pembayaran Anda sebesar Rp 250.000 untuk tagihan (Maret 2026) berhasil diverifikasi Gateway Sandbox.', 1, '2026-04-03 19:02:41', '2026-04-03 19:12:02'),
(160, 6, 'Pembayaran Anda sebesar Rp 250.000 untuk tagihan (Januari 2027) berhasil diverifikasi Gateway Sandbox.', 1, '2026-04-03 19:13:20', '2026-04-03 19:37:49'),
(161, 6, 'Pembayaran Anda sebesar Rp 2.500.000 untuk tagihan (Februari 2027, Maret 2027, April 2027, Mei 2027, Juni 2027, Juli 2027, Agustus 2027, September 2027, Oktober 2027, November 2027) berhasil diverifikasi Gateway Sandbox.', 1, '2026-04-03 19:13:53', '2026-04-03 19:37:49'),
(162, 6, 'Pembayaran bulan Juli 2025 ditolak. Silakan upload ulang bukti transfer. ❌', 1, '2026-04-03 19:15:19', '2026-04-03 19:37:49'),
(163, 6, 'Pembayaran bulan Agustus 2025 ditolak. Silakan upload ulang bukti transfer. ❌', 1, '2026-04-03 19:15:24', '2026-04-03 19:37:49'),
(164, 6, 'Pembayaran Anda sebesar Rp 500.000 untuk tagihan (Juli 2025, Agustus 2025) berhasil diverifikasi Gateway Sandbox.', 1, '2026-04-03 19:16:32', '2026-04-03 19:37:49'),
(165, 6, 'Pembayaran Anda sebesar Rp 250.000 untuk tagihan (Desember 2027) berhasil diverifikasi Gateway Sandbox.', 1, '2026-04-03 19:17:04', '2026-04-03 19:37:49'),
(166, 6, 'Tagihan bulan April 2026 telah tersedia. Nominal: Rp 250.000', 1, '2026-04-03 19:21:51', '2026-04-03 19:37:49'),
(167, 7, 'Tagihan bulan April 2026 telah tersedia. Nominal: Rp 250.000', 0, '2026-04-03 19:21:51', '2026-04-03 19:21:51'),
(168, 8, 'Tagihan bulan April 2026 telah tersedia. Nominal: Rp 250.000', 0, '2026-04-03 19:21:51', '2026-04-03 19:21:51'),
(169, 9, 'Tagihan bulan April 2026 telah tersedia. Nominal: Rp 275.000', 0, '2026-04-03 19:21:51', '2026-04-03 19:21:51'),
(170, 10, 'Tagihan bulan April 2026 telah tersedia. Nominal: Rp 275.000', 0, '2026-04-03 19:21:51', '2026-04-03 19:21:51'),
(171, 11, 'Tagihan bulan April 2026 telah tersedia. Nominal: Rp 300.000', 0, '2026-04-03 19:21:51', '2026-04-03 19:21:51'),
(172, 6, 'Pembayaran Anda sebesar Rp 250.000 untuk tagihan (April 2026) berhasil diverifikasi Gateway Sandbox.', 0, '2026-04-03 19:38:11', '2026-04-03 19:38:11'),
(173, 6, 'Tagihan bulan Januari 2028 telah tersedia. Nominal: Rp 250.000', 0, '2026-04-04 03:18:27', '2026-04-04 03:18:27'),
(174, 6, 'Tagihan bulan Februari 2028 telah tersedia. Nominal: Rp 250.000', 0, '2026-04-04 03:18:27', '2026-04-04 03:18:27'),
(175, 6, 'Tagihan bulan Maret 2028 telah tersedia. Nominal: Rp 250.000', 0, '2026-04-04 03:18:27', '2026-04-04 03:18:27'),
(176, 6, 'Tagihan bulan April 2028 telah tersedia. Nominal: Rp 250.000', 0, '2026-04-04 03:18:27', '2026-04-04 03:18:27'),
(177, 6, 'Tagihan bulan Mei 2028 telah tersedia. Nominal: Rp 250.000', 0, '2026-04-04 03:18:27', '2026-04-04 03:18:27'),
(178, 6, 'Tagihan bulan Juni 2028 telah tersedia. Nominal: Rp 250.000', 0, '2026-04-04 03:18:27', '2026-04-04 03:18:27'),
(179, 6, 'Tagihan bulan Juli 2028 telah tersedia. Nominal: Rp 250.000', 0, '2026-04-04 03:18:27', '2026-04-04 03:18:27'),
(180, 6, 'Tagihan bulan Agustus 2028 telah tersedia. Nominal: Rp 250.000', 0, '2026-04-04 03:18:27', '2026-04-04 03:18:27'),
(181, 6, 'Tagihan bulan September 2028 telah tersedia. Nominal: Rp 250.000', 0, '2026-04-04 03:18:27', '2026-04-04 03:18:27'),
(182, 6, 'Tagihan bulan Oktober 2028 telah tersedia. Nominal: Rp 250.000', 0, '2026-04-04 03:18:27', '2026-04-04 03:18:27'),
(183, 6, 'Tagihan bulan November 2028 telah tersedia. Nominal: Rp 250.000', 0, '2026-04-04 03:18:27', '2026-04-04 03:18:27'),
(184, 6, 'Tagihan bulan Desember 2028 telah tersedia. Nominal: Rp 250.000', 0, '2026-04-04 03:18:27', '2026-04-04 03:18:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tagihan_id` bigint(20) UNSIGNED NOT NULL,
  `file_bukti` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_verifikasi` timestamp NULL DEFAULT NULL,
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `transaksi_sandbox_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `tagihan_id`, `file_bukti`, `tanggal_upload`, `tanggal_verifikasi`, `verified_by`, `catatan`, `created_at`, `updated_at`, `transaksi_sandbox_id`) VALUES
(1, 1, 'bukti_1_1772300985.jpg', '2026-02-28 17:49:45', '2026-02-28 17:50:05', 1, NULL, '2026-02-28 17:49:45', '2026-02-28 17:50:05', NULL),
(4, 21, NULL, '2026-04-03 18:55:45', '2026-04-03 18:59:35', NULL, 'Auto-verified by Sandbox Simulator', '2026-04-03 18:55:45', '2026-04-03 18:59:35', 1),
(5, 22, NULL, '2026-04-03 18:55:45', '2026-04-03 18:59:35', NULL, 'Auto-verified by Sandbox Simulator', '2026-04-03 18:55:45', '2026-04-03 18:59:35', 1),
(6, 23, NULL, '2026-04-03 18:55:45', '2026-04-03 18:59:35', NULL, 'Auto-verified by Sandbox Simulator', '2026-04-03 18:55:45', '2026-04-03 18:59:35', 1),
(7, 24, NULL, '2026-04-03 18:55:45', '2026-04-03 18:59:35', NULL, 'Auto-verified by Sandbox Simulator', '2026-04-03 18:55:45', '2026-04-03 18:59:35', 1),
(8, 25, NULL, '2026-04-03 18:55:45', '2026-04-03 18:59:35', NULL, 'Auto-verified by Sandbox Simulator', '2026-04-03 18:55:45', '2026-04-03 18:59:35', 1),
(9, 26, NULL, '2026-04-03 18:55:45', '2026-04-03 18:59:35', NULL, 'Auto-verified by Sandbox Simulator', '2026-04-03 18:55:45', '2026-04-03 18:59:35', 1),
(10, 27, NULL, '2026-04-03 19:02:07', '2026-04-03 19:02:41', NULL, 'Auto-verified by Sandbox Simulator', '2026-04-03 19:02:07', '2026-04-03 19:02:41', 2),
(11, 74, NULL, '2026-04-03 19:13:02', '2026-04-03 19:13:20', NULL, 'Auto-verified by Sandbox Simulator', '2026-04-03 19:13:02', '2026-04-03 19:13:20', 3),
(12, 81, NULL, '2026-04-03 19:13:47', '2026-04-03 19:13:53', NULL, 'Auto-verified by Sandbox Simulator', '2026-04-03 19:13:47', '2026-04-03 19:13:53', 4),
(13, 88, NULL, '2026-04-03 19:13:47', '2026-04-03 19:13:53', NULL, 'Auto-verified by Sandbox Simulator', '2026-04-03 19:13:47', '2026-04-03 19:13:53', 4),
(14, 95, NULL, '2026-04-03 19:13:47', '2026-04-03 19:13:53', NULL, 'Auto-verified by Sandbox Simulator', '2026-04-03 19:13:47', '2026-04-03 19:13:53', 4),
(15, 102, NULL, '2026-04-03 19:13:47', '2026-04-03 19:13:53', NULL, 'Auto-verified by Sandbox Simulator', '2026-04-03 19:13:47', '2026-04-03 19:13:53', 4),
(16, 109, NULL, '2026-04-03 19:13:47', '2026-04-03 19:13:53', NULL, 'Auto-verified by Sandbox Simulator', '2026-04-03 19:13:47', '2026-04-03 19:13:53', 4),
(17, 116, NULL, '2026-04-03 19:13:47', '2026-04-03 19:13:53', NULL, 'Auto-verified by Sandbox Simulator', '2026-04-03 19:13:47', '2026-04-03 19:13:53', 4),
(18, 123, NULL, '2026-04-03 19:13:47', '2026-04-03 19:13:53', NULL, 'Auto-verified by Sandbox Simulator', '2026-04-03 19:13:47', '2026-04-03 19:13:53', 4),
(19, 130, NULL, '2026-04-03 19:13:47', '2026-04-03 19:13:53', NULL, 'Auto-verified by Sandbox Simulator', '2026-04-03 19:13:47', '2026-04-03 19:13:53', 4),
(20, 137, NULL, '2026-04-03 19:13:47', '2026-04-03 19:13:53', NULL, 'Auto-verified by Sandbox Simulator', '2026-04-03 19:13:47', '2026-04-03 19:13:53', 4),
(21, 144, NULL, '2026-04-03 19:13:47', '2026-04-03 19:13:53', NULL, 'Auto-verified by Sandbox Simulator', '2026-04-03 19:13:47', '2026-04-03 19:13:53', 4),
(22, 19, NULL, '2026-04-03 19:16:27', '2026-04-03 19:16:32', NULL, 'Auto-verified by Sandbox Simulator', '2026-04-03 19:16:27', '2026-04-03 19:16:32', 5),
(23, 20, NULL, '2026-04-03 19:16:27', '2026-04-03 19:16:32', NULL, 'Auto-verified by Sandbox Simulator', '2026-04-03 19:16:27', '2026-04-03 19:16:32', 5),
(24, 151, NULL, '2026-04-03 19:16:46', '2026-04-03 19:17:04', NULL, 'Auto-verified by Sandbox Simulator', '2026-04-03 19:16:46', '2026-04-03 19:17:04', 6),
(25, 157, NULL, '2026-04-03 19:32:46', '2026-04-03 19:38:11', NULL, 'Auto-verified by Sandbox Simulator', '2026-04-03 19:32:46', '2026-04-03 19:38:11', 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('54HfvR5Z5G6pWvCsu6ZZcf4UR9oYrRC3Y7Jsx6Q4', 6, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRFBIc25tQmtKV1BMdHR4RDhWQW83Snl6eFJxZnptOFRUMUZWUjNrWSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNzoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL3Npc3dhL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvc2lzd2EvZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjE1OiJzaXN3YS5kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo2O30=', 1775272716),
('uU8sNqt4rpunrL1MK8VvfeYnS56ut6qhVGDFNKu9', 12, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibzVKOGtwMGVxT2NzVzg0eHZ3RlZ4U0FFNkhjYTFSUVlmT3JYMHk3MCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3NwcCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM1OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vdGFnaWhhbiI7czo1OiJyb3V0ZSI7czoxOToiYWRtaW4udGFnaWhhbi5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjEyO30=', 1775272707);

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelas_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`id`, `user_id`, `nis`, `nama`, `kelas_id`, `tanggal_lahir`, `jenis_kelamin`, `alamat`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 2, '123456', 'Budi Suntana', 1, '2006-10-11', 'L', 'Jalan Persaudaraan, Gg. Melati No. 19', 0, '2026-02-28 17:48:03', '2026-02-28 17:48:03'),
(2, 6, '10001', 'Ahmad Fadilah', 2, '2007-03-15', 'L', 'Jl. Merdeka No. 1', 0, '2026-03-01 06:07:36', '2026-03-01 06:07:36'),
(3, 7, '10002', 'Siti Nurhaliza', 2, '2007-05-22', 'P', 'Jl. Sudirman No. 5', 0, '2026-03-01 06:07:36', '2026-03-01 06:07:36'),
(4, 8, '10003', 'Budi Santoso', 3, '2007-01-10', 'L', 'Jl. Gatot Subroto No. 10', 0, '2026-03-01 06:07:36', '2026-03-01 06:07:36'),
(5, 9, '11001', 'Dewi Lestari', 4, '2006-08-03', 'P', 'Jl. Ahmad Yani No. 15', 0, '2026-03-01 06:07:37', '2026-03-01 06:07:37'),
(6, 10, '11002', 'Rizky Pratama', 5, '2006-12-25', 'L', 'Jl. Diponegoro No. 20', 0, '2026-03-01 06:07:37', '2026-03-01 06:07:37'),
(7, 11, '12001', 'Putri Anggraini', 6, '2005-06-17', 'P', 'Jl. Imam Bonjol No. 25', 0, '2026-03-01 06:07:37', '2026-03-01 06:07:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `spp`
--

CREATE TABLE `spp` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kelas_id` bigint(20) UNSIGNED NOT NULL,
  `nominal` decimal(12,2) NOT NULL,
  `tahun_ajaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `berlaku_mulai` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `spp`
--

INSERT INTO `spp` (`id`, `kelas_id`, `nominal`, `tahun_ajaran`, `berlaku_mulai`, `created_at`, `updated_at`) VALUES
(1, 1, 125000.00, '2026/2027', '2026-03-01', '2026-02-28 17:46:53', '2026-02-28 17:46:53'),
(2, 1, 300000.00, '2025/2026', '2025-07-01', '2026-03-01 06:07:35', '2026-03-01 06:07:35'),
(3, 2, 250000.00, '2025/2026', '2025-07-01', '2026-03-01 06:07:35', '2026-03-01 06:07:35'),
(4, 3, 250000.00, '2025/2026', '2025-07-01', '2026-03-01 06:07:35', '2026-03-01 06:07:35'),
(5, 4, 275000.00, '2025/2026', '2025-07-01', '2026-03-01 06:07:35', '2026-03-01 06:07:35'),
(6, 5, 275000.00, '2025/2026', '2025-07-01', '2026-03-01 06:07:35', '2026-03-01 06:07:35'),
(7, 6, 300000.00, '2025/2026', '2025-07-01', '2026-03-01 06:07:35', '2026-03-01 06:07:35'),
(8, 7, 300000.00, '2025/2026', '2025-07-01', '2026-03-01 06:07:35', '2026-03-01 06:07:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tagihan`
--

CREATE TABLE `tagihan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `siswa_id` bigint(20) UNSIGNED NOT NULL,
  `spp_id` bigint(20) UNSIGNED NOT NULL,
  `bulan` tinyint(3) UNSIGNED NOT NULL,
  `tahun` smallint(5) UNSIGNED NOT NULL,
  `nominal` decimal(12,2) NOT NULL,
  `status` enum('belum_bayar','menunggu_verifikasi','lunas','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_bayar',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tagihan`
--

INSERT INTO `tagihan` (`id`, `siswa_id`, `spp_id`, `bulan`, `tahun`, `nominal`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, 2026, 125000.00, 'lunas', '2026-02-28 17:48:22', '2026-02-28 17:50:05'),
(2, 1, 1, 4, 2026, 125000.00, 'belum_bayar', '2026-03-01 05:59:42', '2026-03-01 05:59:42'),
(3, 1, 1, 5, 2026, 125000.00, 'belum_bayar', '2026-03-01 05:59:42', '2026-03-01 05:59:42'),
(4, 1, 1, 6, 2026, 125000.00, 'belum_bayar', '2026-03-01 05:59:42', '2026-03-01 05:59:42'),
(5, 1, 1, 7, 2026, 125000.00, 'belum_bayar', '2026-03-01 05:59:42', '2026-03-01 05:59:42'),
(6, 1, 1, 8, 2026, 125000.00, 'belum_bayar', '2026-03-01 05:59:42', '2026-03-01 05:59:42'),
(7, 1, 1, 9, 2026, 125000.00, 'belum_bayar', '2026-03-01 05:59:42', '2026-03-01 05:59:42'),
(8, 1, 1, 10, 2026, 125000.00, 'belum_bayar', '2026-03-01 05:59:42', '2026-03-01 05:59:42'),
(9, 1, 1, 11, 2026, 125000.00, 'belum_bayar', '2026-03-01 05:59:42', '2026-03-01 05:59:42'),
(10, 1, 1, 12, 2026, 125000.00, 'belum_bayar', '2026-03-01 05:59:42', '2026-03-01 05:59:42'),
(11, 1, 2, 7, 2025, 300000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(12, 1, 2, 8, 2025, 300000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(13, 1, 2, 9, 2025, 300000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(14, 1, 2, 10, 2025, 300000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(15, 1, 2, 11, 2025, 300000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(16, 1, 2, 12, 2025, 300000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(17, 1, 2, 1, 2026, 300000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(18, 1, 2, 2, 2026, 300000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(19, 2, 3, 7, 2025, 250000.00, 'lunas', '2026-03-01 06:10:29', '2026-04-03 19:16:32'),
(20, 2, 3, 8, 2025, 250000.00, 'lunas', '2026-03-01 06:10:29', '2026-04-03 19:16:32'),
(21, 2, 3, 9, 2025, 250000.00, 'lunas', '2026-03-01 06:10:29', '2026-04-03 18:59:35'),
(22, 2, 3, 10, 2025, 250000.00, 'lunas', '2026-03-01 06:10:29', '2026-04-03 18:59:35'),
(23, 2, 3, 11, 2025, 250000.00, 'lunas', '2026-03-01 06:10:29', '2026-04-03 18:59:35'),
(24, 2, 3, 12, 2025, 250000.00, 'lunas', '2026-03-01 06:10:29', '2026-04-03 18:59:35'),
(25, 2, 3, 1, 2026, 250000.00, 'lunas', '2026-03-01 06:10:29', '2026-04-03 18:59:35'),
(26, 2, 3, 2, 2026, 250000.00, 'lunas', '2026-03-01 06:10:29', '2026-04-03 18:59:35'),
(27, 2, 3, 3, 2026, 250000.00, 'lunas', '2026-03-01 06:10:29', '2026-04-03 19:02:41'),
(28, 3, 3, 7, 2025, 250000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(29, 3, 3, 8, 2025, 250000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(30, 3, 3, 9, 2025, 250000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(31, 3, 3, 10, 2025, 250000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(32, 3, 3, 11, 2025, 250000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(33, 3, 3, 12, 2025, 250000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(34, 3, 3, 1, 2026, 250000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(35, 3, 3, 2, 2026, 250000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(36, 3, 3, 3, 2026, 250000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(37, 4, 4, 7, 2025, 250000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(38, 4, 4, 8, 2025, 250000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(39, 4, 4, 9, 2025, 250000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(40, 4, 4, 10, 2025, 250000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(41, 4, 4, 11, 2025, 250000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(42, 4, 4, 12, 2025, 250000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(43, 4, 4, 1, 2026, 250000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(44, 4, 4, 2, 2026, 250000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(45, 4, 4, 3, 2026, 250000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(46, 5, 5, 7, 2025, 275000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(47, 5, 5, 8, 2025, 275000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(48, 5, 5, 9, 2025, 275000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(49, 5, 5, 10, 2025, 275000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(50, 5, 5, 11, 2025, 275000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(51, 5, 5, 12, 2025, 275000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(52, 5, 5, 1, 2026, 275000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(53, 5, 5, 2, 2026, 275000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(54, 5, 5, 3, 2026, 275000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(55, 6, 6, 7, 2025, 275000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(56, 6, 6, 8, 2025, 275000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(57, 6, 6, 9, 2025, 275000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(58, 6, 6, 10, 2025, 275000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(59, 6, 6, 11, 2025, 275000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(60, 6, 6, 12, 2025, 275000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(61, 6, 6, 1, 2026, 275000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(62, 6, 6, 2, 2026, 275000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(63, 6, 6, 3, 2026, 275000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(64, 7, 7, 7, 2025, 300000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(65, 7, 7, 8, 2025, 300000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(66, 7, 7, 9, 2025, 300000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(67, 7, 7, 10, 2025, 300000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(68, 7, 7, 11, 2025, 300000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(69, 7, 7, 12, 2025, 300000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(70, 7, 7, 1, 2026, 300000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(71, 7, 7, 2, 2026, 300000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(72, 7, 7, 3, 2026, 300000.00, 'belum_bayar', '2026-03-01 06:10:29', '2026-03-01 06:10:29'),
(73, 1, 1, 1, 2027, 125000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(74, 2, 3, 1, 2027, 250000.00, 'lunas', '2026-03-03 04:49:59', '2026-04-03 19:13:20'),
(75, 3, 3, 1, 2027, 250000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(76, 4, 4, 1, 2027, 250000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(77, 5, 5, 1, 2027, 275000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(78, 6, 6, 1, 2027, 275000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(79, 7, 7, 1, 2027, 300000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(80, 1, 1, 2, 2027, 125000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(81, 2, 3, 2, 2027, 250000.00, 'lunas', '2026-03-03 04:49:59', '2026-04-03 19:13:53'),
(82, 3, 3, 2, 2027, 250000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(83, 4, 4, 2, 2027, 250000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(84, 5, 5, 2, 2027, 275000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(85, 6, 6, 2, 2027, 275000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(86, 7, 7, 2, 2027, 300000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(87, 1, 1, 3, 2027, 125000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(88, 2, 3, 3, 2027, 250000.00, 'lunas', '2026-03-03 04:49:59', '2026-04-03 19:13:53'),
(89, 3, 3, 3, 2027, 250000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(90, 4, 4, 3, 2027, 250000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(91, 5, 5, 3, 2027, 275000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(92, 6, 6, 3, 2027, 275000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(93, 7, 7, 3, 2027, 300000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(94, 1, 1, 4, 2027, 125000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(95, 2, 3, 4, 2027, 250000.00, 'lunas', '2026-03-03 04:49:59', '2026-04-03 19:13:53'),
(96, 3, 3, 4, 2027, 250000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(97, 4, 4, 4, 2027, 250000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(98, 5, 5, 4, 2027, 275000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(99, 6, 6, 4, 2027, 275000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(100, 7, 7, 4, 2027, 300000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(101, 1, 1, 5, 2027, 125000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(102, 2, 3, 5, 2027, 250000.00, 'lunas', '2026-03-03 04:49:59', '2026-04-03 19:13:53'),
(103, 3, 3, 5, 2027, 250000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(104, 4, 4, 5, 2027, 250000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(105, 5, 5, 5, 2027, 275000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(106, 6, 6, 5, 2027, 275000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(107, 7, 7, 5, 2027, 300000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(108, 1, 1, 6, 2027, 125000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(109, 2, 3, 6, 2027, 250000.00, 'lunas', '2026-03-03 04:49:59', '2026-04-03 19:13:53'),
(110, 3, 3, 6, 2027, 250000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(111, 4, 4, 6, 2027, 250000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(112, 5, 5, 6, 2027, 275000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(113, 6, 6, 6, 2027, 275000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(114, 7, 7, 6, 2027, 300000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(115, 1, 1, 7, 2027, 125000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(116, 2, 3, 7, 2027, 250000.00, 'lunas', '2026-03-03 04:49:59', '2026-04-03 19:13:53'),
(117, 3, 3, 7, 2027, 250000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(118, 4, 4, 7, 2027, 250000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(119, 5, 5, 7, 2027, 275000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(120, 6, 6, 7, 2027, 275000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(121, 7, 7, 7, 2027, 300000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(122, 1, 1, 8, 2027, 125000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(123, 2, 3, 8, 2027, 250000.00, 'lunas', '2026-03-03 04:49:59', '2026-04-03 19:13:53'),
(124, 3, 3, 8, 2027, 250000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(125, 4, 4, 8, 2027, 250000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(126, 5, 5, 8, 2027, 275000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(127, 6, 6, 8, 2027, 275000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(128, 7, 7, 8, 2027, 300000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(129, 1, 1, 9, 2027, 125000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(130, 2, 3, 9, 2027, 250000.00, 'lunas', '2026-03-03 04:49:59', '2026-04-03 19:13:53'),
(131, 3, 3, 9, 2027, 250000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(132, 4, 4, 9, 2027, 250000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(133, 5, 5, 9, 2027, 275000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(134, 6, 6, 9, 2027, 275000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(135, 7, 7, 9, 2027, 300000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(136, 1, 1, 10, 2027, 125000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(137, 2, 3, 10, 2027, 250000.00, 'lunas', '2026-03-03 04:49:59', '2026-04-03 19:13:53'),
(138, 3, 3, 10, 2027, 250000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(139, 4, 4, 10, 2027, 250000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(140, 5, 5, 10, 2027, 275000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(141, 6, 6, 10, 2027, 275000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(142, 7, 7, 10, 2027, 300000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(143, 1, 1, 11, 2027, 125000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(144, 2, 3, 11, 2027, 250000.00, 'lunas', '2026-03-03 04:49:59', '2026-04-03 19:13:53'),
(145, 3, 3, 11, 2027, 250000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(146, 4, 4, 11, 2027, 250000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(147, 5, 5, 11, 2027, 275000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(148, 6, 6, 11, 2027, 275000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(149, 7, 7, 11, 2027, 300000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(150, 1, 1, 12, 2027, 125000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(151, 2, 3, 12, 2027, 250000.00, 'lunas', '2026-03-03 04:49:59', '2026-04-03 19:17:04'),
(152, 3, 3, 12, 2027, 250000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(153, 4, 4, 12, 2027, 250000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(154, 5, 5, 12, 2027, 275000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(155, 6, 6, 12, 2027, 275000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(156, 7, 7, 12, 2027, 300000.00, 'belum_bayar', '2026-03-03 04:49:59', '2026-03-03 04:49:59'),
(157, 2, 3, 4, 2026, 250000.00, 'lunas', '2026-04-03 19:21:51', '2026-04-03 19:38:11'),
(158, 3, 3, 4, 2026, 250000.00, 'belum_bayar', '2026-04-03 19:21:51', '2026-04-03 19:21:51'),
(159, 4, 4, 4, 2026, 250000.00, 'belum_bayar', '2026-04-03 19:21:51', '2026-04-03 19:21:51'),
(160, 5, 5, 4, 2026, 275000.00, 'belum_bayar', '2026-04-03 19:21:51', '2026-04-03 19:21:51'),
(161, 6, 6, 4, 2026, 275000.00, 'belum_bayar', '2026-04-03 19:21:51', '2026-04-03 19:21:51'),
(162, 7, 7, 4, 2026, 300000.00, 'belum_bayar', '2026-04-03 19:21:51', '2026-04-03 19:21:51'),
(163, 2, 3, 1, 2028, 250000.00, 'belum_bayar', '2026-04-04 03:18:27', '2026-04-04 03:18:27'),
(164, 2, 3, 2, 2028, 250000.00, 'belum_bayar', '2026-04-04 03:18:27', '2026-04-04 03:18:27'),
(165, 2, 3, 3, 2028, 250000.00, 'belum_bayar', '2026-04-04 03:18:27', '2026-04-04 03:18:27'),
(166, 2, 3, 4, 2028, 250000.00, 'belum_bayar', '2026-04-04 03:18:27', '2026-04-04 03:18:27'),
(167, 2, 3, 5, 2028, 250000.00, 'belum_bayar', '2026-04-04 03:18:27', '2026-04-04 03:18:27'),
(168, 2, 3, 6, 2028, 250000.00, 'belum_bayar', '2026-04-04 03:18:27', '2026-04-04 03:18:27'),
(169, 2, 3, 7, 2028, 250000.00, 'belum_bayar', '2026-04-04 03:18:27', '2026-04-04 03:18:27'),
(170, 2, 3, 8, 2028, 250000.00, 'belum_bayar', '2026-04-04 03:18:27', '2026-04-04 03:18:27'),
(171, 2, 3, 9, 2028, 250000.00, 'belum_bayar', '2026-04-04 03:18:27', '2026-04-04 03:18:27'),
(172, 2, 3, 10, 2028, 250000.00, 'belum_bayar', '2026-04-04 03:18:27', '2026-04-04 03:18:27'),
(173, 2, 3, 11, 2028, 250000.00, 'belum_bayar', '2026-04-04 03:18:27', '2026-04-04 03:18:27'),
(174, 2, 3, 12, 2028, 250000.00, 'belum_bayar', '2026-04-04 03:18:27', '2026-04-04 03:18:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_sandbox`
--

CREATE TABLE `transaksi_sandbox` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `siswa_id` bigint(20) UNSIGNED NOT NULL,
  `total_nominal` decimal(15,2) NOT NULL,
  `metode_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','sukses','kadaluarsa','gagal') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `expired_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `transaksi_sandbox`
--

INSERT INTO `transaksi_sandbox` (`id`, `order_id`, `siswa_id`, `total_nominal`, `metode_pembayaran`, `tipe`, `kode_pembayaran`, `status`, `expired_at`, `created_at`, `updated_at`) VALUES
(1, 'TRX-1775242545-IVVWH', 2, 1500000.00, 'qris', 'qris', 'http://localhost:8000/sandbox/simulator/TRX-1775242545-IVVWH', 'sukses', '2026-04-04 18:55:45', '2026-04-03 18:55:45', '2026-04-03 18:59:35'),
(2, 'TRX-1775242927-MMZ9S', 2, 250000.00, 'qris', 'qris', 'http://localhost:8000/sandbox/simulator/TRX-1775242927-MMZ9S', 'sukses', '2026-04-04 19:02:07', '2026-04-03 19:02:07', '2026-04-03 19:02:41'),
(3, 'TRX-1775243582-ZWXJN', 2, 250000.00, 'va_bca', 'va', '390110001', 'sukses', '2026-04-04 19:13:02', '2026-04-03 19:13:02', '2026-04-03 19:13:20'),
(4, 'TRX-1775243627-4MYX4', 2, 2500000.00, 'qris', 'qris', 'http://localhost:8000/sandbox/simulator/TRX-1775243627-4MYX4', 'sukses', '2026-04-04 19:13:47', '2026-04-03 19:13:47', '2026-04-03 19:13:53'),
(5, 'TRX-1775243787-X6A0E', 2, 500000.00, 'qris', 'qris', 'http://localhost:8000/sandbox/simulator/TRX-1775243787-X6A0E', 'sukses', '2026-04-04 19:16:27', '2026-04-03 19:16:27', '2026-04-03 19:16:32'),
(6, 'TRX-1775243806-IMGQC', 2, 250000.00, 'va_mandiri', 'va', '8950810001', 'sukses', '2026-04-04 19:16:46', '2026-04-03 19:16:46', '2026-04-03 19:17:03'),
(7, 'TRX-1775244766-ODASJ', 2, 250000.00, 'qris', 'qris', 'http://localhost:8000/sandbox/simulator/TRX-1775244766-ODASJ', 'sukses', '2026-04-04 19:32:46', '2026-04-03 19:32:46', '2026-04-03 19:38:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','siswa') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_first_login` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `is_first_login`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$12$SDZ73qTdzvhJkv7FR0LS1eJGcshlT6kc46nEEfU7.7zB.iWmizjbW', 'admin', 0, 1, NULL, '2026-02-28 17:44:09', '2026-02-28 17:44:09'),
(2, '123456', '$2y$12$See0TYHEBsYcH0XwnR8sJOU7k7pbL8PMBzf6.CafWzMO5SHmrPjE2', 'siswa', 0, 1, NULL, '2026-02-28 17:48:03', '2026-02-28 17:49:21'),
(5, 'keuangan', '$2y$12$UpWEUMsjwp.JOBZ2U3pcnecL.nIK99bcIG7mWiujaZIOMAUQV5T4C', 'admin', 0, 1, NULL, '2026-03-01 06:07:35', '2026-03-01 06:07:35'),
(6, '10001', '$2y$12$Vppmw.JtTqntbRR2lmzjru7tm5ZbaGo2Aad1iMbaVzH6UALiWmxrK', 'siswa', 0, 1, NULL, '2026-03-01 06:07:36', '2026-04-03 18:55:22'),
(7, '10002', '$2y$12$UzHvx8AdjYHNfTVJS5Z20OllfCAYiMNC76zLtL7ZYNWBoIPi.76K.', 'siswa', 1, 1, NULL, '2026-03-01 06:07:36', '2026-03-01 06:07:36'),
(8, '10003', '$2y$12$Mhiovc2Hz7vc5Ky2E7lM8eHZHj6EA3OHSQKCcziOYIx9/i12SHsEi', 'siswa', 1, 1, NULL, '2026-03-01 06:07:36', '2026-03-01 06:07:36'),
(9, '11001', '$2y$12$DsFrIpS61TgAPGpo1bnk5u6t.5n7rhFl3q0UsqGa3hJfStetLMgw2', 'siswa', 1, 1, NULL, '2026-03-01 06:07:37', '2026-03-01 06:07:37'),
(10, '11002', '$2y$12$Y2nDUuwBb1Z1m0UAWcFDKOJwn6Aw87Ym1GplPrjVKtXbtMoz2BkzW', 'siswa', 1, 1, NULL, '2026-03-01 06:07:37', '2026-03-01 06:07:37'),
(11, '12001', '$2y$12$h8tJllW76EnqVedvLyVdMO/Bmpz6DpVZ58Y8xnEvRE01ID.T0tzmm', 'siswa', 1, 1, NULL, '2026-03-01 06:07:37', '2026-03-01 06:07:37'),
(12, 'keuangan@gmail.com', '$2y$12$ZUOM3l56HaPwwytFFtXDOOjFadpLeGbKyzhZhXnf0BABQ60b6R5n.', 'admin', 0, 1, NULL, '2026-04-03 18:13:55', '2026-04-03 18:13:55');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_aktivitas_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifikasi_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pembayaran_tagihan_id_foreign` (`tagihan_id`),
  ADD KEY `pembayaran_verified_by_foreign` (`verified_by`),
  ADD KEY `pembayaran_transaksi_sandbox_id_foreign` (`transaksi_sandbox_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `siswa_nis_unique` (`nis`),
  ADD KEY `siswa_user_id_foreign` (`user_id`),
  ADD KEY `siswa_kelas_id_foreign` (`kelas_id`);

--
-- Indeks untuk tabel `spp`
--
ALTER TABLE `spp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spp_kelas_id_foreign` (`kelas_id`);

--
-- Indeks untuk tabel `tagihan`
--
ALTER TABLE `tagihan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tagihan_siswa_id_bulan_tahun_unique` (`siswa_id`,`bulan`,`tahun`),
  ADD KEY `tagihan_spp_id_foreign` (`spp_id`);

--
-- Indeks untuk tabel `transaksi_sandbox`
--
ALTER TABLE `transaksi_sandbox`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaksi_sandbox_order_id_unique` (`order_id`),
  ADD KEY `transaksi_sandbox_siswa_id_foreign` (`siswa_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `spp`
--
ALTER TABLE `spp`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `tagihan`
--
ALTER TABLE `tagihan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;

--
-- AUTO_INCREMENT untuk tabel `transaksi_sandbox`
--
ALTER TABLE `transaksi_sandbox`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_tagihan_id_foreign` FOREIGN KEY (`tagihan_id`) REFERENCES `tagihan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembayaran_transaksi_sandbox_id_foreign` FOREIGN KEY (`transaksi_sandbox_id`) REFERENCES `transaksi_sandbox` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembayaran_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`),
  ADD CONSTRAINT `siswa_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `spp`
--
ALTER TABLE `spp`
  ADD CONSTRAINT `spp_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`);

--
-- Ketidakleluasaan untuk tabel `tagihan`
--
ALTER TABLE `tagihan`
  ADD CONSTRAINT `tagihan_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tagihan_spp_id_foreign` FOREIGN KEY (`spp_id`) REFERENCES `spp` (`id`);

--
-- Ketidakleluasaan untuk tabel `transaksi_sandbox`
--
ALTER TABLE `transaksi_sandbox`
  ADD CONSTRAINT `transaksi_sandbox_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
