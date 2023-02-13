<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use App\Models\Hotel\Review;
use App\Models\Hotel\Hotel;
use Illuminate\Http\Request;
use App\Helpers\CacheSystem;
use App\Helpers\ReCAPTCHA;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function reply(Request $request, Hotel $hotel)
    {
        $validated = $request->validateWithBag('reply', [
            'name' => ['required', 'string', 'max:255'],
            'reply' => ['required', 'string', 'max:4096'],
            'reply_to' => ['required', 'exists:reviews,id'],
            'g-recaptcha-response' => ['required'],
        ]);

        $reCAPTCHA = new ReCAPTCHA($request->{'g-recaptcha-response'});

        if (!$reCAPTCHA->verify()) {
            return redirect()->back()->with('recaptchaInvalid', __('reCAPTCHA is invalid'));
        }

        $review = Review::create([
            'hotel_id' => $hotel->id,
            'name' => $validated['name'],
            'time' => time(),
            'rating' => null,
            'review' => $validated['reply'],
            'is_accepted' => 'N',
            'source' => 'reply',
            'parent_id' => $validated['reply_to'],
        ]);

        CacheSystem::forget('hotel' . $hotel['slug']);

        return redirect()->route('hotel', ['hotel' => $hotel->slug, 'unapproved' => $review->id, '#review' . $review->id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Hotel $hotel)
    {   
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'review' => ['required', 'string', 'max:4096'],
            'rating' => ['required', 'in:1,2,3,4,5'],
            'g-recaptcha-response' => ['required'],
        ]);

        $reCAPTCHA = new ReCAPTCHA($request->{'g-recaptcha-response'});

        if (!$reCAPTCHA->verify()) {
            return redirect()->back()->with('recaptchaInvalid', __('reCAPTCHA is invalid'));
        }

        $validated['hotel_id'] = $hotel->id;
        $validated['time'] = time();
        $validated['is_accepted'] = 'N';
        $validated['source'] = 'review';

        $review = Review::create([
            'name' => $validated['name'],
            'review' => $validated['review'],
            'rating' => $validated['rating'],
            'hotel_id' => $validated['hotel_id'],
            'time' => $validated['time'],
            'is_accepted' => $validated['is_accepted'],
            'source' => $validated['source'],
        ]);

        CacheSystem::forget('hotel' . $hotel['slug']);

        return redirect()->route('hotel', [$hotel->slug, 'unapproved' => $review->id, '#review' . $review->id]);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hotel\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        //
    }
}
