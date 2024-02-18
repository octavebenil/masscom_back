<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiVideoController extends Controller
{
    public function syncVideosFromLocal(Request $request): JsonResponse
    {
        $data = collect($request->all());

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
