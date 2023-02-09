<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\NomineeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoterController;
use App\Models\Election;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/electionList',[ElectionController::class,'index']);
Route::middleware('auth:api')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
 
    Route::get('/delete-this-user',[UserController::class,'deleteThisUser']);
    Route::get('/election/{id}/nominee', [NomineeController::class,'listNominee']);
    Route::get('/election-open', [ElectionController::class,'eleOpenList']);
    Route::get('/election-close', [ElectionController::class,'eleCloseList']);
    Route::get('/can-vote/{id}',[VoterController::class,'canVote']);
    Route::post('/vote',[VoterController::class,'voting']);
    Route::get('/result/{id}',[VoterController::class,'showResult']);
    Route::get('/is-admin', [AdminController::class,'index']);
    Route::get('/user-image', [UserController::class,'userImagePath']);
    Route::post('/user-image', [UserController::class,'userImageUpload']);

    Route::group(['middleware' => 'isAdmin'], function () {

        Route::post('/create-election', [ElectionController::class,'createElection']);
        Route::get('/election-all', [ElectionController::class,'eleList']);
        Route::post('/election/{id}/create-nominee', [NomineeController::class,'createNominee']);
        Route::post('/uploadImage/{id}', [NomineeController::class,'uploadImage']);
        Route::get('/delete-election/{id}', [ElectionController::class,'deleteElection']);
        Route::get('/delete-nominee/{id}', [NomineeController::class,'deleteNominee']);
        Route::post('/create-account',[UserController::class,'createAccount']);
        Route::get('/list-account',[UserController::class,'listAccount']);
        Route::get('/delete-account/{id}',[UserController::class,'deleteAccount']);
        Route::get('/start-election/{id}', [ElectionController::class,'startElection']);
        Route::get('/stop-election/{id}', [ElectionController::class,'stopElection']);
    });
    // // Route::post('/posts', 'PostController@store');
    // Route::get('/deleteThisUser',[UserController::class,'deleteThisUser']);
    // Route::get('/election/{ele}/nominee', [NomineeController::class,'listNominee']);
    // Route::get('/ElectionOpen', [ElectionController::class,'eleOpenList']);
    // Route::get('/ElectionClose', [ElectionController::class,'eleCloseList']);
    // Route::get('/canVote/{ele}',[VoterController::class,'canVote']);
    // Route::post('/vote',[VoterController::class,'voting']);
    // Route::get('/result/{ele}',[VoterController::class,'showResult']);
    // Route::get('/isAdmin', [AdminController::class,'index']);
    // Route::get('/userImage', [UserController::class,'userImagePath']);
    // Route::post('/userImage', [UserController::class,'userImageUpload']);

    // Route::group(['middleware' => 'isAdmin'], function () {

    //     Route::post('/createElection', [ElectionController::class,'createElection']);
    //     Route::get('/ElectionAll', [ElectionController::class,'eleList']);
    //     Route::post('/election/{ele}/createNominee', [NomineeController::class,'createNominee']);
    //     Route::post('/uploadImage/{nom}', [NomineeController::class,'uploadImage']);
    //     Route::get('/deleteElection/{ele}', [ElectionController::class,'deleteElection']);
    //     Route::get('/deleteNominee/{nom}', [NomineeController::class,'deleteNominee']);
    //     Route::post('/createAccount',[UserController::class,'createAccount']);
    //     Route::get('/listAccount',[UserController::class,'listAccount']);
    //     Route::get('/deleteAccount/{id}',[UserController::class,'deleteAccount']);
    //     Route::get('/startElection/{id}', [ElectionController::class,'startElection']);
    //     Route::get('/stopElection/{id}', [ElectionController::class,'stopElection']);
    // });
});
