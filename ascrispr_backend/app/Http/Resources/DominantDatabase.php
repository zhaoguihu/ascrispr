<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class DominantDatabase extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        col_name = ['rsid', 'geneSymbol', 'location1', 'Ref', 'Alt', 'pathogenic', 'seq',
//            'count', 'spacerSequence', 'PAM', 'PAM_show', 'targetingStrand', 'direction', 'GCcontent',
//            'self_complementarity', 'N1', 'N2', 'outStart',
//            'spacerStart', 'spacerEnd', 'PAMStart', 'PAMEnd', 'PAMLen', 'MUTposStart', 'MUTposEnd',
//            'inputSeq', 'str_print', 'inputSeq_type']
        return [
            'rsid' => $this->rsid,
            'geneSymbol' => $this->geneSymbol,
            'location' => $this->location,
            'Ref' => $this->Ref,
            'Alt' => $this->Alt,
            'pathogenic' => $this->pathogenic,
            'seq' => $this->seq,
            'disease' => $this->disease,
            'count' => $this->count,
            'spacerSequence' => $this->spacerSequence,
            'PAM' => $this->PAM,
            'PAM_show' => $this->PAM_show,
            'targetingStrand' => $this->targetingStrand,
            'direction' => $this->direction,
            'GCcontent' => $this->GCcontent,
            'self_complementarity' => $this->self_complementarity,
            'N1' => $this->N1,
            'N2' => $this->N2,
            'outStart' => $this->outStart,
            'spacerStart' => $this->spacerStart,
            'spacerEnd' => $this->spacerEnd,
            'PAMStart' => $this->PAMStart,
            'PAMEnd' => $this->PAMEnd,
            'PAMLen' => $this->PAMLen,
            'MUTposStart' => $this->MUTposStart,
            'MUTposEnd' => $this->MUTposEnd,
            'inputSeq' => $this->inputSeq,
            'str_print' => $this->str_print,
            'inputSeq_type' => $this->inputSeq_type
        ];
    }
}
