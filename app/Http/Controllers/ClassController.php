<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassRequest;
use App\Models\SchoolClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    //
    public function index(): View
    {
        return view('classes.index');
    }

    public function data(): JsonResponse
    {
        $classes = SchoolClass::orderBy('name')->get();

        $data = $classes->map(function ($class, $index) {
            return [
                'no'         => $index + 1,
                'id'         => $class->id,
                'name'       => $class->name,
                'level'      => $class->level,
                'edit_url'   => route('classes.edit', $class->id),
                'delete_url' => route('classes.destroy', $class->id),
            ];
        });

        return response()->json(['data' => $data]);
    }
    public function create(): View
    {
        return view('classes.add');
    }

    public function store(ClassRequest $request): RedirectResponse
    {
        SchoolClass::create($request->validated());

        return redirect()->route('classes.index')->with('success', 'Data kelas berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $class = SchoolClass::findOrFail($id);

        return view('classes.edit', compact('class'));
    }

    public function update(ClassRequest $request, int $id): RedirectResponse
    {
        $class = SchoolClass::findOrFail($id);
        $class->update($request->validated());

        return redirect()->route('classes.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $class = SchoolClass::findOrFail($id);
        $class->archive();

        return redirect()->route('classes.index')->with('success', 'Data kelas berhasil dihapus.');
    }
}
