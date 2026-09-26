<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    // Menggunakan tanda tanya (?) untuk setiap bidang agar kompatibel di semua driver PDO
    $sql = "SELECT * FROM products WHERE name LIKE ? OR category LIKE ? OR colors LIKE ? OR description LIKE ? ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $searchTerm = "%$q%";
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
} else {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
}
$products = $stmt->fetchAll();
$_SESSION['csrf'] ??= bin2hex(random_bytes(32));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Butik Syfaaa - Product Manager</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="header">
        <h1>Butik Syfaaa</h1>
        <p>Aplikasi Sistem Manajemen Katalog & Stok Produk</p>
    </div>
    <div class="container">
        <?php if (isset($_GET['status'])): ?>
            <div class="alert">
                <?php
                    if ($_GET['status'] === 'created') echo "Produk berhasil ditambahkan!";
                    if ($_GET['status'] === 'updated') echo "Data produk berhasil diperbarui!";
                    if ($_GET['status'] === 'deleted') echo "Produk berhasil dihapus!";
                ?>
            </div>
        <?php endif; ?>
        <div class="top-bar">
            <a href="create.php" class="btn">+ Tambah Produk</a>
            <form method="GET" class="search-form">
                <input type="text" name="q" placeholder="Cari produk..." value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>">
                <button type="submit" class="btn">Cari</button>
            </form>
        </div>
        <div class="products-grid">
            <?php foreach ($products as $p): ?>
                <div class="card">
                    <div>
                        <span class="badge"><?= htmlspecialchars($p['category'], ENT_QUOTES, 'UTF-8') ?></span>
                        <h3><?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <div class="price">Rp <?= number_format($p['price'], 0, ',', '.') ?></div>
                        <div class="info"><strong>Stok:</strong> <?= (int)$p['stock'] ?> pcs</div>
                        <div class="info"><strong>Warna:</strong> <?= htmlspecialchars($p['colors'] ?: '-', ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="info"><em><?= htmlspecialchars($p['description'] ?: '-', ENT_QUOTES, 'UTF-8') ?></em></div>
                    </div>
                    <div class="card-actions">
                        <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-secondary" style="flex:1; text-align:center;">Edit</a>
                        <form action="delete.php" method="POST" style="flex:1;" onsubmit="return confirm('Yakin hapus?');">
                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                            <button type="submit" class="btn btn-danger" style="width:100%;">Hapus</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>