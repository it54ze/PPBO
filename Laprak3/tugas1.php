<?php
class Mobil{
    private $merek;
    private $warna;
    private $kecepatan;
    
    public function __construct($merek, $warna, $kecepatan) {
        $this->merek = $merek;
        $this->warna = $warna;
        $this->kecepatan = $kecepatan;
    }
    public function setMerek($merek) {
        $this->merek = $merek;
    }

    public function setWarna($warna) {
        if (!empty($warna) && strlen($warna) >= 3) {
            $this->warna = $warna;
        } else {
            echo "Warna tidak valid. Warna tidak boleh kosong dan minimal 3 karakter.<br>";
        }
    }

    public function setKecepatan($kecepatan) {
        if ($kecepatan >= 0 && $kecepatan <= 200) {
            $this->kecepatan = $kecepatan;
        } else {
            echo "Kecepatan tidak valid. Kecepatan harus antara 0 dan 200 km/jam.<br>";
        }
    }

    public function getMerek() {
        return $this->merek;
    }

    public function getWarna() {
        return $this->warna;
    }

    public function getKecepatan() {
        return $this->kecepatan;
    }

    public function getInfo() {
        echo "Merek: " . $this->getMerek() . "<br>";
        echo "Warna: " . $this->getWarna() . "<br>";
        echo "Kecepatan: " . $this->getKecepatan() . " km/jam<br>";
    }
    public function berjalan(){
        echo "Mobil " . $this->getMerek() . " sedang berjalan dengan kecepatan " . $this->getKecepatan() . " km/jam.<br>";
    }
    public function berhenti(){
        echo "Mobil " . $this->getMerek() . " telah berhenti.<br>";
    }
}

$mbl = new Mobil("Honda", "Merah", 100);
$mbl->getInfo();
$mbl->berjalan();
$mbl->berhenti();
$mbl2 = new Mobil("Mercedes", "Hitam", 350);
$mbl2->getInfo();
$mbl2->berjalan();
$mbl2->berhenti();
$mbl3 = new Mobil("Ferrari", "Merah", 75);
$mbl3->getInfo();
$mbl3->berjalan();
$mbl3->berhenti();
?>