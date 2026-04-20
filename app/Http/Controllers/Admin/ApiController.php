<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Track;
use App\Models\SocialLink;
use App\Models\Update;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function toggle(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer',
            'model' => 'required|in:Album,Track,SocialLink,Update',
            'field' => 'required|in:active,visible',
            'value' => 'required|boolean',
        ]);

        $model = '\\App\\Models\\' . $data['model'];
        $record = $model::findOrFail($data['id']);
        $record->update([$data['field'] => $data['value']]);

        return response()->json(['success' => true]);
    }

    public function reorder(Request $request)
    {
        $data = $request->validate([
            'model' => 'required|in:Album,Track,SocialLink',
            'ids' => 'required|array',
        ]);

        $model = '\\App\\Models\\' . $data['model'];
        foreach ($data['ids'] as $sort => $id) {
            $model::where('id', $id)->update(['sort' => $sort]);
        }

        return response()->json(['success' => true]);
    }
}
