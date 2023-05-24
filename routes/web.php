<?php

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Common Resource Routes:
// index - Show all listings
// show - Show single listing
// create - Show form to create new listing
// store - Store new listing
// edit - Show form to edit listing
// update - Update listing
// destroy - Delete listing  


// All listings

Route::get('/', [ListingController::class, 'index']);

//Store listing data

Route::post('/listings', [ListingController::class, 'store']);


// Show create form

Route::get('/listings/create', [ListingController::class, 'create']);



//Show edit form

Route::get('/listings/{listing}/edit', [ListingController::class, 'edit']);

//Edit submit to update

Route::put('/listings/{listing}', [ListingController::class, 'update']);


//Delete listing

Route::delete('/listings/{listing}', [ListingController::class, 'delete']);


//Single listing

Route::get('/listings/{listing}', [ListingController::class, 'show']);


// Route::get('/hello', function() {
//     return response('<h1>Hello word</h1>', 200)
//     ->header('Content-Type', 'text/plain') // changing content type in header
//     ->header('foo', 'bar'); // adding some values to header
// });

// Route::get('/posts/{id}', function($id){
//     return response('Post ' . $id);
// })->where('id', '[0-9]+'); // allowing only numbers in query params

// Route::get('/search', function(Request $request){
//  return $request->name . ' ' . $request->city;
// });