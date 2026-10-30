<?php
declare(strict_types=1);

namespace RentalFilm\Rule;

use RentalFilm\Model\Film;
use RentalFilm\Model\Pelanggan;

interface AturanPenyewaan
{
    public function periksa(Film $film, Pelanggan $pelanggan): void;
}