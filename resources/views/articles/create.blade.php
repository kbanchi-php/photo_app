@extends('layouts.main')
@section('title', '新規登録')
@section('content')
    <div class="col-8 col-offset-2 mx-auto">
        @include('partial.errors')
        <form action="{{ route('articles.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card mb-3">
                <div class="row m-3">
                    <div class="mb-3">
                        <label for="file" class="form-label">画像ファイルを選択してください</label>
                        <input type="file" name="file" id="file" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="caption" class="form-label">イメージの説明を入力してください</label>
                        <input type="text" name="caption" id="caption" class="form-control">
                    </div>
                    <div>
                        <label for="info" class="form-label">その他情報を入力してください</label>
                        <textarea name="info" id="info" rows="5" class="form-control"></textarea>
                    </div>
                    <input type="submit">
                </div>
        </form>
    </div>
@endsection
