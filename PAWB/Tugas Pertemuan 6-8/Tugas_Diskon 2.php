<?php
// 1. Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_penjualan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// 2. Fungsi/Prosedur hitungDiskon() sesuai instruksi dosen
function hitungDiskon($totalBelanja) {
    $diskon = 0;
    
    if ($totalBelanja >= 100000) {
        $diskon = 0.1 * $totalBelanja; // Diskon 10%
    } elseif ($totalBelanja >= 50000) {
        $diskon = 0.05 * $totalBelanja; // Diskon 5%
    }

    return $diskon;
}

$adaHasil = false;
$totalBelanja = 0;
$diskon = 0;
$totalBayar = 0;
$persentase = "0%";

// 3. Proses ketika tombol 'Hitung' diklik
if (isset($_POST['hitung'])) {
    $totalBelanja = floatval($_POST['total_belanja']);
    $diskon = hitungDiskon($totalBelanja);
    $totalBayar = $totalBelanja - $diskon;

    if ($totalBelanja >= 100000) {
        $persentase = "10%";
    } elseif ($totalBelanja >= 50000) {
        $persentase = "5%";
    } else {
        $persentase = "0%";
    }

    // Simpan ke database XAMPP
    $sqlInsert = "INSERT INTO transaksi (total_belanja, diskon, total_bayar) 
                  VALUES ('$totalBelanja', '$diskon', '$totalBayar')";
    $conn->query($sqlInsert);

    $adaHasil = true;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Commerce Discount Calculator</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #e0f7fa, #80deea, #4dd0e1); 
            background-attachment: fixed;
            margin: 0; 
            padding: 40px 20px; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 85vh; 
            flex-direction: column; 
        }
        
        .app-header { text-align: center; margin-bottom: 20px; width: 850px; max-width: 100%; }
        .app-header h1 { color: #006064; margin: 0 0 5px 0; font-size: 28px; text-shadow: 0 1px 2px rgba(255,255,255,0.6); }
        .app-header p { color: #00838f; margin: 0; font-size: 14px; font-weight: 600; }

        /* Kotak Info Ketentuan Diskon */
        .info-ketentuan { 
            background: rgba(255, 255, 255, 0.95); 
            padding: 15px 20px; 
            border-radius: 12px; 
            box-shadow: 0 5px 15px rgba(0, 131, 143, 0.15); 
            width: 850px; 
            max-width: 100%; 
            box-sizing: border-box; 
            margin-bottom: 20px; 
            border-left: 5px solid #00acc1;
            font-size: 13px;
            color: #004d40;
        }
        .info-ketentuan b { color: #006064; }
        .info-ketentuan ul { margin: 5px 0 0 20px; padding: 0; }

        .main-container { display: flex; gap: 25px; width: 850px; max-width: 100%; margin-bottom: 25px; flex-wrap: wrap; }
        
        .card-form, .card-result { 
            background: rgba(255, 255, 255, 0.95); 
            padding: 30px; 
            border-radius: 18px; 
            box-shadow: 0 10px 25px rgba(0, 131, 143, 0.2); 
            flex: 1; 
            min-width: 340px; 
        }
        
        h2 { color: #006064; margin-top: 0; font-size: 20px; border-bottom: 2px solid #b2ebf2; padding-bottom: 12px; }
        p.subtitle { color: #555; font-size: 13px; margin-bottom: 20px; }
        
        label { font-weight: 600; color: #333; font-size: 14px; display: block; margin-bottom: 8px; }
        input[type="number"] { width: 100%; padding: 12px; margin-bottom: 20px; border: 2px solid #b2ebf2; border-radius: 10px; box-sizing: border-box; font-size: 15px; outline: none; background: #fff; transition: 0.3s; }
        input[type="number"]:focus { border-color: #00acc1; box-shadow: 0 0 8px rgba(0, 172, 193, 0.3); }
        
        button { 
            width: 100%; 
            background: linear-gradient(135deg, #00838f, #00acc1); 
            color: white; 
            border: none; 
            padding: 12px; 
            border-radius: 10px; 
            font-size: 15px; 
            font-weight: bold; 
            cursor: pointer; 
            transition: opacity 0.3s, transform 0.2s; 
            box-shadow: 0 4px 15px rgba(0, 131, 143, 0.3);
        }
        button:hover { opacity: 0.9; transform: translateY(-1px); }
        
        .rincian-box { background: linear-gradient(135deg, #e0f7fa, #b2ebf2); border-left: 5px solid #00838f; padding: 15px; border-radius: 10px; margin-top: 10px; }
        .row-rincian { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; color: #004d40; }
        .total-row { display: flex; justify-content: space-between; margin-top: 15px; padding-top: 12px; border-top: 1px dashed #80deea; font-weight: bold; font-size: 16px; color: #006064; }

        .riwayat-container { 
            background: rgba(255, 255, 255, 0.95); 
            padding: 25px; 
            border-radius: 18px; 
            box-shadow: 0 10px 25px rgba(0, 131, 143, 0.2); 
            width: 850px; 
            max-width: 100%; 
            box-sizing: border-box; 
            margin-bottom: 15px; 
        }
        .riwayat-container h3 { color: #006064; margin-top: 0; font-size: 18px; margin-bottom: 15px; }
        
        table { width: 100%; border-collapse: collapse; overflow: hidden; border-radius: 10px; }
        th, td { border: 1px solid #b2ebf2; padding: 12px; text-align: left; font-size: 14px; }
        th { background: linear-gradient(135deg, #00838f, #00acc1); color: white; }
        tr:nth-child(even) { background-color: #f0fdfd; }

        .footer-credit { text-align: center; color: #004d40; font-size: 13px; margin-top: 10px; width: 850px; max-width: 100%; font-weight: 500; }
    </style>
</head>
<body>

<div class="app-header">
    <h1>🛒 E-Commerce Discount Calculator</h1>
    <p>Sistem Pengelolaan & Perhitungan Transaksi Penjualan Berbasis Web</p>
</div>

<!-- Informasi Ketentuan Diskon (10%, 5%, 0%) -->
<div class="info-ketentuan">
    <b>ℹ️ Ketentuan Diskon Toko:</b>
    <ul>
        <li>Belanja <b>Rp. 100.000 atau lebih</b> mendapatkan diskon <b>10%</b></li>
        <li>Belanja <b>Rp. 50.000 s/d kurang dari Rp. 100.000</b> mendapatkan diskon <b>5%</b></li>
        <li>Belanja <b>kurang dari Rp. 50.000</b> tidak mendapatkan diskon (<b>0%</b>)</li>
    </ul>
</div>

<div class="main-container">
    <div class="card-form">
        <h2>💳 Input Transaksi</h2>
        <p class="subtitle">Masukkan total belanjaan untuk menghitung diskon otomatis.</p>

        <form method="POST" action="">
            <label>Total Belanja (Rp)</label>
            <input type="number" name="total_belanja" placeholder="Contoh: 120000" required>
            <button type="submit" name="hitung">Proses Perhitungan</button>
        </form>
    </div>

    <div class="card-result">
        <h2>📊 Hasil Output</h2>
        <?php if ($adaHasil): ?>
            <p class="subtitle">Berikut rincian diskon dari transaksi terakhir:</p>
            <div class="rincian-box">
                <div class="row-rincian">
                    <span>Total Belanja:</span>
                    <span><strong>Rp. <?= number_format($totalBelanja, 0, ',', '.') ?></strong></span>
                </div>
                <div class="row-rincian">
                    <span>Besar Diskon (<?= $persentase ?>):</span>
                    <span><strong>Rp. <?= number_format($diskon, 0, ',', '.') ?></strong></span>
                </div>
                <div class="total-row">
                    <span>Total Bayar:</span>
                    <span>Rp. <?= number_format($totalBayar, 0, ',', '.') ?></span>
                </div>
            </div>
        <?php else: ?>
            <p class="subtitle" style="margin-top: 40px; text-align: center; color: #888;">Belum ada data dihitung. Silakan masukkan nominal belanja di samping.</p>
        <?php endif; ?>
    </div>
</div>

<div class="riwayat-container">
    <h3>📋 Log Riwayat Transaksi (Database XAMPP)</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Total Belanja</th>
                <th>Diskon</th>
                <th>Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sqlSelect = "SELECT * FROM transaksi ORDER BY id_transaksi DESC";
            $result = $conn->query($sqlSelect);

            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id_transaksi'] . "</td>";
                    echo "<td>Rp. " . number_format($row['total_belanja'], 0, ',', '.') . "</td>";
                    echo "<td>Rp. " . number_format($row['diskon'], 0, ',', '.') . "</td>";
                    echo "<td>Rp. " . number_format($row['total_bayar'], 0, ',', '.') . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center; color:#777;'>Belum ada data tersimpan.</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<div class="footer-credit">
    <p>Tugas Pemrograman Web &bull; Semester 3</p>
</div>

</body>
</html>