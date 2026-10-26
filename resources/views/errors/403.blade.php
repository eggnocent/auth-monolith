<x-guest-layout>
    <div class="text-center">
        <p class="text-sm font-semibold uppercase tracking-widest text-indigo-600">403</p>

        <h1 class="mt-2 text-2xl font-bold text-gray-900">
            Akses Ditolak
        </h1>

        <p class="mt-3 text-sm text-gray-600">
            Anda tidak memiliki akses ke halaman ini. Halaman ini hanya dapat dibuka oleh administrator.
        </p>

        <a href="{{ route('dashboard') }}"
           class="mt-6 inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700">
            Kembali ke Dashboard
        </a>
    </div>
</x-guest-layout>
