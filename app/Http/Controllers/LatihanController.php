<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LatihanController extends Controller
{
    public function index()
    {
        return "Ini adalah halaman index pada LatihanController";
    }
    public function blog($id)
    {
        return "Ini adalah halaman blog dengan id : " . $id;
    }
    public function Komentar($idblog, $idkomentar)
    {
        echo "Ini adalah halaman komentar dengan id blog : " . $idblog;
        echo "<br>";
        echo "Ini adalah halaman komentar dengan id blog : " .$idkomentar;
    }
    public function beranda()
    {
        $data = array(
            "nama" => "Fauzan",
            "alamat" => "Bandung"
        );
        return view('beranda', $data);
    }
}
