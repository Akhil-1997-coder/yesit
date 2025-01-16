<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    // Display Home Page with Form and Items
    public function index()
    {
        $items = DB::table('items')->get();
        return view('home', compact('items'));
    }

    // Store a New Item
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
        ]);

        DB::table('items')->insert([
            'name' => $request->name,
            'description' => $request->description,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('home')->with('success', 'Item added successfully.');
    }

    // Show Edit Form
    public function edit($id)
    {
        $item = DB::table('items')->where('id', $id)->first();
        return view('edit', compact('item'));
    }

    // Update Item
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
        ]);

        DB::table('items')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
                'description' => $request->description,
                'updated_at' => now(),
            ]);

        return redirect()->route('home')->with('success', 'Item updated successfully.');
    }

    // Delete Item
    public function destroy($id)
    {
        DB::table('items')->where('id', $id)->delete();
        return redirect()->route('home')->with('success', 'Item deleted successfully.');
    }
}
