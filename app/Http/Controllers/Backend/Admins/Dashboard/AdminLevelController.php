<?php

namespace App\Http\Controllers\Backend\Admins\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;

class AdminLevelController extends Controller
{
    public function index()
    {
        $levels = Level::orderBy('min_deposit', 'asc')->get();
        return view('backend.admins.pages.levels', compact('levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'min_deposit' => 'required|numeric',
            'daily_limit' => 'required|integer',
            'rate' => 'required|numeric',
        ]);

        $data = $request->all();

        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $filename = time() . '_' . $file->getClientOriginalName();

         
            $file->move(public_path('uploads/levels'), $filename);

         
            $data['icon'] = 'uploads/levels/' . $filename;
        }

        Level::create($data);
        return back()->with('success', 'New VIP Level Created!');
    }

    public function destroy($id)
    {
        Level::destroy($id);
        return back()->with('success', 'Level Deleted!');
    }
}
