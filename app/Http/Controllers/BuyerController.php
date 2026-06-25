<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        return view('buyer.profile', compact('user'));
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('buyer.edit_profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'gender' => 'nullable|string',
            'dob' => 'nullable|date',
        ]);

        $user->update($request->only('name', 'phone', 'address', 'gender', 'dob'));

        return redirect()->route('buyer.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function checkoutForm($productId)
    {
        $product = Product::findOrFail($productId);
        if ($product->status !== 'available') {
            return redirect()->route('home')->with('error', 'Produk ini sudah terjual.');
        }
        $user = Auth::user();
        return view('buyer.checkout', compact('product', 'user'));
    }

    public function checkout(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        if ($product->status !== 'available') {
            return redirect()->route('home')->with('error', 'Produk ini sudah terjual.');
        }

        $request->validate([
            'shipping_address' => 'required|string',
            'shipping_phone' => 'required|string',
            'payment_method' => 'required|in:qris,cod',
        ]);

        // Auto-assign a courier from seeded couriers (role = kurir)
        $courier = User::where('role', 'kurir')->first();

        // Create Order
        $order = Order::create([
            'buyer_id' => Auth::id(),
            'product_id' => $product->id,
            'courier_id' => $courier ? $courier->id : null,
            'price' => $product->price,
            'service_fee' => $product->service_fee,
            'total_price' => $product->price + $product->service_fee,
            'payment_method' => $request->payment_method,
            'status' => 'pending_shipping_approval', // Requires admin approval before shipping
            'shipping_address' => $request->shipping_address,
            'shipping_phone' => $request->shipping_phone,
        ]);

        // Update product to sold
        $product->update(['status' => 'sold']);

        // Create Notification for Buyer
        Notification::create([
            'user_id' => Auth::id(),
            'order_id' => $order->id,
            'title' => 'Pesanan Berhasil Dibuat',
            'message' => "Pesanan {$product->name} Anda sedang menunggu persetujuan pengiriman dari admin.",
        ]);

        // Create Notification for Seller
        Notification::create([
            'user_id' => $product->seller_id,
            'order_id' => $order->id,
            'title' => 'Produk Terjual',
            'message' => "Produk {$product->name} telah dibeli oleh " . Auth::user()->name . ". Menunggu persetujuan pengiriman admin.",
        ]);

        return redirect()->route('buyer.purchases')->with('success', 'Pembelian berhasil! Menunggu persetujuan pengiriman dari admin.');
    }

    public function purchases()
    {
        $orders = Order::where('buyer_id', Auth::id())
            ->with('product.seller')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('buyer.purchases', compact('orders'));
    }

    public function notifications()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Mark all as read
        Notification::where('user_id', Auth::id())->update(['is_read' => true]);

        return view('buyer.notifications', compact('notifications'));
    }

    public function confirmDelivery($orderId)
    {
        $order = Order::where('buyer_id', Auth::id())->findOrFail($orderId);
        $order->update(['status' => 'completed']);

        // Notification for Buyer
        Notification::create([
            'user_id' => Auth::id(),
            'order_id' => $order->id,
            'title' => 'Pesanan Selesai',
            'message' => "Terima kasih! Pesanan {$order->product->name} telah selesai.",
        ]);

        // Notification for Seller
        Notification::create([
            'user_id' => $order->product->seller_id,
            'order_id' => $order->id,
            'title' => 'Dana Diteruskan',
            'message' => "Pesanan {$order->product->name} telah selesai dikonfirmasi oleh pembeli. Saldo diteruskan.",
        ]);

        return redirect()->back()->with('success', 'Konfirmasi penerimaan berhasil.');
    }

    public function requestReturn($orderId)
    {
        $order = Order::where('buyer_id', Auth::id())->findOrFail($orderId);
        
        // Can only return if order is delivered or completed
        if (!in_array($order->status, ['delivered', 'completed'])) {
            return redirect()->back()->with('error', 'Pesanan belum diterima atau tidak dapat diretur.');
        }
        
        $order->update(['status' => 'pending_return']);
        
        // Notification for Buyer
        Notification::create([
            'user_id' => Auth::id(),
            'order_id' => $order->id,
            'title' => 'Pengajuan Retur Diajukan',
            'message' => "Pengajuan retur untuk {$order->product->name} sedang menunggu persetujuan admin.",
        ]);
        
        // Notification for Seller
        Notification::create([
            'user_id' => $order->product->seller_id,
            'order_id' => $order->id,
            'title' => 'Pengajuan Retur Baru',
            'message' => "Pembeli mengajukan retur untuk produk {$order->product->name}. Menunggu persetujuan admin.",
        ]);
        
        return redirect()->back()->with('success', 'Pengajuan retur berhasil dikirim. Menunggu persetujuan admin.');
    }
}
