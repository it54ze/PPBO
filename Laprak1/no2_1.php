<?php
class NilaiAkhir {
    public $uts;
    public $uas;
    public $tugas;

    public function hitungNilaiAkhir() {
        return (float)($this->uts * 0.3) + ($this->uas * 0.4) + ($this->tugas * 0.3);
    }
}

$newNilaiAkhir = new NilaiAkhir();
$newNilaiAkhir->uts = 80;
$newNilaiAkhir->uas = 90;
$newNilaiAkhir->tugas = 85;
echo "Nilai Akhir: " . $newNilaiAkhir->hitungNilaiAkhir();
?>