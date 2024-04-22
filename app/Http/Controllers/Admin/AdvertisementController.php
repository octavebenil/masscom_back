<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\AdvertisementDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdvertisementRequest;
use App\Http\Requests\UpdateAdvertisementRequest;
use App\Models\Advertisement;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AdvertisementDataTable $dataTable)
    {
        return $dataTable->render('admin.advertisement.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdvertisementRequest $request): RedirectResponse
    {
        $file_link = "";

        $request->validate([
            'file' => 'required|mimes:mp4,mpeg4,avi,mkv',
        ]);

        $file_link = $request->file('file')->store('advertisements');

        $request->request->add(['link' => $file_link]);
        $ads  = Advertisement::create([
            "name" => $request->get("name"),
            "max_views" => $request->get("max_views"),
            "link" => $request->get("link"),
        ]);

        // $ads->name = $request->get("name");
        // $ads->max_views = $request->get("max_views");
        // $ads->link = $file_link;

        // $ads->save();

        return redirect()->route('admin.advertisement.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('admin.advertisement.create');
        }
        catch (Exception $e) {
            Log::error($e);
            return back()->with(['error' => __('admin.default_error_message')]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Advertisement $advertisement): View
    {
        return view('admin.advertisement.view', compact('advertisement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdvertisementRequest $request, Advertisement $advertisement): RedirectResponse
    {
        $file_link = "";

        $request->validate([
            'file' => 'required|mimes:mp4,mpeg4,avi,mkv',
        ]);

        $file_link = $request->file('file')->store('advertisements');

        $request->request->add(['link' => $file_link]);
        $ads  = Advertisement::update([
            "name" => $request->get("name"),
            "max_views" => $request->get("max_views"),
            "link" => $request->get("link"),
        ]);

        return redirect()->route('admin.advertisement.list');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Advertisement $advertisement): JsonResponse
    {
        $advertisement->delete();
        return response()->json("");
    }
}
