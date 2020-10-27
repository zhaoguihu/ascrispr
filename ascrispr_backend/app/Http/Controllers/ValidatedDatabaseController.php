<?php

namespace App\Http\Controllers;

use App\Models\ValidatedDatabase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ValidatedDatabaseController extends Controller
{

    public function getInfoValidatedDatabase()
    {
        $data = ValidatedDatabase::select('*')->get();
        $match = [];
        $match['ValidatedDatabase'] = $data;
        return $match;
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
     * @param  \App\Models\ValidatedDatabase  $validatedDatabase
     * @return \Illuminate\Http\Response
     */
    public function show(ValidatedDatabase $validatedDatabase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ValidatedDatabase  $validatedDatabase
     * @return \Illuminate\Http\Response
     */
    public function edit(ValidatedDatabase $validatedDatabase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ValidatedDatabase  $validatedDatabase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ValidatedDatabase $validatedDatabase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ValidatedDatabase  $validatedDatabase
     * @return \Illuminate\Http\Response
     */
    public function destroy(ValidatedDatabase $validatedDatabase)
    {
        //
    }
}
