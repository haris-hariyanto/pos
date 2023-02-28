<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Hotel\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Helpers\CacheSystemDB;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Text;

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
        $queryType = $request->query('type', 'all');

        $reviewsCount = Cache::rememberForever('reviewscount', function () {
            return Review::count();
        });

        $reviews = Review::with('hotel')
            ->when($querySearch, function ($query) use ($querySearch) {
                $query->where('review', 'like', '%' . $querySearch . '%');
            })
            ->when($queryType, function ($query) use ($queryType) {
                if ($queryType == 'reviews') {
                    $query->where('parent_id', 0);
                }
                if ($queryType == 'replies') {
                    $query->where('parent_id', '<>', 0);
                }
            });

        if ($querySearch || $queryType != 'all') {
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
                    'rating' => $review->rating ? $review->rating . '/5' : null,
                    'hotel' => '<a href="' . route('hotel', [$review->hotel->slug]) . '" target="_blank">' . $review->hotel->name . '</a>',
                    'review' => $review->review,
                    'is_accepted_raw' => $review->is_accepted,
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

    public function reply(Request $request, Review $review)
    {
        $validator = Validator::make($request->all(), [
            'reply' => ['required', 'max:4096'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            return redirect()->back()->with('error', $errors->first('reply'));
        }

        CacheSystemDB::forget('hotel' . $review->hotel->slug);

        $validated = $validator->validated();

        Review::create([
            'hotel_id' => $review->hotel_id,
            'name' => $request->user()->username,
            'time' => time(),
            'rating' => null,
            'review' => Text::plain($validated['reply']),
            'is_accepted' => 'Y',
            'source' => 'reply',
            'parent_id' => $review->id,
        ]);

        $review->update([
            'is_accepted' => 'Y',
        ]);

        return redirect()->back()->with('success', __('Reply has been created!'));
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
        CacheSystemDB::forget('hotel' . $review->hotel->slug);

        $validationRules = [
            'review' => ['required'],
            'rating' => ['required', 'in:1,2,3,4,5'],
        ];

        $validated = $request->validate($validationRules);
        $validated['review'] = Text::plain($validated['review']);

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
        Cache::forget('reviewscount');

        CacheSystemDB::forget('hotel' . $review->hotel->slug);
        $review->delete();

        return redirect()->back()->with('success', __('Review has been deleted!'));
    }

    public function approveAll(Request $request)
    {
        $reviews = Review::with('hotel')->where('is_accepted', 'N')->get();
        foreach ($reviews as $review) {
            $review->update([
                'is_accepted' => 'Y',
            ]);

            CacheSystemDB::forget('hotel' . $review->hotel->slug);
        }

        return redirect()->back();
    }

    public function approve(Request $request, Review $review)
    {
        CacheSystemDB::forget('hotel' . $review->hotel->slug);
        
        $review->update([
            'is_accepted' => 'Y',
        ]);

        $newReviews = Review::where('is_accepted', 'N')->count();

        return [
            'success' => true,
            'reviewID' => $review->id,
            'new' => $newReviews,
        ];
    }

    public function unapprove(Request $request, Review $review)
    {
        CacheSystemDB::forget('hotel' . $review->hotel->slug);
        
        $review->update([
            'is_accepted' => 'N',
        ]);

        $newReviews = Review::where('is_accepted', 'N')->count();

        return [
            'success' => true,
            'reviewID' => $review->id,
            'new' => $newReviews,
        ];
    }
}
