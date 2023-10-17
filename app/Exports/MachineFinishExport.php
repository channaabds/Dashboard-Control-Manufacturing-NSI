<?php

namespace App\Exports;

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

class MachineFinishExport implements FromArray, ShouldAutoSize, WithHeadings, WithStyles, WithEvents
{
    use Exportable;

    public $min, $max, $monthMin, $yearMin, $monthMax, $yearMax;

    public function __construct($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    // fungsi ini akan merubah downtime ke bentuk yang mudah dibaca
    // 0:0:0:0 -> 0 Hari 0 Jam 0 Menit 0 Detik
    public function downtimeTranslator($downtime) {
        $downtimeParts = explode(':', $downtime);
        $days = $downtimeParts[0];
        $hours = $downtimeParts[1];
        $minutes = $downtimeParts[2];
        $seconds = $downtimeParts[3];

        return $days . ' Hari ' . $hours . ' Jam ' . $minutes .  ' Menit ' . $seconds . ' Detik';
    }

    public function downtimeToSeconds($downtime) {
        $downtimeParts = explode(':', $downtime);

        $days = intval($downtimeParts[0]);
        $hours = intval($downtimeParts[1]);
        $minutes = intval($downtimeParts[2]);
        $seconds = intval($downtimeParts[3]);

        $totalSeconds = ($days * 86400) + ($hours * 3600) + ($minutes * 60) + $seconds;

        return $totalSeconds;
    }

    // function untuk menambahkan antara 2 downtime yang memiliki format '0:0:0:0'
    public function addDowntimeByDowntime($firstDowntime, $secDowntime) {
        $firstDowntimeParts = explode(':', $firstDowntime);
        $secDowntimeParts = explode(':', $secDowntime);

        $firstDowntimeDays = intval($firstDowntimeParts[0]);
        $firstDowntimeHours = intval($firstDowntimeParts[1]);
        $firstDowntimeMinutes = intval($firstDowntimeParts[2]);
        $firstDowntimeSeconds = intval($firstDowntimeParts[3]);

        $secDowntimeDays = intval($secDowntimeParts[0]);
        $secDowntimeHours = intval($secDowntimeParts[1]);
        $secDowntimeMinutes = intval($secDowntimeParts[2]);
        $secDowntimeSeconds = intval($secDowntimeParts[3]);

        $totalSeconds = (($firstDowntimeDays * 86400) + ($firstDowntimeHours * 3600) + ($firstDowntimeMinutes * 60) + $firstDowntimeSeconds) + (($secDowntimeDays * 86400) + ($secDowntimeHours * 3600) + ($secDowntimeMinutes * 60) + $secDowntimeSeconds);

        $days = floor($totalSeconds / 86400);
        $totalSeconds %= 86400;
        $hours = floor($totalSeconds / 3600);
        $totalSeconds %= 3600;
        $minutes = floor($totalSeconds / 60);
        $seconds = $totalSeconds %  60;

        $result = "$days:$hours:$minutes:$seconds";
        return $result;
    }

    public function array(): array
    {
        $dataExport = [];
        $i = 1;
        if ($this->min === null && $this->max === null) {
            $dataExportDB = MachineRepair::where('status_mesin', 'OK Repair (Finish)')
                            ->orderBy('tgl_input', 'desc')->orderBy('id', 'desc')->get();
        }

        if ($this->min !== null || $this->max !== null) {
            $minDate = Carbon::create($this->min);
            $maxDate = Carbon::create($this->max);

            if ($this->min !== null && $this->max === null) {
                $dataExportDB = MachineRepair::where('status_mesin', 'OK Repair (Finish)')
                                ->whereDate('tgl_kerusakan', '>=', $minDate)->orderBy('tgl_input', 'desc')
                                ->orderBy('id', 'desc')->get();
            }

            if ($this->min === null && $this->max !== null) {
                $dataExportDB = MachineRepair::where('status_mesin', 'OK Repair (Finish)')
                                ->whereDate('tgl_kerusakan', '<=', $maxDate)->orderBy('tgl_input', 'desc')
                                ->orderBy('id', 'desc')->get();
                            }

            if ($this->min !== null && $this->max !== null) {
                $dataExportDB = MachineRepair::where('status_mesin', 'OK Repair (Finish)')
                                ->whereDate('tgl_kerusakan', '>=', $minDate)
                                ->whereDate('tgl_kerusakan', '<=', $maxDate)->orderBy('tgl_input', 'desc')
                                ->orderBy('id', 'desc')->get();
            }
        }



        foreach ($dataExportDB as $dataDB) {
            $dataDowntime = $this->downtimeTranslator($dataDB->total_downtime);
            $detikDowntime = $this->downtimeToSeconds($dataDB->total_downtime);
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
                    $i = MachineRepair::where('status_mesin', 'OK Repair (Finish)')->count();
                    $cellRange = "A1:W" . $i+1;
                }

                $minDate = Carbon::create($this->min);
                $maxDate = Carbon::create($this->max);

                if ($this->min !== null && $this->max === null) {
                    $i = MachineRepair::where('status_mesin', 'OK Repair (Finish)')->whereDate('tgl_kerusakan', '>=', $minDate)->count();
                    $cellRange = "A1:W" . $i+1;
                }

                if ($this->min === null && $this->max !== null) {
                    $i = MachineRepair::where('status_mesin', 'OK Repair (Finish)')->whereDate('tgl_kerusakan', '<=', $maxDate)->count();
                    $cellRange = "A1:W" . $i+1;
                }

                if ($this->min !== null && $this->max !== null) {
                    $i = MachineRepair::where('status_mesin', 'OK Repair (Finish)')->whereDate('tgl_kerusakan', '>=', $minDate)
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
