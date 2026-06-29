<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourierController extends Controller
{
    public function dashboard()
    {
        $deliveries = Order::where('courier_id', Auth::id())
            ->with(['product', 'buyer'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $pendingCount = $deliveries->whereIn('status', ['pending_shipping', 'shipped'])->count();
        $completedCount = $deliveries->whereIn('status', ['delivered', 'completed'])->count();

        return view('courier.dashboard', compact('deliveries', 'pendingCount', 'completedCount'));
    }

    public function updateStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:shipped,delivered',
            'delivery_proof' => 'required_if:status,delivered|image|max:2048',
        ]);

        $order = Order::where('courier_id', Auth::id())->findOrFail($orderId);
        
        $updateData = ['status' => $request->status];

        if ($request->status === 'delivered' && $request->hasFile('delivery_proof')) {
            $fileName = time() . '_' . $request->file('delivery_proof')->getClientOriginalName();
            $request->file('delivery_proof')->move(public_path('uploads/delivery_proofs'), $fileName);
            $updateData['delivery_proof'] = 'uploads/delivery_proofs/' . $fileName;
        }

        $order->update($updateData);

        // Send notifications based on status change
        if ($request->status === 'shipped') {
            Notification::create([
                'user_id' => $order->buyer_id,
                'order_id' => $order->id,
                'title' => 'Pesanan Sedang Dikirim',
                'message' => "Pesanan {$order->product->name} Anda sedang dikirim oleh kurir " . Auth::user()->name . ".",
            ]);
        } elseif ($request->status === 'delivered') {
            // Matches Screenshot Page 6
            Notification::create([
                'user_id' => $order->buyer_id,
                'order_id' => $order->id,
                'title' => 'Pesanan Tiba di Tujuan',
                'message' => 'Silahkan cek apakah pesananmu sudah sesuai.',
            ]);
            
            // Also notify seller
            Notification::create([
                'user_id' => $order->product->seller_id,
                'order_id' => $order->id,
                'title' => 'Pesanan Tiba di Tujuan',
                'message' => "Pesanan {$order->product->name} telah diantarkan ke pembeli.",
            ]);
        }

        return redirect()->route('courier.dashboard')->with('success', 'Status pengiriman berhasil diperbarui.');
    }
}
