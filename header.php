<?php
// Define the base URL for navigation links (adjust as needed)
$base_url = "http://localhost/TI/";
?>

<header class="header">
    <div class="logo">
    <img src="gambar/um-tapsel.png" alt="Logo Universitas">
    </div>
    <nav class="nav-menu">
        <a href="<?php echo $base_url; ?>home.php">Home</a>
        <div class="dropdown">
            <a href="<?php echo $base_url; ?>profil.php">Profil</a>
            <div class="dropdown-content">
                <a href="<?php echo $base_url; ?>dosen.php">Dosen</a>
                <a href="<?php echo $base_url; ?>lulusan.php">Lulusan</a>
            </div>
        </div>
        <div class="dropdown">
            <a href="<?php echo $base_url; ?>layanan.php">Layanan</a>
            <div class="dropdown-content">
                <a href="<?php echo $base_url; ?>kerja.php">Kerja Praktek</a>
                <a href="<?php echo $base_url; ?>mbkm.php">MBKM</a>
                <a href="<?php echo $base_url; ?>tugas.php">Tugas Akhir</a>
            </div>
        </div>
        <a href="https://pmb.um-tapsel.ac.id/">PMB Online</a>
        <a href="<?php echo $base_url; ?>login.php">Login</a>
    </nav>
</header>