<?php

if (! function_exists('formatTanggalIndo')) {
    function formatTanggalIndo($tanggal): string
    {
        if (empty($tanggal)) {
            return '-';
        }

        $bulan = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        $carbon = \Carbon\Carbon::parse($tanggal);

        return $carbon->day . ' ' . $bulan[$carbon->month] . ' ' . $carbon->year;
    }
}

if (! function_exists('formatAngka')) {
    function formatAngka($angka): string
    {
        return number_format((float) $angka, 0, ',', '.');
    }
}