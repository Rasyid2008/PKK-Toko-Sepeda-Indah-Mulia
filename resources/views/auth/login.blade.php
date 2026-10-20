<!DOCTYPE html>
<html lang="id" class="light-style customizer-hide" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Login - Toko Sepeda Indah Mulia</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon/bike.svg') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://googleapis.com" />
    <link rel="preconnect" href="https://gstatic.com" crossorigin />
    <link href="https://googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Icons & Core CSS Bawaan Sneat -->
    <link rel="stylesheet" href="{{ asset('vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/css/pages/page-auth.css') }}" />
    <style>
      .login-brand {
        width: 100%;
      }

      .login-brand .app-brand-link {
        max-width: 100%;
      }

      .login-brand .app-brand-text {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 0;
        max-width: 100%;
        flex-shrink: 1;
        font-size: 1.5rem;
        line-height: 1.2;
        text-align: center;
        white-space: normal;
      }

      .login-brand .app-brand-text i {
        flex: 0 0 auto;
      }

      @media (max-width: 575.98px) {
        .login-brand .app-brand-text {
          font-size: 1.15rem;
        }
      }
    </style>
  </head>

  <body>
    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Login Card -->
          <div class="card">
            <div class="card-body">
              <div class="app-brand login-brand justify-content-center">
                <a href="/" class="app-brand-link gap-2">
                  <span class="app-brand-text demo text-body fw-bolder text-uppercase text-primary">
                    <i class=" fs-3 align-center me-1"></i>Toko Sepeda Indah Mulia
                  </span>
                </a>
              </div>
              
              <h4 class="mb-2 text-center">Selamat Datang!</h4>
              <p class="mb-4 text-center text-muted small">Silakan login untuk mengakses Sistem Manajemen Toko Sepeda</p>

              <!-- Pesan Error -->
              @if($errors->any())
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <div class="small">{{ $errors->first() }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              @endif

              <form id="formAuthentication" class="mb-3" action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                  <label for="email" class="form-label font-weight-bold">Alamat Email</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="username" value="{{ old('email') }}" required autofocus />
                </div>
                
                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label font-weight-bold" for="password">Password</label>
                  </div>
                  <div class="input-group input-group-merge">
                    <input type="password" id="password" class="form-control" name="password" placeholder="••••••••" required />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>

                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember" />
                    <label class="form-check-content text-muted small" for="remember"> Ingat Saya di Perangkat Ini </label>
                  </div>
                </div>
                
                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" type="submit">Masuk ke Aplikasi</button>
                </div>
              </form>

              <div class="text-center">
                <a href="/" class="d-flex align-items-center justify-content-center text-muted small">
                  <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i> Kembali ke Landing Page
                </a>
              </div>
            </div>
          </div>
          <!-- /Login Card -->
        </div>
      </div>
    </div>

    <!-- Core JS Bawaan Sneat --><!-- SCRIPT BARU UNTUK MENGAKTIFKAN MATA PASSWORD SECARA AKURAT -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Mencari elemen form-password-toggle bawaan template
        const passwordToggles = document.querySelectorAll('.form-password-toggle');
        
        passwordToggles.forEach(function (toggle) {
            const toggleBtn = toggle.querySelector('.input-group-text');
            const passwordInput = toggle.querySelector('input');
            
            if (toggleBtn && passwordInput) {
                toggleBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const icon = toggleBtn.querySelector('i');
                    
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.classList.remove('bx-hide');
                        icon.classList.add('bx-show');
                    } else {
                        passwordInput.type = 'password';
                        icon.classList.remove('bx-show');
                        icon.classList.add('bx-hide');
                    }
                });
            }
        });
    });
</script>

    <script src="{{ asset('vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('js/dashboards-analytics.js') }}"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>