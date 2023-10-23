<?php

namespace App\Exports;

use App\Http\Controllers\DowntimeController;
use App\Models\MachineRepair;
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

class MachinesWaitingSparepartExport implements FromArray, ShouldAutoSize, WithHeadings, WithStyles, WithEvents
{
    use Exportable;

    public $min, $max, $monthMin, $yearMin, $monthMax, $yearMax;

    public function __construct($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    // function untuk mendapatkan interval antara waktu strat downtime dan waktu sekarang ini (current downtime)
    public function getInterval($startDowntime) {
        $now = Carbon::now();
        $start = Carbon::parse($startDowntime);
        $result = $start->diff($now)->format('%a:%h:%i:%s');
        return $result;
    }

    public function array(): array
    {
        $DowntimeController = (new DowntimeController());

        $dataExport = [];
        $i = 1;
        if ($this->min === null && $this->max === null) {
            $dataExportDB = MachineRepair::where('status_mesin', 'Waiting Sparepart')
                            ->orderBy('tgl_input', 'desc')->orderBy('id', 'desc')->get();
        }

        if ($this->min !== null || $this->max !== null) {
            $minDate = Carbon::create($this->min);
            $maxDate = Carbon::create($this->max);

            if ($this->min !== null && $this->max === null) {
                $dataExportDB = MachineRepair::where('status_mesin', 'Waiting Sparepart')
                                ->whereDate('tgl_kerusakan', '>=', $minDate)->orderBy('tgl_input', 'desc')
                                ->orderBy('id', 'desc')->get();
            }

            if ($this->min === null && $this->max !== null) {
                $dataExportDB = MachineRepair::where('status_mesin', 'Waiting Sparepart')
                                ->whereDate('tgl_kerusakan', '<=', $maxDate)->orderBy('tgl_input', 'desc')
                                ->orderBy('id', 'desc')->get();
                            }

            if ($this->min !== null && $this->max !== null) {
                $dataExportDB = MachineRepair::where('status_mesin', 'Waiting Sparepart')->whereDate('tgl_kerusakan', '>=', $minDate)
                                ->whereDate('tgl_kerusakan', '<=', $maxDate)->orderBy('tgl_input', 'desc')
                                ->orderBy('id', 'desc')->get();
            }
        }



        foreach ($dataExportDB as $dataDB) {
            if ($dataDB->status_aktifitas == 'Running') {
                $totalDowntime = $dataDB->total_downtime;
            } else {
                $interval = $this->getInterval($dataDB->start_downtime);
                $totalDowntime = $DowntimeController->addDowntimeByDowntime($interval, $dataDB->total_downtime);
            }
            $dataDowntime = $DowntimeController->downtimeTranslator($totalDowntime, true);
            $detikDowntime = $DowntimeController->downtimeToSeconds($totalDowntime);
            $dataExport[$i] = [
                                $i,
                                $dataDB->dataMesin->no_mesin,
                                $dataDB->dataMesin->tipe_mesin,
                                $dataDB->dataMesin->tipe_bartop,
                                $dataDB->dataMesin->seri_mesin,
                                $dataDB->pic,
                                $dataDB->request,
                                $dataDB->bagian_rusak,
                                $dataDB->sebab,
                                $dataDB->analisa,
                                $dataDB->aksi,
                                $dataDB->sparepart,
                                $dataDB->prl,
                                $dataDB->po,
                                $dataDB->kedatangan_po,
                                $dataDB->kedatangan_prl,
                                $dataDB->tgl_kerusakan,
                                $dataDB->tgl_input,
                                $dataDB->tgl_ok_repair,
                                $dataDB->status_mesin,
                                $dataDB->status_aktifitas,
                                $dataDowntime,
                                $detikDowntime,
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
            'Type Mesin',
            'Type Bartop',
            'Serial Mesin',
            'PIC',
            'Request',
            'Bagian Rusak',
            'Sebab',
            'Analisa',
            'Action',
            'Sparepart',
            'PRL',
            'PO',
            'Kedatangan PO',
            'Kedatangan Request PRL',
            'Tgl Kerusakan',
            'Tanggal Input',
            'Tanggal OK Repair',
            'Status Mesin',
            'Status Aktivitas',
            'Downtime',
            'Detik Downtime',
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
                if ($this->min === null && $this->max === null) {
                    $i = MachineRepair::where('status_mesin', 'Waiting Sparepart')->count();
                    $cellRange = "A1:W" . $i+1;
                }

                $minDate = Carbon::create($this->min);
                $maxDate = Carbon::create($this->max);

                if ($this->min !== null && $this->max === null) {
                    $i = MachineRepair::where('status_mesin', 'Waiting Sparepart')->whereDate('tgl_kerusakan', '>=', $minDate)->count();
                    $cellRange = "A1:W" . $i+1;
                }

                if ($this->min === null && $this->max !== null) {
                    $i = MachineRepair::where('status_mesin', 'Waiting Sparepart')->whereDate('tgl_kerusakan', '<=', $maxDate)->count();
                    $cellRange = "A1:W" . $i+1;
                }

                if ($this->min !== null && $this->max !== null) {
                    $i = MachineRepair::where('status_mesin', 'Waiting Sparepart')->whereDate('tgl_kerusakan', '>=', $minDate)
                            ->whereDate('tgl_kerusakan', '<=', $maxDate)->count();
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
