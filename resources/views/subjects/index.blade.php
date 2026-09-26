<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mata Pelajaran
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-breadcrumb :items="['Subjects' => null]" />

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-800">Daftar Mata Pelajaran</h3>
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('subjects.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded">
                            + Tambah Mata Pelajaran
                        </a>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table id="subjects-table" class="min-w-full text-sm w-full">
                        <thead>
                            <tr class="text-left text-xs uppercase text-gray-500 border-b">
                                <th class="py-2 pr-4">No.</th>
                                <th class="py-2 pr-4">Kode</th>
                                <th class="py-2 pr-4">Nama Mata Pelajaran</th>
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

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script>
    $(function () {
        var isAdmin = @json(Auth::user()->isAdmin());

        var columns = [
            { data: 'no' },
            { data: 'code' },
            { data: 'name' },
        ];

        if (isAdmin) {
            columns.push({
                data: null, orderable: false, searchable: false,
                render: row => '<a href="' + row.edit_url + '" class="text-indigo-600 hover:underline mr-3">Edit</a>' +
                    '<button type="button" class="text-red-600 hover:underline btn-delete" data-id="' + row.id + '">Hapus</button>'
            });
        }

        $('#subjects-table').DataTable({
            ajax: { url: "{{ route('subjects.data') }}", dataSrc: 'data' },
            columns: columns,
            language: {
                emptyTable: 'Belum ada data mata pelajaran.', zeroRecords: 'Data tidak ditemukan.',
                search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data', infoEmpty: 'Tidak ada data',
                paginate: { previous: 'Sebelumnya', next: 'Berikutnya' }
            }
        });

        $(document).on('click', '.btn-delete', function () {
            if (!confirm('Yakin ingin menghapus mata pelajaran ini?')) return;

            var id = $(this).data('id');
            var form = $('<form>', {
                action: "{{ url('subjects') }}/" + id,
                method: 'POST'
            }).append('@csrf').append('@method("DELETE")');

            form.appendTo('body').submit();
        });
    });
    </script>
    @endpush
</x-app-layout>