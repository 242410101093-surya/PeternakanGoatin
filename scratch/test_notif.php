<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pendingOrders = \Illuminate\Support\Facades\DB::table('notifications')->where('is_read', false)->count();
$unreadNotifications = \App\Models\Notification::with('pesanan.produk')
    ->where('is_read', false)
    ->orderBy('created_at', 'desc')
    ->get();

var_dump($pendingOrders);
var_dump($unreadNotifications->count());
