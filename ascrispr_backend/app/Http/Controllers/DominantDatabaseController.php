<?php

namespace App\Http\Controllers;

use App\Models\DominantDatabase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\AscrisprDiseases;
use App\Models\AscrisprGenes;

class DominantDatabaseController extends Controller
{
    public function getKeyListDominantDatabase(Request $request)
    {
        $data_diseases = AscrisprDiseases::select('diseases')->get();
        $data_genes = AscrisprGenes::select('gene_symbol')->get();
        $match = [];
        $match['diseasesDominantDatabase'] = $data_diseases;
        $match['genesDominantDatabase'] = $data_genes;
        return $match;
    }
    public function getInfoDominantDatabase(Request $request)
    {
        $inputValueDisease = $request->input('inputValueDisease');
        $inputValueGeneSymbol = $request->input('inputValueGeneSymbol');
        $inputValueRSID = $request->input('inputValueRSID');

        if ($inputValueDisease != '' && $inputValueGeneSymbol == '' && $inputValueRSID == ''){
            $data = DominantDatabase::where('disease', 'like', '%'.$inputValueDisease.'%')
                ->where('targetingStrand','!=', 'Reference')
                ->get();
        }
        if ($inputValueDisease != '' && $inputValueGeneSymbol == '' && $inputValueRSID != ''){
            $data = DominantDatabase::where('disease', 'like', '%'.$inputValueDisease.'%')
                ->where('rsid', $inputValueRSID)
                ->where('targetingStrand','!=', 'Reference')
                ->get();
        }
        if ($inputValueDisease != '' && $inputValueGeneSymbol != '' && $inputValueRSID == ''){
            $data = DominantDatabase::where('disease', 'like', '%'.$inputValueDisease.'%')
                ->where('geneSymbol', 'like', '%'.$inputValueGeneSymbol.'%')
                ->where('targetingStrand','!=', 'Reference')
                ->get();
        }
        if ($inputValueDisease == '' && $inputValueGeneSymbol != '' && $inputValueRSID != ''){
            $data = DominantDatabase::where('geneSymbol', 'like', '%'.$inputValueGeneSymbol.'%')
                ->where('rsid', $inputValueRSID)
                ->where('targetingStrand','!=', 'Reference')
                ->get();
        }
        if ($inputValueDisease == '' && $inputValueGeneSymbol != '' && $inputValueRSID == ''){
            $data = DominantDatabase::where('geneSymbol', 'like', '%'.$inputValueGeneSymbol.'%')
                ->where('targetingStrand','!=', 'Reference')
                ->get();
        }
        if ($inputValueDisease == '' && $inputValueGeneSymbol == '' && $inputValueRSID != ''){
            $data = DominantDatabase::where('rsid', $inputValueRSID)
                ->where('targetingStrand','!=', 'Reference')
                ->get();
        }
        if ($inputValueDisease != '' && $inputValueGeneSymbol != '' && $inputValueRSID != ''){
            $data = DominantDatabase::where('disease', 'like', '%'.$inputValueDisease.'%')
                ->where('geneSymbol', 'like', '%'.$inputValueGeneSymbol.'%')
                ->where('rsid', $inputValueRSID)
                ->where('targetingStrand','!=', 'Reference')
                ->get();
        }

        //        rs80358857
//        $data = DominantDatabase::
//            where('rsid', $inputValueRSID)
//            ->get();
        $match = [];
        $match['DominantDatabase'] = $data;
        return $match;
//        return $request;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DominantDatabase  $dominantDatabase
     * @return \Illuminate\Http\Response
     */
    public function show(DominantDatabase $dominantDatabase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DominantDatabase  $dominantDatabase
     * @return \Illuminate\Http\Response
     */
    public function edit(DominantDatabase $dominantDatabase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DominantDatabase  $dominantDatabase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DominantDatabase $dominantDatabase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DominantDatabase  $dominantDatabase
     * @return \Illuminate\Http\Response
     */
    public function destroy(DominantDatabase $dominantDatabase)
    {
        //
    }
}
