<?php
declare(strict_types=1);

namespace RentalFilm\Model;

class Pelanggan
{
    public function __construct(
        private string $idPelanggan,
        private string $nama,
        private string $nomorTelepon,
        private string $alamat
    ) {
    }

    public function getIdPelanggan(): string { return $this->idPelanggan; }
    public function getNama(): string { return $this->nama; }
    public function getNomorTelepon(): string { return $this->nomorTelepon; }
    public function getAlamat(): string { return $this->alamat; }

    public function ubahKontak(string $nomorTelepon, string $alamat): void
    {
        $this->nomorTelepon = $nomorTelepon;
        $this->alamat = $alamat;
    }
}