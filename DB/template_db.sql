-- ==========================================================
-- TEMPLATE DATABASE SCHEMA & SEED DATA (CLEAN VERSION)
-- Generated at: 2026-07-09 01:48:54
-- ==========================================================

DROP TABLE IF EXISTS public.tugas CASCADE;
DROP TABLE IF EXISTS public.file_asset CASCADE;
DROP TABLE IF EXISTS public.hak_akses CASCADE;
DROP TABLE IF EXISTS public.sub_modul CASCADE;
DROP TABLE IF EXISTS public.modul CASCADE;
DROP TABLE IF EXISTS public.user CASCADE;
DROP TABLE IF EXISTS public.level_user CASCADE;

-- ----------------------------------------------------------
-- Table structure for level_user
-- ----------------------------------------------------------
CREATE TABLE public.level_user (
    "id" int4 NOT NULL,
    "nama_level" varchar NOT NULL,
    "deskripsi" text,
    "is_active" bool DEFAULT TRUE,
    "created_at" timestamp DEFAULT CURRENT_TIMESTAMP,
    "updated_at" timestamp DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT "pk_level_user" PRIMARY KEY ("id")
);

-- Dumping data for table level_user
INSERT INTO public.level_user ("id", "nama_level", "deskripsi", "is_active", "created_at", "updated_at") VALUES (1, 'Super Admin', 'Akses penuh ke semua fitur sistem', TRUE, '2025-12-10 09:38:49', '2025-12-10 09:38:49');
INSERT INTO public.level_user ("id", "nama_level", "deskripsi", "is_active", "created_at", "updated_at") VALUES (2, 'PROVINSI', 'Level wilayah provinsi', TRUE, '2026-05-07 09:12:48', '2026-05-07 09:12:48');
INSERT INTO public.level_user ("id", "nama_level", "deskripsi", "is_active", "created_at", "updated_at") VALUES (3, 'KAB/KOTA', 'Level wilayah kabupaten/kota', TRUE, '2026-05-07 09:12:48', '2026-05-07 09:12:48');
INSERT INTO public.level_user ("id", "nama_level", "deskripsi", "is_active", "created_at", "updated_at") VALUES (4, 'KECAMATAN/DESA', 'Level wilayah kecamatan/desa', TRUE, '2026-05-07 09:12:48', '2026-05-07 09:12:48');
INSERT INTO public.level_user ("id", "nama_level", "deskripsi", "is_active", "created_at", "updated_at") VALUES (7, 'Masyarakat', 'Akses masyarakat untuk pengajuan akun publik', TRUE, '2026-06-10 10:23:38', '2026-06-10 10:23:38');

-- ----------------------------------------------------------
-- Table structure for user
-- ----------------------------------------------------------
CREATE TABLE public.user (
    "id" int4 NOT NULL,
    "username" varchar NOT NULL,
    "password" varchar NOT NULL,
    "nama_lengkap" varchar NOT NULL,
    "email" varchar NOT NULL,
    "no_telpon" varchar,
    "level_user_id" int4 NOT NULL,
    "is_active" bool DEFAULT TRUE,
    "created_at" timestamp DEFAULT CURRENT_TIMESTAMP,
    "updated_at" timestamp DEFAULT CURRENT_TIMESTAMP,
    "foto_profil" varchar,
    "id_user_level" int4,
    "status" bool DEFAULT TRUE,
    "password_reset_otp" varchar,
    "password_reset_otp_expires_at" timestamp,
    "password_reset_requested_at" timestamp,
    "alamat" text,
    "jenis_kelamin" varchar,
    CONSTRAINT "pk_user" PRIMARY KEY ("id")
);

-- Dumping data for table user
INSERT INTO public.user ("id", "username", "password", "nama_lengkap", "email", "no_telpon", "level_user_id", "is_active", "created_at", "updated_at", "foto_profil", "id_user_level", "status", "password_reset_otp", "password_reset_otp_expires_at", "password_reset_requested_at", "alamat", "jenis_kelamin") VALUES (1, 'admin', '$2y$13$6PtYEVz37K/hTj.T1FQCEu8.uiegC7nsTHneWTgUeuq8fqWMFXj6a', 'Super Administrator', 'yosua.admin@gmail.com', '', 1, TRUE, '2025-12-10 09:38:49', '2026-05-07 10:22:36', 'uploads/profile/6a4b18c0b8c3f.png', 1, TRUE, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------------------------------------
-- Table structure for modul
-- ----------------------------------------------------------
CREATE TABLE public.modul (
    "id" int4 NOT NULL,
    "nama_modul" varchar NOT NULL,
    "label" varchar NOT NULL,
    "deskripsi" varchar,
    "icon" varchar,
    "urutan" int4 DEFAULT 0,
    "is_active" bool DEFAULT TRUE,
    "created_at" timestamp DEFAULT CURRENT_TIMESTAMP,
    "updated_at" timestamp DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT "pk_modul" PRIMARY KEY ("id")
);

-- Dumping data for table modul
INSERT INTO public.modul ("id", "nama_modul", "label", "deskripsi", "icon", "urutan", "is_active", "created_at", "updated_at") VALUES (2, 'dashboard-laporan', 'DASHBOARD & LAPORAN', 'Laporan Kinerja Sistem Pencatatan dan Pelaporan', NULL, 1, TRUE, '2025-12-10 09:38:49', '2025-12-10 09:38:49');
INSERT INTO public.modul ("id", "nama_modul", "label", "deskripsi", "icon", "urutan", "is_active", "created_at", "updated_at") VALUES (3, 'master-data', 'MASTER DATA & KONFIGURASI', 'Data Referensi dan Konfigurasi Sistem', NULL, 3, TRUE, '2025-12-10 09:38:49', '2025-12-10 09:38:49');

-- ----------------------------------------------------------
-- Table structure for sub_modul
-- ----------------------------------------------------------
CREATE TABLE public.sub_modul (
    "id" int4 NOT NULL,
    "modul_id" int4 NOT NULL,
    "nama_sub_modul" varchar NOT NULL,
    "label" varchar NOT NULL,
    "route" varchar,
    "icon" varchar,
    "urutan" int4 DEFAULT 0,
    "is_active" bool DEFAULT TRUE,
    "created_at" timestamp DEFAULT CURRENT_TIMESTAMP,
    "updated_at" timestamp DEFAULT CURRENT_TIMESTAMP,
    "parent_id" int4,
    CONSTRAINT "pk_sub_modul" PRIMARY KEY ("id")
);

-- Dumping data for table sub_modul
INSERT INTO public.sub_modul ("id", "modul_id", "nama_sub_modul", "label", "route", "icon", "urutan", "is_active", "created_at", "updated_at", "parent_id") VALUES (15, 3, 'konfigurasi', 'KONFIGURASI', '#!', 'ph-duotone ph-gear', 5, TRUE, '2025-12-10 09:38:49', '2025-12-10 09:38:49', NULL);
INSERT INTO public.sub_modul ("id", "modul_id", "nama_sub_modul", "label", "route", "icon", "urutan", "is_active", "created_at", "updated_at", "parent_id") VALUES (36, 3, 'data-user-akses', 'DATA USER AKSES', '#', 'ph-duotone ph-user', 6, TRUE, '2025-12-18 14:48:06', '2025-12-18 14:48:06', NULL);
INSERT INTO public.sub_modul ("id", "modul_id", "nama_sub_modul", "label", "route", "icon", "urutan", "is_active", "created_at", "updated_at", "parent_id") VALUES (2, 3, 'modul', 'MODUL', '/modul/index', 'ph-duotone ph-squares-four', 2, TRUE, '2025-12-10 09:38:48', '2025-12-10 09:38:48', 15);
INSERT INTO public.sub_modul ("id", "modul_id", "nama_sub_modul", "label", "route", "icon", "urutan", "is_active", "created_at", "updated_at", "parent_id") VALUES (39, 3, 'sub-modul', 'SUB MODUL', '/sub-modul/index', NULL, 1, TRUE, '2025-12-18 14:51:21', '2025-12-18 14:51:21', 15);
INSERT INTO public.sub_modul ("id", "modul_id", "nama_sub_modul", "label", "route", "icon", "urutan", "is_active", "created_at", "updated_at", "parent_id") VALUES (1, 3, 'navigasi', 'NAVIGASI', '/navigasi/index', 'ph-duotone ph-navigation', 1, TRUE, '2025-12-10 09:38:48', '2025-12-10 09:38:48', 15);
INSERT INTO public.sub_modul ("id", "modul_id", "nama_sub_modul", "label", "route", "icon", "urutan", "is_active", "created_at", "updated_at", "parent_id") VALUES (3, 2, 'beranda', 'BERANDA', '/beranda/index', 'ph-duotone ph-house', 0, TRUE, '2025-12-10 09:38:49', '2025-12-10 09:38:49', NULL);
INSERT INTO public.sub_modul ("id", "modul_id", "nama_sub_modul", "label", "route", "icon", "urutan", "is_active", "created_at", "updated_at", "parent_id") VALUES (38, 3, 'data-user', 'DATA USER', '/user-model/index', '', 2, TRUE, '2025-12-18 14:50:24', '2025-12-18 14:50:24', 36);
INSERT INTO public.sub_modul ("id", "modul_id", "nama_sub_modul", "label", "route", "icon", "urutan", "is_active", "created_at", "updated_at", "parent_id") VALUES (37, 3, 'level-user', 'LEVEL USER', '/level-user/index', '', 1, TRUE, '2025-12-18 14:49:45', '2025-12-18 14:49:45', 36);

-- ----------------------------------------------------------
-- Table structure for hak_akses
-- ----------------------------------------------------------
CREATE TABLE public.hak_akses (
    "id" int4 NOT NULL,
    "level_user_id" int4 NOT NULL,
    "modul_id" int4,
    "sub_modul_id" int4,
    "can_view" bool DEFAULT FALSE,
    "can_create" bool DEFAULT FALSE,
    "can_update" bool DEFAULT FALSE,
    "can_delete" bool DEFAULT FALSE,
    "created_at" timestamp DEFAULT CURRENT_TIMESTAMP,
    "updated_at" timestamp DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT "pk_hak_akses" PRIMARY KEY ("id")
);

-- Dumping data for table hak_akses
INSERT INTO public.hak_akses ("id", "level_user_id", "modul_id", "sub_modul_id", "can_view", "can_create", "can_update", "can_delete", "created_at", "updated_at") VALUES (208, 1, 2, 3, TRUE, TRUE, TRUE, TRUE, '2025-12-18 14:43:20', '2025-12-18 14:43:20');
INSERT INTO public.hak_akses ("id", "level_user_id", "modul_id", "sub_modul_id", "can_view", "can_create", "can_update", "can_delete", "created_at", "updated_at") VALUES (216, 1, 3, 1, TRUE, TRUE, TRUE, TRUE, '2025-12-18 14:43:20', '2025-12-18 14:43:20');
INSERT INTO public.hak_akses ("id", "level_user_id", "modul_id", "sub_modul_id", "can_view", "can_create", "can_update", "can_delete", "created_at", "updated_at") VALUES (217, 1, 3, 2, TRUE, TRUE, TRUE, TRUE, '2025-12-18 14:43:20', '2025-12-18 14:43:20');
INSERT INTO public.hak_akses ("id", "level_user_id", "modul_id", "sub_modul_id", "can_view", "can_create", "can_update", "can_delete", "created_at", "updated_at") VALUES (1068, 2, 2, 3, TRUE, FALSE, FALSE, FALSE, '2026-07-06 09:14:21', '2026-07-06 09:14:21');
INSERT INTO public.hak_akses ("id", "level_user_id", "modul_id", "sub_modul_id", "can_view", "can_create", "can_update", "can_delete", "created_at", "updated_at") VALUES (1070, 3, 2, 3, TRUE, FALSE, FALSE, FALSE, '2026-07-06 09:14:21', '2026-07-06 09:14:21');
INSERT INTO public.hak_akses ("id", "level_user_id", "modul_id", "sub_modul_id", "can_view", "can_create", "can_update", "can_delete", "created_at", "updated_at") VALUES (1072, 4, 2, 3, TRUE, FALSE, FALSE, FALSE, '2026-07-06 09:14:21', '2026-07-06 09:14:21');





-- ----------------------------------------------------------
-- Table structure for file_asset
-- ----------------------------------------------------------
CREATE TABLE public.file_asset (
    "id" int4 NOT NULL,
    "file_path" text,
    "hash" text,
    "tipe_file" varchar,
    "ukuran" varchar,
    "id_user" int4,
    "update_date" timestamp DEFAULT CURRENT_TIMESTAMP,
    "file_name" varchar,
    CONSTRAINT "pk_file_asset" PRIMARY KEY ("id")
);

-- ----------------------------------------------------------
-- Table structure for tugas
-- ----------------------------------------------------------
CREATE TABLE public.tugas (
    "id" SERIAL NOT NULL,
    "nama_tugas" varchar NOT NULL,
    "deskripsi" text,
    "status" varchar NOT NULL DEFAULT 'pending',
    "created_at" timestamp DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT "pk_tugas" PRIMARY KEY ("id")
);

-- ----------------------------------------------------------
-- Foreign Key Constraints
-- ----------------------------------------------------------
ALTER TABLE public.user ADD CONSTRAINT "fk_user_level_user" FOREIGN KEY ("level_user_id") REFERENCES public.level_user ("id") ON DELETE CASCADE;
