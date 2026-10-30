<?php
declare(strict_types=1);

namespace RentalFilm\Model;

enum StatusPenyewaan: string
{
    case Disewa = 'Disewa';
    case Dikembalikan = 'Dikembalikan';
}