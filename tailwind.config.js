/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            // KONFIGURASI WARNA: Mengganti tema lama dengan palet warna Navy dan Putih yang lebih modern dan cerah
            colors: {
                'navy-dark': '#1e3a8a',   // Warna biru laut gelap untuk teks utama, header, dan elemen dengan kontras tinggi
                'navy-base': '#1d4ed8',   // Warna biru laut standar untuk aksen utama, tombol, dan sidebar
                'navy-light': '#60a5fa',  // Warna biru muda terang untuk efek hover, border, dan elemen ornamen
                'white-pure': '#ffffff',  // Warna putih murni untuk latar belakang kartu dan kontainer utama
                'white-off': '#f8fafc',   // Warna putih keabu-abuan (slate-50) untuk latar belakang halaman secara keseluruhan
                'gray-muted': '#64748b',  // Warna abu-abu sekunder (slate-500) untuk teks deskripsi atau elemen kurang menonjol
            },
            fontFamily: {
                'sans': ['"Nunito Sans"', 'sans-serif'],
                'heading': ['Lora', 'serif'],
                'body': ['"Nunito Sans"', 'sans-serif'],
            }
        },
    },
    plugins: [],
};
