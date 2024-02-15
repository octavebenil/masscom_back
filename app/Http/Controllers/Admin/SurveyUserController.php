<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SurveyUserDatatable;
use App\Http\Controllers\Controller;

class SurveyUserController extends Controller
{
    public function index(SurveyUserDatatable $dataTable)
    {
        return $dataTable->render('admin.survey_users.index');
    }
}
