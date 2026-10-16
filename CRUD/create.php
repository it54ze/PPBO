<?php
require_once '../Catatan.php';

$c = new Catatan();
$hasil = $c->setJudul("Atongs")
           ->setIsi("Atongs menjual kopi di Lim Kok Tong")
           ->save();

echo $hasil ? "Berhasil disimpan" : "Gagal";