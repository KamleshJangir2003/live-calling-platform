<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LiveCall</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #00b894 0%, #00cec9 100%); min-height: 100vh; display: flex; align-items: center; }
        .auth-card { border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
        .auth-logo { color: #00b894; font-size: 2rem; font-weight: 800; }
        .btn-primary { background: #00b894; border-color: #00b894; }
        .btn-primary:hover { background: #00a381; border-color: #00a381; }
        .form-control:focus { border-color: #00b894; box-shadow: 0 0 0 0.2rem rgba(0,184,148,0.15); }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card auth-card p-4">
                    <div class="text-center mb-4">
                        <div class="auth-logo"><i class="bi bi-camera-video-fill"></i> LiveCall</div>
                        <p class="text-muted mt-1">Welcome back!</p>
                    </div>
                    @if($errors->any())
                        <div class="alert alert-danger py-2">
                            @foreach($errors->all() as $e) <small>{{ $e }}</small><br> @endforeach
                        </div>
                    @endif
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Login</button>
                    </form>
                    <hr>
                    <p class="text-center mb-0">Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none fw-semibold" style="color:#00b894">Register</a></p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
