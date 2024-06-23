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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        var loginForm = $('#login-form');
        var validationResult = $('#validation-result');
        var resultMessage = $('#result-message');


        loginForm.on('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            // Show loading message
            resultMessage.text('Processing...');
            validationResult.removeClass().addClass('alert alert-info').show();

            $.ajax({
                url: '/api/login',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        console.log(data);
                        if (data.status != 200) {
                            if (data.errors) {
                                resultMessage.text(Object.values(data.errors).flat().join(' '));
                            } else {
                                resultMessage.text(data.error_message);
                            }
                            validationResult.removeClass().addClass('alert alert-danger').show();
                            setTimeout(function() {
                                validationResult.hide();
                            }, 5000);
                        } else {
                            validationResult.hide();
                        }
                    }
                },
                error: function(xhr) {
                    console.log(xhr);
                  if(xhr.responseJSON.error_message){
                    resultMessage.text(xhr.responseJSON.error_message);
                    }else{
                    resultMessage.text('An error occurred while processing the request.');
                    }
                    validationResult.removeClass().addClass('alert alert-danger').show();
                    setTimeout(function() {
                        validationResult.hide();
                    }, 5000);
                }
            });
        });
    });
</script>

@endsection
