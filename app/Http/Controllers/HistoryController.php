<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        $history = Recipe::with('machine')->orderBy('created_at', 'desc')->get();
        return view('history', compact('history'));
    }
}
