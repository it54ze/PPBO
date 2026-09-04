<?php
class Product {
    public $nama;
    public $harga;
    public $kategori;

    public function __construct($nama, $harga, $kategori) {
        $this->nama = $nama;
        $this->harga = $harga;
        $this->kategori = $kategori;
    }
    public function getInfo() {
        echo "Nama: " . $this->nama . "<br>";
        echo "Harga: " . $this->harga . "<br>";
        echo "Kategori: " . $this->kategori . "<br>";
    }
    public function applyDiskon($persentase) {
        $diskon = $this->harga * $persentase / 100;
        $this->harga -= $diskon;
        echo "Diskon sebesar " . $persentase . "% telah diterapkan pada produk " . $this->nama . ".<br>";
    }
}

$prd = new Product("Laptop", 15000000, "Elektronik");
$prd->getInfo();
$prd->applyDiskon(10);
$prd->getInfo();
$prd2 = new Product("Mouse", 500000, "Aksesoris");
$prd2->getInfo();
$prd2->applyDiskon(5);
$prd2->getInfo();
?>