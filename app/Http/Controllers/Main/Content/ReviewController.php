<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use App\Models\Hotel\Review;
use App\Models\Hotel\Hotel;
use Illuminate\Http\Request;
use App\Helpers\CacheSystemDB;
use App\Helpers\ReCAPTCHA;
use App\Helpers\Settings;
use App\Helpers\Text;
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
        $allowReplyToReviews = Settings::get('reviewssettings__allow_reply_to_reviews', 'Y');
        if ($allowReplyToReviews == 'N') {
            abort(403);
        }

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

        $repliesMustBeApproved = Settings::get('reviewssettings__replies_must_be_approved');
        if ($repliesMustBeApproved == 'Y') {
            $isAccepted = 'N';
        }
        else {
            $isAccepted = 'Y';
        }

        $review = Review::create([
            'hotel_id' => $hotel->id,
            'name' => $validated['name'],
            'time' => time(),
            'rating' => null,
            'review' => Text::plain($validated['reply']),
            'is_accepted' => $isAccepted,
            'source' => 'reply',
            'parent_id' => $validated['reply_to'],
        ]);

        CacheSystemDB::forgetWithTags($hotel['id'], 'hotel');

        Cache::forget('reviewscount');

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
        $allowNewReviews = Settings::get('reviewssettings__allow_new_reviews', 'Y');
        if ($allowNewReviews == 'N') {
            abort(403);
        }

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

        $reviewsMustBeApproved = Settings::get('reviewssettings__reviews_must_be_approved');
        if ($reviewsMustBeApproved == 'Y') {
            $isAccepted = 'N';
        }
        else {
            $isAccepted = 'Y';
        }

        $review = Review::create([
            'name' => $validated['name'],
            'review' => Text::plain($validated['review']),
            'rating' => $validated['rating'],
            'hotel_id' => $validated['hotel_id'],
            'time' => $validated['time'],
            'is_accepted' => $isAccepted,
            'source' => $validated['source'],
        ]);

        CacheSystemDB::forgetWithTags($hotel['id'], 'hotel');

        Cache::forget('reviewscount');

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
