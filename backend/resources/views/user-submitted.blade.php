<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cadastro enviado | {{ config('app.name', 'SpeedApp') }}</title>

    <link rel="icon" href="{{ url('oldfavicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>

<body class="font-sans antialiased overflow-x-hidden bg-gray-50">

    <div class="min-h-full flex flex-col justify-center py-6 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <a href="{{ route('welcome') }}">
                <img class="mx-auto h-28 w-auto" src="{{ asset('images/logo.svg') }}" alt="SpeedApp"/>
            </a>
            
            <h2 class="text-center text-xl font-medium text-gray-800">Recebemos seu cadastro!</h2>
        </div>

        <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex flex-col items-center py-8 px-4 sm:px-10">
                <span class="text-lg text-gray-800 mt-2">Entraremos em contato em breve. Dúvidas envie um mensagem em nosso WhatsApp 47 2106-1904.</span>
            </div>
        </div>
    </div>
</body>

</html>
