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

    public $minDate, $maxDate;

    public function __construct($min, $max)
    {
        if ($min !== null && $max !== null) {
            $this->minDate = Carbon::create($min);
            $this->maxDate = Carbon::create($max);
        } elseif ($min !== null && $max === null) {
            $this->minDate = Carbon::create($min);
            $this->maxDate = null;
        } elseif ($min === null && $max !== null) {
            $this->minDate = null;
            $this->maxDate = Carbon::create($max);
        } else {
            $this->minDate = null;
            $this->maxDate = null;
        }
    }

    public function array(): array
    {
        $dataExport = [];
        $i = 1;
        $dataExportDB = [];
        if ($this->minDate === null && $this->maxDate === null) {
            $dataExportDB = Quality::where('departement', 'IPQC')
                            ->orderBy('date', 'desc')->orderBy('id', 'desc')->get();
        } elseif ($this->minDate !== null && $this->maxDate === null) {
            $dataExportDB = Quality::where('departement', 'IPQC')->whereDate('date', '>=', $this->minDate)
                            ->orderBy('date', 'desc')->orderBy('id', 'desc')->get();
        } elseif ($this->minDate === null && $this->maxDate !== null) {
            $dataExportDB = Quality::where('departement', 'IPQC')->whereDate('date', '<=', $this->maxDate)
                            ->orderBy('date', 'desc')->orderBy('id', 'desc')->get();
        } else {
            $dataExportDB = Quality::where('departement', 'IPQC')->whereDate('date', '>=', $this->minDate)
                            ->whereDate('date', '<=', $this->maxDate)->orderBy('date', 'desc')
                            ->orderBy('id', 'desc')->get();
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
                                $dataDB->pic_departement,
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
            'PIC Departement (IPQC/OQC)',
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
                if ($this->minDate === null && $this->maxDate === null) {
                    $i = Quality::where('departement', 'IPQC')->count();
                    $cellRange = "A1:W" . $i+1;
                }  elseif ($this->minDate !== null && $this->maxDate === null) {
                    $i = Quality::where('departement', 'IPQC')->whereDate('date', '>=', $this->minDate)->count();
                    $cellRange = "A1:W" . $i+1;
                }  elseif ($this->minDate === null && $this->maxDate !== null) {
                    $i = Quality::where('departement', 'IPQC')->whereDate('date', '<=', $this->maxDate)->count();
                    $cellRange = "A1:W" . $i+1;
                } else {
                    $i = Quality::where('departement', 'IPQC')->whereDate('date', '>=', $this->minDate)
                            ->whereDate('date', '<=', $this->maxDate)->count();
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
