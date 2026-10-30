<?php
declare(strict_types=1);

require __DIR__ . '/src/autoload.php';

use RentalFilm\Exception\RentalException;
use RentalFilm\Model\Film;
use RentalFilm\Model\Pelanggan;
use RentalFilm\Model\Penyewaan;
use RentalFilm\Repository\InMemoryFilmRepository;
use RentalFilm\Repository\InMemoryPelangganRepository;
use RentalFilm\Repository\InMemoryPenyewaanRepository;
use RentalFilm\Rule\AturanBatasPenyewaanAktif;
use RentalFilm\Rule\AturanStokTersedia;
use RentalFilm\Service\RentalService;

function judul(string $teks): void
{
    echo "\n=== $teks ===\n";
}

function coba(callable $aksi): void
{
    try {
        $aksi();
    } catch (RentalException $e) {
        echo "  [DITOLAK] " . $e->getMessage() . "\n";
    }
}

function tampil(Penyewaan $p): void
{
    printf(
        "  %s | %s menyewa \"%s\" | sewa: %s | kembali: %s | status: %s\n",
        $p->getIdPenyewaan(),
        $p->getPelanggan()->getNama(),
        $p->getFilm()->getJudul(),
        $p->getTanggalSewa()->format('Y-m-d'),
        $p->getTanggalKembali()?->format('Y-m-d') ?? '-',
        $p->getStatus()->value
    );
}

function tampilStok(InMemoryFilmRepository $repo): void
{
    foreach ($repo->semua() as $f) {
        printf("  %s %-16s (%s, %d) stok: %d\n", $f->getKodeFilm(), $f->getJudul(), $f->getGenre(), $f->getTahunRilis(), $f->getJumlahStok());
    }
}

$filmRepo = new InMemoryFilmRepository();
$pelangganRepo = new InMemoryPelangganRepository();
$penyewaanRepo = new InMemoryPenyewaanRepository();

$filmRepo->simpan(new Film('F001', 'Inception', 'Sci-Fi', 2010, 2));
$filmRepo->simpan(new Film('F002', 'Parasite', 'Thriller', 2019, 1));
$filmRepo->simpan(new Film('F003', 'Spirited Away', 'Animasi', 2001, 3));
$filmRepo->simpan(new Film('F004', 'The Dark Knight', 'Aksi', 2008, 2));

$pelangganRepo->simpan(new Pelanggan('P001', 'Budi Santoso', '081234567890', 'Jl. Ahmad Yani No. 1, Pontianak'));
$pelangganRepo->simpan(new Pelanggan('P002', 'Siti Aminah', '085212345678', 'Jl. Gajah Mada No. 7, Pontianak'));

$service = new RentalService($filmRepo, $pelangganRepo, $penyewaanRepo, [
    new AturanStokTersedia(),
    new AturanBatasPenyewaanAktif($penyewaanRepo, 3),
]);

judul('Kondisi awal stok');
tampilStok($filmRepo);

judul('Skenario 1: Budi menyewa Inception');
coba(fn () => tampil($service->sewaFilm('P001', 'F001', new DateTimeImmutable('2026-10-01'))));

judul('Skenario 2: Stok terakhir Parasite disewa Budi, lalu Siti mencoba menyewa');
coba(fn () => tampil($service->sewaFilm('P001', 'F002', new DateTimeImmutable('2026-10-02'))));
coba(fn () => tampil($service->sewaFilm('P002', 'F002', new DateTimeImmutable('2026-10-02'))));

judul('Skenario 3: Batas 3 penyewaan aktif per pelanggan');
coba(fn () => tampil($service->sewaFilm('P001', 'F003', new DateTimeImmutable('2026-10-03'))));
coba(fn () => tampil($service->sewaFilm('P001', 'F004', new DateTimeImmutable('2026-10-03'))));

judul('Skenario 4: Budi mengembalikan Inception, status berubah, stok pulih');
coba(fn () => tampil($service->kembalikanFilm('RNT-001', new DateTimeImmutable('2026-10-05'))));
coba(fn () => tampil($service->sewaFilm('P001', 'F004', new DateTimeImmutable('2026-10-06'))));

judul('Skenario 5: Kasus error lain');
coba(fn () => $service->kembalikanFilm('RNT-001', new DateTimeImmutable('2026-10-07')));
coba(fn () => $service->sewaFilm('P001', 'F999', new DateTimeImmutable('2026-10-07')));
coba(fn () => $service->sewaFilm('P999', 'F001', new DateTimeImmutable('2026-10-07')));

judul('Riwayat seluruh penyewaan');
foreach ($penyewaanRepo->semua() as $p) {
    tampil($p);
}

judul('Kondisi akhir stok');
tampilStok($filmRepo);