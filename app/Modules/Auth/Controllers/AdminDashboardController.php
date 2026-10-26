<?php

namespace Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with user statistics.
     */
    public function index(): View
    {
        $totalUsers = User::count();
        $totalAdmins = User::where('role', User::ROLE_ADMIN)->count();
        $totalRegularUsers = User::where('role', User::ROLE_USER)->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'totalRegularUsers',
        ));
    }
}
