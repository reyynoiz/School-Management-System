<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola User</h2>
    </x-slot>

    <div class="py-4 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-breadcrumb :items="['Users' => null]" />

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-1">Daftar Akun</h3>
                <p class="text-xs text-gray-400 mb-4">Akun nonaktif tidak dapat login. Reset password membuat password acak baru.</p>

                <div class="overflow-x-auto">
                    <table id="users-table" class="min-w-full text-sm w-full">
                        <thead>
                            <tr class="text-left text-xs uppercase text-gray-500 border-b">
                                <th class="py-2 pr-4">No.</th>
                                <th class="py-2 pr-4">Nama</th>
                                <th class="py-2 pr-4">Username</th>
                                <th class="py-2 pr-4">Email</th>
                                <th class="py-2 pr-4">Role</th>
                                <th class="py-2 pr-4">Status</th>
                                <th class="py-2 pr-4">Login Terakhir</th>
                                <th class="py-2 pr-4">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @push('styles')
        <link href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
        <link href="{{ asset('css/datatables-custom.css') }}" rel="stylesheet">
    @endpush

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
    $(function () {
        $('#users-table').DataTable({
            responsive: true,
            autoWidth: false,
            ajax: { url: "{{ route('users.data') }}", dataSrc: 'data' },
            columns: [
                { data: 'no', responsivePriority: 5 },
                { data: 'name', responsivePriority: 1 },
                { data: 'username', responsivePriority: 4 },
                { data: 'email', responsivePriority: 6 },
                { data: 'role', responsivePriority: 5 },
                {
                    data: 'active', responsivePriority: 3,
                    render: a => a
                        ? '<span class="px-2 py-0.5 text-xs rounded bg-green-100 text-green-700">Aktif</span>'
                        : '<span class="px-2 py-0.5 text-xs rounded bg-red-100 text-red-700">Nonaktif</span>'
                },
                { data: 'last_login', responsivePriority: 7 },
                {
                    data: null, orderable: false, searchable: false, responsivePriority: 2,
                    render: function (row) {
                        if (row.is_self) return '<span class="text-xs text-gray-400">Akun Anda</span>';
                        return '<button type="button" class="text-indigo-600 hover:underline mr-3 btn-status" data-url="' + row.status_url + '" data-active="' + (row.active ? 1 : 0) + '">' +
                                   (row.active ? 'Nonaktifkan' : 'Aktifkan') + '</button>' +
                               '<button type="button" class="text-amber-600 hover:underline btn-reset" data-url="' + row.reset_url + '">Reset Password</button>';
                    }
                }
            ],
            language: {
                emptyTable: 'Belum ada akun.', zeroRecords: 'Data tidak ditemukan.',
                search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data', infoEmpty: 'Tidak ada data',
                paginate: { previous: 'Sebelumnya', next: 'Berikutnya' }
            }
        });

        function post(url) {
            $('<form>', { action: url, method: 'POST' }).append('@csrf').appendTo('body').submit();
        }

        $(document).on('click', '.btn-status', function () {
            var msg = $(this).data('active') ? 'Nonaktifkan akun ini? Pengguna tidak akan bisa login.' : 'Aktifkan kembali akun ini?';
            if (confirm(msg)) post($(this).data('url'));
        });

        $(document).on('click', '.btn-reset', function () {
            if (confirm('Reset password akun ini? Password lama tidak bisa dipakai lagi.')) post($(this).data('url'));
        });
    });
    </script>
    @endpush
</x-app-layout>