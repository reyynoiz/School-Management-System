<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Kelas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-800">Daftar Kelas</h3>
                    <a href="{{ route('classes.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded">
                        + Tambah Kelas
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table id="classes-table" class="min-w-full text-sm w-full">
                        <thead>
                            <tr class="text-left text-xs uppercase text-gray-500 border-b">
                                <th class="py-2 pr-4">No.</th>
                                <th class="py-2 pr-4">Nama Kelas</th>
                                <th class="py-2 pr-4">Tingkat</th>
                                <th class="py-2 pr-4">Aksi</th>
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
        var table = $('#classes-table').DataTable({
            ajax: { url: "{{ route('classes.data') }}", dataSrc: 'data' },
            columns: [
                { data: 'no' },
                { data: 'name' },
                { data: 'level' },
                {
                    data: null, orderable: false, searchable: false,
                    render: row => '<a href="' + row.edit_url + '" class="text-indigo-600 hover:underline mr-3">Edit</a>' +
                        '<button type="button" class="text-red-600 hover:underline btn-delete" data-id="' + row.id + '">Hapus</button>'
                }
            ],
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