<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TravelPackageController extends Controller
{
    public function index()
    {
        $packages = \App\Models\TravelPackage::all();
        return response()->json([
            'status' => 'success',
            'data' => $packages
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer',
            'departure_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $package = \App\Models\TravelPackage::create($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Travel package created successfully',
            'data' => $package
        ], 201);
    }

    public function show($id)
    {
        $package = \App\Models\TravelPackage::find($id);

        if (!$package) {
            return response()->json([
                'status' => 'error',
                'message' => 'Travel package not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $package
        ]);
    }

    public function update(Request $request, $id)
    {
        $package = \App\Models\TravelPackage::find($id);

        if (!$package) {
            return response()->json([
                'status' => 'error',
                'message' => 'Travel package not found'
            ], 404);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|string|max:255',
            'destination' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric',
            'duration_days' => 'sometimes|required|integer',
            'departure_date' => 'sometimes|required|date',
            'description' => 'nullable|string',
        ]);

        $package->update($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Travel package updated successfully',
            'data' => $package
        ]);
    }

    public function destroy($id)
    {
        $package = \App\Models\TravelPackage::find($id);

        if (!$package) {
            return response()->json([
                'status' => 'error',
                'message' => 'Travel package not found'
            ], 404);
        }

        $package->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Travel package deleted successfully'
        ]);
    }
}
