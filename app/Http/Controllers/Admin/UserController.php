<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExportUser;
use App\Http\Controllers\Controller;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UserController extends Controller
{
    public function exportUsers(Request $request): BinaryFileResponse
    {
        return Excel::download(new ExportUser, 'users.xlsx');
    }

    public function exportUsersPdf(Request $request): \Illuminate\Http\Response
    {
        $users = User::query()->whereNotNull('survey_id')->get();

        $pdf = PDF::loadView('admin.pdf.survey_user', array('users' =>  $users))
            ->setPaper('a4', 'portrait');

        return $pdf->download('users-details.pdf');
    }
}
