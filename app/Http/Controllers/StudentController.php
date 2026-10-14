<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use App\Traits\HandlesPhotoUpload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    use HandlesPhotoUpload;
    public function index(): View
    {
        $classes = SchoolClass::orderBy('name')->get();

        return view('students.index', compact('classes'));
    }

    public function data(Request $request): JsonResponse
    {
        $students = Student::with('schoolClass')
            ->when($request->filled('class_id'), fn ($q) => $q->where('class_id', $request->class_id))
            ->when($request->filled('gender'), fn ($q) => $q->where('gender', $request->gender))
            ->orderBy('name')
            ->get();

        $data = $students->map(function ($student, $index) {
            return [
                'no'         => $index + 1,
                'id'         => $student->id,
                'nis'        => $student->nis,
                'name'       => $student->name,
                'gender'     => $student->gender === 'L' ? 'Laki-laki' : 'Perempuan',
                'class_name' => $student->schoolClass->name ?? '-',
                'photo_url'  => $student->photo ? asset('uploads/students/' . $student->photo) : null,
                'created_at' => formatTanggalIndo($student->created_at),
                'edit_url'   => route('students.edit', $student->id),
                'delete_url' => route('students.destroy', $student->id),
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function create(): View
    {
        $classes = SchoolClass::orderBy('name')->get();
        $users = User::where('role', 'student')
            ->whereDoesntHave('student')
            ->orderBy('name')
            ->get();

        return view('students.add', compact('classes', 'users'));
    }

    public function store(StudentRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->storePhoto($request->file('photo'), 'students');
        } else {
            unset($data['photo']);
        }

        Student::create($data);

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit($id): View
    {
        $student = Student::findOrFail($id);
        $classes = SchoolClass::orderBy('name')->get();
        $users = User::where('role', 'student')
            ->where(function ($query) use ($student) {
                $query->whereDoesntHave('student')
                      ->orWhere('id', $student->user_id);
            })
            ->orderBy('name')
            ->get();

        return view('students.edit', compact('student', 'classes', 'users'));
    }

    public function update(StudentRequest $request, $id): RedirectResponse
    {
        $student = Student::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $newPhoto = $this->storePhoto($request->file('photo'), 'students');
            $this->deletePhoto($student->photo, 'students');
            $data['photo'] = $newPhoto;
        } else {
            unset($data['photo']);
        }

        $student->update($data);

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }
}