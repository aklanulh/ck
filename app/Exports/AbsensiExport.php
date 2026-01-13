<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;

class AbsensiExport implements FromCollection, WithHeadings, WithTitle
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return new Collection($this->data);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama User',
            'Email User',
            'Tanggal',
            'Check In',
            'Check Out',
            'Lokasi Check In',
            'Lokasi Check Out',
            'Total Jam Kerja',
            'Status',
            'Catatan',
            'Dibuat Pada',
        ];
    }

    public function title(): string
    {
        return 'Laporan Absensi';
    }
}
