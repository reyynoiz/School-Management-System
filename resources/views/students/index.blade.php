<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Siswa
        </h2>
    </x-slot>

    <div class="py-4 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-breadcrumb :items="['Students' => null]" />

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-800">Daftar Siswa</h3>
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('students.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded">
                            + Tambah Siswa
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                    <div>
                        <label for="filter-class" class="block text-xs font-medium text-gray-500 mb-1">Kelas</label>
                        <select id="filter-class" class="block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Kelas</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filter-gender" class="block text-xs font-medium text-gray-500 mb-1">Jenis Kelamin</label>
                        <select id="filter-gender" class="block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="button" id="filter-reset" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium px-4 py-2 rounded">
                            Reset Filter
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table id="students-table" class="min-w-full text-sm w-full">
                        <thead>
                            <tr class="text-left text-xs uppercase text-gray-500 border-b">
                                <th class="py-2 pr-4">No.</th>
                                <th class="py-2 pr-4">Foto</th>
                                <th class="py-2 pr-4">NIS</th>
                                <th class="py-2 pr-4">Nama</th>
                                <th class="py-2 pr-4">L/P</th>
                                <th class="py-2 pr-4">Kelas</th>
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
            {
                data: 'photo_url', orderable: false, searchable: false, responsivePriority: 8,
                render: function (url, type, row) {
                    if (url) {
                        return '<img src="' + url + '" alt="" class="h-9 w-9 rounded-full object-cover border border-gray-200">';
                    }
                    return '<div class="h-9 w-9 rounded-full bg-gray-200 flex items-center justify-center text-xs font-medium text-gray-500">' +
                        row.name.charAt(0).toUpperCase() + '</div>';
                }
            },
            { data: 'nis', responsivePriority: 3 },
            { data: 'name', responsivePriority: 1 },
            { data: 'gender', responsivePriority: 6 },
            { data: 'class_name', responsivePriority: 5 },
            { data: 'created_at', responsivePriority: 7 },
        ];

        if (isAdmin) {
            columns.push({
                data: null, orderable: false, searchable: false, responsivePriority: 2,
                render: row => '<a href="' + row.edit_url + '" class="text-indigo-600 hover:underline mr-3">Edit</a>' +
                    '<button type="button" class="text-red-600 hover:underline btn-delete" data-id="' + row.id + '">Hapus</button>'
            });
        }

        var table = $('#students-table').DataTable({
            responsive: true,
            autoWidth: false,
            ajax: {
                url: "{{ route('students.data') }}",
                data: function (d) {
                    d.class_id = $('#filter-class').val();
                    d.gender = $('#filter-gender').val();
                },
                dataSrc: 'data'
            },
            columns: columns,
            language: {
                emptyTable: 'Belum ada data siswa.', zeroRecords: 'Data tidak ditemukan.',
                search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data', infoEmpty: 'Tidak ada data',
                paginate: { previous: 'Sebelumnya', next: 'Berikutnya' }
            }
        });

        $('#filter-class, #filter-gender').on('change', function () {
            table.ajax.reload();
        });

        $('#filter-reset').on('click', function () {
            $('#filter-class, #filter-gender').val('');
            table.ajax.reload();
        });

        $(document).on('click', '.btn-delete', function () {
            if (!confirm('Yakin ingin menghapus data siswa ini?')) return;

            var id = $(this).data('id');
            var form = $('<form>', {
                action: "{{ url('students') }}/" + id,
                method: 'POST'
            }).append('@csrf').append('@method("DELETE")');

            form.appendTo('body').submit();
        });
    });
    </script>
    @endpush
</x-app-layout>