<?php

namespace App\Http\Controllers\Api;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Trait\ApiResponse;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Auth::user()->addresses;

        return ApiResponse::sendResponse(true, __('messages.data_retrieved_successfully'), $addresses);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'city' => 'required|string|max:255',
            'center' => 'required|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'building_number' => 'required|string|max:10',
        ]);

        $validated['user_id'] = Auth::id();

        $address = Address::create($validated);
        return ApiResponse::sendResponse(true, __('messages.address_created_successfully'), $address);
    }

    public function update(Request $request, Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            return ApiResponse::errorResponse(false, 'Unauthorized');
        }

        $validated = $request->validate([
            'city' => 'sometimes|string|max:255',
            'center' => 'sometimes|string|max:255',
            'neighborhood' => 'sometimes|string|max:255',
            'street' => 'sometimes|string|max:255',
            'building_number' => 'sometimes|string|max:10',
        ]);


        $address->update($validated);
        return ApiResponse::sendResponse(true, __('messages.address_updated_successfully'), $address);
    }

    public function destroy(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            return ApiResponse::errorResponse(false, 'Unauthorized');
        }

        $address->delete();
        return ApiResponse::sendResponse(true, __('messages.address_deleted_successfully'));
    }
}