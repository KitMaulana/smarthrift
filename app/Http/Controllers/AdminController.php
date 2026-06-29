<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $userCount = User::count();
        $productCount = Product::count();
        $orderCount = Order::count();
        $totalSales = Order::whereIn('status', ['completed', 'delivered', 'shipped'])->sum('total_price');

        $latestOrders = Order::with(['buyer', 'product'])->orderBy('created_at', 'desc')->take(5)->get();
        $latestUsers = User::orderBy('created_at', 'desc')->take(5)->get();

        // Get DANA number setting
        $danaNumber = Setting::get('admin_dana_number', '08111222333');

        // Get delivered orders that require admin completion
        $deliveredOrders = Order::where('status', 'delivered')->with(['buyer', 'product'])->get();

        return view('admin.dashboard', compact('userCount', 'productCount', 'orderCount', 'totalSales', 'latestOrders', 'latestUsers', 'danaNumber', 'deliveredOrders'));
    }

    public function users()
    {
        $users = User::orderBy('role')->orderBy('name')->get();
        return view('admin.users', compact('users'));
    }

    public function updateUserRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,pelanggan,penjual,kurir',
        ]);

        $user = User::findOrFail($id);
        $user->update(['role' => $request->role]);

        return redirect()->route('admin.users')->with('success', "Role user {$user->name} berhasil diperbarui.");
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting oneself
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus.');
    }

    public function products()
    {
        $products = Product::with('seller')->orderBy('created_at', 'desc')->get();
        return view('admin.products', compact('products'));
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus.');
    }

    public function transactions()
    {
        $orders = Order::with(['buyer', 'product.seller', 'courier'])->orderBy('created_at', 'desc')->get();
        $couriers = User::where('role', 'kurir')->get();
        return view('admin.transactions', compact('orders', 'couriers'));
    }

    public function approveProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['status' => 'available']);

        // Send notification to Seller
        \App\Models\Notification::create([
            'user_id' => $product->seller_id,
            'title' => 'Produk Disetujui',
            'message' => "Produk Anda {$product->name} telah disetujui oleh admin dan sekarang aktif dijual.",
        ]);

        return redirect()->back()->with('success', 'Produk berhasil disetujui untuk dijual.');
    }

    public function approveShipping($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => 'pending_shipping']);

        // Notification for Buyer
        \App\Models\Notification::create([
            'user_id' => $order->buyer_id,
            'order_id' => $order->id,
            'title' => 'Pengiriman Disetujui',
            'message' => "Pengiriman untuk pesanan {$order->product->name} telah disetujui oleh admin dan akan diantar oleh kurir.",
        ]);

        // Notification for Courier
        if ($order->courier_id) {
            \App\Models\Notification::create([
                'user_id' => $order->courier_id,
                'order_id' => $order->id,
                'title' => 'Tugas Pengiriman Baru',
                'message' => "Admin menyetujui pengiriman baru #ST-{$order->id} untuk Anda antarkan.",
            ]);
        }

        return redirect()->back()->with('success', 'Tugas pengiriman berhasil disetujui untuk kurir.');
    }

    public function approveReturn($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => 'returned']);
        
        // Return product status to available so it can be sold again
        $order->product->update(['status' => 'available']);

        // Notification for Buyer
        \App\Models\Notification::create([
            'user_id' => $order->buyer_id,
            'order_id' => $order->id,
            'title' => 'Retur Disetujui',
            'message' => "Pengajuan retur untuk {$order->product->name} telah disetujui oleh admin. Saldo dikembalikan.",
        ]);

        // Notification for Seller
        \App\Models\Notification::create([
            'user_id' => $order->product->seller_id,
            'order_id' => $order->id,
            'title' => 'Retur Disetujui',
            'message' => "Admin menyetujui retur untuk produk {$order->product->name}. Produk kembali dijual.",
        ]);

        return redirect()->back()->with('success', 'Pengajuan retur berhasil disetujui.');
    }

    public function rejectReturn($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => 'return_rejected']);

        // Notification for Buyer
        Notification::create([
            'user_id' => $order->buyer_id,
            'order_id' => $order->id,
            'title' => 'Retur Ditolak',
            'message' => "Pengajuan retur untuk {$order->product->name} ditolak oleh admin.",
        ]);

        // Notification for Seller
        Notification::create([
            'user_id' => $order->product->seller_id,
            'order_id' => $order->id,
            'title' => 'Retur Ditolak',
            'message' => "Admin menolak pengajuan retur untuk produk {$order->product->name}.",
        ]);

        return redirect()->back()->with('success', 'Pengajuan retur berhasil ditolak.');
    }

    public function updateDanaNumber(Request $request)
    {
        $request->validate([
            'dana_number' => 'required|string',
        ]);

        Setting::set('admin_dana_number', $request->dana_number);

        return redirect()->back()->with('success', 'Nomor DANA Admin berhasil diperbarui.');
    }

    public function confirmPaymentAndAssignCourier(Request $request, $orderId)
    {
        $request->validate([
            'courier_id' => 'required|exists:users,id',
        ]);

        $order = Order::findOrFail($orderId);
        $order->update([
            'status' => 'pending_shipping',
            'courier_id' => $request->courier_id
        ]);

        // Notification for Buyer
        Notification::create([
            'user_id' => $order->buyer_id,
            'order_id' => $order->id,
            'title' => 'Pembayaran Terverifikasi',
            'message' => "Pembayaran Anda untuk pesanan {$order->product->name} telah dikonfirmasi oleh admin. Pengiriman akan ditangani oleh kurir.",
        ]);

        // Notification for Courier
        Notification::create([
            'user_id' => $request->courier_id,
            'order_id' => $order->id,
            'title' => 'Tugas Pengiriman Baru',
            'message' => "Admin menyetujui pengiriman baru #ST-{$order->id} untuk Anda antarkan.",
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi dan pesanan diteruskan ke kurir.');
    }

    public function completeOrder($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => 'completed']);

        // Notification for Buyer
        Notification::create([
            'user_id' => $order->buyer_id,
            'order_id' => $order->id,
            'title' => 'Pesanan Selesai',
            'message' => "Pesanan {$order->product->name} Anda telah diselesaikan oleh admin. Terima kasih!",
        ]);

        // Notification for Seller
        Notification::create([
            'user_id' => $order->product->seller_id,
            'order_id' => $order->id,
            'title' => 'Pesanan Selesai & Saldo Diteruskan',
            'message' => "Pesanan {$order->product->name} telah selesai dikonfirmasi. Saldo diteruskan ke Anda.",
        ]);

        return redirect()->back()->with('success', 'Pesanan berhasil diselesaikan.');
    }
}
