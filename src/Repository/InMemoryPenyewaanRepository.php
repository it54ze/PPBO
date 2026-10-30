<?php
declare(strict_types=1);

namespace RentalFilm\Repository;

use RentalFilm\Model\Penyewaan;

class InMemoryPenyewaanRepository implements PenyewaanRepository
{
    /** @var array<string, Penyewaan> */
    private array $data = [];

    public function simpan(Penyewaan $penyewaan): void
    {
        $this->data[$penyewaan->getIdPenyewaan()] = $penyewaan;
    }

    public function cariById(string $idPenyewaan): ?Penyewaan
    {
        return $this->data[$idPenyewaan] ?? null;
    }

    public function semua(): array
    {
        return array_values($this->data);
    }

    public function aktifMilikPelanggan(string $idPelanggan): array
    {
        return array_values(array_filter(
            $this->data,
            fn (Penyewaan $p): bool => $p->getPelanggan()->getIdPelanggan() === $idPelanggan && $p->masihAktif()
        ));
    }
}