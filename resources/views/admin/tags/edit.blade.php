<x-app-layout>
<section class="tag-edit-page">
    <h1 class="tag-edit-title">タグ編集</h1>
    <form method="POST" action="/admin/tags/{{ $tag->id }}" class="tag-edit-card">
        @csrf
        @method('PUT')
        <label class="tag-edit-field">タグ名
            <input class="tag-edit-input" name="name" value="{{ old('name', $tag->name) }}">
            @error('name')<p class="error">{{ $message }}</p>@enderror
        </label>
        <div class="tag-edit-actions">
            <a class="tag-edit-back" href="/admin">戻る</a>
            <button class="tag-edit-submit" type="submit">更新</button>
        </div>
    </form>
</section>
</x-app-layout>
