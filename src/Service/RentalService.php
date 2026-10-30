<?php
declare(strict_types=1);

namespace RentalFilm\Service;

use DateTimeImmutable;
use RentalFilm\Exception\RentalException;
use RentalFilm\Model\Penyewaan;
use RentalFilm\Repository\FilmRepository;
use RentalFilm\Repository\PelangganRepository;
use RentalFilm\Repository\PenyewaanRepository;
use RentalFilm\Rule\AturanPenyewaan;

class RentalService
{
    /**
     * @param AturanPenyewaan[] $aturan
     */
    public function __construct(
        private FilmRepository $filmRepo,
        private PelangganRepository $pelangganRepo,
        private PenyewaanRepository $penyewaanRepo,
        private array $aturan = []
    ) {
        foreach ($aturan as $a) {
            if (!$a instanceof AturanPenyewaan) {
                throw new \InvalidArgumentException('Setiap aturan harus mengimplementasikan AturanPenyewaan.');
            }
        }
    }

    public function sewaFilm(string $idPelanggan, string $kodeFilm, DateTimeImmutable $tanggalSewa): Penyewaan
    {
        $pelanggan = $this->pelangganRepo->cariById($idPelanggan)
            ?? throw RentalException::pelangganTidakDitemukan($idPelanggan);
        $film = $this->filmRepo->cariByKode($kodeFilm)
            ?? throw RentalException::filmTidakDitemukan($kodeFilm);

        foreach ($this->aturan as $a) {
            $a->periksa($film, $pelanggan);
        }

        $film->kurangiStok();
        $this->filmRepo->simpan($film);

        $penyewaan = new Penyewaan($this->buatId(), $film, $pelanggan, $tanggalSewa);
        $this->penyewaanRepo->simpan($penyewaan);

        return $penyewaan;
    }

    public function kembalikanFilm(string $idPenyewaan, DateTimeImmutable $tanggalKembali): Penyewaan
    {
        $penyewaan = $this->penyewaanRepo->cariById($idPenyewaan)
            ?? throw RentalException::penyewaanTidakDitemukan($idPenyewaan);

        $penyewaan->selesai($tanggalKembali);

        $film = $penyewaan->getFilm();
        $film->tambahStok();
        $this->filmRepo->simpan($film);
        $this->penyewaanRepo->simpan($penyewaan);

        return $penyewaan;
    }

    private function buatId(): string
    {
        return sprintf('RNT-%03d', count($this->penyewaanRepo->semua()) + 1);
    }
}