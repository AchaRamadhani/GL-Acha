<?php

namespace App\Controllers;

use App\Core\Auth;
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
        if (Auth::check()) {
            Auth::redirect('/admin');
        }

        $this->view('admin/login', [
            'title' => 'Login Admin - Ghava Laundry',
            'csrfToken' => Auth::csrfToken(),
            'loginError' => Auth::pullFlash('login_error'),
            'oldUsername' => Auth::pullFlash('old_username'),
        ]);
    }

    public function adminAuthenticate(): void
    {
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $remember = isset($_POST['remember']);

        if (!Auth::verifyCsrf($_POST['_token'] ?? null)) {
            Auth::flash('login_error', 'Sesi login sudah kedaluwarsa. Silakan coba lagi.');
            Auth::flash('old_username', $username);
            Auth::redirect('/admin/login');
        }

        if (Auth::attempt($username, $password, $remember)) {
            Auth::redirect('/admin');
        }

        Auth::flash('login_error', 'Username atau password tidak sesuai.');
        Auth::flash('old_username', $username);
        Auth::redirect('/admin/login');
    }

    public function adminLogout(): void
    {
        Auth::logout();
        Auth::redirect('/admin/login');
    }

    public function adminDashboard(): void
    {
        Auth::requireAdmin();

        $this->view('admin/dashboard', [
            'title' => 'Dashboard Admin - Ghava Laundry',
        ]);
    }

    public function adminCucian(): void
    {
        Auth::requireAdmin();

        $this->view('admin/cucian', [
            'title' => 'Data Cucian - Ghava Laundry',
        ]);
    }

    public function adminTransaksi(): void
    {
        Auth::requireAdmin();

        $this->view('admin/transaksi', [
            'title' => 'Transaksi - Ghava Laundry',
        ]);
    }

    public function adminUpdateStatus(): void
    {
        Auth::requireAdmin();

        $this->view('admin/update-status', [
            'title' => 'Update Status - Ghava Laundry',
        ]);
    }

    public function adminPelanggan(): void
    {
        Auth::requireAdmin();

        $this->view('admin/pelanggan', [
            'title' => 'Pelanggan - Ghava Laundry',
        ]);
    }

    public function adminPaketLaundry(): void
    {
        Auth::requireAdmin();

        $this->view('admin/paket-laundry', [
            'title' => 'Paket Laundry - Ghava Laundry',
        ]);
    }

    public function adminPengaturan(): void
    {
        Auth::requireAdmin();

        $this->view('admin/pengaturan', [
            'title' => 'Pengaturan - Ghava Laundry',
        ]);
    }
}
