<?php

namespace App\Exports;

use App\Models\Quality;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class IpqcExport implements FromArray, ShouldAutoSize, WithHeadings, WithStyles, WithEvents
{
    use Exportable;

    public $month, $year;

    public function __construct($filter)
    {
        if ($filter !== null) {
            $carbon = Carbon::create($filter);
            $this->month = $carbon->format('m');
            $this->year = $carbon->format('Y');
        } else {
            $this->month = null;
            $this->year = null;
        }
    }

    public function array(): array
    {
        $dataExport = [];
        $i = 1;
        $dataExportDB = [];
        if ($this->month === null && $this->year === null) {
            $dataExportDB = Quality::where('departement', 'IPQC')->get();
        } else {
            $dataExportDB = Quality::whereMonth('date', $this->month)->whereYear('date', $this->year)->where('departement', 'IPQC')->get();
        }
        foreach ($dataExportDB as $dataDB) {
            $dataExport[$i] = [
                                $i,
                                $dataDB->date,
                                $dataDB->part_no,
                                $dataDB->lot_no,
                                $dataDB->mesin,
                                $dataDB->defect,
                                $dataDB->standard,
                                $dataDB->actual,
                                $dataDB->sampling,
                                $dataDB->qty_check,
                                $dataDB->ng,
                                number_format(($dataDB->ng/$dataDB->qty_check)*100, 2),
                                $dataDB->ng_pic,
                                $dataDB->approve_pic,
                                $dataDB->penyebab,
                                $dataDB->action,
                                $dataDB->deadline,
                                $dataDB->status,
                                $dataDB->pic_input,
                                $dataDB->no_ncr_lot,
                                $dataDB->keterangan,
                                $dataDB->judgement,
                                $dataDB->pembahasan,
                            ];
            $i++;
        }
        return $dataExport;
    }

    public function headings(): array
    {
        return [
            'No',
            'Date',
            'Part No',
            'Lot No',
            'Mesin',
            'Defect',
            'Standard',
            'Actual',
            'Sampling Qty',
            'Qty Check',
            'NG',
            '%',
            'NG PIC',
            'Approve PIC',
            'Penyebab',
            'Action',
            'Deadline',
            'Status',
            'PIC Input',
            'No NCR/LOT TAG',
            'Keterangan',
            'judgement',
            'Pembahasan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
            $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        });

        return [
            AfterSheet::class => function(AfterSheet $event) {
                if ($this->month === null && $this->year === null) {
                    $i = Quality::where('departement', 'IPQC')->count();
                    $cellRange = "A1:W" . $i+1;
                } else {
                    $i = Quality::whereMonth('date', $this->month)->whereYear('date', $this->year)->where('departement', 'IPQC')->count();
                    $cellRange = "A1:W" . $i+1;
                }

                $event->sheet->styleCells(
                    $cellRange,
                    [
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ]
                    ]
                );
            },
        ];
    }
}
