<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;

class TagController extends Controller
{
    public function store(StoreTagRequest $request)
    {
        Tag::create($request->validated());

        return redirect('/admin')->with('status', 'タグを追加しました');
    }

    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $tag->update($request->validated());

        return redirect('/admin')->with('status', 'タグを更新しました');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect('/admin')->with('status', 'タグを削除しました');
    }
}
