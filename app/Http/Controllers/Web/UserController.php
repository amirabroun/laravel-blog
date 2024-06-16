<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Knp\Snappy\Pdf;
use App\Excel\JsonToExcel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    public function filterUsers(Request $request)
    {
        $data = $request->validate([
            'from' => 'date',
            'to' => 'date'
        ]);

        $users = User::query()->whereBetWeen('created_at', [$data['from'], $data['to']])->latest()->get();

        return view('filterUsers', [
            ...compact('users'),
            'from' => $data['from'],
            'to' => $data['to'],
        ]);
    }

    public function exportUsers(Request $request)
    {
        $data = $request->validate([
            'type' => Rule::in(['excel', 'pdf', 'word']),
            'from' => 'date',
            'to' => 'date'
        ]);

        $users = User::query()->whereBetWeen('created_at', [$data['from'], $data['to']])->get()->map(function ($user) {
            return [
                'name' => $user->full_name,
                'username' => $user->username,
                'created_at' => $user->created_at
            ];
        })->toArray();

        $filePath = match ($data['type']) {
            'pdf' => $this->pdfExport($users),
            'excel' => $this->excelExport($users),
            'word' => $this->wordExport($users),
            default => 0,
        };

        return response()->download($filePath);
    }

    private function excelExport($users)
    {
        $response = JsonToExcel::generate([
            'headers' => ['نام و نام خانوادگی', 'نام کاربری منحصر به فرد', 'تاریخ ثبت نام'],
            'title' => 'esml',
            'data' => $users
        ]);

        return $response['data']['link'];
    }

    private function pdfExport($users)
    {
        $snappy = new Pdf('wkhtmltopdf', [
            'encoding' => 'UTF-8',
            'page-size' => 'a4',
        ]);

        $filename = uniqid() . '.pdf';
        $snappy->generateFromHtml(
            view('pdf.users-report')->with(compact(('users')))->render(),
            'temp/' . $filename
        );

        return Storage::disk('public')->url($filename);
    }

    private function wordExport($users)
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $section = $phpWord->addSection();
        foreach ($users as $user) {
            $string = '';
            $string .= $user['username'] . ' با نام کاربری منحصر به فرد ';
            $string .=  $user['name'] . 'کاربر ';
            
            $section->addText(
                $string,
                array('name' => 'Tahoma', 'size' => 10)
            );
            
            $string = '';
            $string .=  ' در تاریخ ' . $user['created_at'];
            $string .= ' ثبت نام کرده است ';
            
            $section->addLine();
            $section->addText(
                $string,
                array('name' => 'Tahoma', 'size' => 10)
            );
            
            $section->addLine();
            $section->addLine();
            $section->addLine();
        }

        File::isDirectory('temp') or File::makeDirectory('temp', 0777, true, true);

        $filename = uniqid() . '.docx';
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('temp/' . $filename);

        return Storage::disk('public')->url($filename);
    }
}
