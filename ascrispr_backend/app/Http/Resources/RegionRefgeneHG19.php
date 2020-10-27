<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class RegionRefgeneHG19 extends Resource
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
            'Gene_symbol' => $this->Gene_symbol,
            'ID' => $this->ID,

        ];
    }
}
