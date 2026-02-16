<!DOCTYPE html>
<html>
<head>
    <title>Contact Form</title>

    <style>
    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .form-container {
        background: #ffffff;
        padding: 35px;
        width: 420px;
        border-radius: 15px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        animation: fadeIn 0.6s ease-in-out;
    }

    h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #333;
        font-weight: 600;
        letter-spacing: 1px;
    }

    input, textarea {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        outline: none;
    }

    input:focus, textarea:focus {
        border-color: #667eea;
        box-shadow: 0 0 8px rgba(102,126,234,0.3);
    }

    textarea {
        resize: none;
        height: 90px;
    }

    button {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        color: white;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    button:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }

    button:active {
        transform: scale(0.98);
    }

    .success {
        background: #e6fffa;
        color: #065f46;
        padding: 10px;
        border-radius: 6px;
        margin-bottom: 15px;
        text-align: center;
        font-size: 14px;
    }

    .error {
        background: #ffe6e6;
        color: #991b1b;
        padding: 8px;
        border-radius: 6px;
        margin-bottom: 10px;
        font-size: 13px;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

</head>

<body>

<div class="form-container">

<h2>Contact Form</h2>

@if(session('success'))
    <p class="success">{{ session('success') }}</p>
@endif

@if(session('error'))
    <p class="error">{{ session('error') }}</p>
@endif

<form method="POST" action="/contact">
    @csrf

    {{-- Honeypot fields --}}
    @honeypot

    <input type="text" name="name" placeholder="Enter Name">
    @error('name') <p class="error">{{ $message }}</p> @enderror

    <input type="email" name="email" placeholder="Enter Email">
    @error('email') <p class="error">{{ $message }}</p> @enderror

    <textarea name="message" placeholder="Enter Message"></textarea>
    @error('message') <p class="error">{{ $message }}</p> @enderror

    <button type="submit">Submit</button>
</form>

</div>

</body>
</html>
