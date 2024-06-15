<?php

namespace App\Excel\Exports;

use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;


class ExcelExport implements FromArray, WithTitle, WithEvents, WithStyles, ShouldAutoSize, WithStrictNullComparison
{

    protected $data;
    protected $title;
    protected $linkTitles;

    public function __construct(array $data, $title, array $linkTitles)
    {
        $this->data = $data;
        $this->title = $title;
        $this->linkTitles = $linkTitles;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                /** @var Worksheet $sheet */
                foreach ($event->sheet->getColumnIterator() as $row)
                    foreach ($row->getCellIterator() as $cell) {
                        if (filter_var($cell->getValue(), FILTER_VALIDATE_URL) || Str::contains($cell->getValue(), '://')) {
                            $cell->getHyperlink()->setUrl($cell->getValue());
                            if (!empty($this->linkTitles)) {
                                if (array_key_exists($cell->getColumn(), $this->linkTitles))
                                    $cell->setvalue($this->linkTitles[$cell->getColumn()]);
                            }
                        }
                    }
            }
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A:AZ')->getFont()->setName('Tahoma');
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('A:AZ')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('1')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('1')->getFill()->getStartColor()->setARGB('158466');
    }
}
