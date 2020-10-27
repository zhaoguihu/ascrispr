<?php

namespace App\Http\Controllers;

use App\Models\GeneInfoHS;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GeneInfoHSCollection;


class GeneInfoHSController extends Controller
{

    public function geneSymbol(Request $request)
    {
        $geneSymbol = $request->input('geneSymbol');
        $pageSize = $request->input('pageSize', 10);
        $data = GeneInfoHS::where('symbol', $geneSymbol)->paginate($pageSize);
        return new GeneInfoHSCollection($data);
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
     * @param  \App\Models\GeneInfoHS  $geneInfoHS
     * @return \Illuminate\Http\Response
     */
    public function show(GeneInfoHS $geneInfoHS)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GeneInfoHS  $geneInfoHS
     * @return \Illuminate\Http\Response
     */
    public function edit(GeneInfoHS $geneInfoHS)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GeneInfoHS  $geneInfoHS
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GeneInfoHS $geneInfoHS)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GeneInfoHS  $geneInfoHS
     * @return \Illuminate\Http\Response
     */
    public function destroy(GeneInfoHS $geneInfoHS)
    {
        //
    }
}
