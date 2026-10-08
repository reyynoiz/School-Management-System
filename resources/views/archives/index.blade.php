<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Archive Management
        </h2>
    </x-slot>

    <div class="py-4 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-breadcrumb :items="['Archive' => null]" />

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach ($types as $key => $item)
                        <a href="{{ route('archives.index', ['type' => $key]) }}"
                           class="px-4 py-2 rounded text-sm font-medium {{ $type === $key ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>

                <h3 class="font-semibold text-gray-800 mb-1">Data {{ $meta['label'] }} Ter-arsip</h3>
                <p class="text-xs text-gray-400 mb-4">Restore mengembalikan data ke daftar aktif. Hapus Permanen tidak dapat dibatalkan.</p>

                <div class="overflow-x-auto">
                    <table id="archive-table" class="min-w-full text-sm w-full">
                        <thead>
                            <tr class="text-left text-xs uppercase text-gray-500 border-b">
                                <th class="py-2 pr-4">No.</th>
                                <th class="py-2 pr-4">{{ $meta['ident'] }}</th>
                                <th class="py-2 pr-4">Nama</th>
                                <th class="py-2 pr-4">Keterangan</th>
                                <th class="py-2 pr-4">Diarsipkan</th>
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
        function submitForm(url, method) {
            var form = $('<form>', { action: url, method: 'POST' })
                .append('@csrf');
            if (method === 'DELETE') {
                form.append('@method("DELETE")');
            }
            form.appendTo('body').submit();
        }

        $('#archive-table').DataTable({
            responsive: true,
            autoWidth: false,
            ajax: { url: "{{ route('archives.data', ['type' => $type]) }}", dataSrc: 'data' },
            columns: [
                { data: 'no', responsivePriority: 4 },
                { data: 'ident', responsivePriority: 3 },
                { data: 'name', responsivePriority: 1 },
                { data: 'info', responsivePriority: 5 },
                { data: 'archived_at', responsivePriority: 6 },
                {
                    data: null, orderable: false, searchable: false, responsivePriority: 2,
                    render: row =>
                        '<button type="button" class="text-green-600 hover:underline mr-3 btn-restore" data-url="' + row.restore_url + '">Restore</button>' +
                        '<button type="button" class="text-red-600 hover:underline btn-force-delete" data-url="' + row.delete_url + '">Hapus Permanen</button>'
                }
            ],
            language: {
                emptyTable: 'Tidak ada data ter-arsip.', zeroRecords: 'Data tidak ditemukan.',
                search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data', infoEmpty: 'Tidak ada data',
                paginate: { previous: 'Sebelumnya', next: 'Berikutnya' }
            }
        });

        $(document).on('click', '.btn-restore', function () {
            if (!confirm('Pulihkan data ini ke daftar aktif?')) return;
            submitForm($(this).data('url'), 'POST');
        });

        $(document).on('click', '.btn-force-delete', function () {
            if (!confirm('Hapus permanen? Data tidak bisa dikembalikan lagi.')) return;
            submitForm($(this).data('url'), 'DELETE');
        });
    });
    </script>
    @endpush
</x-app-layout>