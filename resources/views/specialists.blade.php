<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BB</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
</head>
<body>
    <x-header/>
    <div class="bg-blue-100 py-8">
        <div class="relative text-center">
            <img src="{{asset('img/7.png')}}" alt="Background Image" class="w-full h-64 object-cover">
            <button class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white py-1 px-4 rounded-full shadow-lg">специалисты</button>
        </div>
    
        <div class="container mx-auto mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($specialists as $specialist)
            <div class="text-center">
                <a href="{{ route('specialists.show', $specialist) }}">
                    <img src="{{ asset($specialist->image) }}" alt="{{ $specialist->name }}" class="w-full h-48 object-cover">
                    <p class="mt-2">{{ $specialist->name }}</p>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    <x-footer/>
</body>
</html>