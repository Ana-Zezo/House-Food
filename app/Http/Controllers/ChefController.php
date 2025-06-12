<?php

namespace App\Http\Controllers;

use App\Models\Chef;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChefController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chefs = Chef::latest()->paginate(8);
        return view('admin.chefs.index', compact('chefs'));
    }


    public function show(string $id)
    {
        $chef = Chef::findorfail($id);
        return view('admin.chefs.show', compact('chef'));
    }
}
