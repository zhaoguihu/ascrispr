<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class DBSNP extends Resource
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
            'chr' => $this->chr,
            'start' => $this->start,
            'end' => $this->end,
            'ref' => $this->ref,
            'alt' => $this->alt,
            'rs_id' => $this->rs_id,
            'ID' => $this->ID,

        ];
    }
}
