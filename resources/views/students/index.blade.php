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

            @if (session('import_errors'))
                <div class="mb-4 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded text-sm">
                    <p class="font-medium mb-1">Import dibatalkan, tidak ada data yang tersimpan:</p>
                    <ul class="list-disc ml-5">
                        @foreach (session('import_errors') as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif
            @error('file')
                <div class="mb-4 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded text-sm">{{ $message }}</div>
            @enderror

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Header Card & Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-4">
                    <h3 class="font-semibold text-gray-800">Daftar Siswa</h3>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('students.export-pdf') }}" id="btn-export-pdf" class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-3 py-2 rounded">
                            Export PDF
                        </a>

                        @if (Auth::user()->isAdmin())
                            <button type="button" onclick="document.getElementById('modal-import').classList.remove('hidden')" class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-3 py-2 rounded">
                                Import Excel/CSV
                            </button>
                            <a href="{{ route('students.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-3 py-2 rounded">
                                + Tambah Siswa
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Filter Section -->
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

                <!-- Datatable Container -->
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

    <!-- Modal Import File Excel / CSV -->
    <div id="modal-import" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Import Data Siswa</h3>
            <form action="{{ route('students.import-excel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Pilih File (.xlsx, .xls, .csv)</label>
                    <input type="file" name="file" required class="block w-full text-sm text-gray-500 border border-gray-300 rounded p-1">
                    <p class="text-[10px] text-gray-500 mt-1">Format Kolom Header Excel: <strong>nis, nama, jenis_kelamin, kelas, username, email</strong></p>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-import').classList.add('hidden')" class="bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-green-600 text-white text-sm px-4 py-2 rounded hover:bg-green-700">Upload & Import</button>
                </div>
            </form>
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

        function updateExportPdfUrl() {
            var classId = $('#filter-class').val();
            var gender = $('#filter-gender').val();
            var url = "{{ route('students.export-pdf') }}?class_id=" + classId + "&gender=" + gender;
            $('#btn-export-pdf').attr('href', url);
        }

        $('#filter-class, #filter-gender').on('change', function () {
            table.ajax.reload();
            updateExportPdfUrl();
        });

        $('#filter-reset').on('click', function () {
            $('#filter-class, #filter-gender').val('');
            table.ajax.reload();
            updateExportPdfUrl();
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