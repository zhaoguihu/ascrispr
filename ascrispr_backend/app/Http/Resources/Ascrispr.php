<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Ascrispr extends Resource
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
            'bsid' => $this->bsid,
            'gene_id' => $this->gene_id,
            'score' => $this->score,
            'source' => $this->source,
            'source_accession' => $this->source_accession,
            'name' => $this->name,
            'type_of_biosystem' => $this->type_of_biosystem,
            'taxonomic_scope' => $this->taxonomic_scope,
            'tax_id' => $this->tax_id,
            'description' => $this->description,
        ];
    }
}
