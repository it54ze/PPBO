<?php
declare(strict_types=1);

namespace RentalFilm\Model;

use DateTimeImmutable;
use RentalFilm\Exception\RentalException;

class Penyewaan
{
    private ?DateTimeImmutable $tanggalKembali = null;
    private StatusPenyewaan $statusPenyewaan = StatusPenyewaan::Disewa;

    public function __construct(
        private string $idPenyewaan,
        private Film $film,
        private Pelanggan $pelanggan,
        private DateTimeImmutable $tanggalSewa
    ) {
    }

    public function getIdPenyewaan(): string { return $this->idPenyewaan; }
    public function getFilm(): Film { return $this->film; }
    public function getPelanggan(): Pelanggan { return $this->pelanggan; }
    public function getTanggalSewa(): DateTimeImmutable { return $this->tanggalSewa; }
    public function getTanggalKembali(): ?DateTimeImmutable { return $this->tanggalKembali; }
    public function getStatus(): StatusPenyewaan { return $this->statusPenyewaan; }

    public function masihAktif(): bool
    {
        return $this->statusPenyewaan === StatusPenyewaan::Disewa;
    }

    public function selesai(DateTimeImmutable $tanggalKembali): void
    {
        if (!$this->masihAktif()) {
            throw RentalException::sudahDikembalikan($this->idPenyewaan);
        }
        if ($tanggalKembali < $this->tanggalSewa) {
            throw new \InvalidArgumentException('Tanggal kembali tidak boleh sebelum tanggal sewa.');
        }
        $this->tanggalKembali = $tanggalKembali;
        $this->statusPenyewaan = StatusPenyewaan::Dikembalikan;
    }
}