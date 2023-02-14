<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Reviews Settings') }}</x-slot:pageTitle>
    <div class="row">
        <div class="col-12 col-lg-6">

            @if (session('success'))
                <x-admin.components.alert>
                    {{ session('success') }}
                </x-admin.components.alert>
            @endif
        
            @if (session('error'))
                <x-admin.components.alert type="danger">
                    {{ session('error') }}
                </x-admin.components.alert>
            @endif

            <form action="{{ route('admin.settings.reviews') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <b>{{ __('Reviews Settings') }}</b>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="font-weight-bold">{{ __('Allow new reviews') }}</div>
                            <x-admin.forms.radio :label="__('Yes')" name="reviewssettings__allow_new_reviews" value="Y" :selected="$settings['reviewssettings__allow_new_reviews'] == 'Y'" />
                            <x-admin.forms.radio :label="__('No')" name="reviewssettings__allow_new_reviews" value="N" :selected="$settings['reviewssettings__allow_new_reviews'] == 'N'" />
                        </div>

                        <div class="mb-3">
                            <div class="font-weight-bold">{{ __('Allow reply to reviews') }}</div>
                            <x-admin.forms.radio :label="__('Yes')" name="reviewssettings__allow_reply_to_reviews" value="Y" :selected="$settings['reviewssettings__allow_reply_to_reviews'] == 'Y'" />
                            <x-admin.forms.radio :label="__('No')" name="reviewssettings__allow_reply_to_reviews" value="N" :selected="$settings['reviewssettings__allow_reply_to_reviews'] == 'N'" />
                        </div>

                        <div class="mb-3">
                            <div class="font-weight-bold">{{ __('Reviews must be approved') }}</div>
                            <x-admin.forms.radio :label="__('Yes')" name="reviewssettings__reviews_must_be_approved" value="Y" :selected="$settings['reviewssettings__reviews_must_be_approved'] == 'Y'" />
                            <x-admin.forms.radio :label="__('No')" name="reviewssettings__reviews_must_be_approved" value="N" :selected="$settings['reviewssettings__reviews_must_be_approved'] == 'N'" />
                        </div>

                        <div>
                            <div class="font-weight-bold">{{ __('Replies must be approved') }}</div>
                            <x-admin.forms.radio :label="__('Yes')" name="reviewssettings__replies_must_be_approved" value="Y" :selected="$settings['reviewssettings__replies_must_be_approved'] == 'Y'" />
                            <x-admin.forms.radio :label="__('No')" name="reviewssettings__replies_must_be_approved" value="N" :selected="$settings['reviewssettings__replies_must_be_approved'] == 'N'" />
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-admin.layouts.app>