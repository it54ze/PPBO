<?php

namespace App\Models;

class Product {
    private string $name;
    private float $price;

    public function __construct(string $name, float $price) {
        $this->name = $name;
        $this->price = $price;
    }
    public function getName(): string {
        return $this->name;
    }
    public function getPrice(): float {
        return $this->price;
    }
    public function getFormattedPrice(): string {
        return 'Rp ' . number_format($this->price, 2, ',', '.');
    }
}