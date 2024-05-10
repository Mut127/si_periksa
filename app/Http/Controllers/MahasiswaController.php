<?php

namespace App\Http\Controllers;

//import model makasiswa
use App\Models\Mahasiswa;

//import return type View
use Illuminate\View\View;

class MahasiswaController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index(): View
    {
        //get all products
        $products = Mahasiswa::latest()->paginate(10);

        //render view with products
        return view('mahasiswas.index', compact('mahasiswas'));
    }
}
