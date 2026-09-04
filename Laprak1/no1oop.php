<?php
class luaspersegipanjang {
    public $panjang;
    public $lebar;
    public $luas;

    public function hitungluas(){
        $this->luas = $this->panjang * $this->lebar;
    }
}

$newPersegiPanjang = new luaspersegipanjang();
$newPersegiPanjang->panjang = 10;
$newPersegiPanjang->lebar = 5;
$newPersegiPanjang->hitungluas();
echo "Luas persegi panjang: " . $newPersegiPanjang->luas;
?>