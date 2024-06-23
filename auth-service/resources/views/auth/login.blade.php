<!-- resources/views/auth/login.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form id="login-form">
                        @csrf
                        <div class="form-group">
                            <label for="username">{{ __('Username') }}</label>
                            <input type="text" id="username" class="form-control" name="username" required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('Password') }}</label>
                            <input type="password" id="password" class="form-control" name="password" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>
                        </div>

                        {{-- freeipa checkbox --}}
                        <div class="form-group">
                            <label for="freeipa">{{ __('FreeIPA Account') }}</label>
                            <input type="checkbox" id="freeipa" name="freeipa">
                        </div>
                    </form>

                    <div id="validation-result" style="display:none;">
                        <p id="result-message"></p>
                    </div>

                    {{-- Notes in gray style freeipa account username :admin ,password :Secret123 --}}
                    <div class="alert alert-secondary" role="alert">
                        <strong>Notes FreeIpa account:</strong> admin <br> Secret123
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var loginForm = document.getElementById('login-form');
        var validationResult = document.getElementById('validation-result');
        var resultMessage = document.getElementById('result-message');

        // var microserviceUrl = 'http://127.0.0.1:8001/api/validate-token';
        var microserviceUrl = '{{ env('TOKEN_VALIDATION_SERVICE_URL') }}';
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(loginForm);

            fetch('/api/login', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.redirect) {
                    // Redirect to the token-result page with the token
                    window.location.href = data.redirect;
                } else{
                if (data.token) {

                    // Token received, now validate it
                    console.log(data.data.token);

                    // validateToken(data.data.token);
                } else {
                    // Handle login error
                    resultMessage.textContent = 'Login failed. Please check your credentials and try again.';
                    //change style to danger container
                    validationResult.className = 'alert alert-danger';
                    // fade it out slowly after 5 seconds
                    setTimeout(function() {
                        validationResult.style.display = 'none';
                    }, 5000);
                    validationResult.style.display = 'block';
                }
            }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        function validateToken(token) {
    fetch(microserviceUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ token: token })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('error : ' +response.message);
        }
        return response.json();
    })
    .then(data => {
        if (data) {
            resultMessage.textContent = 'Token is valid.';
            validationResult.className = 'alert alert-success';
        }
        setTimeout(function() {
            validationResult.style.display = 'none';
        }, 5000);
        validationResult.style.display = 'block';
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
    });
</script>
@endsection
