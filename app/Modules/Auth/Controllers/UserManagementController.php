<?php

namespace Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Auth\Requests\UpdateUserRoleRequest;

class UserManagementController extends Controller
{
    /**
     * Display a paginated list of all users.
     */
    public function index(): View
    {
        $users = User::query()
            ->orderBy('name')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Update the role of the given user.
     */
    public function updateRole(UpdateUserRoleRequest $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return back()->withErrors([
                'role' => 'Anda tidak dapat mengubah role akun sendiri.',
            ]);
        }

        $user->update([
            'role' => $request->validated()['role'],
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', "Role {$user->name} berhasil diubah menjadi {$user->role}.");
    }

    /**
     * Delete the given user.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return back()->withErrors([
                'user' => 'Anda tidak dapat menghapus akun sendiri.',
            ]);
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('status', "Pengguna {$user->name} berhasil dihapus.");
    }
}
