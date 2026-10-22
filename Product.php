<?php
require_once 'DatabaseConnection.php';

class Product {
    private $id;
    private $nama_produk;
    private $harga;
    private $stok;
    private $db;

    public function __construct() {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    public function setNamaProduk($nama_produk) {
        $this->nama_produk = $nama_produk;
        return $this;
    }

    public function setHarga($harga) {
        $this->harga = $harga;
        return $this;
    }

    public function setStok($stok) {
        $this->stok = $stok;
        return $this;
    }

    public function save() {
        $sql = "INSERT INTO produk (nama_produk, harga, stok) VALUES (:nama_produk, :harga, :stok)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nama_produk' => $this->nama_produk,
            'harga' => $this->harga,
            'stok' => $this->stok
        ]);
    }

    public static function findAll() {
        $db = DatabaseConnection::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM produk ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public static function findById($id) {
        $db = DatabaseConnection::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM produk WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function update($id) {
        $sql = "UPDATE produk SET nama_produk = :nama_produk, harga = :harga, stok = :stok WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nama_produk' => $this->nama_produk,
            'harga' => $this->harga,
            'stok' => $this->stok,
            'id' => $id
        ]);
    }

    public static function delete($id) {
        $db = DatabaseConnection::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM produk WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}