<?php

namespace App\Http\Resources;

use App\Models\MenuItem;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        
        return [
            "id"=> $this->id,
            "name"=> $this->name,
            "url"=> $this->url,
            "parent_id"=> $this->parent_id,
            "created_at"=> $this->created_at,
            "updated_at"=> $this->updated_at,
            "children"=>MenuResource::collection($this->grouped[$this->id]??[])
        ];
    }
}
