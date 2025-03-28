<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HackathonController;
use App\Http\Controllers\JuryMemberController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\TeamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// user
Route::post('/login',  [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware("jwtAuth");
Route::post('/refresh', [AuthController::class, 'refresh'])->middleware("jwtAuth");
Route::get('/user-profile', [AuthController::class, 'getUser'])->middleware("jwtAuth");

Route::post('/storerule', [RuleController::class, 'store']);
Route::delete('/deleterule', [RuleController::class, 'destroy']);
Route::post('/jurymembers',[JuryMemberController::class,'store'])->middleware(['jwtAuth','can:isAdmin']);
Route::post('/hackathon',[HackathonController::class , 'store']);


Route::post('/team/{id}/registerteams',[TeamController::class,'registerTeam'])->middleware(["jwtAuth",'can:isParticipant']);
Route::post('/approveteams',[TeamController::class,'approveTeam'])->middleware(["jwtAuth",'can:isAdmin']);
Route::post('/addroles',[RoleController::class , 'store'])->middleware(['jwtAuth','can:isAdmin']);
Route::post('/jury/login', [JuryMemberController::class, 'login']);
