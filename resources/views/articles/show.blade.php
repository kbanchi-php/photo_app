+ @extends('layouts.app')
@section('title', '詳細画面')
@section('content')
    <h1>画像詳細</h1>
    <section>
        <article class="card shadow position-relative">
            <figure class="m-3">
                <div class="row">
                    <div class="col-6">
                        <img src="{{ $article->image_url }}" width="100%">
                    </div>
                    <div class="col-6">
                        <figcaption>
                            <h1>
                                {{ $article->caption }}
                            </h1>
                            <h3>
                                {{ $article->info }}
                            </h3>
                        </figcaption>
                    </div>
                </div>
            </figure>
            <a href="{{ route('articles.edit', $article) }}">
                <i class="fas fa-edit position-absolute top-0 end-0 fs-1"></i>
            </a>
        </article>
    </section>
@endsection
