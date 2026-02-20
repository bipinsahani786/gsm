<?php

namespace App\Http\Controllers\Backend\Admins\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Position;
use Illuminate\Http\Request;

class AdminPositionController extends Controller
{
    public function index()
    {

        $levels = Level::where('status', 1)->get();
        $positions = Position::orderBy('salary', 'asc')->get();

        return view('backend.admins.pages.positions', compact('positions', 'levels'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'salary' => 'required|numeric',
            'team_condition' => 'required',
            'required_level_id' => 'nullable|integer', 
            'required_directs' => 'required|integer',  
            'required_members' => 'required|integer', 
        ]);

        $data = $request->all();
        $data['status'] = true;

        if ($request->hasFile('icon')) {
            $filename = time() . '_' . $request->file('icon')->getClientOriginalName();
            $request->file('icon')->move(public_path('uploads/positions'), $filename);
            $data['icon'] = 'uploads/positions/' . $filename;
        }

        Position::create($data);
        return back()->with('success', 'New Flexible Position Added!');
    }

    // Status Toggle
    public function toggleStatus($id)
    {
        $pos = Position::findOrFail($id);
        $pos->status = !$pos->status;
        $pos->save();
        return back()->with('success', 'Position Status Updated!');
    }

    public function destroy($id)
    {
        Position::destroy($id);
        return back()->with('success', 'Position Deleted');
    }
}
