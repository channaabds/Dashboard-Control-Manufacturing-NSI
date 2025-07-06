<?php

namespace App\Exports;

use App\Models\Machine; // Import model Machine
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class MesinExport implements FromArray, ShouldAutoSize, WithHeadings, WithStyles, WithEvents
{
    use Exportable;

    public function array(): array
    {
        // Fetch data from the 'machines' table
        $machines = Machine::all(); // Ambil semua data dari tabel 'machines'
        $dataExport = [];
        $i = 1;

        foreach ($machines as $machine) {
            $dataExport[] = [
                $i,
                $machine->no_mesin,
                $machine->tipe_mesin,
                $machine->tipe_bartop,
                $machine->seri_mesin,
            ];
            $i++;
        }

        return $dataExport;
    }

    public function headings(): array
    {
        return [
            'No',
            'No Mesin',
            'Tipe Mesin',
            'Tipe Bartop',
            'Seri Mesin',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:E' . $event->sheet->getHighestRow())->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
            },
        ];
    }
}
