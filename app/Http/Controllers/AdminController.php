<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexContactRequest;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;

class AdminController extends Controller
{
    public function index(IndexContactRequest $request)
    {
        $filters = $request->validated();
        $contacts = Contact::with(['category', 'tags'])
            ->search($filters)
            ->latest()
            ->paginate(7)
            ->withQueryString();

        return view('admin.index', [
            'contacts' => $contacts,
            'categories' => Category::all(),
            'tags' => Tag::latest()->get(),
            'filters' => $filters,
        ]);
    }

    public function show(Contact $contact)
    {
        $contact->load(['category', 'tags']);

        return view('admin.show', compact('contact'));
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect('/admin')->with('status', 'お問い合わせを削除しました');
    }
}
