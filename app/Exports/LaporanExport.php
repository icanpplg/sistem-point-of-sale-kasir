<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell; 
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class LaporanExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    ShouldAutoSize,
    WithEvents,
    WithCustomStartCell
{
    protected $laporans;
    protected $totalTerjual;
    protected $totalTransaksi;
    protected $totalKeuntungan;
    protected $title;

    /**
     * @param  $laporans        : data laporan
     * @param  $totalTerjual    : total item terjual
     * @param  $totalTransaksi  : total transaksi
     * @param  $totalKeuntungan : total keuntungan
     * @param  $title           : nama file / judul
     */
    public function __construct($laporans, $totalTerjual, $totalTransaksi, $totalKeuntungan, $title)
    {
        $this->laporans        = $laporans;
        $this->totalTerjual    = $totalTerjual;
        $this->totalTransaksi  = $totalTransaksi;
        $this->totalKeuntungan = $totalKeuntungan;
        $this->title           = $title;
    }

    /**
     * Mulai heading di baris kedua (A2).
     */
    public function startCell(): string
    {
        return 'A2';
    }

    /**
     * Heading kolom (baris kedua).
     */
    public function headings(): array
    {
        return [
            'No',
            'Kode Barang',
            'Nama Barang',
            'Jumlah',
            'Modal',
            'Total',
            'Kasir',
            'Tanggal Transaksi',
        ];
    }

    /**
     * Data mulai baris ketiga.
     */
    public function collection()
    {
        $rows = new Collection();
        $no   = 1;

        // Data laporan
        foreach ($this->laporans as $laporan) {
            $rows->push([
                'No'                => $no++,
                'Kode Barang'       => $laporan->kode_barang,
                'Nama Barang'       => $laporan->nama_barang,
                'Jumlah'            => $laporan->jumlah,
                'Modal'             => 'Rp ' . number_format($laporan->modal, 0, ',', '.'),
                'Total'             => 'Rp ' . number_format($laporan->total, 0, ',', '.'),
                'Kasir'             => $laporan->kasir,
                'Tanggal Transaksi' => Carbon::parse($laporan->transaction_date)
                                             ->translatedFormat('d F Y H:i'),
            ]);
        }

        // Baris kosong pemisah
        $rows->push(['', '', '', '', '', '', '', '']);

        // Baris total terjual
        $rows->push([
            'No'                => '',
            'Kode Barang'       => '',
            'Nama Barang'       => '',
            'Jumlah'            => 'Total Terjual:',
            'Modal'             => $this->totalTerjual,
            'Total'             => '',
            'Kasir'             => '',
            'Tanggal Transaksi' => '',
        ]);

        // Baris total transaksi
        $rows->push([
            'No'                => '',
            'Kode Barang'       => '',
            'Nama Barang'       => '',
            'Jumlah'            => 'Total Transaksi:',
            'Modal'             => 'Rp ' . number_format($this->totalTransaksi, 0, ',', '.'),
            'Total'             => '',
            'Kasir'             => '',
            'Tanggal Transaksi' => '',
        ]);

        // Baris total keuntungan
        $rows->push([
            'No'                => '',
            'Kode Barang'       => '',
            'Nama Barang'       => '',
            'Jumlah'            => 'Keuntungan:',
            'Modal'             => 'Rp ' . number_format($this->totalKeuntungan, 0, ',', '.'),
            'Total'             => '',
            'Kasir'             => '',
            'Tanggal Transaksi' => '',
        ]);

        return $rows;
    }

    /**
     * Styling heading (baris kedua) dll.
     */
    public function styles(Worksheet $sheet)
    {
        // Heading kolom di baris kedua (A2:H2)
        $sheet->getStyle('A2:H2')->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E88E5'], // Biru
            ],
        ]);

        // Border tipis untuk heading
        $sheet->getStyle('A2:H2')->getBorders()
              ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Alignment tengah untuk heading
        $sheet->getStyle('A2:H2')
              ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }

    /**
     * Event AfterSheet: judul besar di baris pertama (A1).
     */
    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function(\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Merge cell A1:H1 (judul besar)
                $sheet->mergeCells('A1:H1');

                // Ubah '_' dan '-' agar lebih rapi
                $niceTitle = str_replace(['_', '-'], ' ', $this->title);
                $sheet->setCellValue('A1', 'Data ' . ucfirst($niceTitle));

                // Styling judul (baris pertama)
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold'  => true,
                        'size'  => 14,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '2196F3'], // Biru
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Tinggi baris pertama (opsional)
                $sheet->getRowDimension(1)->setRowHeight(25);

                // Buat border di area data (A2 sampai H baris terakhir)
                $lastRow   = $sheet->getHighestRow();
                $rangeData = 'A2:H' . $lastRow;

                $sheet->getStyle($rangeData)
                      ->getBorders()->getAllBorders()
                      ->setBorderStyle(Border::BORDER_THIN);
            },
        ];
    }
}
