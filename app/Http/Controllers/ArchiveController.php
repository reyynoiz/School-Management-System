<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArchiveController extends Controller
{
    private const TYPES = [
        'students' => ['model' => Student::class,     'label' => 'Siswa',          'ident' => 'NIS'],
        'teachers' => ['model' => Teacher::class,     'label' => 'Guru',           'ident' => 'NIP'],
        'classes'  => ['model' => SchoolClass::class, 'label' => 'Kelas',          'ident' => 'Tingkat'],
        'subjects' => ['model' => Subject::class,     'label' => 'Mata Pelajaran', 'ident' => 'Kode'],
    ];

    private function resolve(string $type): array
    {
        abort_unless(array_key_exists($type, self::TYPES), 404);

        return self::TYPES[$type];
    }

    public function index(Request $request): View
    {
        $type = $request->query('type', 'students');
        $meta = $this->resolve($type);

        return view('archives.index', [
            'type'  => $type,
            'meta'  => $meta,
            'types' => self::TYPES,
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        $type = $request->query('type', 'students');
        $meta = $this->resolve($type);

        $query = $meta['model']::withArchived()
            ->where('archived', 1)
            ->orderByDesc('updated_at');

        if ($type === 'students') {
            $query->with('schoolClass');
        } elseif ($type === 'teachers') {
            $query->with('subject');
        }

        $data = $query->get()->map(function ($item, $index) use ($type) {
            [$ident, $info] = match ($type) {
                'students' => [$item->nis, $item->schoolClass->name ?? '-'],
                'teachers' => [$item->nip, $item->subject->name ?? '-'],
                'classes'  => [$item->level, '-'],
                'subjects' => [$item->code, '-'],
            };

            return [
                'no'          => $index + 1,
                'ident'       => $ident,
                'name'        => $item->name,
                'info'        => $info,
                'archived_at' => formatTanggalIndo($item->updated_at),
                'restore_url' => route('archives.restore', [$type, $item->id]),
                'delete_url'  => route('archives.destroy', [$type, $item->id]),
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function restore(string $type, int $id): RedirectResponse
    {
        $meta = $this->resolve($type);

        $item = $meta['model']::withArchived()->where('archived', 1)->findOrFail($id);
        $item->archived = 0;
        $item->save();

        return redirect()
            ->route('archives.index', ['type' => $type])
            ->with('success', 'Data ' . strtolower($meta['label']) . ' berhasil dipulihkan.');
    }

    public function destroy(string $type, int $id): RedirectResponse
    {
        $meta = $this->resolve($type);

        $item = $meta['model']::withArchived()->where('archived', 1)->findOrFail($id);
        $item->delete();

        return redirect()
            ->route('archives.index', ['type' => $type])
            ->with('success', 'Data ' . strtolower($meta['label']) . ' dihapus permanen.');
    }
}