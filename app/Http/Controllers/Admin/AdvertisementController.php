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
        Advertisement::create($request->validated());

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
        $advertisement->update($request->validated());

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
