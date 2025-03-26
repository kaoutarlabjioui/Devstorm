<?php

namespace App\Http\Controllers;

use App\Models\Jury;
use Illuminate\Http\Request;

class JuryController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'name'=>'required|string|max:225'
        ]);

        $jury = new Jury();
        $jury->name = $request->input('name');
        $jury->save();
        return response()->json([
            'message'=>'Jury created successfully',
            'Jury'=>$jury
        ]);
    }

    public function update(Request $request){
        $request->validate([
            'name'=>'required|string|max:225',
            'new_name'=>'required|string|max:225'
        ]);

       $jury = Jury::where('name',$request->input('name'))->first();

       if(!$jury){
        return response()->json([
            'message'=>'Jury non trouvé'
        ]);
       }
       $jury->name = $request->input('new_name');

            return response()->json([
                'message'=>'Jury updated successfully',
                'jury'=>$jury
            ]);
    }

  public function destroy(Request $request){
    $request->validate([
        'name'=>'required|string|max:225'

    ]);
  }
}
