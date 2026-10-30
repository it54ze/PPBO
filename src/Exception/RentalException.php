<?php
declare(strict_types=1);

namespace RentalFilm\Exception;

final class RentalException extends \RuntimeException
{
    public static function stokHabis(string $judul): self
    {
        return new self("Stok film \"$judul\" habis.");
    }

    public static function filmTidakDitemukan(string $kode): self
    {
        return new self("Film dengan kode $kode tidak ditemukan.");
    }

    public static function pelangganTidakDitemukan(string $id): self
    {
        return new self("Pelanggan dengan ID $id tidak ditemukan.");
    }

    public static function penyewaanTidakDitemukan(string $id): self
    {
        return new self("Penyewaan dengan ID $id tidak ditemukan.");
    }

    public static function batasTerlampaui(string $nama, int $batas): self
    {
        return new self("Pelanggan $nama sudah menyewa $batas film yang belum dikembalikan (batas maksimum).");
    }

    public static function sudahDikembalikan(string $id): self
    {
        return new self("Penyewaan $id sudah dikembalikan sebelumnya.");
    }
}