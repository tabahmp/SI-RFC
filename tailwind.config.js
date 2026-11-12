/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.{vue,js,ts,blade.php}",
    "./resources/views/**/*.blade.php",
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx,vue}",
  ],
  theme: {
    extend: {
      // ─────────────────────────────────────────────
      // FONT FAMILY — extracted from Figma PT SI-RFC
      // ─────────────────────────────────────────────
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        inter: ['Inter', 'sans-serif'],
      },

      // ─────────────────────────────────────────────
      // FONT SIZE — extracted from Figma PT SI-RFC
      // ─────────────────────────────────────────────
      fontSize: {
        'xs-2':  ['9px',  { lineHeight: '110%' }],   // Kategori label uppercase
        'xs':    ['10px', { lineHeight: '132%' }],   // Audit trail, badge
        'xs-1':  ['11px', { lineHeight: '124%' }],   // Caption / note kecil
        'sm':    ['12px', { lineHeight: '124%' }],   // Body small, label form
        'sm-1':  ['13px', { lineHeight: '124%' }],   // Body medium kecil
        'base':  ['14px', { lineHeight: '124%' }],   // Body utama
        'base-1':['15px', { lineHeight: '124%' }],   // Body besar
        'md':    ['16px', { lineHeight: '124%' }],   // Paragraf / konten
        'lg':    ['17px', { lineHeight: '124%' }],   // Sub-heading kecil
        'xl':    ['18px', { lineHeight: '124%' }],   // Heading section
        'xl-1':  ['19px', { lineHeight: '120%' }],   // Dashboard stats
        '2xl':   ['20px', { lineHeight: '116%' }],   // Heading card
        '2xl-1': ['22px', { lineHeight: '124%' }],   // Icon / symbol besar
        '2xl-2': ['23px', { lineHeight: '120%' }],   // Form title kecil
        '3xl':   ['24px', { lineHeight: '110%' }],   // Heading utama card
        '3xl-1': ['26px', { lineHeight: '124%' }],   // CTA heading
        '4xl':   ['28px', { lineHeight: '106%' }],   // Page title
        '4xl-1': ['30px', { lineHeight: '124%' }],   // Success / konfirmasi
        '5xl':   ['32px', { lineHeight: '124%' }],   // Auth heading
        '5xl-1': ['36px', { lineHeight: '124%' }],   // Pusat bantuan heading
        '6xl':   ['48px', { lineHeight: '90%' }],    // Hero / judul utama
      },

      // ─────────────────────────────────────────────
      // FONT WEIGHT — extracted from Figma PT SI-RFC
      // ─────────────────────────────────────────────
      fontWeight: {
        normal:    '400',
        medium:    '500',
        semibold:  '600',
        bold:      '700',
        extrabold: '800',
      },

      // ─────────────────────────────────────────────
      // LETTER SPACING
      // ─────────────────────────────────────────────
      letterSpacing: {
        tightest: '0px',
        tight:    '1.4px',   // badge uppercase kecil
        wide:     '1.8px',   // kategori label
        wider:    '2.0px',   // akun demo uppercase
      },

      // ─────────────────────────────────────────────
      // COLORS — extracted from Figma PT SI-RFC
      // ─────────────────────────────────────────────
      colors: {
        // ── Neutral / Slate ──
        'neutral': {
          950: '#020617',  // Latar modal / overlay gelap
          900: '#09090b',  // Judul utama gelap
          800: '#0b1021',  // Judul formulir
          700: '#0f172a',  // Judul halaman
          600: '#334155',  // Nama pengguna
          500: '#475569',  // Posisi / subtext
          400: '#52525b',  // Penjelasan
          300: '#64748b',  // Placeholder sekunder
          200: '#e2e8f0',  // Garis bawah / divider
          100: '#f1f5f9',  // Background pencarian
          50:  '#f8fafc',  // Background tabel head
        },

        // ── Biru / Brand Utama (SI-RFC) ──
        'brand': {
          900: '#063d78',  // Peringatan SLA gelap
          800: '#073b70',  // Kategori
          700: '#0754a6',  // Aksen garis
          600: '#0757a8',  // Aksen Jawa Barat utama
          550: '#1f5186',  // Timer SLA
          500: '#1d4ed8',  // Label / link aksi
          400: '#1e40af',  // Isi konten link
          300: '#2563eb',  // CTA / tombol utama
          200: '#416d9c',  // Track progress
          150: '#26568a',  // Latar lambang
          100: '#bfdbfe',  // Petunjuk info
          50:  '#eff6ff',  // Background icon biru
          25:  '#eaf2fd',  // Tab aktif / filter
        },

        // ── Biru Panel / Sidebar ──
        'panel': {
          800: '#285981',  // Jumlah / count
          700: '#2d405e',  // Label email
          600: '#32608e',  // Audit trail link
          500: '#344965',  // Pemohon
          400: '#7d94b1',  // Akun demo
          300: '#8ca3c1',  // Garis dekoratif
          200: '#a9c9e8',  // Bagian menu
          100: '#d8e7f8',  // Latar kartu dinas
          50:  '#e0eaf5',  // Deskripsi layanan
          25:  '#f3f7fb',  // Background editable
        },

        // ── Hijau Jawa Barat (Aksen Resmi) ──
        'jabar': {
          800: '#065f46',  // Catatan
          700: '#047857',  // Label hijau tua
          600: '#16834a',  // Aksen Jawa Barat
          500: '#059669',  // Garis vektor
          400: '#106144',  // Audit trail lengkap
          300: '#65c36e',  // Progres implementasi
          200: '#6ee7b7',  // Setujui stroke
          100: '#a7f3d0',  // Priority rendah stroke
          50:  '#ecfdf5',  // Background icon hijau
          25:  '#f7fcfa',  // Background dibuat oleh
        },

        // ── Merah / Danger ──
        'danger': {
          800: '#991b1b',  // Pesan error tua
          700: '#b91c1c',  // Label error
          600: '#dc2626',  // Garis error
          500: '#ef4444',  // Notifikasi baru
          100: '#fecaca',  // Priority kritis stroke
          50:  '#fef2f2',  // Background icon merah
          25:  '#ffd5d5',  // Label merah muda
        },

        // ── Oranye / Warning ──
        'warning': {
          700: '#c2410c',  // Label oranye tua
          600: '#b45309',  // Garis oranye
          500: '#92400e',  // Label amber
          100: '#fed7aa',  // Priority tinggi stroke
          50:  '#fff7ed',  // Background priority P2
        },

        // ── Kuning / Pending ──
        'pending': {
          400: '#f4ce18',  // Label layanan / badge
          300: '#fcd34d',  // Tunda stroke
          200: '#fde68a',  // Priority sedang stroke
          50:  '#fffbeb',  // Background icon kuning
        },

        // ── Cyan / Info ──
        'info': {
          600: '#0e7490',  // Label cyan
          50:  '#ecfeff',  // Background pengerjaan
        },

        // ── State Helpers ──
        'white': '#ffffff',
        'surface': '#f7f9fc',  // Background upload / area konten
      },

      // ─────────────────────────────────────────────
      // SPACING — extracted from Figma PT SI-RFC
      // ─────────────────────────────────────────────
      spacing: {
        '2.5': '10px',  // Gap kecil antar elemen
        '2':   '8px',   // Padding kompak
      },

      // ─────────────────────────────────────────────
      // BORDER RADIUS — umum untuk sistem SI-RFC
      // ─────────────────────────────────────────────
      borderRadius: {
        'sm':   '4px',
        'md':   '6px',
        'lg':   '8px',
        'xl':   '12px',
        '2xl':  '16px',
        'full': '9999px',
      },

      // ─────────────────────────────────────────────
      // BOX SHADOW — kartu & panel
      // ─────────────────────────────────────────────
      boxShadow: {
        'card':  '0 1px 3px 0 rgba(0, 0, 0, 0.10), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
        'panel': '0 4px 6px -1px rgba(0, 0, 0, 0.10), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
        'modal': '0 20px 25px -5px rgba(2, 6, 23, 0.15), 0 10px 10px -5px rgba(2, 6, 23, 0.04)',
      },
    },
  },
  plugins: [],
}


