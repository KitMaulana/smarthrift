<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    public function dashboard()
    {
        $products = Product::where('seller_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $soldCount = $products->where('status', 'sold')->count();
        $activeCount = $products->where('status', 'available')->count();
        $totalRevenue = Order::whereHas('product', function($q) {
                $q->where('seller_id', Auth::id());
            })
            ->whereIn('status', ['completed', 'delivered', 'shipped'])
            ->sum('price');

        return view('seller.dashboard', compact('products', 'soldCount', 'activeCount', 'totalRevenue'));
    }

    public function createProduct()
    {
        return view('seller.create_product');
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|in:atasan,bawahan,aksesoris',
            'payment_method' => 'required|in:qris,cod,both',
            'image' => 'nullable|image|max:2048', // 2MB max
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads'), $imageName);
            $imagePath = 'uploads/' . $imageName;
        } else {
            // Assign a fallback based on category
            if ($request->category === 'atasan') {
                $imagePath = 'images/kaos_putih.jpg';
            } elseif ($request->category === 'bawahan') {
                $imagePath = 'images/jeans_biru.jpg';
            } else {
                $imagePath = 'images/sepatu_hitam.jpg';
            }
        }

        Product::create([
            'seller_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'service_fee' => 6000.00, // Fixed service fee
            'category' => $request->category,
            'image_path' => $imagePath,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
        ]);

        return redirect()->route('seller.dashboard')->with('success', 'Produk berhasil diajukan! Menunggu persetujuan admin.');
    }

    public function sales()
    {
        $orders = Order::whereHas('product', function($q) {
                $q->where('seller_id', Auth::id());
            })
            ->with(['product', 'buyer'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('seller.sales', compact('orders'));
    }
}
