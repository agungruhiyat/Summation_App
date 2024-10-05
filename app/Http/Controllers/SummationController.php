<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoresummationRequest;
use App\Http\Requests\UpdatesummationRequest;
use App\Models\Summations;
use Illuminate\Http\Request;

class SummationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Summations::query();
    
    if ($request->has('search')) {
    $search = $request->input('search');
    $query->where('processed_by', 'like', "%{$search}%");
    }

    $summations = Summations::paginate(2);
    return response()->json(["success"=> true,"summations"=>$summations]);
    
    
    // Tampilkan error untuk debugging
    
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
        return response()->json(["status"=> "success","summations"=>$summations]);
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