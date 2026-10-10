<?php

namespace App\Services;

use App\Models\Product;

class ProductService {
    public function createProduct(string $name, float $price): Product {
        return new Product($name, $price);
    }

    public function displayProduct(Product $product): string {
        return "<br>Nama Produk: " . $product->getName() . "<br>" .
               "Harga Produk: " . $product->getFormattedPrice();
    }
}