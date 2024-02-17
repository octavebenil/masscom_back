<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdvertisementResource;
use App\Models\Advertisement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiAdvertisementController extends Controller
{
    public function latest(Request $request): AdvertisementResource
    {
        $advertisement = Advertisement::query()
                                      ->notFinished()
                                      ->latest()
                                      ->firstOrfail();

        return new AdvertisementResource($advertisement);
    }

    public function syncAdvertisementFromLocal(Request $request): JsonResponse
    {
        $data = collect($request->all());

        $advertisements = $data->groupBy('advertisement_id')
                               ->map(function ($item) {
                                   return $item->count();
                               });

        $advertisements->each(function ($count, $advertisement_id) {
            $adv = Advertisement::findOrFail($advertisement_id);
            $adv->update(['current_views' => $adv->current_views + $count]);
        });

        return response()->json();
    }
}
