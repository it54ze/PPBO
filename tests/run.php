<?php
declare(strict_types=1);

require __DIR__ . '/../src/autoload.php';

use RentalFilm\Exception\RentalException;
use RentalFilm\Model\Film;
use RentalFilm\Model\Pelanggan;
use RentalFilm\Model\Penyewaan;
use RentalFilm\Model\StatusPenyewaan;
use RentalFilm\Repository\InMemoryFilmRepository;
use RentalFilm\Repository\InMemoryPelangganRepository;
use RentalFilm\Repository\InMemoryPenyewaanRepository;
use RentalFilm\Rule\AturanBatasPenyewaanAktif;
use RentalFilm\Rule\AturanStokTersedia;
use RentalFilm\Service\RentalService;

$total = 0;
$gagal = 0;

function uji(string $nama, callable $fn): void
{
    global $total, $gagal;
    $total++;
    try {
        $fn();
        echo "[PASS] $nama\n";
    } catch (Throwable $e) {
        $gagal++;
        echo "[FAIL] $nama -> " . $e->getMessage() . "\n";
    }
}

function pastikan(bool $kondisi, string $pesan = 'Assertion gagal'): void
{
    if (!$kondisi) {
        throw new AssertionError($pesan);
    }
}

function pastikanError(string $kelas, callable $fn): void
{
    try {
        $fn();
    } catch (Throwable $e) {
        if ($e instanceof $kelas) {
            return;
        }
        throw new AssertionError("Diharapkan $kelas tetapi mendapat " . get_class($e));
    }
    throw new AssertionError("Diharapkan $kelas tetapi tidak ada exception");
}

/** @return array{RentalService, InMemoryFilmRepository, InMemoryPenyewaanRepository} */
function buatLingkungan(): array
{
    $filmRepo = new InMemoryFilmRepository();
    $pelangganRepo = new InMemoryPelangganRepository();
    $penyewaanRepo = new InMemoryPenyewaanRepository();

    $filmRepo->simpan(new Film('F001', 'Inception', 'Sci-Fi', 2010, 2));
    $filmRepo->simpan(new Film('F002', 'Parasite', 'Thriller', 2019, 1));
    $filmRepo->simpan(new Film('F003', 'Spirited Away', 'Animasi', 2001, 5));
    $filmRepo->simpan(new Film('F004', 'The Dark Knight', 'Aksi', 2008, 5));
    $filmRepo->simpan(new Film('F005', 'Interstellar', 'Sci-Fi', 2014, 5));
    $pelangganRepo->simpan(new Pelanggan('P001', 'Budi', '0811', 'Pontianak'));
    $pelangganRepo->simpan(new Pelanggan('P002', 'Siti', '0852', 'Pontianak'));

    $service = new RentalService($filmRepo, $pelangganRepo, $penyewaanRepo, [
        new AturanStokTersedia(),
        new AturanBatasPenyewaanAktif($penyewaanRepo, 3),
    ]);

    return [$service, $filmRepo, $penyewaanRepo];
}

$d = fn (string $tgl) => new DateTimeImmutable($tgl);

//Film
uji('Film: stok negatif ditolak', function () {
    pastikanError(InvalidArgumentException::class, fn () => new Film('X', 'X', 'X', 2000, -1));
});
uji('Film: kurangiStok dan tambahStok mengubah stok', function () {
    $f = new Film('F', 'Judul', 'G', 2000, 1);
    $f->kurangiStok();
    pastikan($f->getJumlahStok() === 0 && !$f->tersedia());
    $f->tambahStok();
    pastikan($f->getJumlahStok() === 1 && $f->tersedia());
});
uji('Film: kurangiStok saat stok 0 melempar RentalException', function () {
    $f = new Film('F', 'Judul', 'G', 2000, 0);
    pastikanError(RentalException::class, fn () => $f->kurangiStok());
});

//Pelanggan
uji('Pelanggan: ubahKontak memperbarui telepon dan alamat', function () {
    $p = new Pelanggan('P', 'Nama', '1', 'A');
    $p->ubahKontak('2', 'B');
    pastikan($p->getNomorTelepon() === '2' && $p->getAlamat() === 'B');
});

//Penyewaan
uji('Penyewaan: status awal Disewa dan tanggal kembali kosong', function () {
    $p = new Penyewaan('R', new Film('F', 'J', 'G', 2000, 1), new Pelanggan('P', 'N', '1', 'A'), new DateTimeImmutable('2026-10-01'));
    pastikan($p->getStatus() === StatusPenyewaan::Disewa && $p->masihAktif() && $p->getTanggalKembali() === null);
});
uji('Penyewaan: selesai() mengubah status ke Dikembalikan', function () {
    $p = new Penyewaan('R', new Film('F', 'J', 'G', 2000, 1), new Pelanggan('P', 'N', '1', 'A'), new DateTimeImmutable('2026-10-01'));
    $p->selesai(new DateTimeImmutable('2026-10-03'));
    pastikan($p->getStatus() === StatusPenyewaan::Dikembalikan && !$p->masihAktif());
    pastikan($p->getTanggalKembali()->format('Y-m-d') === '2026-10-03');
});
uji('Penyewaan: selesai() dua kali ditolak', function () {
    $p = new Penyewaan('R', new Film('F', 'J', 'G', 2000, 1), new Pelanggan('P', 'N', '1', 'A'), new DateTimeImmutable('2026-10-01'));
    $p->selesai(new DateTimeImmutable('2026-10-03'));
    pastikanError(RentalException::class, fn () => $p->selesai(new DateTimeImmutable('2026-10-04')));
});
uji('Penyewaan: tanggal kembali sebelum tanggal sewa ditolak', function () {
    $p = new Penyewaan('R', new Film('F', 'J', 'G', 2000, 1), new Pelanggan('P', 'N', '1', 'A'), new DateTimeImmutable('2026-10-05'));
    pastikanError(InvalidArgumentException::class, fn () => $p->selesai(new DateTimeImmutable('2026-10-01')));
});

//RentalService
uji('Service: sewaFilm berhasil, stok berkurang, status Disewa', function () use ($d) {
    [$s, $filmRepo] = buatLingkungan();
    $p = $s->sewaFilm('P001', 'F001', $d('2026-10-01'));
    pastikan($p->getIdPenyewaan() === 'RNT-001');
    pastikan($p->getStatus() === StatusPenyewaan::Disewa);
    pastikan($filmRepo->cariByKode('F001')->getJumlahStok() === 1);
});
uji('Service: ID transaksi bertambah berurutan', function () use ($d) {
    [$s] = buatLingkungan();
    $a = $s->sewaFilm('P001', 'F001', $d('2026-10-01'));
    $b = $s->sewaFilm('P002', 'F001', $d('2026-10-01'));
    pastikan($a->getIdPenyewaan() === 'RNT-001' && $b->getIdPenyewaan() === 'RNT-002');
});
uji('Service: film stok habis ditolak dan stok tetap 0', function () use ($d) {
    [$s, $filmRepo] = buatLingkungan();
    $s->sewaFilm('P001', 'F002', $d('2026-10-01'));
    pastikanError(RentalException::class, fn () => $s->sewaFilm('P002', 'F002', $d('2026-10-01')));
    pastikan($filmRepo->cariByKode('F002')->getJumlahStok() === 0);
});
uji('Service: penyewaan ke-4 aktif ditolak dan stok tidak berubah', function () use ($d) {
    [$s, $filmRepo] = buatLingkungan();
    $s->sewaFilm('P001', 'F001', $d('2026-10-01'));
    $s->sewaFilm('P001', 'F003', $d('2026-10-01'));
    $s->sewaFilm('P001', 'F004', $d('2026-10-01'));
    pastikanError(RentalException::class, fn () => $s->sewaFilm('P001', 'F005', $d('2026-10-01')));
    pastikan($filmRepo->cariByKode('F005')->getJumlahStok() === 5);
});
uji('Service: kembalikanFilm mengubah status dan memulihkan stok', function () use ($d) {
    [$s, $filmRepo] = buatLingkungan();
    $p = $s->sewaFilm('P001', 'F001', $d('2026-10-01'));
    $s->kembalikanFilm($p->getIdPenyewaan(), $d('2026-10-04'));
    pastikan($p->getStatus() === StatusPenyewaan::Dikembalikan);
    pastikan($filmRepo->cariByKode('F001')->getJumlahStok() === 2);
});
uji('Service: setelah mengembalikan, kuota sewa pelanggan pulih', function () use ($d) {
    [$s] = buatLingkungan();
    $a = $s->sewaFilm('P001', 'F001', $d('2026-10-01'));
    $s->sewaFilm('P001', 'F003', $d('2026-10-01'));
    $s->sewaFilm('P001', 'F004', $d('2026-10-01'));
    $s->kembalikanFilm($a->getIdPenyewaan(), $d('2026-10-02'));
    $baru = $s->sewaFilm('P001', 'F005', $d('2026-10-03'));
    pastikan($baru->masihAktif());
});
uji('Service: pengembalian ganda tidak menambah stok dua kali', function () use ($d) {
    [$s, $filmRepo] = buatLingkungan();
    $p = $s->sewaFilm('P001', 'F001', $d('2026-10-01'));
    $s->kembalikanFilm($p->getIdPenyewaan(), $d('2026-10-02'));
    pastikanError(RentalException::class, fn () => $s->kembalikanFilm($p->getIdPenyewaan(), $d('2026-10-03')));
    pastikan($filmRepo->cariByKode('F001')->getJumlahStok() === 2);
});
uji('Service: film, pelanggan, dan penyewaan tak dikenal ditolak', function () use ($d) {
    [$s] = buatLingkungan();
    pastikanError(RentalException::class, fn () => $s->sewaFilm('P001', 'F999', $d('2026-10-01')));
    pastikanError(RentalException::class, fn () => $s->sewaFilm('P999', 'F001', $d('2026-10-01')));
    pastikanError(RentalException::class, fn () => $s->kembalikanFilm('RNT-999', $d('2026-10-01')));
});
uji('Service: aturan non-AturanPenyewaan ditolak (kontrak OCP/LSP)', function () {
    pastikanError(InvalidArgumentException::class, fn () => new RentalService(
        new InMemoryFilmRepository(), new InMemoryPelangganRepository(), new InMemoryPenyewaanRepository(), [new stdClass()]
    ));
});

//Repository
uji('Repository: aktifMilikPelanggan hanya mengembalikan yang belum kembali', function () use ($d) {
    [$s, , $penyewaanRepo] = buatLingkungan();
    $a = $s->sewaFilm('P001', 'F001', $d('2026-10-01'));
    $s->sewaFilm('P001', 'F003', $d('2026-10-01'));
    $s->sewaFilm('P002', 'F004', $d('2026-10-01'));
    $s->kembalikanFilm($a->getIdPenyewaan(), $d('2026-10-02'));
    pastikan(count($penyewaanRepo->aktifMilikPelanggan('P001')) === 1);
    pastikan(count($penyewaanRepo->aktifMilikPelanggan('P002')) === 1);
});

//Ringkasan 
echo "\nTotal: $total | Lulus: " . ($total - $gagal) . " | Gagal: $gagal\n";
exit($gagal > 0 ? 1 : 0);