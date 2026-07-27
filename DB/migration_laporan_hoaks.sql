-- =========================================================================
-- SQL Migration: Tabel Laporan Hoaks & Pengaduan Masyarakat (SIPKK Kemenkes)
-- DBMS: PostgreSQL
-- =========================================================================

CREATE TABLE IF NOT EXISTS public.tbl_laporan_hoaks (
    id SERIAL PRIMARY KEY,
    no_tiket VARCHAR(100) NOT NULL UNIQUE,
    nama_pelapor VARCHAR(255) NOT NULL,
    email_pelapor VARCHAR(255) NOT NULL,
    telepon_pelapor VARCHAR(30),
    judul_isu VARCHAR(255) NOT NULL,
    kategori_slug VARCHAR(100) DEFAULT 'pengaduan-masyarakat',
    deskripsi_isu TEXT NOT NULL,
    bukti_url TEXT,
    status_verifikasi VARCHAR(50) DEFAULT 'BARU',
    status_hoaks BOOLEAN DEFAULT TRUE,
    penjelasan_fakta TEXT,
    counter_fact_urls TEXT,
    alasan_penolakan TEXT,
    verified_by INT,
    verified_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_laporan_no_tiket ON public.tbl_laporan_hoaks(no_tiket);
CREATE INDEX IF NOT EXISTS idx_laporan_status ON public.tbl_laporan_hoaks(status_verifikasi);
CREATE INDEX IF NOT EXISTS idx_laporan_email ON public.tbl_laporan_hoaks(email_pelapor);
