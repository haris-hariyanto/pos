<meta name="description" content="{{ $description }}">

<meta property="og:type" content="website">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="[current_url]">
<meta property="og:locale" content="{{ config('app.locale') }}">
<meta property="og:site_name" content="[appname]">
@if (isset($image))<meta property="og:image" content="{{ $image }}"> @endif

@if (isset($image))<meta property="og:image:secure_url" content="{{ $image }}"> @endif

@if (isset($image_alt))<meta property="og:image:alt" content="{{ $image_alt }}"> @endif


<meta name="twitter:card" content="{{ isset($twitter_card) ? $twitter_card : 'summary' }}">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
@if (isset($image))<meta name="twitter:image" content="{{ $image }}"> @endif

@if (isset($image_alt))<meta name="twitter:image:alt" content="{{ $image_alt }}"> @endif