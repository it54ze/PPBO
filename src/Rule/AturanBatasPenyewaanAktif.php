<?php
declare(strict_types=1);

namespace RentalFilm\Rule;

use RentalFilm\Exception\RentalException;
use RentalFilm\Model\Film;
use RentalFilm\Model\Pelanggan;
use RentalFilm\Repository\PenyewaanRepository;

class AturanBatasPenyewaanAktif implements AturanPenyewaan
{
    public function __construct(
        private PenyewaanRepository $repo,
        private int $batas = 3
    ) {
    }

    public function periksa(Film $film, Pelanggan $pelanggan): void
    {
        $aktif = $this->repo->aktifMilikPelanggan($pelanggan->getIdPelanggan());
        if (count($aktif) >= $this->batas) {
            throw RentalException::batasTerlampaui($pelanggan->getNama(), $this->batas);
        }
    }
}