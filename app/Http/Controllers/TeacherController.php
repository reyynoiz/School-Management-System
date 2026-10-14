<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherRequest;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use App\Traits\HandlesPhotoUpload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherController extends Controller
{
    use HandlesPhotoUpload;
    public function index(): View
    {
        $subjects = Subject::orderBy('name')->get();

        return view('teachers.index', compact('subjects'));
    }

    public function data(Request $request): JsonResponse
    {
        $teachers = Teacher::with('subject')
            ->when($request->filled('subject_id'), fn ($q) => $q->where('subject_id', $request->subject_id))
            ->when($request->filled('gender'), fn ($q) => $q->where('gender', $request->gender))
            ->orderBy('name')
            ->get();

        $data = $teachers->map(function ($teacher, $index) {
            return [
                'no'           => $index + 1,
                'id'           => $teacher->id,
                'nip'          => $teacher->nip,
                'name'         => $teacher->name,
                'gender'       => $teacher->gender === 'L' ? 'Laki-laki' : 'Perempuan',
                'subject_name' => $teacher->subject->name ?? '-',
                'photo_url'    => $teacher->photo ? asset('uploads/teachers/' . $teacher->photo) : null,
                'created_at'   => formatTanggalIndo($teacher->created_at),
                'edit_url'     => route('teachers.edit', $teacher->id),
                'delete_url'   => route('teachers.destroy', $teacher->id),
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function create(): View
    {
        $subjects = Subject::orderBy('name')->get();
        $users = User::where('role', 'teacher')
            ->whereDoesntHave('teacher')
            ->orderBy('name')
            ->get();

        return view('teachers.add', compact('subjects', 'users'));
    }

    public function store(TeacherRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->storePhoto($request->file('photo'), 'teachers');
        } else {
            unset($data['photo']);
        }

        Teacher::create($data);

        return redirect()->route('teachers.index')->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $teacher = Teacher::findOrFail($id);
        $subjects = Subject::orderBy('name')->get();
        $users = User::where('role', 'teacher')
            ->where(function ($query) use ($teacher) {
                $query->whereDoesntHave('teacher')
                    ->orWhere('id', $teacher->user_id);
            })
            ->orderBy('name')
            ->get();

        return view('teachers.edit', compact('teacher', 'subjects', 'users'));
    }

    public function update(TeacherRequest $request, int $id): RedirectResponse
    {
        $teacher = Teacher::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $newPhoto = $this->storePhoto($request->file('photo'), 'teachers');
            $this->deletePhoto($teacher->photo, 'teachers');
            $data['photo'] = $newPhoto;
        } else {
            unset($data['photo']);
        }

        $teacher->update($data);

        return redirect()->route('teachers.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->archive();

        return redirect()->route('teachers.index')->with('success', 'Data guru berhasil dihapus.');
    }
}