@props(['size' => 'compact'])

<div @class(['is-invalid' => $errors->has('g-recaptcha-response')])>
    <div class="g-recaptcha" data-sitekey="{{ config('services.grecaptcha.site_key') }}" data-size="{{ $size}}" {{ $attributes }}></div>
</div>
@error('g-recaptcha-response')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror