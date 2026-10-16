<?php
require_once '../Catatan.php';

$id = 3;
$catatanData = Catatan::getById($id);

if ($catatanData) {
    $catatan = new Catatan();
    $catatan->setJudul($catatanData['judul'] . ' (Diperbarui)')
            ->setIsi($catatanData['isi'] . ' (Diperbarui)');

    if ($catatan->update($id)) {
        echo "Catatan ($id) berhasil diperbarui";
    } else {
        echo "Gagal memperbarui catatan";
    }
} else {
    echo "Catatan tidak ditemukan";
}