<?php

namespace App\Http\Controllers\Public;

use App\DataTables\PublicSurveyDataTable;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Winner;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index(Company $company, PublicSurveyDataTable $dataTable)
    {
        $surveys = $company
            ->surveys;

        return $dataTable->render('public.surveys.index');

//        return view('admin.surveys.index', compact('company', 'surveys'));
    }


    public function gagnants(Request $request)
    {
          $gagnants = Winner::all();
        return view('public.surveys.gagnants', compact('gagnants'));
    }
}
