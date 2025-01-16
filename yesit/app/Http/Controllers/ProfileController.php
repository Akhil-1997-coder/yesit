<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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
            'phone' => 'required|regex:/^\+1-\(\d{3}\) \d{3}-\d{4}$/|regex:/^\+?\d{1,3}[-.\s]?(\(?\d{1,4}?\)?[-.\s]?)?\d{1,4}[-.\s]?\d{1,4}[-.\s]?\d{1,9}$/',
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

    // Import profiles from a CSV file
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        try {
            $path = $request->file('file')->store('uploads');
            Log::info('CSV file uploaded to: ' . $path);
            $filePath = storage_path('app/' . $path);

            // Log the full file path for debugging
            Log::info('Full file path: ' . $filePath);

            // Check if the file exists
            if (!file_exists($filePath)) {
                Log::error('CSV Import Error: File does not exist at path: ' . $filePath);
                return redirect()->route('profiles.index')->with('error', 'The uploaded file is missing. Please try again.');
            }

            $file = fopen($filePath, 'r');
            $header = fgetcsv($file); // Get the header row

            while ($row = fgetcsv($file)) {
                $data = array_combine($header, $row);

                // Additional validation for required fields
                if (empty($data['email']) || empty($data['name']) || empty($data['phone'])) {
                    Log::error('CSV Import Error: Missing required fields in row: ' . json_encode($data));
                    continue; // Skip this row
                }

                // Clean phone number
                $data['phone'] = preg_replace('/[^\d]/', '', $data['phone']);

                Profile::updateOrCreate(
                    ['email' => $data['email']],
                    [
                        'profile_image' => $data['profile_image'] ?? null,
                        'name' => $data['name'],
                        'phone' => $data['phone'],
                        'street_address' => $data['street_address'],
                        'city' => $data['city'],
                        'state' => $data['state'],
                        'country' => $data['country'],
                    ]
                );
            }
            fclose($file);
        } catch (\Exception $e) {
            Log::error('CSV Import Error: ' . $e->getMessage());
            return redirect()->route('profiles.index')->with('error', 'Error importing profiles. Please check the log for details.');
        }

        return redirect()->route('profiles.index')->with('success', 'Profiles imported successfully.');
    }

    // Export profiles to a CSV file
    public function export()
    {
        $profiles = Profile::all();
        $filename = 'profiles.csv';
        $handle = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Add CSV header
        fputcsv($handle, ['Profile Image', 'Name', 'Phone', 'Email', 'Street Address', 'City', 'State', 'Country']);

        foreach ($profiles as $profile) {
            fputcsv($handle, [
                $profile->profile_image,
                $profile->name,
                $profile->phone,
                $profile->email,
                $profile->street_address,
                $profile->city,
                $profile->state,
                $profile->country,
            ]);
        }
        fclose($handle);
        exit;
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
