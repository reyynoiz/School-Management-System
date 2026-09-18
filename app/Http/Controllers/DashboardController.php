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
                'students' => Student::where('archived', 0)->count(),
                'teachers' => Teacher::count(),
                'classes'  => SchoolClass::count(),
                'subjects' => Subject::count(),
            ];

            $latestStudents = Student::where('archived', 0)
                ->with('schoolClass')
                ->latest()
                ->take(5)
                ->get();

            return view('dashboard', compact('summary', 'latestStudents'));
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
