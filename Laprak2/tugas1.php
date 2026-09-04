<?php
class Mobil{
    public $merek;
    public $warna;
    public $kecepatan;

    public function __construct($merek, $warna, $kecepatan) {
        $this->merek = $merek;
        $this->warna = $warna;
        $this->kecepatan = $kecepatan;
    }
    public function getInfo() {
        echo "Merek: " . $this->merek . "<br>";
        echo "Warna: " . $this->warna . "<br>";
        echo "Kecepatan: " . $this->kecepatan . " km/jam<br>";
    }
    public function berjalan(){
        echo "Mobil " . $this->merek . " sedang berjalan dengan kecepatan " . $this->kecepatan . " km/jam.<br>";
    }
    public function berhenti(){
        echo "Mobil " . $this->merek . " telah berhenti.<br>";
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