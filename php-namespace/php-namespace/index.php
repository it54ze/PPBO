<?php 

require_once __DIR__ . '/vendor/autoload.php';

use App\Services\UserService;
use App\Services\ProductService;

try {
$pservice = new ProductService();
$uservice = new UserService();

$user = $uservice->createUser(
    'Atong',
    'atong@example.com'
);

$product = $pservice->createProduct(
    'Laptop',
    10000000
);
echo $uservice->displayUser($user);
echo $pservice->displayProduct($product);

} catch (Throwable $e) {
    echo "<h3>Terjadi Error</h3>";
    echo "<p>Pesan: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . "</p>";
    echo "<p>Line: " . $e->getLine() . "</p>";
}