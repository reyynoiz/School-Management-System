<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (Auth::user()->isAdmin())
                {{-- Ringkasan untuk Admin --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 border-l-4 border-indigo-500">
                        <p class="text-xs uppercase text-gray-500 font-medium">Pengguna (Users)</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($summary['users'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 border-l-4 border-blue-500">
                        <p class="text-xs uppercase text-gray-500 font-medium">Siswa (Students)</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($summary['students'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 border-l-4 border-green-500">
                        <p class="text-xs uppercase text-gray-500 font-medium">Guru (Teachers)</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($summary['teachers'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 border-l-4 border-yellow-500">
                        <p class="text-xs uppercase text-gray-500 font-medium">Kelas (Classes)</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($summary['classes'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 border-l-4 border-red-500">
                        <p class="text-xs uppercase text-gray-500 font-medium">Mapel (Subjects)</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($summary['subjects'], 0, ',', '.') }}</p>
                    </div>
                </div>

                {{-- Grafik --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-semibold text-gray-800 mb-4">Jumlah Siswa per Kelas</h3>
                        <div class="relative h-64">
                            <canvas id="classChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-semibold text-gray-800 mb-4">Komposisi Gender</h3>
                        <div class="relative h-64">
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-semibold text-gray-800">Data Siswa Terbaru</h3>
                            <a href="{{ route('students.index') }}" class="text-sm text-indigo-600 hover:underline">Lihat Semua</a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="text-left text-xs uppercase text-gray-500 border-b">
                                        <th class="py-2 pr-4">NIS</th>
                                        <th class="py-2 pr-4">Nama Siswa</th>
                                        <th class="py-2 pr-4">Kelas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($latestStudents as $student)
                                        <tr class="border-b last:border-0">
                                            <td class="py-2 pr-4">{{ $student->nis }}</td>
                                            <td class="py-2 pr-4">{{ $student->name }}</td>
                                            <td class="py-2 pr-4">{{ $student->schoolClass->name ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-4 text-center text-gray-400">Belum ada data siswa.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            @elseif (Auth::user()->isTeacher())
                {{-- Ringkasan untuk Teacher --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Profil Guru</h3>
                    @if ($teacher)
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <dt class="text-gray-500">NIP</dt>
                                <dd class="font-medium text-gray-800">{{ $teacher->nip }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">Nama</dt>
                                <dd class="font-medium text-gray-800">{{ $teacher->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">Mata Pelajaran</dt>
                                <dd class="font-medium text-gray-800">{{ $teacher->subject->name ?? '-' }}</dd>
                            </div>
                        </dl>
                    @else
                        <p class="text-gray-500 text-sm">Data guru untuk akun ini belum terhubung. Silakan hubungi administrator.</p>
                    @endif
                </div>

            @else
                {{-- Ringkasan untuk Student --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Profil Siswa</h3>
                    @if ($student)
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <dt class="text-gray-500">NIS</dt>
                                <dd class="font-medium text-gray-800">{{ $student->nis }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">Nama</dt>
                                <dd class="font-medium text-gray-800">{{ $student->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">Kelas</dt>
                                <dd class="font-medium text-gray-800">{{ $student->schoolClass->name ?? '-' }}</dd>
                            </div>
                        </dl>
                    @else
                        <p class="text-gray-500 text-sm">Data siswa untuk akun ini belum terhubung. Silakan hubungi administrator.</p>
                    @endif
                </div>
            @endif

        </div>
    </div>

    @if (Auth::user()->isAdmin())
        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            new Chart(document.getElementById('classChart'), {
                type: 'bar',
                data: {
                    labels: @json($classChart['labels']),
                    datasets: [{
                        label: 'Jumlah Siswa',
                        data: @json($classChart['values']),
                        backgroundColor: '#6366f1',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });

            new Chart(document.getElementById('genderChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($genderChart['labels']),
                    datasets: [{
                        data: @json($genderChart['values']),
                        backgroundColor: ['#3b82f6', '#ec4899']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        </script>
        @endpush
    @endif
</x-app-layout>