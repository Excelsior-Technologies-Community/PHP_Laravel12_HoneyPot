# PHP_Laravel12_HoneyPot

<p align="center">
<a href="#"><img src="https://img.shields.io/badge/Laravel-12-red" alt="Laravel Version"></a>
<a href="#"><img src="https://img.shields.io/badge/PHP-8.2-blue" alt="PHP Version"></a>
<a href="#"><img src="https://img.shields.io/badge/Spam%20Protection-Honeypot-green" alt="Honeypot"></a>
<a href="#"><img src="https://img.shields.io/badge/Status-Active-success" alt="Project Status"></a>
</p>

---

## Overview

This project demonstrates how to implement spam protection in Laravel 12 using:

* spatie/laravel-honeypot
* Custom spam responder
* Time-based spam detection
* Clean UI contact form

The honeypot protects forms by:

* Adding a hidden trap field
* Checking submission timing
* Blocking suspicious requests

---

##  Features

* Laravel 12 clean project setup
* Spam protection using spatie/laravel-honeypot
* Hidden honeypot trap field
* Time-based spam validation (5 seconds minimum)
* Custom spam response handler
* Clean and modern UI contact form
* Middleware-based spam protection
* Easy configuration via config/honeypot.php

---

##  Folder Structure

```
honeypot-demo/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── ContactController.php
│   │
│   └── Spam/
│       └── CustomSpamResponder.php
│
├── config/
│   └── honeypot.php
│
├── resources/
│   └── views/
│       └── contact.blade.php
│
├── routes/
│   └── web.php
│
├── .env
└── composer.json
```


## Step 1 — Create Laravel Project

```bash
composer create-project laravel/laravel honeypot-demo
```

Start development server:

```bash
php artisan serve
```

Open in browser:

```
http://127.0.0.1:8000
```

---

## .env Configuration

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

---

## Step 2 — Install Honeypot Package

Install Spatie Honeypot:

```bash
composer require spatie/laravel-honeypot
```

Publish configuration file:

```bash
php artisan vendor:publish --provider="Spatie\Honeypot\HoneypotServiceProvider" --tag="honeypot-config"
```

This creates:

```
config/honeypot.php
```

---

## Step 3 — Configure Honeypot

Open:

```
config/honeypot.php
```

Replace content with:

```php
<?php

use App\Spam\CustomSpamResponder;

return [

    'enabled' => true, // Enable or disable honeypot protection

    'name_field_name' => 'my_name', // Name of the hidden trap field

    'randomize_name_field_name' => true, // Randomizes the honeypot field name for better security

    'valid_from_timestamp' => true, // Enables time-based spam protection check

    'valid_from_field_name' => 'valid_from', // Hidden field that stores form load timestamp

    'amount_of_seconds' => 5, // Minimum seconds required before form submission is allowed

    'respond_to_spam_with' => CustomSpamResponder::class, // Custom response shown when spam is detected

    'honeypot_fields_required_for_all_forms' => false, // If true, all forms must contain honeypot fields

    'spam_protection' => \Spatie\Honeypot\SpamProtection::class, // Core spam protection logic class

    'with_csp' => false, // Enable only if using Laravel CSP package
];
```

Clear config cache:

```bash
php artisan optimize:clear
```

---

## How It Works

* A hidden field is added to the form.
* A timestamp is stored when the page loads.

If:

* Hidden field is filled → spam
* Form is submitted in less than 5 seconds → spam

A custom error message is shown.

---

## Step 4 — Create Custom Spam Responder

Create folder:

```
app/Spam
```

Create file:

```
app/Spam/CustomSpamResponder.php
```

Add:

```php
<?php

namespace App\Spam;

use Closure;
use Illuminate\Http\Request;
use Spatie\Honeypot\SpamResponder\SpamResponder;

class CustomSpamResponder implements SpamResponder
{
    public function respond(Request $request, Closure $next)
    {
        return redirect()
            ->back()
            ->with('error', 'Spam detected. Please try again slowly.');
    }
}
```

---

## Step 5 — Create Controller

Run:

```bash
php artisan make:controller ContactController
```

Open:

```
app/Http/Controllers/ContactController.php
```

Replace with:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        return back()->with('success', 'Form submitted successfully!');
    }
}
```

---

## Step 6 — Add Routes

Open:

```
routes/web.php
```

Add:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use Spatie\Honeypot\ProtectAgainstSpam;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/contact', [ContactController::class, 'index']);

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware(ProtectAgainstSpam::class);
```

---

## Step 7 — Create Contact View

Create:

```
resources/views/contact.blade.php
```

Add:

```blade
<!DOCTYPE html>
<html>
<head>
    <title>Contact Form</title>

    <style>
    * { box-sizing: border-box; }

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
    }

    h2 {
        text-align: center;
        margin-bottom: 25px;
        font-weight: 600;
    }

    input, textarea {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    textarea { resize: none; height: 90px; }

    button {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        color: white;
        border-radius: 8px;
        cursor: pointer;
    }

    .success {
        background: #e6fffa;
        color: #065f46;
        padding: 10px;
        border-radius: 6px;
        margin-bottom: 15px;
        text-align: center;
    }

    .error {
        background: #ffe6e6;
        color: #991b1b;
        padding: 8px;
        border-radius: 6px;
        margin-bottom: 10px;
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
```

---

## Step 8 — Testing

Open:

```
http://127.0.0.1:8000/contact
```

### Normal User Test

* Fill form
* Wait 5 seconds
* Submit
* Success message appears

  <img width="425" height="470" alt="Screenshot 2026-02-16 164853" src="https://github.com/user-attachments/assets/b7c00c65-66c7-4ccf-93d0-2b11aca22664" />


### Spam Test

* Submit immediately (under 5 seconds)
  OR
* Manually fill hidden honeypot field using DevTools
* Spam error message appears

<img width="423" height="451" alt="Screenshot 2026-02-16 163909" src="https://github.com/user-attachments/assets/922be5db-3d08-4fa8-995f-3423e6f64cd1" />

---

## Final Result

* Laravel 12 project
* Honeypot spam protection
* Time-based validation
* Custom spam message
* Clean modern UI
* Fully functional example
