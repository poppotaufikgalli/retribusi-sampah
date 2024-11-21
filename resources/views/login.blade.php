@extends('layouts.login')
@section('content')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <main>
        <section id="hero">
            <div class="hero-container">
                <div class="container vh-sm-100 d-sm-flex justify-content-center align-items-center">
                    <!--<h1>Lomba Gerak Jalan Proklamasi<br>Tahun 2024</h1>-->
                    <section class="contact" style="width: 40%">
                        <div class="card card-body rounded-lg" style="background-color:rgba(0, 0, 0, 0.7);">
                            <form method="POST" action="{{route('login')}}" class="php-email-form bg-transparent">
                                @csrf
                                <div class="row">
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <h2>Form Login</h2>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                                    </div>
                                    <div class="form-group">
                                        <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <button type="submit">Login</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </section><!-- End Hero -->
    </main>
@endsection
@section('js-content')
    <script>
        window.addEventListener('DOMContentLoaded', event => {
            const $recaptcha = document.querySelector('#g-recaptcha-response');
            if ($recaptcha) {
                $recaptcha.setAttribute('required', 'required');
            }
        })
    </script>
@endsection