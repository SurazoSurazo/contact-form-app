<x-app-layout>
<section class="admin-detail-page">
    <h1 class="admin-detail-title">お問い合わせ詳細</h1>
    <div class="admin-detail-panel">
        <dl class="admin-detail-table">
            <div class="admin-detail-row"><dt>お名前</dt><dd>{{ $contact->full_name }}</dd></div>
            <div class="admin-detail-row"><dt>性別</dt><dd>{{ $contact->gender_label }}</dd></div>
            <div class="admin-detail-row"><dt>メールアドレス</dt><dd>{{ $contact->email }}</dd></div>
            <div class="admin-detail-row"><dt>電話番号</dt><dd>{{ $contact->tel }}</dd></div>
            <div class="admin-detail-row"><dt>住所</dt><dd>{{ $contact->address }}</dd></div>
            <div class="admin-detail-row"><dt>建物名</dt><dd>{{ $contact->building }}</dd></div>
            <div class="admin-detail-row"><dt>お問い合わせの種類</dt><dd>{{ $contact->category->content }}</dd></div>
            <div class="admin-detail-row"><dt>タグ</dt><dd>{{ $contact->tags->pluck('name')->join('、') }}</dd></div>
            <div class="admin-detail-row admin-detail-row-long"><dt>お問い合わせ内容</dt><dd>{{ $contact->detail }}</dd></div>
        </dl>
        <div class="admin-detail-actions">
            <a class="admin-detail-back" href="/admin">一覧に戻る</a>
            <form method="POST" action="/admin/contacts/{{ $contact->id }}">
                @csrf
                @method('DELETE')
                <button class="admin-detail-delete" type="submit">削除</button>
            </form>
        </div>
    </div>
</section>
</x-app-layout>
