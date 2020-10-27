<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ValidatedDatabase extends Resource
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
            'disease_types' => $this->disease_types,
            'targeted_genes' => $this->targeted_genes,
            'protein' => $this->protein,
            'genetics' => $this->genetics,
            'locations' => $this->locations,
            'cas_nucleases' => $this->cas_nucleases,
            'pam' => $this->pam,
            'sequence_with_PAM' => $this->sequence_with_PAM,
            'targeting_specificity' => $this->targeting_specificity,
            'functional_outcomes' => $this->functional_outcomes,
            'pmid' => $this->pmid
        ];
    }
}
