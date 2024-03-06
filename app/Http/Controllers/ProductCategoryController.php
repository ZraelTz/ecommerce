<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of all the categories without pagination.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCategories()
    {
        // Get all the categories
        $categories = ProductCategory::all();

        // Return the categories as a JSON response
        return response()->json($categories);
    }
}
