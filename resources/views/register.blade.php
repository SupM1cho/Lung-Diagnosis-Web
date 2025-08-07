<!-- resources/views/register.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Deteksi Paru-Paru</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: linear-gradient(135deg, #dfe9f3 0%, #ffffff 100%);
    }
    .register-card {
      background: #fff;
      padding: 40px 30px;
      border-radius: 20px;
      width: 320px;
      display: flex;
      flex-direction: column;
      align-items: center;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }
    .register-card h2 {
      color: #333;
      margin-bottom: 30px;
      font-size: 32px;
      font-weight: bold;
    }
    .register-card input {
      width: 100%;
      padding: 12px 20px;
      margin-bottom: 20px;
      border: 1px solid #ddd;
      border-radius: 25px;
      font-size: 14px;
      transition: all 0.3s ease;
      box-sizing: border-box;
      background-color: #f8f9fa;
    }
    .register-card input:focus {
      outline: none;
      border-color: #3b82f6;
      background-color: #fff;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .register-card input.error {
      border-color: #dc3545;
      background-color: #fdf2f2;
    }
    .register-card button {
      width: 140px;
      padding: 12px;
      border: none;
      border-radius: 25px;
      background-color: #3b82f6;
      color: white;
      font-size: 16px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 10px;
    }
    .register-card button:hover {
      background-color: #2563eb;
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    .login-link {
      margin-top: 25px;
      font-size: 14px;
      color: #666;
      text-align: center;
    }
    .login-link a {
      color: #3b82f6;
      text-decoration: none;
      font-weight: 600;
    }
    .login-link a:hover {
      text-decoration: underline;
    }
    .error-message {
      color: #dc3545;
      font-size: 13px;
      text-align: center;
      margin-bottom: 15px;
      background: linear-gradient(135deg, #fdf2f2 0%, #ffffff 100%);
      border: 1px solid #f5c6cb;
      border-radius: 12px;
      padding: 12px;
      width: 100%;
      box-sizing: border-box;
    }
    .success-message {
      color: #155724;
      font-size: 13px;
      text-align: center;
      margin-bottom: 15px;
      background: linear-gradient(135deg, #d4edda 0%, #ffffff 100%);
      border: 1px solid #c3e6cb;
      border-radius: 12px;
      padding: 12px;
      width: 100%;
      box-sizing: border-box;
    }
  </style>
</head>
<body>

<div class="register-card">
  <h2>Register</h2>
  
  <!-- Display Success Messages -->
  @if(session('success'))
    <div class="success-message">
      {{ session('success') }}
    </div>
  @endif

  <!-- Display Error Messages -->
  @if($errors->any())
    <div class="error-message">
      @foreach($errors->all() as $error)
        {{ $error }}<br>
      @endforeach
    </div>
  @endif

  <form action="{{ url('/register') }}" method="POST" style="width: 100%; display: flex; flex-direction: column; align-items: center;">
    @csrf
    <input 
      type="text" 
      name="username" 
      placeholder="Username" 
      value="{{ old('username') }}"
      class="{{ $errors->has('username') ? 'error' : '' }}"
      required>
    <input 
      type="email" 
      name="email" 
      placeholder="Email" 
      value="{{ old('email') }}"
      class="{{ $errors->has('email') ? 'error' : '' }}"
      required>
    <input 
      type="password" 
      name="password" 
      placeholder="Password" 
      class="{{ $errors->has('password') ? 'error' : '' }}"
      required>
    <button type="submit">Register</button>
  </form>

  <div class="login-link">
    Sudah punya akun? <a href="{{ url('/login') }}">Login di sini</a>
  </div>
</div>

</body>
</html>