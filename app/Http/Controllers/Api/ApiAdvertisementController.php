<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdvertisementResource;
use App\Models\Advertisement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JsonException;

class ApiAdvertisementController extends Controller
{
    public function latest(Request $request): AdvertisementResource
    {
        $advertisement = Advertisement::query()
                                      ->notFinished()
//                                      ->latest()
                                      ->firstOrfail();

        return new AdvertisementResource($advertisement);
    }

    public function countAdvertisement(Request $request): JsonResponse{
        $advertisement = Advertisement::query()
            ->notFinished()
            ->firstOrfail();

        $advertisement?->update(['current_views' => $advertisement?->current_views + 1]);

        return response()->json();
    }

    /**
     * @throws JsonException
     */
    public function syncAdvertisementFromLocal(Request $request): JsonResponse
    {
        $data = collect(json_decode($request->get('body'), false, 512, JSON_THROW_ON_ERROR));

        $advertisements = $data->groupBy('link')
                               ->map(function ($item) {
                                   return $item->count();
                               });

        $advertisements->each(function ($count, $advertisement_link) {
            $adv = Advertisement::whereLink($advertisement_link)->first();
            $adv?->update(['current_views' => $adv?->current_views + $count]);
        });

        return response()->json();
    }
}
