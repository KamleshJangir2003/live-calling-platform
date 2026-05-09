<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - LiveCall</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #00b894 0%, #00cec9 100%); min-height: 100vh; display: flex; align-items: center; padding: 20px 0; }
        .auth-card { border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
        .auth-logo { color: #00b894; font-size: 2rem; font-weight: 800; }
        .btn-primary { background: #00b894; border-color: #00b894; }
        .btn-primary:hover { background: #00a381; border-color: #00a381; }
        .form-control:focus, .form-select:focus { border-color: #00b894; box-shadow: 0 0 0 0.2rem rgba(0,184,148,0.15); }
        .role-card { border: 2px solid #dee2e6; border-radius: 12px; padding: 16px; cursor: pointer; transition: all 0.2s; }
        .role-card:hover, .role-card.selected { border-color: #00b894; background: #e8f8f5; }
        .role-card input { display: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card auth-card p-4">
                    <div class="text-center mb-4">
                        <div class="auth-logo"><i class="bi bi-camera-video-fill"></i> LiveCall</div>
                        <p class="text-muted mt-1">Create your account</p>
                    </div>
                    @if($errors->any())
                        <div class="alert alert-danger py-2">
                            @foreach($errors->all() as $e) <small>{{ $e }}</small><br> @endforeach
                        </div>
                    @endif
                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">I want to join as</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="role-card text-center d-block {{ old('role','user') === 'user' ? 'selected' : '' }}" id="roleUser">
                                        <input type="radio" name="role" value="user" {{ old('role','user') === 'user' ? 'checked' : '' }}>
                                        <i class="bi bi-person-fill fs-2 d-block mb-1" style="color:#00b894"></i>
                                        <strong>User</strong>
                                        <small class="d-block text-muted">Browse & call models</small>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="role-card text-center d-block {{ old('role') === 'model' ? 'selected' : '' }}" id="roleModel">
                                        <input type="radio" name="role" value="model" {{ old('role') === 'model' ? 'checked' : '' }}>
                                        <i class="bi bi-camera-video-fill fs-2 d-block mb-1" style="color:#fd79a8"></i>
                                        <strong>Model</strong>
                                        <small class="d-block text-muted">Earn from calls</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control" required minlength="8">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Create Account</button>
                    </form>
                    <hr>
                    <p class="text-center mb-0">Already have an account? <a href="{{ route('login') }}" class="text-decoration-none fw-semibold" style="color:#00b894">Login</a></p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('input[name="role"]').forEach(radio => {
            radio.addEventListener('change', () => {
                document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
                radio.closest('.role-card').classList.add('selected');
            });
        });
    </script>
</body>
</html>
