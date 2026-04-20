<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Update;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function index()
    {
        $updates = Update::orderByDesc('published_at')->paginate(20);
        return view('admin.updates.index', compact('updates'));
    }

    public function create()
    {
        return view('admin.updates.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'body' => 'required|string|max:2000',
            'visible' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        $data['visible'] = $request->has('visible');
        $data['published_at'] = $data['published_at'] ?? now();

        Update::create($data);

        return redirect('/admin/updates')->with('success', 'Actualité publiée.');
    }

    public function edit(int $id)
    {
        $update = Update::findOrFail($id);
        return view('admin.updates.edit', compact('update'));
    }

    public function update(Request $request, int $id)
    {
        $update = Update::findOrFail($id);

        $data = $request->validate([
            'body' => 'sometimes|string|max:2000',
            'visible' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        $data['visible'] = $request->has('visible');
        $update->update($data);

        return redirect('/admin/updates')->with('success', 'Actualité mise à jour.');
    }

    public function destroy(int $id)
    {
        $update = Update::findOrFail($id);
        $update->delete();
        return redirect('/admin/updates')->with('success', 'Actualité supprimée.');
    }
}
