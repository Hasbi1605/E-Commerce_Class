<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Kategori;
use App\Models\Image;


class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itemkategori = Kategori::orderBy('created_at', 'desc')->paginate(20);
        $data = array('title' => 'Kategori Produk',
                    'itemkategori' => $itemkategori);
        return view('kategori.index', $data)->with('no', ($request->input('page', 1) - 1) * 20);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array('title' => 'Form Kategori');
        return view('kategori.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'kode_kategori' => 'required|unique:kategoris',
            'nama_kategori'=>'required',
            'slug_kategori' => 'required',
            'deskripsi_kategori' => 'required',
        ]);
        $itemuser = $request->user();
        $inputan = $request->all();
        $inputan['user_id'] = $itemuser->id;
        $inputan['slug_kategori'] = \Str::slug($request->slug_kategori);
        $inputan['status'] = 'publish';
        $itemkategori = Kategori::create($inputan);
        return redirect()->route('kategori.index')->with('success', 'Data kategori berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $itemkategori = Kategori::findOrFail($id);
        $data = array('title' => 'Form Edit Kategori',
                    'itemkategori' => $itemkategori);
        return view('kategori.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama_kategori'=>'required',
            'slug_kategori' => 'required',
            'deskripsi_kategori' => 'required',
        ]);
        $itemkategori = Kategori::findOrFail($id);
        
        $slug = \Str::slug($request->slug_kategori);
        
        $validasislug = Kategori::where('id', '!=', $id)
                                ->where('slug_kategori', $slug)
                                ->first();
        if ($validasislug) {
            return back()->with('error', 'Slug sudah ada, coba yang lain');
        } else {
            $inputan = $request->all();
            $inputan['slug'] = $slug;
            $itemkategori->update($inputan);
            return redirect()->route('kategori.index')->with('success', 'Data berhasil diupdate');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $itemkategori = Kategori::findOrFail($id);
        if (count((array)$itemkategori->produk) > 0) {
            return back()->with('error', 'Anda Perlu Menghapus dulu produk di dalam kategori ini, proses dihentikan');
        } else {
            if ($itemkategori->delete()) {
                return back()->with('success', 'Data berhasil dihapus');
            } else {
                return back()->with('error', 'Data gagal dihapus');
            }
        }
    }

    
     /**
     * Upload image for kategori
     */
    public function uploadimage(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $user = $request->user();
        
        // Find kategori that belongs to user
        $itemkategori = Kategori::where('user_id', $user->id)
            ->where('id', $validated['kategori_id'])
            ->firstOrFail();

        // Upload image
        $image = $this->upload($request->file('image'), $user);
        
        // Update kategori with image URL
        $itemkategori->update([
            'foto' => $image->url
        ]);

        return back()->with('success', 'Image berhasil diupload');
    }

    /**
     * Delete image from kategori
     */
    public function deleteimage(Request $request, string $id): RedirectResponse
    {
        $user = $request->user();
        
        $itemkategori = Kategori::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        if ($itemkategori->foto) {
            // Find image record
            $itemgambar = Image::where('url', $itemkategori->foto)->first();
            
            if ($itemgambar) {
                // Delete file from storage
                if (Storage::disk('public')->exists($itemgambar->url)) {
                    Storage::disk('public')->delete($itemgambar->url);
                }
                
                // Delete database record
                $itemgambar->delete();
            }
            
            // Update kategori
            $itemkategori->update(['foto' => null]);
        }

        return back()->with('success', 'Foto berhasil dihapus');
    }

    /**
     * Upload file and create image record
     */
    private function upload($file, $user): Image
    {
        // Generate unique filename
        $filename = Str::uuid() . '.' . $file->extension();
        
        // Store in storage/app/public/images
        $path = $file->storeAs('images', $filename, 'public');
        
        // Create database record
        return Image::create([
            'url' => $path,
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'user_id' => $user->id,
        ]);
    }
}