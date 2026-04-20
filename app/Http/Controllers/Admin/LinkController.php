<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index()
    {
        $links = SocialLink::orderBy('sort')->get();
        return view('admin.links.index', compact('links'));
    }

    public function create()
    {
        return view('admin.links.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'platform' => 'required|string|max:50',
            'label' => 'required|string|max:100',
            'url' => 'required|url',
            'icon_svg' => 'nullable|string',
            'sort' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        $data['sort'] = $data['sort'] ?? SocialLink::max('sort') + 1;
        $data['active'] = $request->has('active');

        SocialLink::create($data);

        return redirect('/admin/links')->with('success', 'Lien ajouté.');
    }

    public function edit(int $id)
    {
        $link = SocialLink::findOrFail($id);
        return view('admin.links.edit', compact('link'));
    }

    public function update(Request $request, int $id)
    {
        $link = SocialLink::findOrFail($id);

        $data = $request->validate([
            'platform' => 'sometimes|string|max:50',
            'label' => 'sometimes|string|max:100',
            'url' => 'sometimes|url',
            'icon_svg' => 'nullable|string',
            'sort' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        $data['active'] = $request->has('active');
        $link->update($data);

        return redirect('/admin/links')->with('success', 'Lien mis à jour.');
    }

    public function destroy(int $id)
    {
        $link = SocialLink::findOrFail($id);
        $link->delete();
        return redirect('/admin/links')->with('success', 'Lien supprimé.');
    }
}
