<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use League\Csv\Writer;

class ProfileController extends Controller
{
    // Show all profiles
    public function index()
    {
        $profiles = Profile::all();
        return view('profiles.index', compact('profiles'));
    }

    // Show the profile creation form
    public function create()
    {
        return view('profiles.create');
    }

    // Store new profile
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_image' => 'image|mimes:jpg,jpeg|max:2048',
            'name' => 'required|max:25',
            'phone' => 'required|regex:/^\+1-\(\d{3}\) \d{3}-\d{4}$/',
            'email' => 'required|email|unique:profiles',
            'street_address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $profile = new Profile();
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('images', 'public');
            $profile->profile_image = $path;
        }
        $profile->name = $request->name;
        $profile->phone = $request->phone;
        $profile->email = $request->email;
        $profile->street_address = $request->street_address;
        $profile->city = $request->city;
        $profile->state = $request->state;
        $profile->country = $request->country;
        $profile->save();

        return redirect()->route('profiles.index')->with('success', 'Profile created successfully.');
    }

    // Show the edit form for a profile
    public function edit(Profile $profile)
    {
        return view('profiles.edit', compact('profile'));
    }

    // Update an existing profile
    public function update(Request $request, Profile $profile)
    {
        $validator = Validator::make($request->all(), [
            'profile_image' => 'image|mimes:jpg,jpeg|max:2048',
            'name' => 'required|max:25',
            'phone' => 'required|regex:/^\+1-\(\d{3}\) \d{3}-\d{4}$/',
            'email' => 'required|email|unique:profiles,email,' . $profile->id,
            'street_address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('images', 'public');
            $profile->profile_image = $path;
        }
        $profile->name = $request->name;
        $profile->phone = $request->phone;
        $profile->email = $request->email;
        $profile->street_address = $request->street_address;
        $profile->city = $request->city;
        $profile->state = $request->state;
        $profile->country = $request->country;
        $profile->save();

        return redirect()->route('profiles.index')->with('success', 'Profile updated successfully.');
    }

    // Delete a profile
    public function destroy(Profile $profile)
    {
        $profile->delete();
        return redirect()->route('profiles.index')->with('success', 'Profile deleted successfully.');
    }
}
