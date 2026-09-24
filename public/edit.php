<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { header("Location: index.php"); exit; }

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();
if (!$product) { header("Location: index.php"); exit; }

$errors = [];
$name = $product['name'];
$category = $product['category'];
$price = $product['price'];
$stock = $product['stock'];
$colors = $product['colors'];
$description = $product['description'];

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

    if (empty($errors) && $name !== $product['name']) {
        $stmtCek = $pdo->prepare("SELECT id FROM products WHERE name = :name AND id != :id");
        $stmtCek->execute(['name' => $name, 'id' => $id]);
        if ($stmtCek->fetch()) $errors[] = "Nama produk sudah digunakan.";
    }

    if (empty($errors)) {
        $stmtUpdate = $pdo->prepare("UPDATE products SET name = :name, category = :category, price = :price, stock = :stock, colors = :colors, description = :description WHERE id = :id");
        $stmtUpdate->execute([
            'name' => $name, 'category' => $category, 'price' => $price,
            'stock' => $stock, 'colors' => $colors, 'description' => $description, 'id' => $id
        ]);
        header("Location: index.php?status=updated");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="header"><h1>Edit Produk</h1></div>
    <div class="container">
        <div class="form-container">
            <?php if (!empty($errors)): ?>
                <div class="error-list"><ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li><?php endforeach; ?></ul></div>
            <?php endif; ?>
            <form action="edit.php?id=<?= $id ?>" method="POST">
                <div class="form-group"><label>Nama Produk*</label><input type="text" name="name" value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>" required minlength="3"></div>
                <div class="form-group">
                    <label>Kategori*</label>
                    <select name="category" required>
                        <option value="Hijab" <?= $category === 'Hijab' ? 'selected' : '' ?>>Hijab</option>
                        <option value="Outfit Atasan" <?= $category === 'Outfit Atasan' ? 'selected' : '' ?>>Outfit Atasan</option>
                        <option value="Outfit Bawahan" <?= $category === 'Outfit Bawahan' ? 'selected' : '' ?>>Outfit Bawahan</option>
                    </select>
                </div>
                <div class="form-group"><label>Harga (Rp)*</label><input type="number" name="price" value="<?= htmlspecialchars($price, ENT_QUOTES, 'UTF-8') ?>" min="1" required></div>
                <div class="form-group"><label>Stok*</label><input type="number" name="stock" value="<?= htmlspecialchars($stock, ENT_QUOTES, 'UTF-8') ?>" min="0" required></div>
                <div class="form-group"><label>Warna</label><input type="text" name="colors" value="<?= htmlspecialchars($colors, ENT_QUOTES, 'UTF-8') ?>"></div>
                <div class="form-group"><label>Deskripsi</label><textarea name="description" rows="3"><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></textarea></div>
                <button type="submit" class="btn">Update</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</body>
</html>