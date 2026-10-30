<?php
declare(strict_types=1);

namespace RentalFilm\Model;

use RentalFilm\Exception\RentalException;

class Film
{
    public function __construct(
        private string $kodeFilm,
        private string $judul,
        private string $genre,
        private int $tahunRilis,
        private int $jumlahStok
    ) {
        if ($jumlahStok < 0) {
            throw new \InvalidArgumentException('Jumlah stok tidak boleh negatif.');
        }
    }

    public function getKodeFilm(): string { return $this->kodeFilm; }
    public function getJudul(): string { return $this->judul; }
    public function getGenre(): string { return $this->genre; }
    public function getTahunRilis(): int { return $this->tahunRilis; }
    public function getJumlahStok(): int { return $this->jumlahStok; }

    public function tersedia(): bool
    {
        return $this->jumlahStok > 0;
    }

    public function kurangiStok(): void
    {
        if (!$this->tersedia()) {
            throw RentalException::stokHabis($this->judul);
        }
        $this->jumlahStok--;
    }

    public function tambahStok(): void
    {
        $this->jumlahStok++;
    }
}