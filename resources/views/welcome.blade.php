<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMP Science Mutiara Insani Purwakarta - Beranda Utama</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white-off text-navy-dark antialiased font-sans">

    <!-- FUNGSI KODE: Sticky Navbar Navigasi Utama -->
    <nav class="bg-white-pure/90 backdrop-blur-md border-b border-navy-light/20 sticky top-0 z-50 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo & Identitas Sekolah -->
                <a href="#hero" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo SMP Science Mutiara Insani" class="w-11 h-11 object-contain drop-shadow-sm group-hover:scale-105 transition-transform">
                    <div>
                        <h1 class="font-bold text-lg text-navy-dark leading-tight font-heading group-hover:text-navy-base transition-colors">SMP Science Mutiara Insani</h1>
                        <p class="text-[10px] text-gray-muted font-bold uppercase tracking-widest">Purwakarta, Jawa Barat • Terakreditasi A</p>
                    </div>
                </a>

                <!-- Menu Navigasi Tengah (Nav-links dengan ScrollSpy) -->
                <div class="hidden md:flex items-center space-x-8 text-sm">
                    <a href="#hero" class="nav-link text-navy-base font-bold border-b-2 border-navy-base pb-1 transition-all">Beranda</a>
                    <a href="#visi-misi" class="nav-link text-gray-muted font-medium hover:text-navy-base transition-all">Visi & Misi</a>
                    <a href="#tujuan" class="nav-link text-gray-muted font-medium hover:text-navy-base transition-all">Tujuan Sekolah</a>
                    <a href="#keunggulan" class="nav-link text-gray-muted font-medium hover:text-navy-base transition-all">Keunggulan</a>
                </div>

                <!-- Tombol Portal Akademik (Arah ke Login) -->
                <div>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-navy-dark to-navy-base hover:from-navy-base hover:to-navy-dark text-white-off px-5 py-2.5 rounded-xl font-bold text-xs shadow-md shadow-navy-base/20 hover:shadow-lg active:scale-95 transition-all">
                        <span>Portal Akademik</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- FUNGSI KODE: Hero Banner Utama (Navy Gradient) -->
    <section id="hero" class="scroll-mt-20 relative bg-gradient-to-br from-navy-dark via-navy-base to-[#1e3a8a] text-white-off overflow-hidden">
        <!-- Decorative Blurred Circles & Grid -->
        <div class="absolute -right-20 -top-20 w-96 h-96 bg-navy-light/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-96 h-96 bg-navy-light/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-28 text-center">
            <!-- Badge Tahun Ajaran -->
            <div class="inline-flex items-center gap-2 bg-navy-dark/60 border border-navy-light/30 px-4 py-1.5 rounded-full mb-6 backdrop-blur-md shadow-sm">
                <span class="w-2 h-2 rounded-full bg-navy-light animate-pulse"></span>
                <span class="text-xs font-bold text-navy-light tracking-wide font-mono">Tahun Ajaran 2026/2027 • SMP Science Mutiara Insani</span>
            </div>

            <!-- Judul Utama Hero Banner -->
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold font-heading text-white-off mb-6 leading-tight max-w-4xl mx-auto drop-shadow-md">
                Membentuk Generasi Cerdas, <br>
                <span class="text-navy-light underline decoration-navy-light/40 decoration-wavy">Berakhlak Mulia & Berprestasi</span>
            </h1>

            <p class="text-base sm:text-lg text-white-off/90 mb-10 max-w-2xl mx-auto leading-relaxed">
                Selamat datang di website resmi SMP Science Mutiara Insani Purwakarta. Mengintegrasikan kurikulum sains modern, nilai-nilai religius, dan teknologi informasi dalam mewujudkan masa depan peserta didik yang gemilang.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2.5 bg-white-pure hover:bg-white-off text-navy-dark px-7 py-3.5 rounded-xl font-bold text-sm transition-all shadow-xl hover:-translate-y-0.5 active:scale-95">
                    <svg class="w-5 h-5 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    <span>Masuk Portal Siswa & Guru</span>
                </a>
            </div>

            </div>
        </div>
    </section>

    <!-- FUNGSI KODE: Bagian VISI & MISI SEKOLAH -->
    <section id="visi-misi" class="scroll-mt-20 py-14 bg-white-off">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto">
                <span class="text-xs font-bold text-navy-base uppercase tracking-widest bg-navy-light/10 px-3 py-1 rounded-md border border-navy-light/30 inline-block mb-3">
                    Pilar Utama Sekolah
                </span>
                <h2 class="text-3xl sm:text-4xl font-bold text-navy-dark font-heading mb-4">Visi & Misi Sekolah</h2>
                <p class="text-gray-muted text-sm leading-relaxed">
                    Pedoman dan landasan utama SMP Science Mutiara Insani dalam menyelenggarakan pendidikan berkualitas untuk membentuk generasi masa depan yang tangguh.
                </p>
            </div>

            <!-- KARTU VISI (Besar & Menonjol) -->
            <div class="bg-white rounded-3xl p-8 sm:p-10 border border-navy-light/30 shadow-md shadow-navy-base/5 relative overflow-hidden group hover:-translate-y-1 hover:shadow-xl hover:border-navy-base/40 transition-all duration-300">
                <div class="w-2 h-full absolute left-0 top-0 bottom-0 bg-gradient-to-b from-navy-base to-navy-dark"></div>
                
                <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                    <div class="w-16 h-16 rounded-2xl bg-navy-light/10 text-navy-base flex items-center justify-center shrink-0 border border-navy-light/30 shadow-sm group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <div class="inline-block px-3 py-1 bg-navy-dark text-white-off text-[10px] font-bold uppercase tracking-widest rounded-md mb-2 font-mono">
                            VISI SEKOLAH
                        </div>
                        <blockquote class="text-xl sm:text-2xl font-bold text-navy-dark font-heading leading-relaxed">
                            "Terwujudnya peserta didik yang memiliki jatidiri yang tangguh dan kompeten yang berlandaskan nilai-nilai science untuk masa depan gemilang."
                        </blockquote>
                    </div>
                </div>
            </div>

            <!-- GRID MISI SEKOLAH (7 Kartu Interaktif) -->
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-navy-base text-white flex items-center justify-center font-bold text-sm shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-navy-dark font-heading">7 Misi Utama Sekolah</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php
                        $misiList = [
                            "Menciptakan profil pelajar yang berakhlak mulia dan rajin beribadah.",
                            "Menciptakan pembelajaran yang menarik, menyenangkan dan berkarakter yang mampu memfasilitasi pelajar sesuai bakat dan minatnya.",
                            "Mendidik dan menjadikan siswa memiliki nilai sikap dan ketrampilan dibidang science.",
                            "Menciptakan lingkungan sekolah yang bersih, asri dan ramah anak.",
                            "Memfasilitasi pelajar untuk dapat mengembangkan potensi dan kemampuannya secara optimal.",
                            "Mengembangkan sekolah berwawasan global yang didasari nilai-nilai budaya dan nilai-nilai luhur bangsa.",
                            "Melibatkan orang tua dan masyarakat dalam proses pembelajaran dan pengembangan sekolah."
                        ];
                    @endphp

                    @foreach($misiList as $index => $misi)
                    <div class="bg-white p-6 rounded-2xl border border-navy-light/30 shadow-sm shadow-navy-base/5 hover:shadow-lg hover:border-navy-base/40 hover:-translate-y-1 transition-all duration-300 flex items-start gap-4 group">
                        <div class="w-9 h-9 rounded-xl bg-navy-light/10 text-navy-base font-bold font-mono text-sm flex items-center justify-center shrink-0 border border-navy-light/30 group-hover:bg-navy-base group-hover:text-white transition-colors">
                            0{{ $index + 1 }}
                        </div>
                        <p class="text-xs text-navy-dark font-semibold leading-relaxed pt-1">
                            {{ $misi }}
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </section>

    <!-- FUNGSI KODE: Bagian TUJUAN SEKOLAH (10 Target Pendidikan) -->
    <section id="tujuan" class="scroll-mt-20 py-14 bg-white-pure border-t border-b border-navy-light/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto">
                <span class="text-xs font-bold text-navy-base uppercase tracking-widest bg-navy-light/10 px-3 py-1 rounded-md border border-navy-light/30 inline-block mb-3">
                    Target & Komitmen
                </span>
                <h2 class="text-3xl sm:text-4xl font-bold text-navy-dark font-heading mb-4">Tujuan Pendidikan Sekolah</h2>
                <p class="text-gray-muted text-sm leading-relaxed">
                    10 Tujuan strategis SMP Science Mutiara Insani untuk menghasilkan lulusan yang berintegritas dan siap menghadapi perkembangan zaman.
                </p>
            </div>

            <!-- GRID 10 TUJUAN SEKOLAH -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @php
                    $tujuanList = [
                        "Merancang pembelajaran yang mengedepankan ciri khas sekolah dan daerah dalam nuansa kebhinekaan global yang harmonis.",
                        "Merancang dan melaksanakan program-program pendidikan kreatif dan inovatif yang mencakup pembelajaran berbasis proyek, pengembangan keterampilan berpikir kritis, dan stimulasi kreativitas peserta didik.",
                        "Meningkatkan kemampuan berbahasa inggris peserta didik melalui program-program pendidikan.",
                        "Membentuk peserta didik yang memiliki kemampuan daya saing, berkarakter, berprestasi dan memiliki pribadi yang beriman, rajin dan taat beribadah serta saling menghargai perbedaan dan mencintai lingkungan dan bangsanya.",
                        "Meningkatkan prestasi akademis peserta didik dan pengembangan kemampuan berpikir kritis melalui penerapan metode-metode pembelajaran yang efektif dan relevan.",
                        "Meningkatkan tingkat keterlibatan peserta didik dalam kegiatan pembelajaran dan meningkatkan kepuasan peserta didik terhadap proses pendidikan secara keseluruhan.",
                        "Menghasilkan lulusan yang mampu mengimplementasikan Profil Pelajar Pancasila dalam kehidupan nyata yang menguasai ilmu pengetahuan dan teknologi.",
                        "Menjadi pemimpin bagi diri dan temannya untuk menjadi pribadi yang bernalar kritis, tangguh, percaya diri dan bangga dalam kegotong-royongan.",
                        "Menguasai kecakapan dalam berkomunikasi sosial dan berjiwa kompetitif, kreatif dan mandiri yang tetap menjunjung budaya lokal.",
                        "Mempunyai life skill yang mampu berdaptasi dengan perkembangan jaman."
                    ];
                @endphp

                @foreach($tujuanList as $index => $tujuan)
                <div class="bg-white-off p-6 rounded-2xl border border-navy-light/30 shadow-sm hover:shadow-md hover:border-navy-base/40 hover:-translate-y-1 transition-all duration-300 flex items-start gap-4 group">
                    <div class="w-10 h-10 rounded-xl bg-navy-dark text-white-off font-bold font-mono text-xs flex items-center justify-center shrink-0 shadow-sm border border-navy-light/30 group-hover:scale-105 transition-transform">
                        {{ sprintf("%02d", $index + 1) }}
                    </div>
                    <div class="pt-0.5">
                        <h4 class="text-sm font-bold text-navy-dark font-heading mb-1 group-hover:text-navy-base transition-colors">Tujuan {{ $index + 1 }}</h4>
                        <p class="text-xs text-gray-muted font-medium leading-relaxed">
                            {{ $tujuan }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- FUNGSI KODE: Bagian KEUNGGULAN SEKOLAH -->
    <section id="keunggulan" class="scroll-mt-20 py-14 bg-white-off">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            
            <div class="text-center max-w-3xl mx-auto">
                <span class="text-xs font-bold text-navy-base uppercase tracking-widest bg-navy-light/10 px-3 py-1 rounded-md border border-navy-light/30 inline-block mb-3">
                    Keunggulan Sekolah
                </span>
                <h2 class="text-3xl sm:text-4xl font-bold text-navy-dark font-heading mb-4">Mengapa SMP Science Mutiara Insani?</h2>
                <p class="text-gray-muted text-sm leading-relaxed">
                    Fasilitas dan pendekatan pembelajaran terbaik yang dirancang untuk mendukung potensi peserta didik secara optimal.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Keunggulan 1 -->
                <div class="bg-white p-7 rounded-2xl border border-navy-light/30 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 text-center group">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-navy-light/10 text-navy-base flex items-center justify-center mb-5 border border-navy-light/30 group-hover:scale-110 group-hover:bg-navy-base group-hover:text-white transition-all shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <h3 class="font-bold text-navy-dark text-base font-heading mb-2">Kurikulum Science</h3>
                    <p class="text-xs text-gray-muted leading-relaxed font-medium">Pembelajaran sains interaktif, praktik laboratorium, dan pengembangan daya nalar kritis.</p>
                </div>

                <!-- Keunggulan 2 -->
                <div class="bg-white p-7 rounded-2xl border border-navy-light/30 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 text-center group">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-5 border border-emerald-200 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 16.5 5c1.747 0 3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="font-bold text-navy-dark text-base font-heading mb-2">Karakter & Religius</h3>
                    <p class="text-xs text-gray-muted leading-relaxed font-medium">Penanaman akhlak mulia, pembiasaan ibadah harian, dan toleransi kebhinekaan.</p>
                </div>

                <!-- Keunggulan 3 -->
                <div class="bg-white p-7 rounded-2xl border border-navy-light/30 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 text-center group">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-5 border border-amber-200 group-hover:scale-110 group-hover:bg-amber-600 group-hover:text-white transition-all shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="font-bold text-navy-dark text-base font-heading mb-2">Portal Digital</h3>
                    <p class="text-xs text-gray-muted leading-relaxed font-medium">Integrasi teknologi informasi dalam pemantauan akademik, jadwal, dan administrasi.</p>
                </div>

                <!-- Keunggulan 4 -->
                <div class="bg-white p-7 rounded-2xl border border-navy-light/30 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 text-center group">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mb-5 border border-purple-200 group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white transition-all shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    </div>
                    <h3 class="font-bold text-navy-dark text-base font-heading mb-2">Ekstrakurikuler & Bakat</h3>
                    <p class="text-xs text-gray-muted leading-relaxed font-medium">Wadah penelusuran dan pembinaan bakat seni, olahraga, dan sains secara intensif.</p>
                </div>
            </div>

        </div>
    </section>

    <!-- FUNGSI KODE: Section Video Profil & Live Map Lokasi Sekolah -->
    <section class="py-12 bg-white-pure border-t border-navy-light/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                
                <!-- Card 1: Video Profil Sekolah (YouTube Embed) -->
                <div class="lg:col-span-6 bg-white-pure rounded-3xl p-5 sm:p-6 border border-navy-light/30 shadow-xl flex flex-col justify-between relative overflow-hidden group min-h-[280px]">
                    <div class="flex items-center justify-between gap-3 mb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shrink-0 border border-red-200 shadow-sm">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-base text-navy-dark font-heading">Video Profil Sekolah</h4>
                                <p class="text-xs text-gray-muted font-medium">SMP Science Mutiara Insani Purwakarta</p>
                            </div>
                        </div>
                        <a href="https://youtu.be/NYeyeyumWPA?si=ncWARGMhPVnf24Wv" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-600 hover:text-white text-red-600 text-xs font-semibold rounded-xl border border-red-200 transition-all shrink-0">
                            <span>YouTube</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>

                    <!-- YouTube Embed Iframe -->
                    <div class="rounded-2xl overflow-hidden border border-gray-200 shadow-inner flex-1 min-h-[170px] relative aspect-video">
                        <iframe 
                            src="https://www.youtube.com/embed/NYeyeyumWPA" 
                            title="Video Profil SMP Science Mutiara Insani" 
                            class="w-full h-full border-0 rounded-2xl" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>

                <!-- Card 2: Live Map Lokasi SMP Science Mutiara Insani (Samping Card CTA) -->
                <div class="lg:col-span-6 bg-white-pure rounded-3xl p-5 sm:p-6 border border-navy-light/30 shadow-xl flex flex-col justify-between relative overflow-hidden group min-h-[280px]">
                    <div class="flex items-center justify-between gap-3 mb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-navy-light/10 flex items-center justify-center text-navy-base shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-base text-navy-dark font-heading">Lokasi SMP Science Mutiara Insani</h4>
                                <p class="text-xs text-gray-muted font-medium">Munjuljaya, Purwakarta, Jawa Barat</p>
                            </div>
                        </div>
                        <a href="https://www.google.com/maps/place/SMP+Science+Mutiara+Insani/@-6.5312535,107.4705822,17z/data=!3m1!4b1!4m6!3m5!1s0x2e690f71de365203:0x25ee54b1bd5ccdb1!8m2!3d-6.5312535!4d107.4731571!16s%2Fg%2F11s53ys3pp?entry=ttu&g_ep=EgoyMDI2MDkwMi4wIKXMDSoASAFQAw%3D%3D" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-navy-base/10 hover:bg-navy-base hover:text-white-pure text-navy-base text-xs font-semibold rounded-xl transition-all shrink-0">
                            <span>Google Maps</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>

                    <!-- Embed Live Interactive Google Maps -->
                    <div class="rounded-2xl overflow-hidden border border-gray-200 shadow-inner flex-1 min-h-[170px] relative">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.7840131971714!2d107.4705822!3d-6.5312535!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e690f71de365203%3A0x25ee54b1bd5ccdb1!2sSMP%20Science%20Mutiara%20Insani!5e0!3m2!1sid!2sid!4v1710000000000!5m2!1sid!2sid" 
                            class="w-full h-full border-0 transition-all duration-300 min-h-[170px]" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- FUNGSI KODE: Footer Utama Halaman Publik -->
    <footer class="bg-navy-dark text-white-off py-12 border-t border-navy-light/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 pb-10 border-b border-navy-light/20">
                <!-- Kolom 1: Profil Sekolah & Logo -->
                <div class="md:col-span-5 space-y-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo SMP Science Mutiara Insani" class="w-12 h-12 object-contain drop-shadow-md">
                        <div>
                            <h4 class="font-bold text-lg font-heading text-white-pure">SMP Science Mutiara Insani</h4>
                            <p class="text-xs text-white-off/80 font-medium">Terakreditasi A • Purwakarta, Jawa Barat</p>
                        </div>
                    </div>
                    <p class="text-xs text-white-off/90 leading-relaxed max-w-sm">
                        Sekolah Menengah Pertama berbasis Sains & Karakter Islami di Kabupaten Purwakarta, mencetak generasi berprestasi, berilmu, dan berakhlak mulia.
                    </p>
                </div>

                <!-- Kolom 2: Detail Lokasi & Kontak -->
                <div class="md:col-span-4 space-y-3">
                    <h5 class="text-sm font-bold text-white-pure font-heading tracking-wide uppercase">Alamat & Lokasi</h5>
                    <div class="space-y-2.5 text-xs text-white-off/90">
                        <div class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-white-off shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-white-off font-medium">Munjuljaya, Kec. Purwakarta, Kabupaten Purwakarta, Jawa Barat 41117</span>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-white-off font-medium">Jam Operasional: Senin - Sabtu (Tutup pukul 16.00 WIB)</span>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-white-off shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m-9 9a9 9 0 019-9"/>
                            </svg>
                            <a href="https://smpsciencemutiarainsani.com" target="_blank" class="text-white-off font-medium hover:text-white-pure transition-colors underline decoration-white-off/40">smpsciencemutiarainsani.com</a>
                        </div>
                    </div>

                    <div class="pt-1 flex flex-col gap-2">
                        <a href="https://www.google.com/maps/place/SMP+Science+Mutiara+Insani/@-6.5312535,107.4705822,17z/data=!3m1!4b1!4m6!3m5!1s0x2e690f71de365203:0x25ee54b1bd5ccdb1!8m2!3d-6.5312535!4d107.4731571!16s%2Fg%2F11s53ys3pp?entry=ttu&g_ep=EgoyMDI2MDkwMi4wIKXMDSoASAFQAw%3D%3D" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-3 py-1.5 bg-navy-base/80 hover:bg-navy-base text-white-pure text-xs font-semibold rounded-lg border border-navy-light/40 transition-all shadow-md w-fit">
                            <svg class="w-3.5 h-3.5 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                            </svg>
                            <span>Buka di Google Maps</span>
                            <svg class="w-3 h-3 text-white-off" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Kolom 3: Navigasi Halaman -->
                <div class="md:col-span-3 space-y-3">
                    <h5 class="text-sm font-bold text-white-pure font-heading tracking-wide uppercase">Navigasi</h5>
                    <ul class="space-y-2 text-xs text-white-off/90 font-medium">
                        <li><a href="#hero" class="hover:text-white-pure transition-colors">Beranda Utama</a></li>
                        <li><a href="#visi-misi" class="hover:text-white-pure transition-colors">Visi & Misi</a></li>
                        <li><a href="#tujuan" class="hover:text-white-pure transition-colors">Tujuan Sekolah</a></li>
                        <li><a href="#keunggulan" class="hover:text-white-pure transition-colors">Keunggulan Sekolah</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white-pure transition-colors font-bold text-amber-300">Login Portal Sistem</a></li>
                    </ul>
                </div>
            </div>

            <!-- Copyright Footer (Teks Tengah) -->
            <div class="pt-6 text-center text-xs text-white-off/80 font-medium">
                <p>© 2026 SMP Science Mutiara Insani Purwakarta. Seluruh hak cipta dilindungi undang-undang.</p>
            </div>
        </div>
    </footer>

    <!-- FUNGSI KODE: ScrollSpy untuk memindahkan garis bawah (active indicator) navbar sesuai section yang aktif -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-link');

            const activeClasses = ['text-navy-base', 'font-bold', 'border-b-2', 'border-navy-base', 'pb-1'];
            const inactiveClasses = ['text-gray-muted', 'font-medium'];

            function updateActiveLink() {
                let currentSection = 'hero';
                const scrollPosition = window.scrollY + 85; // offset presisi sesuai tinggi sticky navbar 80px

                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.offsetHeight;
                    if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                        currentSection = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    const href = link.getAttribute('href').replace('#', '');
                    if (href === currentSection) {
                        link.classList.remove(...inactiveClasses);
                        link.classList.add(...activeClasses);
                    } else {
                        link.classList.remove(...activeClasses);
                        link.classList.add(...inactiveClasses);
                    }
                });
            }

            window.addEventListener('scroll', updateActiveLink);
            updateActiveLink();
        });
    </script>

</body>
</html>