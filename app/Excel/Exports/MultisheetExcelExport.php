<?php

namespace App\Excel\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultisheetExcelExport implements WithMultipleSheets
{
    use Exportable;

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->data as $sheet) {
            if (!($sheet['data'] ?? false) || !($sheet['headers'] ?? false)) {
                continue;
            }
            $sheets[] = new ExcelExport([$sheet['headers'], $sheet['data'] ?? []], $sheet['title'], $sheet['link_titles'] ?? []);
        }
        return $sheets;
    }
}
