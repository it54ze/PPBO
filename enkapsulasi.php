<?php
class Mahasiswa{
    protected $nama;
}

class MahasiswaAktif extends Mahasiswa{
    public function setNama($nama){
        $this->nama = $nama;
    }
    public function getNama(){
        return $this->nama;
    }
}

$mhs = new MahasiswaAktif();
$mhs->setNama("Budi");
echo $mhs->getNama();