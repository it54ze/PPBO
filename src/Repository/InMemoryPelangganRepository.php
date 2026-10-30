<?php
declare(strict_types=1);

namespace RentalFilm\Repository;

use RentalFilm\Model\Pelanggan;

class InMemoryPelangganRepository implements PelangganRepository
{
    /** @var array<string, Pelanggan> */
    private array $data = [];

    public function simpan(Pelanggan $pelanggan): void
    {
        $this->data[$pelanggan->getIdPelanggan()] = $pelanggan;
    }

    public function cariById(string $idPelanggan): ?Pelanggan
    {
        return $this->data[$idPelanggan] ?? null;
    }

    public function semua(): array
    {
        return array_values($this->data);
    }
}