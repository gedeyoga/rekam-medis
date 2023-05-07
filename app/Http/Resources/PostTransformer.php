<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PostTransformer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return  [
            'id' => $this->id,
            'title' => $this->title,
            'article' => $this->article,
            'short_text' => Str::limit($this->article, 90, '...'),
            'slug' => $this->slug,
            'created_by' => $this->created_by,
            'writer' => new UserTransformer($this->user_created),
            'status' => $this->status,
            'category' => CategoryTransformer::collection($this->category),
            'updated_at' => date('Y-m-d H:i:s' , strtotime($this->updated_at)),
            'created_at' => date('Y-m-d H:i:s', strtotime($this->created_at)),
            'thumbnail' => $this->getFirstMedia('thumbnail_post') ? $this->getFirstMedia('thumbnail_post') : null,
            'read_time' => $this->present()->readTime,
        ];
    }
}
