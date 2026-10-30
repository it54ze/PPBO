<?php
declare(strict_types=1);

namespace RentalFilm\Repository;

use RentalFilm\Model\Penyewaan;

interface PenyewaanRepository
{
    public function simpan(Penyewaan $penyewaan): void;
    public function cariById(string $idPenyewaan): ?Penyewaan;
    /** @return Penyewaan[] */
    public function semua(): array;
    /** @return Penyewaan[] penyewaan milik pelanggan yang belum dikembalikan */
    public function aktifMilikPelanggan(string $idPelanggan): array;
}