<?php

namespace App\Http\Controllers;

use App\Models\JuryMember;
use App\Models\Note;
use App\Models\Team;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'value'=>'required|numeric',
            'jury_member_name'=>'required|string',
            'team_name'=>'required|string'
        ]);



         $note = new Note();
         $note->value = $request->input('value');
         $juryMember =JuryMember::where('username',$request->input('jury_member_name'))->first();
         if($juryMember){
            $note->juryMember()->associate($juryMember);
         }
         $team = Team::where('name', $request->input('team_name'))->first();
         if($team){
            $note->team()->associate($team);
         }

         $note->save();

         return response()->json(['note'=>$note]);
    }


    public function destroy(Request $request)
    {
        $request->validate([
            'value'=>'required|numeric',
            'jury_member_name'=>'required|string',
            'team_name'=>'required|string'
        ]);
            $teamName = $request->input('team_name');
            $juryMemberName = $request->input('jury_member_name');


            $team = Team::where('name', $teamName)->first();
            $juryMember = JuryMember::where('name', $juryMemberName)->first();


            if (!$team) {
                return response()->json(['message' => 'Équipe non trouvée.'], 404);
            }
            if (!$juryMember) {
                return response()->json(['message' => 'Membre du jury non trouvé.'], 404);
            }

            $note = Note::where('team_id', $team->id)
                          ->where('jury_member_id', $juryMember->id)
                          ->first();

            if ($note) {

                $note->delete();
                return response()->json(['message' => 'Note supprimée avec succès.']);
            }

            return response()->json(['message' => 'Note non trouvée.'], 404);
        }



public function update(Request $request){

    $request->validate([
        'value'=>'required|numeric',
        'jury_member_name'=>'required|string',
        'team_name'=>'required|string'
    ]);

        $teamName = $request->input('team_name');
        $juryMemberName = $request->input('jury_member_name');
        $newNote = $request->input('value');


        $team = Team::where('name', $teamName)->first();
        $juryMember = JuryMember::where('name', $juryMemberName)->first();


        if (!$team) {
            return response()->json(['message' => 'Équipe non trouvée.'], 404);
        }
        if (!$juryMember) {
            return response()->json(['message' => 'Membre du jury non trouvé.'], 404);
        }


        $note = Note::where('team_id', $team->id)
                      ->where('jury_member_id', $juryMember->id)
                      ->first();

        if ($note) {

            $note->value = $newNote;
            $note->save();

            return response()->json(['message' => 'Note mise à jour avec succès.']);
        }

        return response()->json(['message' => 'Note non trouvée.'], 404);
    }
}




