<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class MoviesController extends Controller
{
    public function index(Request $request)
    {
        $validatedData = $request->validate([
            'title' => ['required'],
            'fetchCached' => ['boolean'],
        ]);

        $fetchCached = $validatedData['fetchCached'];
        $title = $validatedData['title'];

        if ($fetchCached) {
            return $this->fetchFromCache($title);
        } else {
            return $this->fetchFromDB($title);
        }
    }

    public function fetchFromDB($title)
    {
        $start_time = now();
        $movies = Film::where('title', 'like', "%{$title}%")->get();
        $finish_time = now();

        return response()->json([
            'data' => [
                'movies' => $movies,
                'duration_in_milliseconds' => $finish_time->diffInMilliseconds($start_time)
            ],
        ]);
    }

    public function fetchFromCache($title)
    {
        $start_time = now();
        $cached_movies = Redis::get($title);

        if ($cached_movies) {
            $finish_time = now();

            return response()->json([
                'data' => [
                    'movies' => json_decode($cached_movies),
                    'duration_in_milliseconds' => $finish_time->diffInMilliseconds($start_time),
                ]
            ]);
        } else {
            $movies = Film::where('title', 'like', "%{$title}%")->get();
            $finish_time = now();
            Redis::set($title, $movies);

            return response()->json([
                'data' => [
                    'movies' => $movies,
                    'duration_in_milliseconds' => $finish_time->diffInMilliseconds($start_time)
                ],
            ]);
        }
    }
}
