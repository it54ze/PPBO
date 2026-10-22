<?php
require_once 'Product.php';

if (isset($_POST['save'])) {
    $product = new Product();
    $product->setNamaProduk($_POST['nama_produk'])
            ->setHarga($_POST['harga'])
            ->setStok($_POST['stok'])
            ->save();
    header("Location: index.php");
    exit;
}

if (isset($_GET['delete'])) {
    Product::delete($_GET['delete']);
    header("Location: index.php");
    exit;
}

$editData = null;
if (isset($_GET['edit'])) {
    $editData = Product::findById($_GET['edit']);
}

if (isset($_POST['update'])) {
    $product = new Product();
    $product->setNamaProduk($_POST['nama_produk'])
            ->setHarga($_POST['harga'])
            ->setStok($_POST['stok'])
            ->update($_POST['id']);
    header("Location: index.php");
    exit;
}

$products = Product::findAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Manajemen Produk</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f4f4f9; }
        h2 { color: #333; }
        form { background: #fff; padding: 20px; margin-bottom: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); width: 300px; }
        input { width: 100%; padding: 8px; margin: 8px 0; box-sizing: border-box; }
        button { background: #28a745; color: white; border: none; padding: 10px; width: 100%; cursor: pointer; border-radius: 3px; }
        button:hover { background: #218838; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 5px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #007bff; color: white; }
        a { color: #dc3545; text-decoration: none; margin-right: 10px; }
        a.edit { color: #ffc107; }
    </style>
</head>
<body>
    <h2>Sistem Manajemen Produk</h2>
    <form method="POST" action="">
        <h3><?= $editData ? 'Edit Produk' : 'Tambah Produk' ?></h3>
        <?php if ($editData): ?>
            <input type="hidden" name="id" value="<?= $editData['id'] ?>">
        <?php endif; ?>
        <label>Nama Produk:</label>
        <input type="text" name="nama_produk" value="<?= $editData['nama_produk'] ?? '' ?>" required>
        
        <label>Harga:</label>
        <input type="number" name="harga" value="<?= $editData['harga'] ?? '' ?>" required>
        
        <label>Stok:</label>
        <input type="number" name="stok" value="<?= $editData['stok'] ?? '' ?>" required>
        <?php if ($editData): ?>
            <button type="submit" name="update" style="background: #ffc107; color: black;">Perbarui Produk</button>
            <a href="index.php" style="display:block; text-align:center; margin-top:10px; color:#6c757d;">Batal</a>
        <?php else: ?>
            <button type="submit" name="save">Simpan Produk</button>
        <?php endif; ?>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($products)): ?>
                <tr><td colspan="5" style="text-align: center;">Belum ada data produk.</td></tr>
            <?php else: ?>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['nama_produk']) ?></td>
                    <td>Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                    <td><?= $p['stok'] ?></td>
                    <td>
                        <a href="index.php?edit=<?= $p['id'] ?>" class="edit">Edit</a>
                        <a href="index.php?delete=<?= $p['id'] ?>" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>