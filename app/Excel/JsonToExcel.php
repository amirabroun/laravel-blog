<?php

namespace App\Excel;

use App\Excel\Exports\ExcelExport;
use App\Excel\Exports\MultisheetExcelExport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class JsonToExcel
{
    public static function generate(array $excelData)
    {
        if (isset($excelData['sheets'])) {
            $export = new MultisheetExcelExport($excelData['sheets']);
        } else {
            $export = new ExcelExport(
                [
                    $excelData['headers'],
                    $excelData['data'] ?? []
                ],
                $excelData['title'],
                $excelData['link_titles'] ?? []
            );
        }

        $diskName = 'public';
        $excelFileName = Str::random();
        if (!Excel::store($export, $excelFileName . '.xlsx', $diskName)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'failed to create excel file',
                'data' => [],
            ], 500);
        }

        $filePath = Storage::disk($diskName)->url($excelFileName . '.xlsx');

        return [
            'status' => 'success',
            'data' => ['link' => $filePath],
        ];
    }
}
