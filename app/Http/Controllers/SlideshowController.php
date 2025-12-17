<?php

namespace App\Http\Controllers;

use App\Models\Slideshow;
use Illuminate\Http\Request;

class SlideshowController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itemslideshow = Slideshow::orderBy('id', 'desc')->paginate(20);
        $data = array('title' => 'Slideshow',
                    'itemslideshow' => $itemslideshow);
        return view('slideshow.index', $data)->with('no', ($request->input('page', 1) - 1) * 20);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        // ambil data user yang login
        $itemuser = $request->user();
        // ambil image yang diupload ke dalam variabel
        $fileupload = $request->file('image');
        $folder = 'assets/slideshow';
        $itemgambar = (new ImageController)->upload($fileupload, $itemuser, $folder);
        // menyiapkan url yang mau disimpan ke database
        $inputan = $request->all();
        $inputan['user_id'] = $itemuser->id;
        $inputan['foto'] = $itemgambar->url;
        // simpan ke db
        $itemslideshow = Slideshow::create($inputan);
        return back()->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     * @param  \App\Models\Slideshow  $slideshow
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param  \App\Models\Slideshow  $slideshow
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Slideshow  $slideshow
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param  \App\Models\Slideshow  $slideshow
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $itemslideshow = Slideshow::findOrFail($id);
        // cari dulu data berdasarkan url foto
        $itemgambar = \App\Models\Image::where('url', $itemslideshow->foto)->first();
        // hapus dari storage jika ada
        if ($itemgambar) {
            \Storage::delete($itemgambar->url);
            $itemgambar->delete();
        }
        // hapus slide
        if ($itemslideshow->delete()) {
            return back()->with('success', 'Data berhasil dihapus');
        }
        return back()->with('error', 'Data gagal dihapus');
    }
}
