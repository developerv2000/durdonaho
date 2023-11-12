<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Author;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Quote;
use App\Models\Source;
use App\Models\SourceBook;
use App\Models\SourceMovie;
use App\Models\SourceSong;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuoteController extends Controller
{
    // used while generating route names in dashboard
    const MODEL_SHORTCUT = 'quotes';

    /**
     * Return compacted view with filtered quotes (by categories, userId, authorId etc)
     * Used on AJAX requests by too many routes
     *
     * @return \Illuminate\Contracts\View
     */
    public function ajaxGet(Request $request)
    {
        $quotes = $this->filter($request);

        // validate query pagination path and card style due to the requests route
        $authorId = $request->author_id;
        $favorite = $request->favorite;
        $userId = $request->user_id;

        $cardClass = 'card_with_small_image';

        // authors.show route
        if ($authorId && $authorId != '') {
            $quotes->withPath(route('authors.show', Author::find($authorId)->slug));
        }
        // favorite.quotes route
        else if ($favorite && $favorite == 1) {
            $quotes->withPath(route('favorite.quotes'));
            $cardClass = 'card_with_small_image card--full_width';
        }
        // users.current.quotes route
        else if ($userId && $userId != '' && Auth::check() && $userId == Auth::user()->id) {
            $quotes->withPath(route('users.current.quotes'));
            $cardClass = 'card_with_small_image card--full_width';

            // display edit button for quotes
            $showEditButton = true;
            // return view with compacted showEditButton
            return view('components.list-inner-quotes', compact('quotes', 'cardClass', 'showEditButton'));
        }
        // users.quotes route
        else if ($userId && $userId != '') {
            $quotes->withPath(route('users.quotes', User::find($userId)->slug));
            $cardClass = 'card_with_small_image card--full_width';
        }
        //quotes.index route
        else {
            $quotes->withPath(route('quotes.index'));
        }

        return view('components.list-inner-quotes', compact('quotes', 'cardClass'));
    }

    /**
     * Return filtered quotes for the given request
     * 
     * Manual parameters (manualAuthorId etc) needed because filter function is
     * also called from many different GET routes (index pages). $request may also have author_id
     * & favorite etc parameters, but manuals are more priority
     * 
     * You don`t have to include manual parameters while paginating!!! They are manually declared on each controllers functions
     * 
     * Only approvoed quotes (by admin) will be taken
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function filter($request, $manualAuthorId = null, $manualFavorite = null, $manualUserId = null)
    {
        $quotes = Quote::query();

        // Filter Query Step by step
        $authorId = $manualAuthorId ? $manualAuthorId : $request->author_id;
        $favorite = $manualFavorite ? $manualFavorite : $request->favorite;
        $userId = $manualUserId ? $manualUserId : $request->user_id;

        // 1. Only approved quotes (by admin) will be taken
        $quotes = $quotes->approved();

        // 2. Specific Authors quotes (valid only on authors.show route) 
        if ($authorId && $authorId != '') {
            $quotes = $quotes->where('author_id', $authorId);
        }

        // 3. Favorite (true only on favorite.quotes route)
        else if ($favorite && $favorite != '') {
            $quoteIds = Favorite::where('user_id', Auth::user()->id)->where('quote_id', '!=', '')->pluck('quote_id');
            $quotes = $quotes->whereIn('id', $quoteIds);
        }

        // 4. Specific users quotes (valid only on users.quotes && users.current.quotes route) 
        else if ($userId && $userId != '') {
            $quotes = $quotes->where('user_id', $userId);
        }

        // 5. Categories
        $category_id = $request->category_id;
        if ($category_id && $category_id != '') {
            // category_id comes in string type joined by '-' because of FormData
            $categories = explode('-', $category_id);
            $quotes = $quotes->whereHas('categories', function ($q) use ($categories) {
                $q->whereIn('id', $categories);
            });
        }

        // 6. Search keyword
        $keyword = $request->keyword;
        if ($keyword && $keyword != '') {
            $quotes = $quotes->where('body', 'LIKE', '%' . $keyword . '%');
        }

        $quotes = $quotes->orderBy('updated_at', 'desc')
            ->paginate(6)
            ->appends($request->except(['page', 'token', 'author_id', 'favorite', 'user_id']))
            ->fragment('quotes-section');

        return $quotes;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $quotes = $this->filter($request);

        return view('quotes.index', compact('quotes', 'request'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function top()
    {
        $quotes = Quote::approved()->withCount('likes')->orderBy('likes_count', 'desc')->limit(20)->get()->paginate(10)->fragment('quotes-section');

        return view('quotes.top', compact('quotes'));
    }

    /**
     * Display a listing of the resource in dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboardIndex(Request $request)
    {
        // used while generating route names
        $modelShortcut = self::MODEL_SHORTCUT;

        // for search & counting on index pages
        $allItems = Quote::select('body as title', 'id')->approved()->orderBy('title')->get();

        // Default parameters for ordering
        $orderBy = $request->orderBy ? $request->orderBy : 'updated_at';
        $orderType = $request->orderType ? $request->orderType : 'desc';
        $activePage = $request->page ? $request->page : 1;

        // orderby Categories
        if ($orderBy == 'category_titles') {
            $items = Quote::selectRaw('group_concat(categories.title order by categories.title asc) as category_titles, quotes.*')
                ->join('category_quote', 'quotes.id', '=', 'category_quote.quote_id')
                ->join('categories', 'categories.id', '=', 'category_quote.category_id')
                ->groupBy('quote_id')
                ->where('quotes.approved', true)
                ->orderBy($orderBy, $orderType)
                ->paginate(30, ['*'], 'page', $activePage)
                ->appends($request->except('page'));
        }

        else {
            $items = Quote::approved()->orderBy($orderBy, $orderType)
                ->paginate(30, ['*'], 'page', $activePage)
                ->appends($request->except('page'));
        }

        return view('dashboard.quotes.index', compact('modelShortcut', 'allItems', 'items', 'orderBy', 'orderType', 'activePage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // used while generating route names
        $modelShortcut = self::MODEL_SHORTCUT;

        $authors = Author::approved()->orderBy('name')->select('name', 'id')->get();
        $categories = Category::approved()->orderBy('title')->select('title', 'id')->get();
        $sources = Source::get();
        $users = User::orderBy('name')->select('name', 'id')->get();

        return view('dashboard.quotes.create', compact('modelShortcut', 'authors', 'categories', 'sources', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return rerror if there is already a quote very similar to the createing quote
        $body = $request->body;
        $quotes = Quote::approved()->pluck('body');
        foreach ($quotes as $quote) {
            similar_text($body, $quote, $percentage);
            if ($percentage > 80) {
                return redirect()->back()->withInput()->withErrors(['Андарзи ба ин монанд қаблан  : ' . $quote]);
            }
        };

        // store quote
        $quote = new Quote();
        $fields = ['body', 'user_id', 'popular'];
        Helper::fillModelColumns($quote, $fields, $request);

        // set up quotes source
        $quote->source_id = Source::where('key', $request->source_key)->first()->id;
        $this->setupQuoteSource($quote, $request);

        Helper::uploadModelsFile($request, $quote, 'source_image', uniqid(), SourceController::IMAGE_PATH, 300);

        $quote->verified = true;
        $quote->approved = true;
        
        $quote->save();

        $quote->categories()->attach($request->categories);

        return redirect()->route('dashboard.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // used while generating route names
        $modelShortcut = self::MODEL_SHORTCUT;

        $item = Quote::find($id);

        $authors = Author::approved()->orderBy('name')->select('name', 'id')->get();
        $categories = Category::approved()->orderBy('title')->select('title', 'id')->get();
        $sources = Source::get();
        $users = User::orderBy('name')->select('name', 'id')->get();

        return view('dashboard.quotes.edit', compact('modelShortcut', 'item', 'authors', 'categories', 'sources', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // return error if there is already a quote very similar to the updating quote
        $body = $request->body;
        $quotes = Quote::approved()->where('id', '!=', $request->id)->pluck('body');
        foreach ($quotes as $quote) {
            similar_text($body, $quote, $percentage);
            if ($percentage > 80) {
                return redirect()->back()->withInput()->withErrors(['Андарзи ба ин монанд қаблан  : ' . $quote]);
            }
        };

        // update quote
        $quote = Quote::find($request->id);
        $fields = ['body', 'user_id', 'popular'];
        Helper::fillModelColumns($quote, $fields, $request);
        
        // set up quotes source
        $quote->source_id = Source::where('key', $request->source_key)->first()->id;

        $quote->author_id = null;
        $quote->source_book_id = null;
        $quote->source_movie_id = null;
        $quote->source_song_id = null;

        $this->setupQuoteSource($quote, $request);
        
        Helper::uploadModelsFile($request, $quote, 'source_image', uniqid(), SourceController::IMAGE_PATH, 300);

        $quote->save();

        // reattach categories
        $quote->categories()->detach();
        $quote->categories()->attach($request->categories);

        return redirect()->back();
    }

    /**
     * Request for deleting items by id may come in integer type (single item destroy form) 
     * or in array type (multiple item destroy form)
     * That`s why we need to get them in array type and delete them by loop
     *
     * Checkout Model Boot methods deleting function 
     * Models relations also deleted on deleting function of Models Boot method
     * 
     * @param  int/array  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $ids = (array) $request->id;

        foreach ($ids as $id) {
            Quote::find($id)->delete();
        }

        return redirect()->route('dashboard.index');
    }

    /**
     * Display a listing of the resource in dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboardUnapproved(Request $request)
    {
        // used while generating route names
        $modelShortcut = self::MODEL_SHORTCUT;

        // for search & counting on index pages
        $allItems = Quote::select('body as title', 'id')->unapproved()->orderBy('title')->get();

        // Default parameters for ordering
        $orderBy = $request->orderBy ? $request->orderBy : 'updated_at';
        $orderType = $request->orderType ? $request->orderType : 'desc';
        $activePage = $request->page ? $request->page : 1;

        $items = Quote::unapproved()->orderBy($orderBy, $orderType)
            ->paginate(30, ['*'], 'page', $activePage)
            ->appends($request->except('page'));

        return view('dashboard.quotes.unapproved.index', compact('modelShortcut', 'allItems', 'items', 'orderBy', 'orderType', 'activePage'));
    }

    /**
     * Display a listing of the resource in dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function editUnapproved($id)
    {
        // used while generating route names
        $modelShortcut = self::MODEL_SHORTCUT;

        $item = Quote::find($id);
        // set quote as verified without updating timestamps
        $item->verified = true;
        $item->timestamps = false;
        $item->save();

        $authors = Author::approved()->orderBy('name')->select('name', 'id')->get();
        $categories = Category::approved()->orderBy('title')->select('title', 'id')->get();
        $sources = Source::get();
        $users = User::orderBy('name')->select('name', 'id')->get();

        return view('dashboard.quotes.unapproved.edit', compact('modelShortcut', 'item', 'authors', 'categories', 'sources', 'users'));
    }

    public function approve(Request $request)
    {
        // return error if there is already a quote very similar
        $body = $request->body;
        $quotes = Quote::approved()->where('id', '!=', $request->id)->pluck('body');
        foreach ($quotes as $quote) {
            similar_text($body, $quote, $percentage);
            if ($percentage > 80) {
                return redirect()->back()->withInput()->withErrors(['Андарзи ба ин монанд қаблан  : ' . $quote]);
            }
        };

        $quote = Quote::find($request->id);
        $fields = ['body', 'user_id', 'popular'];
        Helper::fillModelColumns($quote, $fields, $request);
        
        // set up quotes source
        $quote->source_id = Source::where('key', $request->source_key)->first()->id;

        $quote->author_id = null;
        $quote->source_book_id = null;
        $quote->source_movie_id = null;
        $quote->source_song_id = null;

        $this->setupQuoteSource($quote, $request);
        
        Helper::uploadModelsFile($request, $quote, 'source_image', uniqid(), SourceController::IMAGE_PATH, 300);

        $quote->approved = true;
        $quote->save();

        // reattach categories
        $quote->categories()->detach();
        $quote->categories()->attach($request->categories);

        // also approve quotes unapproved author && unapproved categories
        if($quote->author && !$quote->author->approved) {
            $author = $quote->author;
            $author->approved = true;
            $author->save();
        }

        $quote->categories()->each(function ($category) {
            $category->approved = true;
            $category->save();
        });

        return redirect()->route('quotes.dashboard.unapproved.index');
    }

    /**
     * Setup quotes source (create unexisting sources), 
     * while creating or updating by ADMIN (in dashboard)
     */
    private function setupQuoteSource($quote, $request)
    {
        switch ($request->source_key) {
            case Source::OWN_QUOTE_KEY:
            case Source::UNKNOWN_AUTHOR_KEY:
            case Source::FROM_PROVERB_KEY:
            case Source::FROM_PARABLE_KEY:
                // code...
                break;

            // Author
            case Source::AUTHORS_QUOTE_KEY:
                $quote->author_id = $request->author_id;
                break;

            // From Book
            case Source::FROM_BOOK_KEY:
                $bookTitle = $request->book_title;
                $bookAuthor = $request->book_author;

                $sourceBook = SourceBook::where('title', $bookTitle)->where('author', $bookAuthor)->first();

                // create new unapproved source book if its doens exists
                if(!$sourceBook) {
                    SourceBook::createApprovedItem($bookTitle, $bookAuthor);
                }

                $quote->source_book_id = SourceBook::where('title', $bookTitle)->where('author', $bookAuthor)->first()->id;

                break;

            // From Movie
            case Source::FROM_MOVIE_KEY:
                $movieTitle = $request->movie_title;
                $movieYear = $request->movie_year;

                $sourceMovie = SourceMovie::where('title', $movieTitle)->where('year', $movieYear)->first();

                // create new unapproved source movie if its doens exists
                if(!$sourceMovie) {
                    SourceMovie::createApprovedItem($movieTitle, $movieYear);
                }

                $quote->source_movie_id = SourceMovie::where('title', $movieTitle)->where('year', $movieYear)->first()->id;

                break;

            // From song
            case Source::FROM_SONG_KEY:
                $songTitle = $request->song_title;
                $songSinger = $request->song_singer;

                $sourceSong = SourceSong::where('title', $songTitle)->where('singer', $songSinger)->first();

                // create new unapproved source song if its doens exists
                if(!$sourceSong) {
                    SourceSong::createApprovedItem($songTitle, $songSinger);
                }

                $quote->source_song_id = SourceSong::where('title', $songTitle)->where('singer', $songSinger)->first()->id;

                break;
        }
    }

    public function getUnverifiedQuotesCount()
    {
        return Quote::where('verified', false)->count();
    }
}