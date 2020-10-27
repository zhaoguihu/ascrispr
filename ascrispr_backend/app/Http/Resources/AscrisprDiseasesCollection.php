<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AscrisprDiseasesCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'status' => 'success',
            'status_code' => '200',
            'total' => $this->total(),
            'meta' => [
                'total' => $this->total(),
            ]
        ];
    }
}
