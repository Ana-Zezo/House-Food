<?php

namespace App\Http\Controllers\Api;

use App\Models\Food;
use App\Trait\ApiResponse;
use App\Models\ImageGallery;
use Illuminate\Http\Request;
use App\Trait\UploadFileTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\FoodResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\Chef\FoodRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Chef\UpdateFoodRequest;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chef = Auth::user();

        $foods = Food::with(['category', 'chef', 'reviews', 'imageGalleries'])
            ->where('chef_id', $chef->id)
            ->withAvg('reviews', 'star')
            ->paginate(10);

        return ApiResponse::sendResponse(true, 'Data Retrieved Successfully', FoodResource::collection($foods));
    }

    public function store(FoodRequest $request)
    {
        $data = $request->validated();
        $data['chef_id'] = Auth::user()->id;
        $food = Food::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $galleryImage) {
                $galleryPath = UploadFileTrait::store($galleryImage, 'uploads/images/galleries');

                ImageGallery::create([
                    'food_id' => $food->id,
                    'image' => "storage/" . $galleryPath,
                ]);
            }
        }

        return ApiResponse::sendResponse(true, 'تم حفظ الأكلة وتحليلها بنجاح', [
            'food' => new FoodResource($food),
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(Food $food)
    {
        $food->load(['category', 'chef', 'reviews', 'imageGalleries']);
        return ApiResponse::sendResponse(true, 'Food retrieved successfully', new FoodResource($food));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFoodRequest $request, Food $food)
    {
        $data = $request->validated();

        $food->update($data);
        return ApiResponse::sendResponse(true, 'تم تحديث الأكلة بنجاح', new FoodResource($food));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Food $food)
    {
        foreach ($food->imageGalleries as $gallery) {
            Storage::disk('public')->delete($gallery->image); // حذف من التخزين
            $gallery->delete(); // حذف من قاعدة البيانات
        }

        // حذف الأكلة
        $food->delete();

        return ApiResponse::sendResponse(true, 'تم حذف الأكلة والصور بنجاح');
    }
}