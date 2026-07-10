<x-guest-layout>
<section class="confirm-page">
    <h1 class="confirm-title">Confirm</h1>
    <div class="confirm-panel">
        @php($genderLabels = [1 => '男性', 2 => '女性', 3 => 'その他'])
        <dl class="confirm-table">
            <div class="confirm-table-row"><dt>お名前</dt><dd>{{ $contact['first_name'] }} {{ $contact['last_name'] }}</dd></div>
            <div class="confirm-table-row"><dt>性別</dt><dd>{{ $genderLabels[$contact['gender']] }}</dd></div>
            <div class="confirm-table-row"><dt>メールアドレス</dt><dd>{{ $contact['email'] }}</dd></div>
            <div class="confirm-table-row"><dt>電話番号</dt><dd>{{ $contact['tel'] }}</dd></div>
            <div class="confirm-table-row"><dt>住所</dt><dd>{{ $contact['address'] }}</dd></div>
            <div class="confirm-table-row"><dt>建物名</dt><dd>{{ $contact['building'] ?? '' }}</dd></div>
            <div class="confirm-table-row"><dt>お問い合わせの種類</dt><dd>{{ $category->content }}</dd></div>
            <div class="confirm-table-row"><dt>タグ</dt><dd>{{ $tags->pluck('name')->join('、') }}</dd></div>
            <div class="confirm-table-row"><dt>お問い合わせ内容</dt><dd>{{ $contact['detail'] }}</dd></div>
        </dl>
        <div class="confirm-actions">
            <form method="POST" action="/contacts">
                @csrf
                @foreach ($contact as $key => $value)
                    @if (is_array($value))
                        @foreach ($value as $item)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <button class="confirm-submit" type="submit">送信</button>
            </form>
            <form method="GET" action="/">
                @foreach ($contact as $key => $value)
                    @if (is_array($value))
                        @foreach ($value as $item)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <button class="confirm-back" type="submit">修正</button>
            </form>
        </div>
    </div>
</section>
</x-guest-layout>
