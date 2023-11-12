<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Mail\EmailVerifyNotification;
use App\Models\Author;
use App\Models\Category;
use App\Models\Quote;
use App\Models\Source;
use App\Models\SourceBook;
use App\Models\SourceMovie;
use App\Models\SourceSong;
use App\Models\User;
use App\Models\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    const DEFAULT_IMAGE = '__default.jpg';
    // used while uploading images
    const IMAGE_PATH = 'img/users';
    // used while generating route names in dashboard
    const MODEL_SHORTCUT = 'users';

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $user = User::where('slug', $slug)->first();

        return view('users.show', compact('user'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $user = Auth::user();

        return view('users.profile', compact('user'));
    }

    /**
     * Show users quotes
     *
     * @return \Illuminate\Http\Response
     */
    public function quotes(Request $request, $slug)
    {
        $userId = User::where('slug', $slug)->first()->id;

        // redirect to users.current.quotes route if $userId equals to authenticated users id
        if (Auth::check() && $userId == Auth::user()->id) {
            return redirect()->route('users.current.quotes');
        }

        $quotes = QuoteController::filter($request, null, null, $userId);

        return view('users.quotes', compact('quotes', 'userId', 'request'));
    }

    /**
     * Show users quotes
     *
     * @return \Illuminate\Http\Response
     */
    public function currentUsersQuotes(Request $request)
    {
        $userId = Auth::user()->id;
        $quotes = QuoteController::filter($request, null, null, $userId);

        return view('users.current-users-quotes', compact('quotes', 'userId', 'request'));
    }

    public function unverifiedQuotes()
    {
        $user = Auth::user();
        $quotes = Quote::where('user_id', $user->id)->unapproved()->orderBy('updated_at', 'desc')->paginate(10);

        return view('users.unverified-quotes', compact('quotes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = User::find(Auth::user()->id);

        $validationRules = [
            'name' => [
                'required',
                Rule::unique('users')->ignore($user->id),
            ],

            'email' => [
                'required', 'email',
                Rule::unique('users')->ignore($user->id),
            ],

            'gender' => [
                'required',
                Rule::in(['male', 'female']),
            ]
        ];

        $passwordValidationRules = [
            'old_password' => [
                'current_password',
                'required',
                'min:5',
            ],

            'new_password' => [
                'required',
                'min:5',
            ],
        ];

        // merge validation rules while requesting for password change
        if ($request->old_password != '') {
            $validationRules = array_merge($validationRules, $passwordValidationRules);
        }

        $request->validate($validationRules);

        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->biography = $request->biography;

        // change users image if uploaded image
        Helper::uploadModelsFile($request, $user, 'image', uniqid(), self::IMAGE_PATH, 320, 320);

        // set default image on image delete
        if ($request->remove_image == '1') {
            $user->image = self::DEFAULT_IMAGE;
        }

        // force email verification on email change
        if ($request->email != $user->email) {
            $user->email = $request->email;
            $user->verified_email = false;

            //send email veify notification
            $token = str()->random(64);

            VerifyEmail::create([
                'email' => $user->email,
                'token' => $token
            ]);

            Mail::to($request->email)->send(new EmailVerifyNotification($token));
        }

        // change password
        if ($request->old_password != '') {
            $user->password = bcrypt($request->new_password);

            // logout user
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $user->save();

            return redirect()->route('home');
        }

        $user->save();

        return redirect()->back();
    }

    public function createQuote()
    {
        $sources = Source::select('title', 'key')->get();
        $authors = Author::approved()->orderBy('name')->select('name')->get();
        $categories = Category::approved()->orderBy('title')->select('title')->get();

        return view('users.create-quote', compact('authors', 'sources', 'categories'));
    }

    public function storeQuote(Request $request)
    {
        // return rerror if there is already a quote very similar to the createing quote
        $body = $request->body;
        $quotes = Quote::approved()->pluck('body');
        foreach ($quotes as $quote) {
            similar_text($body, $quote, $percentage);
            if ($percentage > 80) {
                return redirect()->back()->with(['status' => 'similar-quote-error', 'similarQuote' => $quote])->withInput();
            }
        };

        // else store quote
        $quote = new Quote();
        $quote->body = $request->body;
        $quote->user_id = Auth::user()->id;

        // set up quotes source
        $quote->source_id = Source::where('key', $request->source_key)->first()->id;

        $this->setupQuoteSource($quote, $request);

        $quote->save();

        // set up & attach categories
        $this->setupQuoteCategories($quote, $request);

        return redirect()->back()->with(['status' => 'success']);
    }

    public function editQuote($id)
    {
        $quote = Quote::find($id);

        // return error case authenticated user isnt owner of this quote
        if ($quote->user_id != Auth::user()->id) {
            abort(404);
        }

        $sources = Source::select('title', 'key')->get();
        $authors = Author::approved()->orderBy('name')->select('name', 'id')->get();
        $categories = Category::approved()->orderBy('title')->select('title', 'id')->get();

        return view('users.edit-quote', compact('quote', 'authors', 'sources', 'categories'));
    }

    public function updateQuote(Request $request)
    {
        $quote = Quote::find($request->id);

        // escape site hack
        if ($quote->user_id != Auth::user()->id) {
            return 'Ваш аккаунт будет заблокировал, при нескольких попыток взлома сайта!';
        }

        // return error if there is already a quote very similar to the updating quote
        $body = $request->body;
        $quotes = Quote::approved()->where('id', '!=', $quote->id)->pluck('body');
        foreach ($quotes as $q) {
            similar_text($body, $q, $percentage);
            if ($percentage > 80) {
                return redirect()->back()->with(['status' => 'similar-quote-error', 'similarQuote' => $q])->withInput();
            }
        };

        // else update quote
        $quote->body = $request->body;
        $quote->verified = false;
        $quote->approved = false;

        // set up quotes source
        $quote->source_id = Source::where('key', $request->source_key)->first()->id;

        $quote->author_id = null;
        $quote->source_book_id = null;
        $quote->source_movie_id = null;
        $quote->source_song_id = null;

        $this->setupQuoteSource($quote, $request);

        $quote->save();

        // set up & reattach categories
        $quote->categories()->detach();
        $this->setupQuoteCategories($quote, $request);

        return redirect()->back()->with(['status' => 'success']);
    }

    /**
     * Set up quotes source (create unexisting sources as unapproved source),
     * while creating or updating quote by USER (not admin)
     */
    private static function setupQuoteSource($quote, $request)
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
                $authorName = $request->author_name;

                $author = Author::where('name', $authorName)->first();

                // Create new unapproved author if author doesnt exists
                if(!$author) {
                    Author::createUnapprovedItem($authorName);
                }

                $quote->author_id = Author::where('name', $authorName)->first()->id;

                break;

            // From Book
            case Source::FROM_BOOK_KEY:
                $bookTitle = $request->book_title;
                $bookAuthor = $request->book_author;

                $sourceBook = SourceBook::where('title', $bookTitle)->where('author', $bookAuthor)->first();

                // create new unapproved source book if its doens exists
                if(!$sourceBook) {
                    SourceBook::createUnapprovedItem($bookTitle, $bookAuthor);
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
                    SourceMovie::createUnapprovedItem($movieTitle, $movieYear);
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
                    SourceSong::createUnapprovedItem($songTitle, $songSinger);
                }

                $quote->source_song_id = SourceSong::where('title', $songTitle)->where('singer', $songSinger)->first()->id;

                break;
        }
    }

    /**
     * Set up quotes categories (create unexisting categories as unapproved categories),
     * while creating or updating quote by USER (not admin)
     */
    private static function setupQuoteCategories($quote, $request)
    {
        foreach ($request->categories as $requestedCategory) {
            $category = Category::where('title', $requestedCategory)->first();

            // create new unappoved category if category doesnt exists
            if (!$category) {
                Category::createUnapprovedItem($requestedCategory);
            }

            $quote->categories()->attach(Category::where('title', $requestedCategory)->first()->id);
        }
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

        // for counting on index pages
        $allItems = User::select('id')->get();

        // Default parameters for ordering
        $orderBy = $request->orderBy ? $request->orderBy : 'created_at';
        $orderType = $request->orderType ? $request->orderType : 'desc';
        $activePage = $request->page ? $request->page : 1;

        $items = User::orderBy($orderBy, $orderType)
            ->withCount('quotes')
            ->paginate(30, ['*'], 'page', $activePage)
            ->appends($request->except('page'));

        return view('dashboard.users.index', compact('modelShortcut', 'allItems', 'items', 'orderBy', 'orderType', 'activePage'));
    }
}
