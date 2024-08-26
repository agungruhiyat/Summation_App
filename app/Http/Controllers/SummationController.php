<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoresummationRequest;
use App\Http\Requests\UpdatesummationRequest;
use App\Models\Summations;

class SummationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $summations = Summations::all();
         return response()->json(["success"=> true,"summation"=>$summations]);
        // try{
               
        // }catch(\Exception $e){

        // }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoresummationRequest $request)
    {
        $summations = Summations::create($request->validated());
        return response()->json(["status"=> "success","summation"=>$summations]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Summation $summation)
    {
        //
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatesummationRequest $request, $id)
    {
        Summations::where("id",$id)->update($request->validated());
        return response()->json(["success"=> true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        Summations::where("id",$id)->delete();
        return response()->json(["success"=>true]);
    }
}