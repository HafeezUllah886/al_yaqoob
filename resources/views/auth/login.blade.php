<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>BMS - Nexgen Pakistan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Paces is a modern, responsive admin dashboard available on ThemeForest. Ideal for building CRM, CMS, project management tools, and custom web applications with a clean UI, flexible layouts, and rich features.">
    <meta name="keywords"
        content="Paces, admin dashboard, ThemeForest, Bootstrap 5 admin, responsive admin, CRM dashboard, CMS admin, web app UI, admin theme, premium admin template">
    <meta name="author" content="Coderthemes">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <script src="{{ asset('assets/js/demo.js') }}"></script>

    <!-- Vendor css -->
    <link href="{{ asset('assets/css/vendors.min.css') }}" rel="stylesheet" type="text/css">

    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/plugins/toastify/toastify.min.css') }}" rel="stylesheet" type="text/css">

</head>

<body>
    <div class="position-absolute top-0 end-0">
        <img src="{{ asset('assets/images/auth-card-bg.svg') }}" class="auth-card-bg-img" alt="auth-card-bg">
    </div>
    <div class="position-absolute bottom-0 start-0" style="transform: rotate(180deg)">
        <img src="{{ asset('assets/images/auth-card-bg.svg') }}" class="auth-card-bg-img" alt="auth-card-bg">
    </div>
    <div class="auth-box d-flex align-items-center">
        <div class="container-xxl">
            <div class="row align-items-center justify-content-center">
                <div class="col-xl-10">
                    <div class="card">
                        <div class="row justify-content-between g-0">
                            <div class="col-lg-6">
                                <div class="card-body">
                                    <div class="auth-brand text-center mb-4 position-relative">
                                        <h2 class="fw-bold text-dark mt-3">{{ projectName() }}</h2>
                                        <p class="text-muted w-lg-75 mx-auto">{{ projectType() }}</p>
                                    </div>
                                    <p class="text-center text-muted my-3 auth-line">
                                        <span> Welcome Back </span>
                                    </p>

                                    <form action="{{ route('login.post') }}" method="post">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="userEmail" class="form-label">
                                                User Name
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="app-search">
                                                <input type="text" class="form-control" id="userEmail"
                                                    placeholder="Enter User Name" autofocus required name="name">
                                                <i class="ti ti-user app-search-icon text-muted"></i>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="userPassword" class="form-label">
                                                Password
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="app-search">
                                                <input type="password" class="form-control" id="userPassword"
                                                    placeholder="••••••••" required="" name="password">
                                                <i class="ti ti-lock-password app-search-icon text-muted"></i>
                                            </div>
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary fw-semibold py-2">Sign
                                                In</button>
                                        </div>
                                    </form>
                                    <p class="text-center text-muted mt-4 mb-0">
                                        ©
                                        <script>
                                            document.write(new Date().getFullYear())
                                        </script>
                                        {{ projectTypeShort() }} — by
                                        <span class="fw-bold">Nexgen Pakistan</span>
                                    </p>
                                </div>
                            </div>

                            <div class="col-lg-6 d-none d-lg-block">
                                <div class="h-100 position-relative card-side-img rounded-end overflow-hidden"
                                    style="background-image: url({{ asset('assets/images/al-yaqoob.jpg') }})">
                                    <div
                                        class="p-4 card-img-overlay rounded-end auth-overlay d-flex align-items-end justify-content-center opacity-25">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end auth-fluid-->

    <!-- Vendor js -->
    <script src="{{ asset('assets/js/vendors.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/plugins/toastify/toastify.min.js') }}"></script>
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Toastify({
                    text: "{{ session('error') }}",
                    className: "info",
                    close: true,
                    gravity: "top", // `top` or `bottom`
                    position: "center", // `left`, `center` or `right`
                    stopOnFocus: true, // Prevents dismissing of toast on hover
                    style: {
                        background: "linear-gradient(to right, #FF5733, #E70000)",
                    }
                }).showToast();
            });
        </script>
    @endif

</body>

</html>
