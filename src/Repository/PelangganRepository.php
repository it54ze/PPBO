<?php
declare(strict_types=1);

namespace RentalFilm\Repository;

use RentalFilm\Model\Pelanggan;

interface PelangganRepository
{
    public function simpan(Pelanggan $pelanggan): void;
    public function cariById(string $idPelanggan): ?Pelanggan;
    /** @return Pelanggan[] */
    public function semua(): array;
}