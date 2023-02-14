<div class="card shadow-sm mb-2">
    <div class="card-body">
        <div class="fw-bold mb-1">{{ $review['name'] }}</div>
        <div class="text-muted small mb-1">{{ \App\Helpers\Text::readableTime($review['time']) }}</div>
        @if ($review['is_accepted'] == 'N')
            <div class="mb-1 fst-italic">{{ __('Your comment is awaiting moderation. This is a preview; your comment will be visible after it has been approved.') }}</div>
        @endif
        @if ($review['rating'] != null)
            <div class="mb-2">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $review['rating'])
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill tw-text-orange-400" viewBox="0 0 16 16">
                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star tw-text-orange-400" viewBox="0 0 16 16">
                            <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
                        </svg>
                    @endif
                @endfor
            </div>
        @else
            <div class="mb-1"></div>
        @endif
        <div>
            <p class="mb-2">{!! nl2br($review['review'], false) !!}</p>
            @if ($allowReplyToReviews == 'Y')
                <div>
                    <button class="btn btn-link px-0" type="button" data-id="{{ $review['id'] }}" @click="reply">{{ __('Reply') }}</button>
                </div>
            @endif
        </div>
    </div>
</div>
<form action="{{ route('reviews.reply', ['hotel' => $review['hotel_id']]) }}" method="POST">
    @csrf
    <input type="hidden" name="reply_to" value="{{ $review['id'] }}">
    <div data-reply-placeholder="{{ $review['id'] }}"></div>
</form>