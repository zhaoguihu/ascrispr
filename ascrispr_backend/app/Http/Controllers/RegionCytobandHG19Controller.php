<?php

namespace App\Http\Controllers;

use App\Models\RegionCytobandHG19;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\RegionCytobandHG19Collection;

use App\Models\RegionRefgeneHG19;
use App\Http\Resources\RegionRefgeneHG19Collection;

class RegionCytobandHG19Controller extends Controller
{
    public function cytoband(Request $request)
    {
        $cytoband = $request->input('cytoband');
        $terms = preg_split("/,/", $cytoband);
        foreach ($terms as $row) {
            if (strlen($row) > 0) {
                $lines[] = trim($row);
            }
        }
        foreach ($lines as $row) {
            if (strpos($row, 'p') !== false) {
                $tmp = explode("p", $row);
                $chr = $tmp[0];
                $pos_info = "p" . $tmp[1];
            } else {
                $tmp = explode("q", $row);
                $chr = $tmp[0];
                $pos_info = "q" . $tmp[1];
            }

            $data_RegionCytobandHG19 = RegionCytobandHG19::where('Chr', 'chr'.$chr)->where('pos_info', 'like', $pos_info . '%')->paginate();

            if (isset($data_RegionCytobandHG19[0]['Chr'])) {
                $chr = $data_RegionCytobandHG19[0]['Chr'];
                $start = $data_RegionCytobandHG19[0]['Start'];
                $end = $data_RegionCytobandHG19[0]['End'];

                $geneRegion[] = $chr . '-' . $start . '-' . $end;
            }
        }
        return $this->region($geneRegion);
    }

    public function region($geneRegion)
    {
        $region = $geneRegion;
        $pageSize = isset($pageSize) && $pageSize?$pageSize:10000;

        foreach ($region as $row) {
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
        $data = RegionRefgeneHG19::select('Gene_symbol')->whereRaw($conditionStr)->groupBy('Gene_symbol')->paginate($pageSize);
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
     * @param  \App\Models\RegionCytobandHG19  $regionCytobandHG19
     * @return \Illuminate\Http\Response
     */
    public function show(RegionCytobandHG19 $regionCytobandHG19)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RegionCytobandHG19  $regionCytobandHG19
     * @return \Illuminate\Http\Response
     */
    public function edit(RegionCytobandHG19 $regionCytobandHG19)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RegionCytobandHG19  $regionCytobandHG19
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RegionCytobandHG19 $regionCytobandHG19)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RegionCytobandHG19  $regionCytobandHG19
     * @return \Illuminate\Http\Response
     */
    public function destroy(RegionCytobandHG19 $regionCytobandHG19)
    {
        //
    }
}
