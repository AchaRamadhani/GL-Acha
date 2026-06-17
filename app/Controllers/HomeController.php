<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home/index', [
            'title' => 'Ghava Laundry',
        ]);
    }

    public function tracking(): void
    {
        $this->view('home/tracking', [
            'title' => 'Tracking Cucian - Ghava Laundry',
        ]);
    }

    public function layanan(): void
    {
        $this->view('home/layanan', [
            'title' => 'Layanan - Ghava Laundry',
        ]);
    }

    public function kontak(): void
    {
        $this->view('home/kontak', [
            'title' => 'Kontak - Ghava Laundry',
        ]);
    }

    public function adminLogin(): void
    {
        $this->view('admin/login', [
            'title' => 'Login Admin - Ghava Laundry',
        ]);
    }

    public function adminDashboard(): void
    {
        $this->view('admin/dashboard', [
            'title' => 'Dashboard Admin - Ghava Laundry',
        ]);
    }

    public function adminCucian(): void
    {
        $this->view('admin/cucian', [
            'title' => 'Data Cucian - Ghava Laundry',
        ]);
    }

    public function adminTransaksi(): void
    {
        $this->view('admin/transaksi', [
            'title' => 'Transaksi - Ghava Laundry',
        ]);
    }

    public function adminUpdateStatus(): void
    {
        $this->view('admin/update-status', [
            'title' => 'Update Status - Ghava Laundry',
        ]);
    }

    public function adminPelanggan(): void
    {
        $this->view('admin/pelanggan', [
            'title' => 'Pelanggan - Ghava Laundry',
        ]);
    }

    public function adminPaketLaundry(): void
    {
        $this->view('admin/paket-laundry', [
            'title' => 'Paket Laundry - Ghava Laundry',
        ]);
    }

    public function adminPengaturan(): void
    {
        $this->view('admin/pengaturan', [
            'title' => 'Pengaturan - Ghava Laundry',
        ]);
    }
}
