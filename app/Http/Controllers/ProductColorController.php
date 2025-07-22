<?php

namespace App\Http\Controllers;

use App\Models\ProductColor;
use Illuminate\Http\Request;

class ProductColorController extends Controller
{
  // Display a listing of product colors
  public function index()
  {
    return response()->json(ProductColor::all());
  }

  // Store a newly created product color
  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:50',
      'description' => 'nullable|string',
      'hex_code' => 'nullable|string|max:8',
    ]);
    $color = ProductColor::create($validated);
    return response()->json($color, 201);
  }

  // Show a specific product color
  public function show($id)
  {
    $color = ProductColor::findOrFail($id);
    return response()->json($color);
  }

  // Update a product color
  public function update(Request $request, $id)
  {
    $color = ProductColor::findOrFail($id);
    $validated = $request->validate([
      'name' => 'required|string|max:50',
      'description' => 'nullable|string',
      'hex_code' => 'nullable|string|max:8',
    ]);
    $color->update($validated);
    return response()->json($color);
  }

  // Delete a product color
  public function destroy($id)
  {
    $color = ProductColor::findOrFail($id);
    $color->delete();
    return response()->json(null, 204);
  }
}
