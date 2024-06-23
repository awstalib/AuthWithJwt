<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    {{-- validation token service url from env --}}
    
    admin <br>
    Secret123
    <form id="loginForm">
            @csrf
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username"><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password"><br><br>
        {{-- freeipa --}}
        <label for="freeipa">FreeIPA Account:</label>
        <input type="checkbox" id="freeipa" name="freeipa">
        <button type="submit">Login</button>
    </form>

    <div id="response"></div>
    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(event) {
            event.preventDefault();
            
            // Gather form data
            const formData = {
                username: document.getElementById('username').value,
                password: document.getElementById('password').value,
                freeipa: document.getElementById('freeipa').checked
            };

            try {
                // Send login request
                const loginResponse = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify(formData)
                });

                const loginData = await loginResponse.json();

                if (loginData.success) {
                    const token = loginData.data;
                    console.log('Token:', token);
                    // Send token validation request
                    const validateResponse = await fetch('/validate-token', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    });

                    const validateData = await validateResponse.json();
                    document.getElementById('response').innerText = JSON.stringify(validateData, null, 2);
                } else {
                    document.getElementById('response').innerText = 'Login failed: ' + loginData.msg;
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('response').innerText = 'An error occurred';
            }
        });
    </script>
</body>
</html>
