<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;

class MenuController extends Controller
{
    public function index()
    {
        $items = MenuItem::where('is_available', true)
            ->orderBy('category')
            ->orderBy('item_name')
            ->get()
            ->groupBy('category');

        return view('menu.index', compact('items'));
    }
}
