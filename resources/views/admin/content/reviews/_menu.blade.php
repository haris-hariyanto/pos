<div class="dropdown">
    <button class="btn btn-dark btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">{{ __('Menu') }}</button>
    <div class="dropdown-menu">
        <button
            type="button"
            @class(['dropdown-item', 'd-none' => $review->is_accepted == 'Y'])
            @click="approveReview"
            data-link-approve="{{ route('admin.reviews.approve', ['review' => $review]) }}"
            data-id="{{ $review->id }}"
        >{{ __('Approve') }}</button>

        <button
            type="button"
            @class(['dropdown-item', 'd-none' => $review->is_accepted == 'N'])
            @click="unapproveReview"
            data-link-unapprove="{{ route('admin.reviews.unapprove', ['review' => $review]) }}"
            data-id="{{ $review->id }}"
        >{{ __('Unapprove') }}</button>

        <button
            type="button"
            class="dropdown-item"
            data-toggle="modal"
            data-target="#modalReply"
            data-btn-text="{{ $review->is_accepted == 'Y' ? __('Send Reply') : __('Approve and Send Reply') }}"
            data-link-reply="{{ route('admin.reviews.reply', ['review' => $review]) }}"
            @click="sendReply"
        >{{ __('Reply') }}</button>

        <a href="{{ route('admin.reviews.edit', ['review' => $review]) }}" class="dropdown-item">{{ __('Edit') }}</a>

        <button
            type="button"
            class="dropdown-item"
            data-toggle="modal"
            data-target="#modalDelete"
            data-link-delete="{{ route('admin.reviews.destroy', ['review' => $review]) }}"
            @click="deleteItem"
        >{{ __('Delete') }}</button>
    </div>
</div>