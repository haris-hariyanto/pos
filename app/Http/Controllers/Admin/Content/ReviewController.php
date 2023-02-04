<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Hotel\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Reviews') => '',
        ];

        return view('admin.content.reviews.index', compact('breadcrumb'));
    }

    public function indexData(Request $request)
    {
        $queryLimit = $request->query('limit', 10);
        $queryOffset = $request->query('offset', 0);
        $querySort = $request->query('sort', 'id');
        $queryOrder = $request->query('order', 'desc');
        $querySearch = $request->query('search');

        $reviewsCount = Review::count();

        $reviews = Review::with('hotel')
        ->when($querySearch, function ($query) use ($querySearch) {
            $query->where('review', 'like', '%' . $querySearch . '%');
        });

        if ($querySearch) {
            $reviewsCountFiltered = $reviews->count();
        }
        else {
            $reviewsCountFiltered = $reviewsCount;
        }

        $reviews = $reviews->orderBy($querySort, $queryOrder)
            ->skip($queryOffset)
            ->take($queryLimit)
            ->get();
        
        return [
            'total' => $reviewsCountFiltered,
            'totalNotFiltered' => $reviewsCount,
            'rows' => $reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'name' => $review->name,
                    'time' => \App\Helpers\Text::readableTime($review->time),
                    'rating' => $review->rating . '/5',
                    'hotel' => '<a href="' . route('hotel', [$review->hotel->slug]) . '" target="_blank">' . $review->hotel->name . '</a>',
                    'review' => $review->review,
                    'menu' => view('admin.content.reviews._menu', ['review' => $review])->render(),
                ];
            }),
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Hotel\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Hotel\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Reviews') => route('admin.reviews.index'),
            __('Edit Review') => '',
        ];

        return view('admin.content.reviews.edit', compact('review', 'breadcrumb'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hotel\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {
        Cache::forget('hotel' . $review->hotel->slug);

        $validationRules = [
            'review' => ['required'],
            'rating' => ['required', 'in:1,2,3,4,5'],
        ];

        $validated = $request->validate($validationRules);

        $review->update($validated);

        return redirect()->back()->with('success', __('Review has been updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hotel\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Review $review)
    {
        Cache::forget('hotel' . $review->hotel->slug);
        $review->delete();

        return redirect()->back()->with('success', __('Review has been deleted!'));
    }
}
