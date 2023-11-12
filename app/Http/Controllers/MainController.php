<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Quote;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function home()
    {
        $latestQuotes = Quote::approved()->latest()->take(3)->get();
        $popularQuotes = Quote::where('popular', true)->approved()->inRandomOrder()->take(8)->get();
        $popularAuthors = Author::where('popular', true)->approved()->inRandomOrder()->take(8)->get();

        return view('home.index', compact('latestQuotes', 'popularQuotes', 'popularAuthors'));
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $authors = Author::where(function ($q) use ($keyword) {
                        $q->where('name', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('biography', 'LIKE', '%' . $keyword . '%');
                    })
                    ->approved()

                    ->orderBy('name')->get();

        $quotes = Quote::where(function ($q) use ($keyword) {
            $q->whereHas('author', function ($a) use ($keyword) {
                $a->where('name', 'LIKE', '%' . $keyword . '%');
            })
                ->approved();
        })

            ->orWhere(function ($q) use ($keyword) {
                $q->where('body', 'LIKE', '%' . $keyword . '%')
                    ->approved();
            })

            ->latest()->get();

        return view('search.index', compact('keyword', 'authors', 'quotes'));
    }

    public function privacyPolicy()
    {
        return view('terms.privacy-policy');
    }

    public function termsOfUse()
    {
        return view('terms.terms-of-use');
    }
}
