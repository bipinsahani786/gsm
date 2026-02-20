<?php

namespace App\Http\Controllers\Backend\Admins\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\IncomeMethod;
use Illuminate\Support\Facades\File;
class AdminGuideController extends Controller
{
    public function index()
    {
        $banners = Banner::latest()->get();
        $methods = IncomeMethod::latest()->get();
        return view('backend.admins.pages.guides', compact('banners', 'methods'));
    }

    // ==========================================
    // BANNERS LOGIC
    // ==========================================
    public function storeBanner(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '_banner.' . $request->image->extension();
            $request->image->move(public_path('uploads/banners'), $imageName);
            
            Banner::create([
                'image' => 'uploads/banners/' . $imageName,
                'status' => 1
            ]);
        }

        return back()->with('success', 'Banner Uploaded Successfully!');
    }

    public function destroyBanner($id)
    {
        $banner = Banner::findOrFail($id);
        
        // Delete image from server
        if (File::exists(public_path($banner->image))) {
            File::delete(public_path($banner->image));
        }
        
        $banner->delete();
        return back()->with('success', 'Banner Deleted!');
    }

    // ==========================================
    // INCOME METHODS (PDF GUIDES) LOGIC
    // ==========================================
    public function storeMethod(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'pdf_file' => 'required|mimes:pdf|max:10000', // Max 10MB PDF allowed
        ]);

        $data = [
            'title' => $request->title,
            'status' => 1
        ];

        // Thumbnail Upload
        if ($request->hasFile('thumbnail')) {
            $thumbName = time() . '_thumb.' . $request->thumbnail->extension();
            $request->thumbnail->move(public_path('uploads/guides'), $thumbName);
            $data['thumbnail'] = 'uploads/guides/' . $thumbName;
        }

        // PDF Upload
        if ($request->hasFile('pdf_file')) {
            $pdfName = time() . '_guide.' . $request->pdf_file->extension();
            $request->pdf_file->move(public_path('uploads/guides'), $pdfName);
            $data['pdf_file'] = 'uploads/guides/' . $pdfName;
        }

        IncomeMethod::create($data);
        return back()->with('success', 'Earning Guide Added Successfully!');
    }

    public function destroyMethod($id)
    {
        $method = IncomeMethod::findOrFail($id);
        
        // Delete Thumbnail from server
        if (File::exists(public_path($method->thumbnail))) {
            File::delete(public_path($method->thumbnail));
        }
        
        // Delete PDF from server
        if (File::exists(public_path($method->pdf_file))) {
            File::delete(public_path($method->pdf_file));
        }
        
        $method->delete();
        return back()->with('success', 'Guide Deleted Successfully!');
    }
}
