<?php
class Produk {
    protected $merek;
    protected $harga;

    public function __construct($merek, $harga) {
        if ($harga < 0) {
            throw new Exception("Harga tidak boleh negatif!");
        }
        $this->merek = $merek;
        $this->harga = $harga;
    }

    public function getInfo() {
        return "Merek: " . $this->merek . "<br>" .
               "Harga: Rp " . number_format($this->harga, 0, ',', '.');
    }
}

class Makanan extends Produk {
    private $namaProduk;
    private $tanggalKadaluarsa;

    public function __construct($namaProduk, $merek, $harga, $tanggalKadaluarsa) {
        parent::__construct($merek, $harga);
        $this->namaProduk = $namaProduk;
        $this->tanggalKadaluarsa = $tanggalKadaluarsa;
    }

    public function getStatus() {
        $today = date('Y-m-d');
        return ($this->tanggalKadaluarsa >= $today) ? "Segar" : "Kadaluarsa";
    }

    public function getInfo() {
        return "Produk: Makanan - " . $this->namaProduk . "<br>" .
               "Merek: " . $this->merek . "<br>" .
               "Harga: Rp " . number_format($this->harga, 0, ',', '.') . "<br>" .
               "Tanggal Kadaluarsa: " . $this->tanggalKadaluarsa . "<br>" .
               "Status: " . $this->getStatus();
    }
}

class Elektronik extends Produk {
    private $namaProduk;
    private $garansi;

    public function __construct($namaProduk, $merek, $harga, $garansi) {
        parent::__construct($merek, $harga);
        $this->namaProduk = $namaProduk;
        $this->garansi = $garansi;
    }

    public function getInfo() {
        return "Produk: Elektronik - " . $this->namaProduk . "<br>" .
               "Merek: " . $this->merek . "<br>" .
               "Harga: Rp " . number_format($this->harga, 0, ',', '.') . "<br>" .
               "Garansi: " . $this->garansi . " bulan";
    }
}

$makanan = new Makanan("Mie Instan", "Indomie", 3500, "2025-06-30");
echo $makanan->getInfo() . "<br><br>";

$elektronik = new Elektronik("Smart TV", "Samsung", 5000000, 12);
echo $elektronik->getInfo() . "<br>";

?>