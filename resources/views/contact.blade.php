<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Secure Contact Form</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            background: white;
            padding: 35px;
            width: 440px;
            max-width: 95%;
            border-radius: 15px;

            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);

            animation: fadeIn 0.6s ease-in-out;
        }

        h2 {
            text-align: center;
            margin: 0 0 8px;
            color: #333;
        }

        .subtitle {
            text-align: center;
            color: #777;
            font-size: 13px;
            margin-bottom: 25px;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px;

            margin-bottom: 15px;

            border: 1px solid #ddd;
            border-radius: 8px;

            font-size: 14px;

            outline: none;

            transition: all 0.3s ease;
        }

        input:focus,
        textarea:focus {
            border-color: #667eea;

            box-shadow:
                0 0 8px rgba(102, 126, 234, 0.3);
        }

        textarea {
            resize: none;
            height: 110px;
        }

        button {
            width: 100%;

            padding: 12px;

            background:
                linear-gradient(135deg, #667eea, #764ba2);

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

            box-shadow:
                0 10px 20px rgba(0, 0, 0, 0.2);
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

        .dashboard-link {
            display: block;

            text-align: center;

            margin-top: 18px;

            color: #667eea;

            text-decoration: none;

            font-size: 13px;
        }

        .security-badge {
            text-align: center;

            margin-top: 15px;

            color: #6b7280;

            font-size: 12px;
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

    <h2>Secure Contact Form</h2>

    <p class="subtitle">
        Protected by Laravel Honeypot Spam Protection
    </p>


    @if(session('success'))

        <p class="success">
            {{ session('success') }}
        </p>

    @endif


    @if(session('error'))

        <p class="error">
            {{ session('error') }}
        </p>

    @endif


    <form
        method="POST"
        action="{{ route('contact.store') }}"
    >

        @csrf

        {{-- Honeypot security fields --}}
        @honeypot


        <input
            type="text"
            name="name"
            value="{{ old('name') }}"
            placeholder="Enter Name"
        >

        @error('name')

            <p class="error">
                {{ $message }}
            </p>

        @enderror


        <input
            type="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="Enter Email"
        >

        @error('email')

            <p class="error">
                {{ $message }}
            </p>

        @enderror


        <textarea
            name="message"
            placeholder="Enter Message"
        >{{ old('message') }}</textarea>

        @error('message')

            <p class="error">
                {{ $message }}
            </p>

        @enderror


        <button type="submit">
            Submit Securely
        </button>

    </form>


    <a
        href="{{ route('spam.dashboard') }}"
        class="dashboard-link"
    >
        🛡️ Open Spam Security Dashboard
    </a>


    <div class="security-badge">
        🔒 Honeypot + Time-Based + IP Protection
    </div>

</div>

</body>

</html>