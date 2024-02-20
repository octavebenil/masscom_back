<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\VideoDataTable;
use App\Http\Controllers\Controller;

class VideoController extends Controller
{
    public function index(VideoDataTable $dataTable)
    {
        return $dataTable->render('admin.videos.index');
    }
}
