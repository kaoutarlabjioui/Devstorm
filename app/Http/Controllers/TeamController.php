<?php

namespace App\Http\Controllers;

use App\Models\Hackathon;
use App\Models\Team;
use App\Models\Theme;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class TeamController extends Controller
{
    public function registerTeam(Request $request)
    {

        Gate::allows('isParticipant');
        // if (!Gate::allows('isParticipant')) {
        //     return response()->json(['error' => 'You do not have permission to register a team.'], 403);
        // }
        try {
            $hackathon = Hackathon::where('');

            if(!$hackathon){

                return response()->json(['error' => 'Hackathon not found'], 404);
            }

            $user = JWTAuth::parseToken()->authenticate();
            // return ['user'=>$user];

            // if (!$user) {
            //     return response()->json(['error' => 'Unauthorized'], 401);
            // }

            $theme = Theme::where('name',$request->project_name)->first();
            // return ['message'=>$theme];
            if(!$theme){
                return response()->json(['error'=> 'theme not found'],404);
            }


            $request->validate([
                'name' => 'required|string|max:255',
                // 'github_link' => 'required|url|max:255',
            ]);


            $team = new Team();
            $team->name = $request->name;
            $team->github_link = $request->github_link;
            $team->hackathon()->associate($hackathon);
            $team->users()->associate($user);
            $team->project=$theme->name;
            $team->status = 'pending';
            $team->score = 0;
            $team->save();

            return response()->json($team, 201);

        } catch (JWTException $e) {
            return response()->json(['error' => 'Invalid token'], 400);
        }
    }


    public function approveTeam(Request $request)
    {
  if (!Gate::allows('isAdmin')) {
            return response()->json(['error' => 'You do not have permission to approve team'], 403);
        }
        $team = Team::where('name',$request->team_name)->first();
// return ["gdgu"=>$team];
        if(!$team){
            return response()->json(['error' => 'Team not found'], 404);
        }

        $team->status = 'approved';
        $team->save();

        return response()->json(['message' => 'Team approved successfully',
    'team'=>$team]);
    }

    public function rejectTeam(Request $request)
    {
        if (!Gate::allows('isAdmin')) {
            return response()->json(['error' => 'You do not have permission to approve team'], 403);
        }
        $team = Team::where('name',$request->team_name);
        $team->status = 'rejected';
        $team->save();

        return response()->json(['message' => 'Team rejected successfully']);
    }


    public function joinTeam(Request $request)
    {
        if (!Gate::allows('isParticipant')) {
            return response()->json(['error' => 'You do not have permission to register a team.'], 403);
        }
        $team = Team::where('name',$request->team_name);

        if(!$team){
            return response()->json(['error' => 'Team not found'], 404);
        }

        $user = JWTAuth::parseToken()->authenticate();

        try {
            $user->team()->associate($team);
            $user->save();
            return response()->json(['message' => $user->name . ' Successfully Joined Team ' . $team->name], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

}
