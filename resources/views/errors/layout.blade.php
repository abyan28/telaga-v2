<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title') - {{ config('app.name') }}</title>
        <style>
            body { margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: system-ui, sans-serif; background: #fafafa; color: #1f2937; }
            main { max-width: 28rem; padding: 1.5rem; text-align: center; }
            .kode { font-size: 3rem; font-weight: 700; color: #6b7280; margin: 0; }
            h1 { font-size: 1.25rem; margin: .5rem 0; }
            p { color: #4b5563; margin: .5rem 0 1.5rem; }
            a { color: #1d4ed8; }
            @media (prefers-color-scheme: dark) { body { background: #0a0a0a; color: #f3f4f6; } p { color: #9ca3af; } a { color: #93c5fd; } }
        </style>
    </head>
    <body>
        <main>
            <p class="kode">@yield('code')</p>
            <h1>@yield('title')</h1>
            <p>@yield('message')</p>
            <a href="{{ url('/') }}">Kembali ke beranda</a>
        </main>
    </body>
</html>
