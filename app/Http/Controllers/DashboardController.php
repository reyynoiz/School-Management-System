<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    //Tampilan dashboard berdasarkan role
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->isAdmin()) {
            $summary = [
                'users'    => User::count(),
                'students' => Student::count(),
                'teachers' => Teacher::count(),
                'classes'  => SchoolClass::count(),
                'subjects' => Subject::count(),
            ];

            $latestStudents = Student::with('schoolClass')
                ->latest()
                ->take(5)
                ->get();

            $classes = SchoolClass::withCount('students')->orderBy('name')->get();
            $classChart = [
                'labels' => $classes->pluck('name'),
                'values' => $classes->pluck('students_count'),
            ];

            $gender = Student::selectRaw('gender, count(*) as total')
                ->groupBy('gender')
                ->pluck('total', 'gender');
            $genderChart = [
                'labels' => ['Laki-laki', 'Perempuan'],
                'values' => [$gender['L'] ?? 0, $gender['P'] ?? 0],
            ];

            return view('dashboard', compact('summary', 'latestStudents', 'classChart', 'genderChart'));
        }

        if ($user->isTeacher()) {
            $teacher = $user->teacher()->with('subject')->first();

            return view('dashboard', compact('teacher'));
        }

        // student
        $student = $user->student()->with('schoolClass')->first();

        return view('dashboard', compact('student'));
    }
    
}
