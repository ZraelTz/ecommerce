<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Handle the incoming request to order a product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function orderProduct(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'email' => 'required|email',
            'delivery_address' => 'required|string|max:255',
            'product_ids' => 'required|array', // Ensure product_ids is an array
            'product_ids.*' => 'required|exists:products,id', // Validate each product_id exists
            'quantity' => 'required|integer|min:1' // Validate quantity
        ]);
    
        // Begin a transaction
        DB::beginTransaction();
    
        try {
            // Find the user by email
            $user = User::where('email', $validatedData['email'])->firstOrFail();
    
            $customer = $user->customer()->where('user_id', $user->id)->first();
            if ($customer) {
                // Update the customer's address
                $customer->update(['address' => $validatedData['delivery_address']]);
            } else {
                // Create a new customer for the user with the given address
                $customer = new Customer([
                    'address' => $validatedData['delivery_address'],
                    'user_id' => $user->id // Set the user_id for the customer
                ]);
                $customer->save();
            }
    
            $totalAmount = 0; // Initialize total amount
    
            // Process each product_id
            foreach ($validatedData['product_ids'] as $product_id) {
                // Find the product by product_id
                $product = Product::findOrFail($product_id);
    
                // Check if enough stock is available
                if ($product->stock < $validatedData['quantity']) {
                    throw new \Exception("Not enough stock available for product ID: {$product_id}.");
                }
    
                // Calculate the total amount for the current product
                $totalAmount += $product->sales_price * $validatedData['quantity'];
    
                // Create the order for the current product
                $order = $customer->orders()->create([
                    'product_id' => $product_id,
                    'quantity' => $validatedData['quantity'],
                    'amount' => $product->sales_price * $validatedData['quantity']
                ]);
    
                // Reduce the stock of the product
                $product->decrement('stock', $validatedData['quantity']);
            }
    
            // Commit the transaction
            DB::commit();
    
            // Return a successful response
            return response()->json(['message' => 'Order processed successfully.', 'customer' => $customer, 'total_amount' => $totalAmount], 200);
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
    
            // Return an error response
            return response()->json(['message' => 'Failed to process the order.', 'error' => $e->getMessage()], 500);
        }
    }
    

}
