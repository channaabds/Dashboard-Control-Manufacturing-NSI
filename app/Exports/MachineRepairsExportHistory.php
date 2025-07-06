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

class MachineRepairsExportHistory implements FromArray, ShouldAutoSize, WithHeadings, WithStyles, WithEvents
{
    use Exportable;

    public $min, $max, $monthMin, $yearMin, $monthMax, $yearMax;

    public function __construct($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    // function untuk mendapatkan interval antara waktu strat downtime dan waktu sekarang ini (current downtime)
    public function getInterval($startDowntime)
    {
        $now = Carbon::now();
        $start = Carbon::parse($startDowntime);
        $result = $start->diff($now)->format('%a:%h:%i:%s');
        return $result;
    }

    public function array(): array
    {
        $DowntimeController = new DowntimeController();

        $dataExport = [];
        $i = 1;

        // Cek apakah min dan max null, jika ya set ke tanggal hari ini
        if (is_null($this->min) && is_null($this->max)) {
            $today = Carbon::now()->format('Y-m-d');  // Ambil tanggal hari ini
            $this->min = $today;
            $this->max = $today;
        }

        // Mengambil semua data tanpa pengecualian status_mesin
        $query = MachineRepair::query();

        if ($this->min !== null || $this->max !== null) {
            $minDate = Carbon::create($this->min);
            $maxDate = Carbon::create($this->max);

            if ($this->min !== null && $this->max === null) {
                $query->whereDate('updated_at', '>=', $minDate);
            }

            if ($this->min === null && $this->max !== null) {
                $query->whereDate('updated_at', '<=', $maxDate);
            }

            if ($this->min !== null && $this->max !== null) {
                $query->whereDate('updated_at', '>=', $minDate)
                    ->whereDate('updated_at', '<=', $maxDate);
            }
        }

        // Ambil semua data yang memiliki keterangan "history" dan urutkan berdasarkan mesin_id dan tanggal input
        $dataExportDB = $query->where('keterangan', 'LIKE', '%history%')
            ->orderBy('mesin_id')
            ->orderBy('tgl_input', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        foreach ($dataExportDB as $dataDB) {
            $tglStartDowntime = $dataDB->updated_at;

            // Cek apakah ini adalah record terakhir untuk id_case yang sama
            $isLastRecord = !MachineRepair::where('mesin_id', $dataDB->mesin_id)
                ->where('id_case', $dataDB->id_case)
                ->where('id', '>', $dataDB->id)
                ->where('keterangan', 'LIKE', '%history%')
                ->exists();

            // Jika record terakhir, gunakan waktu saat ini sebagai tglStopDowntime, jika tidak, ambil updated_at dari record berikutnya
            if ($isLastRecord && $dataDB->status_mesin !== 'OK Repair (Finish)') {
                $tglStopDowntime = Carbon::now();

                // Hitung downtime dari tglStopDowntime - tglStartDowntime
                $interval = $tglStartDowntime->diff($tglStopDowntime);
                $dataDowntime = $interval->format('%d Hari %h Jam %i Menit %s Detik');
                $detikDowntime = $interval->days * 86400 + $interval->h * 3600 + $interval->i * 60 + $interval->s;
            } else {
                $tglStopDowntime = MachineRepair::where('mesin_id', $dataDB->mesin_id)
                    ->where('id_case', $dataDB->id_case)
                    ->where('id', '>', $dataDB->id)
                    ->orderBy('id', 'asc')
                    ->value('updated_at');

                if (!$tglStopDowntime) {
                    $tglStopDowntime = $dataDB->updated_at;
                }

                // Lakukan perhitungan downtime secara normal jika bukan record terakhir
                if ($dataDB->status_aktifitas == 'Running') {
                    $totalDowntime = $dataDB->total_downtime;
                } else {
                    $interval = $this->getInterval($dataDB->start_downtime);
                    $totalDowntime = $DowntimeController->addDowntimeByDowntime($interval, $dataDB->total_downtime);
                }

                $dataDowntime = $DowntimeController->downtimeTranslator($totalDowntime, true);
                $detikDowntime = $DowntimeController->downtimeToSeconds($totalDowntime);
            }

            $dataExport[$i] = [
                $i,
                $dataDB->dataMesin->no_mesin,
                $dataDB->id_case,
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
                $tglStartDowntime,
                $tglStopDowntime,
                $dataDB->keterangan,
            ];
            $i++;
        }

        usort($dataExport, function ($a, $b) {
            $isDowntimeZeroA = $a[22] === "0 Hari 0 Jam 0 Menit 0 Detik";
            $isDowntimeZeroB = $b[22] === "0 Hari 0 Jam 0 Menit 0 Detik";

            if ($a[2] !== $b[2]) {
                return $a[2] <=> $b[2];
            }

            if ($isDowntimeZeroA && !$isDowntimeZeroB)
                return 1;
            if (!$isDowntimeZeroA && $isDowntimeZeroB)
                return -1;

            return strtotime($a[25]) - strtotime($b[25]);
        });

        return $dataExport;
    }

    public function headings(): array
    {
        return [
            'No',
            'No Mesin',
            'ID case',
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
            'Tgl Start Downtime', // Kolom baru
            'Tgl Stop Downtime', // Kolom baru
            'Keterangan', // Kolom baru
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
        Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
            $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        });

        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Ambil data yang telah dimodifikasi untuk diekspor
                $dataExport = $this->array();

                // Hitung jumlah baris yang berisi data
                $rowCount = count($dataExport);

                // Tentukan range berdasarkan jumlah baris yang ada (mulai dari A1)
                $cellRange = "A1:AA" . ($rowCount + 1); // +1 untuk header
    
                // Terapkan border ke range sel yang berisi data
                $event->sheet->styleCells(
                    $cellRange,
                    [
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]
                );

                // Array untuk melacak baris terakhir dari setiap id_case
                $lastRowPerCase = [];

                // Loop untuk menentukan baris terakhir dari setiap id_case
                foreach ($dataExport as $index => $dataRow) {
                    $idCase = $dataRow[2]; // Kolom ke-2 adalah kolom id_case
                    $lastRowPerCase[$idCase] = $index + 2; // Simpan baris terakhir (index + 2 untuk mengimbangi header)
                }

                // Terapkan warna kuning pada baris dengan "No" paling besar untuk setiap id_case
                foreach ($lastRowPerCase as $rowIndex) {
                    $event->sheet->styleCells(
                        "A{$rowIndex}:AA{$rowIndex}",
                        [
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['rgb' => 'FFFF00'], // Warna kuning
                            ],
                        ]
                    );
                }
            },
        ];
    }





}
