<x-app-layout>
<section class="admin-page">
    <h1 class="admin-title">Admin</h1>

    <form method="GET" action="/admin" class="admin-search">
        <input class="admin-control admin-keyword" name="keyword" value="{{ $filters['keyword'] ?? '' }}" placeholder="名前やメールアドレスを入力してください">
        <select class="admin-control admin-gender" name="gender">
            <option value="0">性別</option>
            @foreach ([1 => '男性', 2 => '女性', 3 => 'その他'] as $value => $label)
                <option value="{{ $value }}" @selected((string) ($filters['gender'] ?? '') === (string) $value)>{{ $label }}</option>
            @endforeach
        </select>
        <select class="admin-control admin-category" name="category_id">
            <option value="">お問い合わせの種類</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) ($filters['category_id'] ?? '') === (string) $category->id)>{{ $category->content }}</option>
            @endforeach
        </select>
        <input class="admin-control admin-date" type="date" name="date" value="{{ $filters['date'] ?? '' }}">
        <button class="admin-search-button" type="submit">検索</button>
        <a class="admin-reset-button" href="/admin">リセット</a>
        <a class="admin-export-button" href="{{ url('/contacts/export?'.http_build_query($filters)) }}">エクスポート</a>
        @error('gender')<p class="error admin-search-error">{{ $message }}</p>@enderror
        @error('category_id')<p class="error admin-search-error">{{ $message }}</p>@enderror
    </form>

    <nav class="admin-pagination" aria-label="Pagination Navigation">
        @if ($contacts->previousPageUrl())
            <a href="{{ $contacts->previousPageUrl() }}">&laquo; Previous</a>
        @else
            <span>&laquo; Previous</span>
        @endif
        @if ($contacts->nextPageUrl())
            <a href="{{ $contacts->nextPageUrl() }}">Next &raquo;</a>
        @else
            <span>Next &raquo;</span>
        @endif
    </nav>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>お名前</th>
                    <th>性別</th>
                    <th>メールアドレス</th>
                    <th>お問い合わせの種類</th>
                    <th>タグ</th>
                    <th aria-label="詳細"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($contacts as $contact)
                    <tr>
                        <td>{{ $contact->full_name }}</td>
                        <td>{{ $contact->gender_label }}</td>
                        <td>{{ $contact->email }}</td>
                        <td>{{ $contact->category->content }}</td>
                        <td>
                            <div class="admin-tags">
                                @foreach ($contact->tags as $tag)
                                    <span>{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td><a class="admin-detail-link" href="/admin/contacts/{{ $contact->id }}">詳細</a></td>
                    </tr>
                @empty
                    <tr><td class="admin-empty" colspan="6">お問い合わせはありません</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <section class="admin-tag-card">
        <div class="admin-tag-header">
            <h2>タグ管理</h2>
            <p>お問い合わせフォームで選択できるタグを追加・編集できます</p>
        </div>

        <form method="POST" action="/admin/tags" class="admin-tag-form">
            @csrf
            <label>タグ名
                <input class="admin-control" name="name" value="{{ old('name') }}" placeholder="例: 新機能の要望">
            </label>
            <button class="admin-tag-add" type="submit">追加</button>
        </form>
        @error('name')<p class="error mb-4">{{ $message }}</p>@enderror

        <table class="admin-tag-table">
            <thead>
                <tr>
                    <th>タグ名</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tags as $tag)
                    <tr>
                        <td>{{ $tag->name }}</td>
                        <td>
                            <div class="admin-tag-actions">
                                <a class="admin-tag-edit" href="/admin/tags/{{ $tag->id }}/edit">編集</a>
                                <form method="POST" action="/admin/tags/{{ $tag->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="admin-tag-delete" type="submit">削除</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</section>
</x-app-layout>
