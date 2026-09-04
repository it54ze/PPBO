<?php
$pengulangan = 10;
$no1 = 0;
$no2 = 1;

echo $no1 . ", " . $no2;

for ($i = 2; $i < $pengulangan; $i++) {
    $no3 = $no1 + $no2;
    echo ", " . $no3;
    $no1 = $no2;
    $no2 = $no3;
}
?>