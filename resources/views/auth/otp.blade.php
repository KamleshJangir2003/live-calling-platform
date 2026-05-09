<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - LiveCall</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #00b894 0%, #00cec9 100%); min-height: 100vh; display: flex; align-items: center; }
        .auth-card { border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
        .otp-input { letter-spacing: 12px; font-size: 1.8rem; text-align: center; font-weight: 700; }
        .btn-primary { background: #00b894; border-color: #00b894; }
        .btn-primary:hover { background: #00a381; border-color: #00a381; }
        .form-control:focus { border-color: #00b894; box-shadow: 0 0 0 0.2rem rgba(0,184,148,0.15); }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card auth-card p-4 text-center">
                    <div class="mb-4">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                            <i class="bi bi-phone-fill fs-2" style="color:#00b894"></i>
                        </div>
                        <h4 class="fw-bold">Verify Your Phone</h4>
                        <p class="text-muted">Enter the 6-digit OTP sent to your phone</p>
                    </div>
                    @if(session('info'))
                        <div class="alert alert-info py-2 text-start"><small>{{ session('info') }}</small></div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger py-2 text-start">
                            @foreach($errors->all() as $e) <small>{{ $e }}</small><br> @endforeach
                        </div>
                    @endif
                    <form action="{{ route('otp.verify') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <input type="text" name="otp" class="form-control otp-input" maxlength="6" pattern="\d{6}" required autofocus placeholder="------">
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Verify OTP</button>
                    </form>
                    <div class="mt-3">
                        <form action="{{ route('otp.resend') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link text-decoration-none" style="color:#00b894">
                                <i class="bi bi-arrow-clockwise me-1"></i>Resend OTP
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
