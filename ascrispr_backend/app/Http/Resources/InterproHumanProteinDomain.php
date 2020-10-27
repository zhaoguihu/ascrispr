<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class InterproHumanProteinDomain extends Resource
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
            'Entry' => $this->Entry,
            'Domain_Name' => $this->Domain_Name,
            'Domain_accession' => $this->Domain_accession,
            'Domain_source' => $this->Domain_source,
            'Domain_start' => $this->Domain_start,
            'Domain_end' => $this->Domain_end,

        ];
    }
}
