<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Pengguna') }}
            </h2>

            <a href="{{ route('admin.dashboard') }}"
               class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm transition hover:bg-gray-50">
                {{ __('Kembali ke Dashboard') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-800">
                    <ul class="list-disc ms-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Nama</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Email</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Role</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Terdaftar</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($users as $user)
                                    <tr>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900">
                                            {{ $user->name }}

                                            @if ($user->is(auth()->user()))
                                                <span class="ms-2 inline-flex rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">
                                                    Akun Anda
                                                </span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600">
                                            {{ $user->email }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm">
                                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $user->isAdmin() ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600">
                                            {{ $user->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if ($user->is(auth()->user()))
                                                <span class="text-xs text-gray-500">Tidak tersedia untuk akun sendiri</span>
                                            @else
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <form method="POST"
                                                          action="{{ route('admin.users.update-role', $user) }}"
                                                          class="flex items-center gap-2">
                                                        @csrf
                                                        @method('PATCH')

                                                        <select name="role"
                                                                class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                            <option value="{{ \App\Models\User::ROLE_USER }}" @selected($user->isUser())>User</option>
                                                            <option value="{{ \App\Models\User::ROLE_ADMIN }}" @selected($user->isAdmin())>Admin</option>
                                                        </select>

                                                        <x-primary-button class="!px-3 !py-2 !text-xs">
                                                            {{ __('Simpan') }}
                                                        </x-primary-button>
                                                    </form>

                                                    <form method="POST"
                                                          action="{{ route('admin.users.destroy', $user) }}"
                                                          onsubmit="return confirm('Hapus pengguna {{ $user->name }}?');">
                                                        @csrf
                                                        @method('DELETE')

                                                        <x-danger-button class="!px-3 !py-2 !text-xs">
                                                            {{ __('Hapus') }}
                                                        </x-danger-button>
                                                    </form>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">
                                            Belum ada pengguna.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
