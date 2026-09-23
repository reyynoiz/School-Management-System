<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectRequest;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    //
    public function index(): View
    {
        return view('subjects.index');
    }

    public function data(): JsonResponse
    {
        $subjects = Subject::orderBy('name')->get();

        $data = $subjects->map(function ($subject, $index) {
            return [
                'no'         => $index + 1,
                'id'         => $subject->id,
                'code'       => $subject->code,
                'name'       => $subject->name,
                'edit_url'   => route('subjects.edit', $subject->id),
                'delete_url' => route('subjects.destroy', $subject->id),
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function create(): View
    {
        return view('subjects.add');
    }

    public function store(SubjectRequest $request): RedirectResponse
    {
        Subject::create($request->validated());

        return redirect()->route('subjects.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $subject = Subject::findOrFail($id);

        return view('subjects.edit', compact('subject'));
    }

    public function update(SubjectRequest $request, int $id): RedirectResponse
    {
        $subject = Subject::findOrFail($id);
        $subject->update($request->validated());

        return redirect()->route('subjects.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $subject = Subject::findOrFail($id);
        $subject->archive();

        return redirect()->route('subjects.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
