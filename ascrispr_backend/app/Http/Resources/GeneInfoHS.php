<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class GeneInfoHS extends Resource
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
            'gene_id' => $this->gene_id,
            'symbol' => $this->symbol,
            'locustag' => $this->locustag,
            'synonyms' => $this->synonyms,
            'dbxrefs' => $this->dbxrefs,
            'chromosome' => $this->chromosome,
            'map_location' => $this->map_location,
            'description' => $this->description,
            'type_of_gene' => $this->type_of_gene,
            'symbol_from_nomenclature_authority' => $this->symbol_from_nomenclature_authority,
            'full_name_from_nomenclature_authority' => $this->full_name_from_nomenclature_authority,
            'nomenclature_status' => $this->nomenclature_status,
            'other_designations' => $this->other_designations,
            'modification_date' => $this->modification_date,
            'feature_type' => $this->feature_type,
            'summary' => $this->summary,
            'uniprot_entry' => $this->uniprot_entry,

        ];
    }
}
