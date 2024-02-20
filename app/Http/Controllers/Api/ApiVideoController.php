<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JsonException;

class ApiVideoController extends Controller
{
    /**
     * @throws JsonException
     */
    public function syncVideosFromLocal(Request $request): JsonResponse
    {
        $body = $request->get('body');
        $data = collect(json_decode($body, false, 512, JSON_THROW_ON_ERROR));

        $videos = $data->groupBy('name')
                       ->map(function ($item) {
                           return $item->count();
                       });

        $videos->each(function ($count, $originalName) {
            $name = removeVideoNamePrefixes($originalName);

            $videoModel = Video::query()
                               ->whereName($name)
                               ->first();

            if ($videoModel) {
                $videoModel->update(['views' => $videoModel->views + $count]);
            } else {
                Video::create([
                    'name'      => $name,
                    'link_path' => $originalName,
                    'views'     => $count
                ]);
            }
        });

        return response()->json();
    }
}
