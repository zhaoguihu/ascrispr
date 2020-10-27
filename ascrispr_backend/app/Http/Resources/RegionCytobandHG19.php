<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class RegionCytobandHG19 extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'Chr' => $this->Chr,
            'Start' => $this->Start,
            'End' => $this->End,
            'pos_info' => $this->pos_info,
            'ID' => $this->ID,

        ];
    }
}
