<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherRequest;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    //
    public function index(): View
    {
        return view('teachers.index');
    }

    public function data(): JsonResponse
    {
        $teachers = Teacher::with('subject')->orderBy('name')->get();

        $data = $teachers->map(function ($teacher, $index) {
            return [
                'no'           => $index + 1,
                'id'           => $teacher->id,
                'nip'          => $teacher->nip,
                'name'         => $teacher->name,
                'gender'       => $teacher->gender === 'L' ? 'Laki-laki' : 'Perempuan',
                'subject_name' => $teacher->subject->name ?? '-',
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
        Teacher::create($request->validated());

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
        $teacher->update($request->validated());

        return redirect()->route('teachers.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->archive();

        return redirect()->route('teachers.index')->with('success', 'Data guru berhasil dihapus.');
    }
}
