<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'name'=>'required|string'

        ]);

$rule = new Rule();
 $rule->name = $request->name;

 $rule->save();


 return response()->json(["rule"=>$rule]);

    }

public function destroy(Request $request)
{
    $rule = Rule::where('name', $request->input('name'))->first();
    if($rule){
        $rule->delete();
        return response()->json(['message' => 'Rule supprimée avec succès.']);
    }
    return response()->json(['message' => 'Rule non trouvée.']);
}


public function update(Request $request){

    $request->validate([
        'name'=>'required|string',
        'new_name'=>'required|string'
    ]);


    $rule = Rule::where('name' , $request->input('name'))->first();

    if (!$rule) {
        return response()->json(['message' => 'Rule non trouvée.'], 404);
    }

    $rule->name = $request->input('new_name');
    $rule->save();

    return response()->json([
        'message'=>'Rule mise à jour avec succès' ,
        'Rule'=>$rule
    ]);


}

}
