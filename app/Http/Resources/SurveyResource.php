<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class SurveyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $participantsCount = $this->answers()->distinct('user_id')->count();
        return [
            "id" => $this->id,
            "company_name" => $this->company->name,
            "survey_title" => $this->title,
            "video" => Storage::url('uploads/companies/logo/video-1.mp4'),
            "company_logo" => $this->company->logo ? Storage::url($this->company->logo) : null,
            "company_bg" => $this->company->company_image ? Storage::url($this->company->company_image) : null,
            "questions" => QuestionResource::collection($this->questions),
            "max_part" => max($this->max_participants - $participantsCount, 0),
            "is_closed" => (boolean) $this->is_closed
        ];
    }
}
