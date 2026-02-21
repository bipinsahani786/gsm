<?php

namespace App\Http\Controllers\Backend\Admins\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Popup;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class AdminPopupController extends Controller
{
    public function index()
    {
        $popups = Popup::latest()->get();
        return view('backend.admins.pages.popups.index', compact('popups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Update existing popup
        Popup::where('status', 1)->update(['status' => 0]);

        $data = $request->only('title', 'description', 'link');
        $data['status'] = 1;

        if ($request->hasFile('image')) {
            $imageName = time() . '_popup.' . $request->image->extension();
            $request->image->move(public_path('uploads/popups'), $imageName);
            $data['image'] = 'uploads/popups/' . $imageName;
        }

        Popup::create($data);
        return back()->with('success', 'New Home Screen Popup activated!');
    }

    public function destroy($id)
    {
        $popup = Popup::findOrFail($id);
        if (File::exists(public_path($popup->image))) {
            File::delete(public_path($popup->image));
        }
        $popup->delete();
        return back()->with('success', 'Popup Deleted!');
    }
}
