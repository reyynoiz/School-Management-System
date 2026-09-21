<?php

namespace App\Http\Controllers;
use App\Http\Requests\StudentRequest;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    //
    public function index(): View
    {
        return view('students.index');
    }

    public function data(): JsonResponse
    {
        $students = Student::with('schoolClass')->orderBy('name')->get();

        $data = $students->map(function ($student, $index) {
            return [
                'no'         => $index + 1,
                'id'         => $student->id,
                'nis'        => $student->nis,
                'name'       => $student->name,
                'gender'     => $student->gender === 'L' ? 'Laki-laki' : 'Perempuan',
                'class_name' => $student->schoolClass->name ?? '-',
                'edit_url'   => route('students.edit', $student->id),
                'delete_url' => route('students.destroy', $student->id),
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function create(): View
    {
        $classes = SchoolClass::orderBy('name')->get();

        return view('students.add', compact('classes'));
    }

    public function store(StudentRequest $request): RedirectResponse
    {
        Student::create($request->validated());

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit($id): View
    {
        $student = Student::findOrFail($id);
        $classes = SchoolClass::orderBy('name')->get();

        return view('students.edit', compact('student', 'classes'));
    }

    public function update(StudentRequest $request, $id): RedirectResponse
    {
        $student = Student::findOrFail($id);
        $student->update($request->validated());

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $student = Student::findOrFail($id);
        $student->update(['archived' => 1]);

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
