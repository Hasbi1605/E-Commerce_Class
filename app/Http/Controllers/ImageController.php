<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

use App\Models\Image;

class ImageController extends Controller
{
    public function index(Request $request) {
        $itemuser = $request->user();
        $itemgambar = Image::where('user_id', $itemuser->id)->paginate(20);
        $data = array('title' => 'Data Image',
                     'itemgambar' => $itemgambar);
        return view('image.index', $data)->with('no', ($request->input('page', 1) - 1) * 20);
    }
    
    public function store(Request $request): RedirectResponse
    {
        // Validate the request
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);
        
        // Get authenticated user
        $user = $request->user();
        // Upload the file
        $image = $this->upload($request->file('image'), $user);
        
        return back()->with('success', 'Image berhasil diupload');
    }
    
    public function destroy(Request $request, $id) {
        $itemuser = $request->user();
        $itemgambar = Image::where('user_id', $itemuser->id)
                           ->where('id', $id)
                           ->first();
        
        if ($itemgambar) {
            \Storage::delete($itemgambar->url);
            $itemgambar->delete();
            return back()->with('success', 'Data berhasil dihapus');
        } else {
            return back()->with('error', 'Data tidak ditemukan');
        }
    }
    private function upload($fileupload, $user): Image
    {
        // Store file in storage/app/public/images
        $path = $fileupload->store('images', 'public');
        
        // Create database record
        return Image::create([
            'url' => $path,
            'user_id' => $user->id,
        ]);
    }
}

