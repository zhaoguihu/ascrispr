<?php

namespace App\Http\Controllers;

use App\Models\RegionRefgeneHG19;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\RegionRefgeneHG19Collection;

class RegionRefgeneHG19Controller extends Controller
{

    public function region(Request $request)
    {
        $region = $request->input('region');
        $pageSize = $request->input('pageSize', 10000);
        $pageSize = isset($pageSize) && $pageSize?$pageSize:10000;

        $terms = preg_split("/,/", $region);
        foreach ($terms as $row) {
            $lines[] = explode("-", trim($row));
        }
        $tmpStr = '';
        foreach ($lines as $row){
            $tmpChr = substr($row[0],3, strlen($row[0]));
            $tmpStr = $tmpStr."((Start between $row[1] and $row[2])"
                ."or (Start < $row[1] and $row[2]< End ) "
                ."or (End between $row[1] and $row[2] ) "
                ." and Chr = \"chr$tmpChr\")"." or ";
        }
        $conditionStr = substr($tmpStr, 0, strlen($tmpStr) - 4);
//        return $conditionStr;
        $data = RegionRefgeneHG19::select('Gene_symbol')->whereRaw($conditionStr)->groupBy('Gene_symbol')->paginate($pageSize);

//        return $conditionStr;
        return new RegionRefgeneHG19Collection($data);

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
     * @param  \App\Models\RegionRefgeneHG19  $regionRefgeneHG19
     * @return \Illuminate\Http\Response
     */
    public function show(RegionRefgeneHG19 $regionRefgeneHG19)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RegionRefgeneHG19  $regionRefgeneHG19
     * @return \Illuminate\Http\Response
     */
    public function edit(RegionRefgeneHG19 $regionRefgeneHG19)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RegionRefgeneHG19  $regionRefgeneHG19
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RegionRefgeneHG19 $regionRefgeneHG19)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RegionRefgeneHG19  $regionRefgeneHG19
     * @return \Illuminate\Http\Response
     */
    public function destroy(RegionRefgeneHG19 $regionRefgeneHG19)
    {
        //
    }
}
