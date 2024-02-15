<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ResultDataTable;
use App\Http\Controllers\Controller;

class ResultController extends Controller
{
    public function index(ResultDataTable $dataTable)
    {
        return $dataTable->render('admin.questions.index');
    }
}
