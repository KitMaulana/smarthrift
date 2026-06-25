<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Chat;
use App\Models\Notification;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Users
        $admin = User::create([
            'name' => 'Admin SmartThrift',
            'username' => 'admin',
            'email' => 'admin@st.com',
            'password' => Hash::make('st123'),
            'role' => 'admin',
            'phone' => '08111222333',
            'address' => 'Kantor Pusat SmartThrift, Jakarta',
            'gender' => 'Laki-laki',
            'dob' => '1990-01-01',
        ]);

        $seller = User::create([
            'name' => 'Cicilya2526',
            'username' => 'cicilya',
            'email' => 'penjual@st.com',
            'password' => Hash::make('st123'),
            'role' => 'penjual',
            'phone' => '081234567890',
            'address' => 'Jl. Kebon Jeruk No. 25, Jakarta Barat',
            'gender' => 'Perempuan',
            'dob' => '1997-08-25',
        ]);

        $buyer1 = User::create([
            'name' => 'Naura17',
            'username' => 'naura',
            'email' => 'pelanggan@st.com',
            'password' => Hash::make('st123'),
            'role' => 'pelanggan',
            'phone' => '087776543210',
            'address' => 'Jl. Mawar Indah Blok C No. 17, Tangerang',
            'gender' => 'Perempuan',
            'dob' => '2001-04-17',
        ]);

        $buyer2 = User::create([
            'name' => 'Dimas',
            'username' => 'dimas',
            'email' => 'dimas@smartthrift.com',
            'password' => Hash::make('st123'),
            'role' => 'pelanggan',
            'phone' => '085211223344',
            'address' => 'Kost Harmoni Room 3, Depok',
            'gender' => 'Laki-laki',
            'dob' => '2000-11-12',
        ]);

        $buyer3 = User::create([
            'name' => 'Syafira',
            'username' => 'syafira',
            'email' => 'syafira@smartthrift.com',
            'password' => Hash::make('st123'),
            'role' => 'pelanggan',
            'phone' => '08987654321',
            'address' => 'Perum Griya Asri C-12, Bogor',
            'gender' => 'Perempuan',
            'dob' => '2002-09-05',
        ]);

        $courier = User::create([
            'name' => 'Rian Kurir',
            'username' => 'rian_kurir',
            'email' => 'kurir@st.com',
            'password' => Hash::make('st123'),
            'role' => 'kurir',
            'phone' => '083811223344',
            'address' => 'Hub Kurir SmartThrift, Jakarta Selatan',
            'gender' => 'Laki-laki',
            'dob' => '1995-07-20',
        ]);


        // 2. Create Products (Matches Screenshots)
        $p1 = Product::create([
            'seller_id' => $seller->id,
            'name' => 'Kaos purih basic',
            'description' => 'Kaos polos warna putih, bahan katun combed 30s. Adem nyaman dipakai harian.',
            'price' => 25000,
            'service_fee' => 6000,
            'category' => 'atasan',
            'image_path' => 'kaos_putih.jpg',
            'payment_method' => 'both',
            'status' => 'available',
        ]);

        $p2 = Product::create([
            'seller_id' => $seller->id,
            'name' => 'Sepatu hitam',
            'description' => 'Sepatu sneakers tali warna hitam putih klasik. Kondisi 90% mulus jarang pakai.',
            'price' => 45000,
            'service_fee' => 6000,
            'category' => 'aksesoris',
            'image_path' => 'sepatu_hitam.jpg',
            'payment_method' => 'both',
            'status' => 'available',
        ]);

        $p3 = Product::create([
            'seller_id' => $seller->id,
            'name' => 'Jeans biru soft',
            'description' => 'Celana jeans denim warna biru muda soft. Model lurus (straight cut). Ukuran 30.',
            'price' => 57000,
            'service_fee' => 6000,
            'category' => 'bawahan',
            'image_path' => 'jeans_biru.jpg',
            'payment_method' => 'both',
            'status' => 'available',
        ]);

        $p4 = Product::create([
            'seller_id' => $seller->id,
            'name' => 'scarft wana nude',
            'description' => 'Second scraft, warna nude tanya2 silahkan chat admin.',
            'price' => 15000,
            'service_fee' => 6000,
            'category' => 'aksesoris',
            'image_path' => 'scarf_nude.jpg',
            'payment_method' => 'both',
            'status' => 'available',
        ]);

        $p5 = Product::create([
            'seller_id' => $seller->id,
            'name' => 'Syal krem polos',
            'description' => 'Syal hangat warna krem polos rajut halus. Cocok untuk musim dingin.',
            'price' => 25000,
            'service_fee' => 6000,
            'category' => 'aksesoris',
            'image_path' => 'syal_krem.jpg',
            'payment_method' => 'both',
            'status' => 'available',
        ]);

        $p6 = Product::create([
            'seller_id' => $seller->id,
            'name' => 'Sepatu pink',
            'description' => 'Flat shoes wanita warna pink imut, bahan kulit sintetis lembut.',
            'price' => 45000,
            'service_fee' => 6000,
            'category' => 'aksesoris',
            'image_path' => 'sepatu_pink.jpg',
            'payment_method' => 'both',
            'status' => 'available',
        ]);

        $p7 = Product::create([
            'seller_id' => $seller->id,
            'name' => 'Kemeja flanel merah',
            'description' => 'Kemeja flanel motif kotak-kotak merah hitam. Kondisi sangat bagus, jarang dipakai.',
            'price' => 35000,
            'service_fee' => 6000,
            'category' => 'atasan',
            'image_path' => 'uploads/flanel_merah.jpg',
            'payment_method' => 'both',
            'status' => 'pending',
        ]);

        // 3. Create Orders (to populate courier & notifications page)
        // Order 1: Delivered (Sepatu hitam) bought by Naura17, delivered by Rian
        $o1 = Order::create([
            'buyer_id' => $buyer1->id,
            'product_id' => $p2->id,
            'courier_id' => $courier->id,
            'price' => 45000,
            'service_fee' => 6000,
            'total_price' => 51000,
            'payment_method' => 'cod',
            'status' => 'delivered',
            'shipping_address' => $buyer1->address,
            'shipping_phone' => $buyer1->phone,
        ]);
        $p2->update(['status' => 'sold']);

        // Order 2: Completed (Syal krem polos) bought by Syafira, delivered by Rian
        $o2 = Order::create([
            'buyer_id' => $buyer3->id,
            'product_id' => $p5->id,
            'courier_id' => $courier->id,
            'price' => 25000,
            'service_fee' => 6000,
            'total_price' => 31000,
            'payment_method' => 'qris',
            'status' => 'completed',
            'shipping_address' => $buyer3->address,
            'shipping_phone' => $buyer3->phone,
        ]);
        $p5->update(['status' => 'sold']);

        // Order 3: Pending Shipping Approval (Sepatu pink) bought by Naura17
        $o3 = Order::create([
            'buyer_id' => $buyer1->id,
            'product_id' => $p6->id,
            'courier_id' => $courier->id,
            'price' => 45000,
            'service_fee' => 6000,
            'total_price' => 51000,
            'payment_method' => 'qris',
            'status' => 'pending_shipping_approval',
            'shipping_address' => $buyer1->address,
            'shipping_phone' => $buyer1->phone,
        ]);
        $p6->update(['status' => 'sold']);

        // Order 4: Pending Return (Kaos putih basic) bought by Dimas
        $o4 = Order::create([
            'buyer_id' => $buyer2->id,
            'product_id' => $p1->id,
            'courier_id' => $courier->id,
            'price' => 25000,
            'service_fee' => 6000,
            'total_price' => 31000,
            'payment_method' => 'qris',
            'status' => 'pending_return',
            'shipping_address' => $buyer2->address,
            'shipping_phone' => $buyer2->phone,
        ]);
        $p1->update(['status' => 'sold']);

        // 4. Create Notifications (Matches Screenshot Page 6)
        // Notification 1
        Notification::create([
            'user_id' => $buyer1->id,
            'order_id' => $o1->id,
            'title' => 'Konfirmasi Penerimaan Pesanan',
            'message' => 'Silahkan cek apakah pesananmu sudah sesuai.',
        ]);

        // Notification 2
        Notification::create([
            'user_id' => $buyer1->id,
            'order_id' => $o1->id,
            'title' => 'Pesanan Tiba di Tujuan',
            'message' => 'Silahkan cek apakah pesananmu sudah sesuai.',
        ]);

        // Notification 3 (For buyer3 Syafira)
        Notification::create([
            'user_id' => $buyer3->id,
            'order_id' => $o2->id,
            'title' => 'Konfirmasi Penerimaan Pesanan',
            'message' => 'Silahkan cek apakah pesananmu sudah sesuai.',
        ]);

        // 5. Create Chats (Matches Screenshots Page 8, Page 10)
        // Chat room with Naura17
        Chat::create([
            'sender_id' => $buyer1->id,
            'receiver_id' => $seller->id,
            'product_id' => $p4->id,
            'message' => 'Apakah barang ini tersedia?',
        ]);
        Chat::create([
            'sender_id' => $seller->id,
            'receiver_id' => $buyer1->id,
            'product_id' => $p4->id,
            'message' => 'Barang tersedia',
        ]);
        Chat::create([
            'sender_id' => $buyer1->id,
            'receiver_id' => $seller->id,
            'product_id' => null,
            'message' => 'Baik terimakasih kak 🙏',
        ]);

        // Chat room with Dimas
        Chat::create([
            'sender_id' => $buyer2->id,
            'receiver_id' => $seller->id,
            'product_id' => $p1->id,
            'message' => 'Halo kak apakah barang nya masih ada?',
        ]);

        // Chat room with Syafira
        Chat::create([
            'sender_id' => $buyer3->id,
            'receiver_id' => $seller->id,
            'product_id' => $p5->id,
            'message' => 'Barang nya sudah sampai kak, terimakasih',
        ]);
    }
}
