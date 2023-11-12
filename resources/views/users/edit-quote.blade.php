@extends('layouts.app')
@section('main')

<div class="main__content quotes-edit-page-content">
    <section class="theme-styled-block quotes-edit-section">
        <div class="quotes-edit-section__inner">

            <form class="form main-form quotes-edit-form" action="{{ route('users.quotes.update') }}" method="POST" id="update-quotes-form" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="id" value="{{ $quote->id }}">

                @if(session('status') == 'success')
                    <div class="alert alert--success">
                        <span class="material-icons-outlined alert__icon">error</span>
                        Андарз бо муваффақият нав карда шуд. Он пас аз санҷиши бомуваффақият аз ҷониби администратор нашр карда мешавад!
                    </div>

                @elseif(session('status') == 'similar-quote-error')
                    <div class="alert alert--warning">
                        <span class="material-icons-outlined alert__icon">error</span>
                        Андарзи ба ин монанд қаблан вуҷуд дорад, лутфан андарзро тағйир диҳед ва бори дигар имтиҳон кунед! <br><br>
                        <b>Андарзи монанд: </b> {{ session('similarQuote') }}
                    </div>

                @elseif(!$quote->approved)
                    <div class="alert alert--success">
                        <span class="material-icons-outlined alert__icon">error</span>
                        Андарз пас аз санҷиши бомуваффақият аз ҷониби администратор нашр карда мешавад!
                    </div>
                @endif

                <div class="main-form__block">
                    <h1 class="main-title main-title--indented">Вироиши андарз</h1>

                    <div class="form-group selectize-container">
                        <select class="selectize-multiple-taggable" multiple name="categories[]" placeholder="Выберите категории цитаты или добавьте новый" required>
                            <option></option>
                            {{-- Unapproved categories --}}
                            @foreach ($quote->categories as $category)
                                @if (!$category->approved)
                                    <option value="{{ $category->title }}" selected>{{ $category->title }}</option>
                                @endif
                            @endforeach

                            {{-- Approved Categories --}}
                            @foreach ($categories as $category)
                                <option value="{{ $category->title }}"
                                    @foreach ($quote->categories as $quoteCategory)
                                        @selected($quoteCategory->id == $category->id)
                                    @endforeach
                                    >{{ $category->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @php $activeSource = old('source_key', $quote->source->key); @endphp
                    {{-- Validating additional Source Inputs via JS (visibility and required statements) --}}
                    <script>let activeSource = '{{ $activeSource }}';</script>

                    <div class="form-group selectize-container">
                        <select class="source-selectize" name="source_key">
                            @foreach ($sources as $source)
                                <option value="{{ $source->key }}" @selected($source->key == $activeSource)>{{ $source->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Additional Source Inputs --}}
                    <div class="form-group" data-source-key="book">
                        <input class="input" type="text" name="book_title" placeholder="Муаллифи китоб" value="{{ $quote->sourceBook ? $quote->sourceBook->author : '' }}">
                    </div>

                    <div class="form-group" data-source-key="book">
                        <input class="input" type="text" name="book_author" placeholder="Номи китоб" value="{{ $quote->sourceBook ? $quote->sourceBook->title : '' }}">
                    </div>

                    <div class="form-group" data-source-key="movie">
                        <input class="input" type="text" name="movie_title" placeholder="Номи филм" value="{{ $quote->sourceMovie ? $quote->sourceMovie->title : '' }}">
                    </div>

                    <div class="form-group" data-source-key="movie">
                        <input class="input" type="text" name="movie_year" placeholder="Соли нашр" value="{{ $quote->sourceMovie ? $quote->sourceMovie->year : '' }}">
                    </div>

                    <div class="form-group" data-source-key="song">
                        <input class="input" type="text" name="song_title" placeholder="Номи суруд" value="{{ $quote->sourceSong ? $quote->sourceSong->title : '' }}">
                    </div>

                    <div class="form-group" data-source-key="song">
                        <input class="input" type="text" name="song_singer" placeholder="Иҷрокунанда" value="{{ $quote->sourceSong ? $quote->sourceSong->singer : '' }}">
                    </div>

                    <div class="form-group selectize-container" data-source-key="author">
                        <select class="selectize-singular-taggable" name="author_name" placeholder="Муаллифи андарзро интихоб кунед ё навашро илова кунед" required>
                            <option></option>

                            @if($quote->author && !$quote->author->approved)
                                <option value="{{ $quote->author->name }}" selected>{{ $quote->author->name }}</option>
                            @endif

                            @foreach ($authors as $author)
                                <option value="{{ $author->name }}" @if($quote->author_id == $author->id) selected @endif>{{ $author->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- /end Additional Source Inputs --}}
                </div>

                {{-- Body --}}
                <div class="main-form__block">
                    <h1 class="main-title">Матни андарз</h1>

                    <div class="form-group">
                        <textarea class="textarea" name="body" rows="7" required>{{ old('body', $quote->body) }}</textarea>
                    </div>
                </div>  {{-- /end Body --}}

                <button class="button button--main main-form__submit">Вироиши андарз</button>

                <x-terms-of-use class="accept-terms_with_dark_checkbox" id="update-quote-terms" />
            </form>

        </div>
    </section>
</div>

@endsection
