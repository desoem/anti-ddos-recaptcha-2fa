<h1>Anti-DDOS Protection with reCAPTCHA and 2FA</h1>
<strong>Versi Plugin:</strong> 1.2

<strong>Penulis:</strong> Ridwan Sumantri

<strong>Lisensi:</strong> GPLv2 atau yang lebih baru

<strong>URL Plugin:</strong> <a href="https://github.com/deseom">GitHub Repository</a>
<h2>Deskripsi</h2>
<strong>Anti-DDOS Protection with reCAPTCHA and 2FA</strong> adalah plugin WordPress yang dirancang untuk meningkatkan keamanan situs Anda. Plugin ini menawarkan perlindungan terhadap serangan DDOS, integrasi dengan Google reCAPTCHA v3, serta autentikasi dua faktor (2FA) berbasis Google Authenticator.
<h3>Fitur Utama</h3>
<ul>
 	<li><strong>Perlindungan Anti-DDOS:</strong> Membantu mencegah serangan DDOS sederhana dengan mekanisme validasi pengguna.</li>
 	<li><strong>Google reCAPTCHA v3:</strong> Validasi pengguna untuk mengurangi spam dan bot di halaman login.</li>
 	<li><strong>Autentikasi Dua Faktor (2FA):</strong> Menambahkan lapisan keamanan tambahan dengan Google Authenticator.</li>
 	<li><strong>QR Code untuk 2FA:</strong> QR Code yang dibuat dengan library Endroid QR Code untuk kemudahan penggunaan.</li>
</ul>
<h2>Instalasi</h2>
<h3>Prasyarat</h3>
<ul>
 	<li>PHP versi 7.4 atau lebih baru.</li>
 	<li>WordPress versi 5.8 atau lebih baru.</li>
</ul>
<h3>Langkah Instalasi</h3>
<ol>
 	<li><strong>Clone repositori ini:</strong>
<pre><code>git clone https://github.com/deseom/anti-ddos-recaptcha-2fa.git</code></pre>
</li>
 	<li><strong>Unggah plugin ke server:</strong> Tempatkan folder plugin di dalam direktori <code>wp-content/plugins</code>.</li>
 	<li><strong>Aktifkan plugin:</strong> Masuk ke dashboard WordPress Anda, buka menu <strong>Plugins</strong>, lalu aktifkan plugin <strong>Anti-DDOS Protection with reCAPTCHA and 2FA</strong>.</li>
</ol>
<h2>Pengaturan</h2>
<h3>1. Google reCAPTCHA</h3>
<ul>
 	<li>Masukkan <strong>Site Key</strong> dan <strong>Secret Key</strong> Anda dari Google reCAPTCHA di halaman pengaturan plugin.</li>
</ul>
<h3>2. Autentikasi Dua Faktor (2FA)</h3>
<ul>
 	<li>QR Code untuk 2FA akan otomatis dibuat di halaman pengaturan plugin.</li>
 	<li>Scan QR Code menggunakan aplikasi seperti Google Authenticator untuk mulai menggunakan 2FA.</li>
</ul>
<h2>Penggunaan</h2>
<ul>
 	<li><strong>Login dengan reCAPTCHA:</strong> reCAPTCHA akan aktif di halaman login WordPress untuk semua pengguna.</li>
 	<li><strong>2FA saat login:</strong> Jika pengguna memiliki 2FA aktif, kode autentikasi dari aplikasi Google Authenticator harus dimasukkan saat login.</li>
</ul>
<h2>Lisensi</h2>
Plugin ini dirilis di bawah lisensi GPLv2 atau yang lebih baru. Anda bebas untuk menggunakannya, mengubahnya, dan mendistribusikannya.
<h2>Kontribusi</h2>
Kami menerima kontribusi untuk meningkatkan plugin ini. Jika Anda memiliki ide atau perbaikan, silakan kirim <strong>Pull Request</strong> atau laporkan masalah di halaman <strong>Issues</strong> repositori ini.
<h2>Kredit</h2>
Plugin ini menggunakan library berikut:
<ul>
 	<li><a href="https://github.com/endroid/qr-code">Endroid QR Code</a></li>
 	<li><a href="https://github.com/sonata-project/GoogleAuthenticator">Sonata Google Authenticator</a></li>
</ul>
