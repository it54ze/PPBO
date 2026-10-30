<?php
declare(strict_types=1);

namespace RentalFilm\Repository;

use RentalFilm\Model\Film;

class InMemoryFilmRepository implements FilmRepository
{
    /** @var array<string, Film> */
    private array $data = [];

    public function simpan(Film $film): void
    {
        $this->data[$film->getKodeFilm()] = $film;
    }

    public function cariByKode(string $kodeFilm): ?Film
    {
        return $this->data[$kodeFilm] ?? null;
    }

    public function semua(): array
    {
        return array_values($this->data);
    }
}