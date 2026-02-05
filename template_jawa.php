<?php 
// KOneksi & LOGIKA SIMPAN RSVP
include '../koneksi.php';

if (isset($_POST['kirim_rsvp'])) {
    $n_tamu = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $n_hadir = mysqli_real_escape_string($koneksi, $_POST['kehadiran']);
    $n_pesan = mysqli_real_escape_string($koneksi, $_POST['pesan']);

    $query = "INSERT INTO tb_rsvp (nama_tamu, kehadiran, pesan) VALUES ('$n_tamu','$n_hadir','$n_pesan')";
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('MaturNuwun sampun kersa paring doa restu'); window.location.href='template_jawa.php?to=$n_tamu#rsvp';<script>";
        exit;
    }
}
// --- DATA PENERIMA & PENGANTIN ---
$nama_pria = "Budi Santoso, S.Kom";
$nama_wanita = "Siti Aminah, S.Pd";
$ayah_pria = "Bapak Ahmad"; $ibu_pria = "Ibu Siti";
$ayah_wanita = "Bapak Yusuf"; $ibu_wanita = "Ibu Aminah";

// --- DETAIL ACARA ---
$tanggal_acara_php = "2026-08-25 08:00:00"; 
$tanggal_tampilan = "Selasa, 25 Agustus 2026";
$lokasi = "Kampung Tenjolaya RT 10/04 Desa sukamelang, Subang";
$maps = "https://goo.gl/maps/example"; 
$musik = "Tiara Andini Arsy Widianto - Lagu Pernikahan Kita.mp3";

$tamu = isset($_GET['to']) ? $_GET['to'] : "Tamu Undangan";

// --- PATH FOTO & GALERI ---
$foto_pria = "images/pria.jpeg";
$foto_wanita = "images/wanita.jpeg";
$bg_ornament = "images/cover1.jpg";
$galeri = ["images/prewed1.jpg", "images/prewed2.jpg", "images/prewed3.jpg", "images/prewed4.jpg"];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $nama_pria ?> & <?= $nama_wanita ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@300;400;600&family=Amiri&display=swap" rel="stylesheet">

    <style>
        html { scroll-behavior: smooth; }
        body { margin: 0; background: #fdf2f8; font-family: 'Poppins', sans-serif; overflow-x: hidden; }

        /* --- COVER (SISTEM PINTU) --- */
        .hero {
            height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center;
            color: white; text-align: center; position: fixed; width: 100%; top: 0; z-index: 9999;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('<?= $bg_ornament ?>');
            background-position: center; background-size: cover; transition: transform 1s cubic-bezier(0.77, 0, 0.175, 1);
        }
        .cover-hilang { transform: translateY(-100%); }

        .font-estetik { font-family: 'Great Vibes', cursive; color: #b8860b; }

        /* --- COUNTDOWN --- */
        .timer-box { background: #b8860b; color: white; border-radius: 10px; padding: 10px; min-width: 70px; margin: 0 5px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .timer-box span { display: block; font-size: 22px; font-weight: bold; }
        .timer-box small { font-size: 10px; text-transform: uppercase; }

        /* --- GALLERY --- */
        .gallery-img { width: 100%; height: 200px; object-fit: cover; border-radius: 10px; transition: 0.3s; }
        .gallery-img:hover { transform: scale(1.05); }

        /* --- NAVBAR --- */
        .bottom-nav {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            width: 90%; max-width: 450px; background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px); display: flex; justify-content: space-around;
            padding: 12px; border-radius: 50px; box-shadow: 0 8px 32px rgba(0,0,0,0.15); z-index: 1000;
        }
        .nav-item { color: #666; text-decoration: none; text-align: center; font-size: 10px; flex: 1; }
        .nav-item i { font-size: 20px; display: block; color: #b8860b; }
        
        .foto-mempelai { width: 150px; height: 210px; object-fit: cover; border: 5px solid #d4af37; border-radius: 100px 100px 10px 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .dalil-card { background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); outline: 1px solid #d4af37; outline-offset: -10px; }
        .arab-text { font-family: 'Amiri', serif; direction: rtl; line-height: 2.2; font-size: 26px; }

        /* MUSIK CONTROL */
        .music-control { position: fixed; bottom: 95px; right: 20px; z-index: 9999; display: none; }
        .btn-music { width: 45px; height: 45px; border-radius: 50%; background: #b8860b; color: white; border: 2px solid white; display: flex; align-items: center; justify-content: center; cursor: pointer; }
        .rotate { animation: rotation 3s infinite linear; }
        @keyframes rotation { from { transform: rotate(0deg); } to { transform: rotate(359deg); } }

        /* GUESTBOOK */
        .guestbook-box { max-height: 300px; overflow-y: auto; background: #fff5f7; border-radius: 15px; padding: 15px; border: 1px solid #eee; }
        .ucapan-item { background: white; padding: 12px; border-radius: 10px; margin-bottom: 10px; border-left: 4px solid #b8860b; text-align: left; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    </style>
</head>
<body style="overflow: hidden;">

<div class="music-control" id="musicControl">
    <button class="btn-music rotate" id="musicBtn" onclick="toggleMusic()">
        <i class="bi bi-disc-fill" id="musicIcon"></i>
    </button>
</div>
<audio id="weddingMusic" loop>
    <source src="<?= $musik ?>" type="audio/mpeg">
</audio>

<section id="cover" class="hero">
    <div class="hero-content">
        <h3 class="fw-light text-uppercase" style="letter-spacing: 3px;">The Wedding Of</h3>
        <h1 class="font-estetik my-3" style="font-size: 60px;"><?= $nama_pria ?> & <?= $nama_wanita ?></h1>
        <div class="mt-4">
            <p class="mb-1">Kepada Yth. Bapak/Ibu/Sdr/i:</p>
            <h4 class="fw-bold"><?= htmlspecialchars($tamu) ?></h4>
        </div>
        <button onclick="bukaUndangan()" class="btn btn-light rounded-pill px-4 mt-3 shadow fw-bold">
            <i class="bi bi-envelope-open-fill me-2"></i>BUKA UNDANGAN
        </button>
    </div>
</section>

<div class="bottom-nav">
    <a href="javascript:void(0)" onclick="window.scrollTo({top: 0, behavior: 'smooth'});" class="nav-item">
        <i class="bi bi-house-door"></i><span>Home</span>
    </a>
    <a href="#mempelai" class="nav-item">
        <i class="bi bi-people-fill"></i><span>Mempelai</span>
    </a>
    <a href="#acara" class="nav-item">
        <i class="bi bi-calendar-heart"></i><span>Acara</span>
    </a>
    <a href="#gallery" class="nav-item">
        <i class="bi bi-images"></i><span>Galeri</span>
    </a>
    <a href="#gift" class="nav-item">
        <i class="bi bi-gift"></i><span>Hadiah</span>
    </a>
</div>

<section id="mempelai" class="container text-center my-5 py-5">
    <h6 class="text-uppercase fw-light" style="letter-spacing: 5px; color: #b8860b;">Bride & Groom</h6>
    <h2 class="font-estetik" style="font-size: 45px;">Mempelai</h2>

    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <p class="mb-1"><i>Assalamu'alaikum Warahmatullahi Wabarakatuh</i></p>
            <p class="text-muted small px-3">
                Maha Suci Allah yang telah menciptakan makhluk-Nya berpasang-pasangan. 
                Ya Allah semoga ridho-Mu tercurah mengiringi pernikahan kami:
            </p>
        </div>
    </div>

    <div class="row justify-content-center mt-5">
        <div class="col-6 col-md-4 mb-5">
            <img src="<?= $foto_pria ?>" class="foto-mempelai" alt="pria">
            <h3 class="mt-3 fw-bold font-estetik" style="font-size: 30px;"><?= $nama_pria ?></h3>
            <p class="small mb-0 text-muted">Putra dari</p>
            <p class="fw-bold small"><?= $ayah_pria ?> & <?= $ibu_pria ?></p>
        </div>
        <div class="col-6 col-md-4">
            <img src="<?= $foto_wanita ?>" class="foto-mempelai" alt="wanita">
            <h3 class="mt-3 fw-bold font-estetik" style="font-size: 30px;"><?= $nama_wanita ?></h3>
            <p class="small mb-0 text-muted">Putri dari</p>
            <p class="fw-bold small"><?= $ayah_wanita ?> & <?= $ibu_wanita ?></p>
        </div>
    </div>
</section>

<section class="container my-5 px-3 text-center">
    <div class="dalil-card p-4">
        <h3 class="arab-text mb-3">وَمِنْ آيَاتِهِ أَنْ خَلَقَ لَكُمْ مِنْ أَنْفُسِكُمْ أَزْوَاجًا لِتَسْكُنُوا إِلَيْهَا</h3>
        <p class="small italic">"Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, supaya kamu cenderung dan merasa tenteram kepadanya..."</p>
        <p class="fw-bold" style="color: #b8860b;">(QS. AR-RUM: 21)</p>
    </div>
</section>

<section id="acara" class="container text-center my-5 py-5 bg-white rounded-4 shadow-sm">
    <h2 class="font-estetik mb-4">Hitung Mundur Acara</h2>
    <div class="d-flex justify-content-center mb-5" id="countdown">
        <div class="timer-box"><span id="days">00</span><small>Hari</small></div>
        <div class="timer-box"><span id="hours">00</span><small>Jam</small></div>
        <div class="timer-box"><span id="minutes">00</span><small>Menit</small></div>
        <div class="timer-box"><span id="seconds">00</span><small>Detik</small></div>
    </div>

    <h2 class="font-estetik" style="font-size: 40px;">Detail Acara</h2>
    <p class="fw-bold fs-5 mt-3"><?= $tanggal_tampilan ?></p>
    <p class="px-3"><?= $lokasi ?></p>
    <div class="px-2 mb-4">
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15857.3820239632!2d107.7565!3d-6.5701!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMzQnMTIuNCJTIDEwN8KwNDUnMjMuNCJF!5e0!3m2!1sid!2sid!4v1700000000000" width="100%" height="250" style="border:0; border-radius:15px;" allowfullscreen="" loading="lazy"></iframe>
    </div>
    <a href="<?= $maps ?>" target="_blank" class="btn btn-outline-dark rounded-pill px-4">Buka Google Maps</a>
</section>

<section id="gallery" class="container my-5 text-center">
    <h2 class="font-estetik" style="font-size: 40px;">Galeri Bahagia</h2>
    <div class="row g-2 mt-4">
        <?php foreach($galeri as $f) : ?>
        <div class="col-6 col-md-3">
            <img src="<?= $f ?>" class="gallery-img shadow-sm">
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section id="gift" class="container my-5 py-5 text-center bg-white rounded-4 shadow-sm px-3">
    <h2 class="font-estetik" style="font-size: 40px;">Wedding Gift</h2>
    <p class="small text-muted">Doa restu Anda merupakan hadiah terindah bagi kami. Namun jika ingin memberi lebih, Anda dapat melalui:</p>
    <div class="card p-4 border-0 mx-auto mt-4" style="max-width: 350px; background: #fff9fb;">
        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" height="30" class="mb-3 mx-auto">
        <h5 id="rekBCA" class="fw-bold">1234567890</h5>
        <p class="text-muted small">A/N <?= $nama_pria ?></p>
        <button onclick="copyToClipboard('rekBCA')" class="btn btn-dark btn-sm rounded-pill">Salin Rekening</button>
    </div>
</section>

<section id="rsvp" class="container my-5 py-5 bg-white shadow-sm rounded-4 px-4 text-center">
    <h2 class="font-estetik" style="font-size: 40px;">Ucapan & Doa</h2>
    <div class="row justify-content-center">
        <div class="col-md-8 mt-4 text-start">
            <form action="#rsvp" method="POST" class="mb-5">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nama Tamu</label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($tamu) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Konfirmasi</label>
                    <select name="kehadiran" class="form-select">
                        <option value="Hadir">Hadir</option>
                        <option value="Tidak Hadir">Tidak Hadir</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Pesan / Doa</label>
                    <textarea name="pesan" class="form-control" rows="3" placeholder="Tulis ucapan doa Anda..." required></textarea>
                </div>
                <button type="submit" class="btn btn-dark w-100 py-2 rounded-pill shadow">Kirim Ucapan</button>
            </form>

            <h5 class="fw-bold mb-3">Doa Restu Tamu:</h5>
            <div class="guestbook-box">
                <div class="ucapan-item">
                    <div class="d-flex justify-content-between">
                        <strong style="color:#b8860b;">Siti Aminah</strong>
                        <span class="badge bg-success" style="font-size: 9px;">Hadir</span>
                    </div>
                    <p class="small mb-0 mt-1">Selamat menempuh hidup baru ya budi & siti! Semoga samawa.</p>
                </div>
                </div>
        </div>
    </div>
</section>

<footer class="text-center py-5 text-muted small">
    <p>© 2026 <?= $nama_pria ?> & <?= $nama_wanita ?></p>
</footer>

<script>
    const audio = document.getElementById('weddingMusic');
    const musicControl = document.getElementById('musicControl');
    const musicBtn = document.getElementById('musicBtn');
    const musicIcon = document.getElementById('musicIcon');

    function bukaUndangan() {
        // Play music
        audio.play().catch(e => console.log("Play blocked"));
        
        // Show control
        musicControl.style.display = 'block';
        
        // Hide cover with animation
        document.getElementById('cover').classList.add('cover-hilang');
        
        // Enable scroll
        document.body.style.overflow = 'auto';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function toggleMusic() {
        if (audio.paused) {
            audio.play();
            musicBtn.classList.add('rotate');
            musicIcon.classList.replace('bi-pause-circle-fill', 'bi-disc-fill');
        } else {
            audio.pause();
            musicBtn.classList.remove('rotate');
            musicIcon.classList.replace('bi-disc-fill', 'bi-pause-circle-fill');
        }
    }

    function copyToClipboard(id) {
        const text = document.getElementById(id).innerText;
        navigator.clipboard.writeText(text).then(() => alert("Berhasil disalin!"));
    }

    // Countdown Logic
    const countDate = new Date("<?= $tanggal_acara_php ?>").getTime();
    setInterval(() => {
        const now = new Date().getTime();
        const gap = countDate - now;
        const d = Math.floor(gap / (1000 * 60 * 60 * 24));
        const h = Math.floor((gap % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const m = Math.floor((gap % (1000 * 60 * 60)) / (1000 * 60));
        const s = Math.floor((gap % (1000 * 60)) / 1000);
        
        document.getElementById('days').innerText = d;
        document.getElementById('hours').innerText = h;
        document.getElementById('minutes').innerText = m;
        document.getElementById('seconds').innerText = s;
    }, 1000);
</script>

</body>
</html>