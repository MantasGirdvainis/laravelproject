<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    //Show all lisntings and can filter
    public function index()
    {
        return view('listings.index', [
            'listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(6)
        ]);
    }

    //Show single listing
    public function show(Listing $listing)
    {
        return view('listings.show', [
            'listing' => $listing
        ]);
    }

    // show form

    public function create()
    {
        return view('listings.create');
    }

    // store listing data in database

    public function store(Request $request)
    {

        // validuojam formos duonenys ir priskuriam kintamajam
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);


        //issaugom logo i public folderi
        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        //sukuria user_id, prie kiekvieno posto. taip atskiriam userio listingus
        $formFields['user_id'] = auth()->id();


        // irasom i duomenu baze Listing
        Listing::create($formFields);

        return redirect('/')->with('message', 'Listing created successfully!');
    }

    // Edit listing


    public function edit(Listing $listing)
    {

        return view('listings.edit', [
            'listing' => $listing
        ]);
    }



    // Update listing data

    public function update(Request $request, Listing $listing)
    {

        //Make sure logged in user is owner

        if ($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }

        // validation
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required'],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        //update listing
        $listing->update($formFields);

        return  back()->with('message', 'Listing updated successfully!');
    }


    // Delete listing

    public function delete(Listing $listing)
    {
        //Make sure logged in user is owner
        if ($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }

        //delete
        $listing->delete();
        return redirect('/')->with('message', 'Listing deleted!');
    }


    // Manage Listings
    public function manage()
    {
        return view('listings.manage', ['listings' => auth()->user()->listings()->get()]);
    }
}
