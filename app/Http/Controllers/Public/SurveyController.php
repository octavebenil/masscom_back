<?php

namespace App\Http\Controllers\Public;

use App\DataTables\PublicSurveyDataTable;
use App\Http\Controllers\Controller;
use App\Models\Company;

class SurveyController extends Controller
{
    public function index(Company $company, PublicSurveyDataTable $dataTable)
    {
        $surveys = $company
            ->surveys;

        return $dataTable->render('admin.surveys.index');

//        return view('admin.surveys.index', compact('company', 'surveys'));
    }
}
