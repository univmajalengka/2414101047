<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karya Wisata Bali: Pesona Pulau Dewata</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>KARYA WISATA BALI: PESONA PULAU DEWATA</h1>
            <p>Jelajahi Keindahan Alam, Seni, dan Budaya yang Tak Tertandingi</p>
        </div>
    </header>

    <div class="banner container">
        <img src="https://akcdn.detik.net.id/visual/2020/09/01/kemenpar-iklan-1_169.png?w=1200" alt="Pura Bali" class="banner-image">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTzbHahFmesB35WcWXLLEvs-iO7tUft8OZSCQ&s" alt="Seni Ukir" class="banner-image">
        <img src="https://www.indonesia.travel/contentassets/6a369640e8f542cfa87529d75fad588d/pantai-bali.jpeg" alt="Pantai" class="banner-image">
        <img src="https://bali-home-immo.com/images/blogs/6aa681bfb2398f2dc26d126a29ee2933.jpeg" alt="Desa Tradisional" class="banner-image">
        <img src="https://awsimages.detik.net.id/community/media/visual/2023/02/26/pura-ulun-danu-beratan-bedugul-tabanan-bali_169.png?w=1200" alt="Pura di Danau" class="banner-image">
        <img src="https://static.wixstatic.com/media/db9f0d_29bcd7de31c7498ea67ab4c5c3b60b70~mv2.jpg/v1/fill/w_568,h_318,al_c,q_80,usm_0.66_1.00_0.01,enc_avif,quality_auto/db9f0d_29bcd7de31c7498ea67ab4c5c3b60b70~mv2.jpg" alt="Kopi Bali" class="banner-image">
        <img src="https://ik.imagekit.io/tvlk/blog/2024/09/shutterstock_2400505983.jpg" alt="Sawah Bali" class="banner-image">
        <img src="https://www.water-sport-bali.com/wp-content/uploads/2020/07/20-Tempat-Wisata-Untuk-Dikunjungi-Bali-Facebook.jpg" alt="Objek Wisata Alam" class="banner-image">
    </div>

    <nav class="nav-wrapper">
        <div class="container nav-menu">
            <ul>
                <li><a href="#section-beranda" data-target="section-beranda">Beranda</a></li>
                <li><a href="#section-about" data-target="section-about">About</a></li>
                <li><a href="#section-wisata" data-target="section-wisata">Obyek Wisata</a></li>
                <li><a href="#section-fasilitas" data-target="section-fasilitas">Fasilitas Wisata</a></li>
                <li><a href="#section-paket" data-target="section-paket">Paket Wisata</a></li>
                <li><a href="#section-galeri" data-target="section-galeri">Galeri</a></li>
                <li><a href="#section-pemesanan" data-target="section-pemesanan">Pemesanan</a></li>
            </ul>

            <div class="nav-buttons">
                <a class="btn-pesanan-saya" href="login.php">Pesanan Saya</a>
                <a class="btn-booking-now" href="booking.php">Booking Now</a>
            </div>
        </div>
    </nav>

    <main>
        <section id="section-beranda" class="section active">
            <div class="container beranda-content">
                <h2>Selamat Datang di Karya Wisata Bali</h2>
                <p>Menemani perjalanan Anda menikmati keindahan alam, seni, dan budaya Bali dengan layanan ramah dan profesional.</p>

                <div class="beranda-features">
                    <div class="feature-card">
                        <h3>Pemandu Berpengalaman</h3>
                        <p>Pemandu lokal yang mengetahui rute terbaik dan cerita budaya setempat.</p>
                    </div>
                    <div class="feature-card">
                        <h3>Transportasi Nyaman</h3>
                        <p>Armada yang terawat lengkap dengan sopir berpengalaman.</p>
                    </div>
                    <div class="feature-card">
                        <h3>Paket Terjangkau</h3>
                        <p>Pilihan paket sesuai kebutuhan: adventure, budaya, keluarga, dan private tour.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="section-about" class="section">
            <div class="container about-content">
                <div class="about-text">
                    <h2>Tentang Kami</h2>
                    <h3>Visi & Misi</h3>
                    <p>Kami berkomitmen menghadirkan pengalaman wisata yang aman, mendidik, dan menyenangkan.</p>
                </div>
                <div class="about-image">
                    <img src="https://kemenparekraf.go.id/_next/image?url=https%3A%2F%2Fapi2.kemenparekraf.go.id%2Fstorage%2Fapp%2Fuploads%2Fpublic%2F620%2Fb45%2F3fb%2F620b453fbfafa855804364.jpg&w=3840&q=75" alt="Tentang Bali">
                </div>
            </div>
        </section>

        <section id="section-wisata" class="section">
            <div class="container">
                <h2>Obyek Wisata</h2>
                <div class="wisata-grid">
                    <div class="wisata-card">
                        <img src="https://www.uluwatukecakdance.com/wp-content/uploads/Kawasan-Wisata-Pura-di-Uluwatu.jpg" alt="Uluwatu">
                        <div class="wisata-card-content">
                            <h3>Pura Uluwatu</h3>
                            <p>Pura tepi tebing dengan pemandangan sunset menakjubkan.</p>
                        </div>
                    </div>
                    <div class="wisata-card">
                        <img src="https://asset.kompas.com/crops/4C2-bd8g4csTm8_bB-rvrsw5wCY=/0x0:1000x667/1200x800/data/photo/2020/09/17/5f6374a8b8b82.jpg" alt="Tegalalang">
                        <div class="wisata-card-content">
                            <h3>Terra Sawah Tegalalang</h3>
                            <p>Terra terasering sawah ikonik Bali yang terkenal di dunia.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="section-fasilitas" class="section">
            <div class="container">
                <h2>Fasilitas Wisata</h2>
                <div class="fasilitas-grid">
                    <div class="fasilitas-item">
                        <h3>Akomodasi</h3>
                        <p>Hotel, villa, dan homestay pilihan sesuai anggaran.</p>
                    </div>
                    <div class="fasilitas-item">
                        <h3>Transportasi</h3>
                        <p>Mobil, bus pariwisata, dan antar-jemput bandara.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="section-paket" class="section">
            <div class="container main-content">
                <div class="packages-section">
                    <h2>Paket Wisata</h2>

                    <article class="package-card">
                        <img src="https://www.balitripon.com/wp-content/uploads/2021/09/Paket-Mendaki-Gunung-Batur-dan-Ayung-Rafting-Ubud.jpg" alt="Paket Adventure Bali">
                        <div class="package-info">
                            <h3>Paket Mendaki Gunung Batur dan Ayung Rafting Ubud</h3>
                            <p class="date">📅 Tersedia Setiap Hari</p>
                            <p>Paket lengkap untuk pecinta adrenalin. Sunrise di Batur dilanjutkan dengan arung jeram.</p>
                            <p class="price">Harga Mulai <strong>Rp 650.000</strong></p>
                        </div>
                    </article>

                    <article class="package-card">
                        <img src="https://www.balitripon.com/wp-content/uploads/2024/05/Paket-Bali-ATV-Ride-Kintamani-Tour.jpg" alt="Paket Budaya & ATV">
                        <div class="package-info">
                            <h3>Paket ATV & Tur Budaya Ubud</h3>
                            <p class="date">🛵 Tersedia Setiap Hari</p>
                            <p>Eksplorasi sawah terasering dan desa tradisional dengan ATV.</p>
                            <p class="price">Harga <strong>Rp 850.000</strong> per orang</p>
                        </div>
                    </article>
                </div>

                <aside class="sidebar">
                    <div class="video-widget">
                        <h3>🔎 Cari Paket Wisata</h3>
                        <div class="search-bar" style="margin-bottom: 20px;">
                            <input type="text" placeholder="Masukkan kata kunci...">
                            <button>Cari</button>
                        </div>
                        <h3>📽️ Highlight Petualangan Bali</h3>
                        <iframe class="youtube-frame" src="https://www.youtube.com/embed/Kvp9sOnZZ7U" allowfullscreen></iframe>
                    </div>
                </aside>
            </div>
        </section>

        <section id="section-galeri" class="section">
            <div class="container">
                <h2>Galeri</h2>
                <div class="galeri-grid">
                    <div class="galeri-item"><img src="https://cdn.rri.co.id/berita/Pusat_Pemberitaan/o/1740844350548-1000080430/yb0cqm580byn8hm.jpeg" alt="Galeri 1"></div>
                    <div class="galeri-item"><img src="https://dewatiket.id/blog/wp-content/uploads/2023/11/Tempat-wisata-di-Bali.jpg" alt="Galeri 2"></div>
                    <div class="galeri-item"><img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e" alt="Galeri 3"></div>
                </div>
            </div>
        </section>

        <section id="section-pemesanan" class="section">
            <div class="container pemesanan-content">
                <h2>Pemesanan</h2>
                <div class="pemesanan-info">
                    <p>Siap memesan paket? Klik tombol di bawah untuk membuka form pemesanan.</p>
                    <p>Atau gunakan tombol "Booking Now" di kanan atas.</p>
                </div>
                <a class="btn-booking" href="booking.php">Buka Form Pemesanan</a>
            </div>
        </section>
    </main>

<script>
// Auto-scroll antar section dan tetap melanjutkan meskipun ada interaksi kecil
(function(){
    const sections = Array.from(document.querySelectorAll('.section'));
    const navLinks = Array.from(document.querySelectorAll('.nav-menu ul li a'));
    let current = 0;
    let autoScrollInterval = 7000; // 7 detik per section
    let userInteracted = false;
    let pauseTimeout = null;

    function setActive(index) {
        sections.forEach((s, i) => s.classList.toggle('active', i === index));
        navLinks.forEach((a,i)=> a.classList.toggle('active', i===index));
        current = index;
    }

    function scrollToIndex(index) {
        const el = sections[index];
        if (!el) return;
        el.scrollIntoView({behavior: 'smooth', block: 'start'});
        setActive(index);
    }

    // Auto-advance loop
    let loop = setInterval(() => {
        if (userInteracted) return; // jika ada interaksi singkat, jangan ganggu
        let next = (current + 1) % sections.length;
        scrollToIndex(next);
    }, autoScrollInterval);

    // Jika user berinteraksi, jeda sementara lalu resume
    function registerInteraction() {
        userInteracted = true;
        clearTimeout(pauseTimeout);
        pauseTimeout = setTimeout(()=>{ userInteracted = false; }, 3000); // resume setelah 3s tanpa interaksi
    }

    ['wheel', 'touchstart', 'keydown', 'mousedown'].forEach(ev => {
        window.addEventListener(ev, registerInteraction, {passive:true});
    });

    // Click nav
    navLinks.forEach((a,i)=>{
        a.addEventListener('click', (e)=>{
            e.preventDefault();
            scrollToIndex(i);
            registerInteraction();
        });
    });

    // make clicking booking/pesanan links stop auto-snap shortly
    document.querySelectorAll('.btn-booking-now, .btn-pesanan-saya, .btn-booking').forEach(b => {
        b && b.addEventListener('click', ()=> registerInteraction());
    });

    // Intersection observer to update current index when user scrolls manually
    const obs = new IntersectionObserver((entries)=>{
        entries.forEach(entry=>{
            if (entry.isIntersecting) {
                const idx = sections.indexOf(entry.target);
                if (idx >= 0) setActive(idx);
            }
        });
    }, {threshold: 0.6});

    sections.forEach(s => obs.observe(s));

})();
</script>

</body>
</html>