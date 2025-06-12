<?php

namespace App\Http\Controllers;

use App\Models\Chef;
use App\Models\Food;
use App\Models\Order;
use App\Models\Category;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('home', [
            'usersCount' => User::count(),
            'chefsCount' => Chef::count(),
            'ordersCount' => Order::count(),
            'foodsCount' => Food::count(),
            'categoriesCount' => Category::count(),
            'totalMount' => Withdraw::where('status', 'approved')->sum('amount'),
            'withdrawCount' => Withdraw::count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
