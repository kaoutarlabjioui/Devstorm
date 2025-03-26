<?php

namespace App\Http\Controllers;

use App\Models\Hackathon;
use App\Models\Rule;
use App\Models\Theme;
use Illuminate\Http\Request;

class HackathonController extends Controller
{

    public function index()
    {
        return response()->json(Hackathon::all());
    }

    public function store(Request $request)
    {

        $request->validate([
            'date' => 'required|date|date_format:Y-m-d',
            'place' => 'required',
            'themes' => 'required|array',
            'rules' => 'required|array',
        ]);


        $hackathon = new Hackathon();

        $hackathon->date = $request->date;
        $hackathon->place = $request->place;
        $hackathon->save();
        $themes = $request->input(['themes']);
        foreach ($themes as $name) {
            $theme = Theme::firstOrCreate(
                ['name' => $name],
                ['description' => 'Default description']
            );
            if ($theme) {
                $theme->hackathon()->associate($hackathon);
                $theme->save();
            }
            // $theme->hackathon()->associate($hackathon);
            // $theme->save();
        }

        $rules = $request->input(['rules']);
        foreach ($rules as $rule_name) {
            $rule = Rule::firstOrCreate(['name' => $rule_name]);;
            $rule->hackathons()->attach($hackathon);
        }
        // return response()->json([
        //     'message' => 'hackaton created successfully',
        //     'hackathon' => $hackathon
        // ]);

        // $hackathon->with(['rules'])->get();

        return response()->json([
            'message' => 'hackaton created successfully',
            'hackathon' => $hackathon->load('themes','rules')
        ]);
    }

    public function update(Request $request, Hackathon $hackathon)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'place' => 'required|string|max:255',

        ]);

        $hackathon->update($request->only('date', 'place'));

        if ($request->has('themes')) {
            $themes = $request->input(['themes']);
            foreach ($themes as $themeName) {
                $theme = Theme::firstOrCreate(['name' => $themeName]);
                $theme->hackathon()->associate($hackathon);
                $theme->save();
            }
        }


        if ($request->has('rules')) {
            $existingRules = $hackathon->rules->pluck('name');
            $newRules = collect($request->rules);
            $rulesRemove = $existingRules->diff($newRules);
            $rulesAdd = $newRules->diff($existingRules);
        }
    }

    public function destroy(Hackathon $hackathon)
    {


        $hackathon->delete();
        return response()->json(['message' => 'Hackathon deleted successfully']);
    }
}
