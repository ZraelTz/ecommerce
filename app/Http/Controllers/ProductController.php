<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the products with pagination.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPaginatedProducts(Request $request)
    {
        // Get the products with their images and ratings, using the request parameters
        $page = $request->input('page', 1); // get the current page or default to 1
        $pageSize = $request->input('pageSize', 10); // get the page size or default to 10
        $products = Product::with(['product_images', 'product_reviews'])
            ->withAvg('product_reviews', 'rating')
            ->paginate($pageSize, ['*'], 'page', $page);

        // Return the products as a JSON response
        return response()->json($products);
    }

    /**
     * Display a listing of all the products without pagination.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProducts()
    {
        // Get all the products with their images and ratings
        $products = Product::with(['product_images', 'product_reviews'])
            ->withAvg('product_reviews', 'rating')
            ->get();

        // Return the products as a JSON response
        return response()->json($products);
    }


    /**
     * Display the specified product by id.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getProductById($id)
    {
        // Find the product by id or abort with a 404 response if not found
        $product = Product::find($id);

        if (!$product) {
            abort(404, 'Product not found');
        }

        // Load the product images and reviews
        $product->load(['product_images', 'product_reviews']);

        // Calculate the average rating of the product
        $product->rating = $product->product_reviews()->avg('rating');

        // Return the product as a JSON response
        return response()->json($product);
    }

        /**
     * Display a listing of the products by category name with pagination.
     *
     * @param  string  $category
     * @return \Illuminate\Http\Response
     */
    public function getPaginatedProductsByCategory(Request $request, $category)
    {
        // Get the products by category name using the request parameters
        $page = $request->input('page', 1); // get the current page or default to 1
        $pageSize = $request->input('pageSize', 10); // get the page size or default to 10
        $products = Product::where('category', $category)
            ->with(['product_images', 'product_reviews'])
            ->withAvg('product_reviews', 'rating')
            ->paginate($pageSize, ['*'], 'page', $page);

        // Append the query parameters to the pagination links
        $products->appends($request->all());

        // Return the products as a JSON response
        return response()->json($products);
    }

    /**
     * Display a listing of all the products by category name without pagination.
     *
     * @param  string  $category
     * @return \Illuminate\Http\Response
     */
    public function getProductsByCategory($category)
    {
        // Get all the products by category name
        $products = Product::where('category', $category)
            ->with(['product_images', 'product_reviews'])
            ->withAvg('product_reviews', 'rating')
            ->get();

        // Return the products as a JSON response
        return response()->json($products);
    }
}
