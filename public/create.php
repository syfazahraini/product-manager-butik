<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$errors = [];
$name = $category = $colors = $description = '';
$price = $stock = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $colors = trim($_POST['colors'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
    $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);

    if (mb_strlen($name) < 3) $errors[] = "Nama produk minimal 3 karakter.";
    if (empty($category)) $errors[] = "Pilih kategori.";
    if ($price === false || $price <= 0) $errors[] = "Harga harus > 0.";
    if ($stock === false || $stock < 0) $errors[] = "Stok tidak boleh negatif.";

    if (empty($errors)) {
        $stmtCek = $pdo->prepare("SELECT id FROM products WHERE name = :name");
        $stmtCek->execute(['name' => $name]);
        if ($stmtCek->fetch()) $errors[] = "Nama produk sudah ada.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO products (name, category, price, stock, colors, description) VALUES (:name, :category, :price, :stock, :colors, :description)");
        $stmt->execute([
            'name' => $name, 'category' => $category, 'price' => $price,
            'stock' => $stock, 'colors' => $colors, 'description' => $description
        ]);
        header("Location: index.php?status=created");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="header"><h1>Tambah Produk</h1></div>
    <div class="container">
        <div class="form-container">
            <?php if (!empty($errors)): ?>
                <div class="error-list"><ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li><?php endforeach; ?></ul></div>
            <?php endif; ?>
            <form action="create.php" method="POST">
                <div class="form-group"><label>Nama Produk*</label><input type="text" name="name" value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>" required minlength="3"></div>
                <div class="form-group">
                    <label>Kategori*</label>
                    <select name="category" required>
                        <option value="">-- Pilih --</option>
                        <option value="Hijab">Hijab</option>
                        <option value="Outfit Atasan">Outfit Atasan</option>
                        <option value="Outfit Bawahan">Outfit Bawahan</option>
                    </select>
                </div>
                <div class="form-group"><label>Harga (Rp)*</label><input type="number" name="price" value="<?= htmlspecialchars($price, ENT_QUOTES, 'UTF-8') ?>" min="1" required></div>
                <div class="form-group"><label>Stok*</label><input type="number" name="stock" value="<?= htmlspecialchars($stock, ENT_QUOTES, 'UTF-8') ?>" min="0" required></div>
                <div class="form-group"><label>Warna</label><input type="text" name="colors" value="<?= htmlspecialchars($colors, ENT_QUOTES, 'UTF-8') ?>"></div>
                <div class="form-group"><label>Deskripsi</label><textarea name="description" rows="3"><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></textarea></div>
                <button type="submit" class="btn">Simpan</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</body>
</html>