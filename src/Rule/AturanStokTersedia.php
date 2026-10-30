<?php
declare(strict_types=1);

namespace RentalFilm\Rule;

use RentalFilm\Exception\RentalException;
use RentalFilm\Model\Film;
use RentalFilm\Model\Pelanggan;

class AturanStokTersedia implements AturanPenyewaan
{
    public function periksa(Film $film, Pelanggan $pelanggan): void
    {
        if (!$film->tersedia()) {
            throw RentalException::stokHabis($film->getJudul());
        }
    }
}