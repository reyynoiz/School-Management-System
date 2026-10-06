<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Kelas
        </h2>
    </x-slot>

    <div class="py-4 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-breadcrumb :items="['Classes' => null]" />

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-800">Daftar Kelas</h3>
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('classes.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded">
                            + Tambah Kelas
                        </a>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table id="classes-table" class="min-w-full text-sm w-full">
                        <thead>
                            <tr class="text-left text-xs uppercase text-gray-500 border-b">
                                <th class="py-2 pr-4">No.</th>
                                <th class="py-2 pr-4">Nama Kelas</th>
                                <th class="py-2 pr-4">Tingkat</th>
                                <th class="py-2 pr-4">Terdaftar</th>
                                @if (Auth::user()->isAdmin())
                                    <th class="py-2 pr-4">Aksi</th>
                                @endif
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
        var isAdmin = @json(Auth::user()->isAdmin());

        var columns = [
            { data: 'no', responsivePriority: 4 },
            { data: 'name', responsivePriority: 1 },
            { data: 'level', responsivePriority: 3 },
            { data: 'created_at', responsivePriority: 5 },
        ];

        if (isAdmin) {
            columns.push({
                data: null, orderable: false, searchable: false, responsivePriority: 2,
                render: row => '<a href="' + row.edit_url + '" class="text-indigo-600 hover:underline mr-3">Edit</a>' +
                    '<button type="button" class="text-red-600 hover:underline btn-delete" data-id="' + row.id + '">Hapus</button>'
            });
        }

        $('#classes-table').DataTable({
            responsive: true,
            autoWidth: false,
            ajax: { url: "{{ route('classes.data') }}", dataSrc: 'data' },
            columns: columns,
            language: {
                emptyTable: 'Belum ada data kelas.', zeroRecords: 'Data tidak ditemukan.',
                search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data', infoEmpty: 'Tidak ada data',
                paginate: { previous: 'Sebelumnya', next: 'Berikutnya' }
            }
        });

        $(document).on('click', '.btn-delete', function () {
            if (!confirm('Yakin ingin menghapus data kelas ini?')) return;

            var id = $(this).data('id');
            var form = $('<form>', {
                action: "{{ url('classes') }}/" + id,
                method: 'POST'
            }).append('@csrf').append('@method("DELETE")');

            form.appendTo('body').submit();
        });
    });
    </script>
    @endpush
</x-app-layout>