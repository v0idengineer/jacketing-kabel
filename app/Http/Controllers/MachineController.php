<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MachineController extends Controller
{
    // Menampilkan halaman menu
    public function dashboard()
    {
        return view('dashboard');
    }

    // Menampilkan form untuk mesin tertentu
    public function showForm($machine)
    {
        return view('form', compact('machine'));
    }

    // Menampilkan form input stock
    public function inputStock()
    {
        return view('stock-input');
    }
}
