<?php

namespace App\Http\Controllers;

use App\Models\Withdraw;
use Illuminate\Http\Request;

class WithdrawsControllr extends Controller
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $withdraw = Withdraw::with('chef')->findOrFail($id);
        return view('admin.withdraws.show', compact('withdraw'));
    }

    public function edit(string $id)
    {
        $withdraw = Withdraw::with('chef')->findOrFail($id);
        return view('admin.withdraws.edit', compact('withdraw'));
    }


    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $withdraw = Withdraw::findOrFail($id);
        $withdraw->update([
            'status' => $request->status,
        ]);

        return redirect()->route('dashboard.withdraws.index')->with('success', 'Withdraw status updated successfully.');
    }
}
