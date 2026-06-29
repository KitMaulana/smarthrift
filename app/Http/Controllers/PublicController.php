<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        // Get all available products
        $products = Product::where('status', 'available')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get testimonials
        $testimonials = \App\Models\Testimonial::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('public.home', compact('products', 'testimonials'));
    }

    public function explore(Request $request)
    {
        $query = Product::where('status', 'available');

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Category filter
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }

        $products = $query->orderBy('created_at', 'desc')->get();

        // Get recommendations (items not matching current filter or just a subset)
        $recommendations = Product::where('status', 'available')
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('public.explore', compact('products', 'recommendations'));
    }

    public function showProduct($id)
    {
        $product = Product::with('seller')->findOrFail($id);
        $admin = \App\Models\User::where('role', 'admin')->first();
        return view('public.product_detail', compact('product', 'admin'));
    }
}
