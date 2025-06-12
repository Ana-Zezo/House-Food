<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Withdraw;
use Illuminate\Http\Request;

class WithdrawsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $withdraws = Withdraw::with('chef')->latest()->paginate(8);
        return view('admin.withdraws.index', compact('withdraws'));
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
        $withdraw = Withdraw::with('chef')->find($id);

        if (!$withdraw) {
            return redirect()->route('dashboard.withdraws.index')->with('error', 'Withdraw not found.');
        }

        return view('admin.withdraws.show', compact('withdraw'));
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
    public function update(Request $request, Withdraw $withdraw)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $withdraw->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Withdraw status updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
