@extends('layouts.app')

@section('content')
    <div class="container">
        @auth
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4>Welcome, {{ Auth::user()->name }}</h4>
                </div>
                <div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger">{{ __('Logout') }}</button>
                    </form>
                </div>
            </div>
        @endauth

        <div id="validationResult" class="alert" role="alert" style="display: none;">
            <span id="resultMessage"></span>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var token = window.location.pathname.split('/').pop();

            if (token) {
                validateToken(token);
            } else {
                displayValidationResult('Token is not provided.', 'alert-warning');
            }
        });

        function validateToken(token) {
            var microserviceUrl = '{{ env('TOKEN_VALIDATION_SERVICE_URL') }}';

            fetch(microserviceUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ token: token })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Token is invalid.');
                }
                return response.json();
            })
            .then(data => {
                displayValidationResult('Token is valid.', 'alert-success');
            })
            .catch(error => {
                displayValidationResult('Error: ' + error.message, 'alert-danger');
            });
        }

        function displayValidationResult(message, alertClass) {
            var validationResult = document.getElementById('validationResult');
            var resultMessage = document.getElementById('resultMessage');

            resultMessage.textContent = message;
            validationResult.className = 'alert ' + alertClass;
            validationResult.style.display = 'block';
        }
    </script>
@endsection
