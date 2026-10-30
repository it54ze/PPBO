<?php
declare(strict_types=1);

namespace RentalFilm\Repository;

use RentalFilm\Model\Film;

interface FilmRepository
{
    public function simpan(Film $film): void;
    public function cariByKode(string $kodeFilm): ?Film;
    /** @return Film[] */
    public function semua(): array;
}