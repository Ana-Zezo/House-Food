<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Chef;

class FoodController extends Controller
{

    public function index()
    {
        $foods = Food::latest()->paginate(8);
        $chefs = Chef::all();
        return view('admin.foods.index', compact('foods', 'chefs'));
    }


    public function show(string $id)
    {
        $food = Food::findorfail($id);
        return view('admin.foods.show', compact('food'));
    }


    public function destroy(string $id)
    {
        $food = Food::findOrFail($id);
        $food->delete();

        return response()->json(['success' => true]);

        if ($food->image && file_exists(public_path('uploads/foods/' . $food->image))) {
            unlink(public_path('uploads/foods/' . $food->image));
        }

        // حذف العنصر
        $food->delete();


        return response()->json(['success' => true, 'message' => 'Food deleted successfully.']);
    }

    public function toggleStatus(Request $request, Food $food)
    {
        $request->validate([
            'status' => ['required', 'in:active,inactive'],
        ]);

        $food->status = $request->status;
        $food->save();

        return response()->json([
            'status' => true,
            'new_status' => $food->status,
        ]);
    }
}
