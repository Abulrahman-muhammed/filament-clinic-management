<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="احجز موعدك في عيادتنا الطبية — رعاية صحية متميزة وخدمة احترافية">
  <title>  {{$clinicSettings->clinic_name}} | حجز المواعيد</title>

  <!-- Bootstrap 5 RTL -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <!-- Google Fonts — Arabic -->
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Custom Styles -->
  <link href="{{asset('front-assets')}}/css/style.css" rel="stylesheet">
  <link href="{{asset('front-assets')}}/css/responsive.css" rel="stylesheet">
  @stack('styles')
</head>
<body>

  <!-- ========== NAVBAR ========== -->
  <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNavbar">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="#home">
        <span class="brand-icon">
          @if($clinicSettings->logo)
            <img src="{{ asset('storage/' . $clinicSettings->logo) }}" alt="Logo" class="img-fluid">
          @else
            <i class="bi bi-heart-pulse-fill"></i>
          @endif
        </span>
        <span class="brand-text"> {{$clinicSettings->clinic_name}}</span>
      </a>

      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
              aria-controls="navbarNav" aria-expanded="false" aria-label="تبديل القائمة">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto gap-lg-1">
          <li class="nav-item"><a class="nav-link" href="#home">الرئيسية</a></li>
          <li class="nav-item"><a class="nav-link" href="#doctor">الطبيب</a></li>
          <li class="nav-item"><a class="nav-link" href="#services">الخدمات</a></li>
          <li class="nav-item"><a class="nav-link" href="#ai-assistant">المساعد الذكي</a></li>
          <li class="nav-item"><a class="nav-link" href="#booking">حجز موعد</a></li>
          <li class="nav-item"><a class="nav-link" href="#contact">تواصل معنا</a></li>
        </ul>
        <div class="navbar-actions d-flex align-items-center gap-2">
          <a href="#login" class="btn btn-outline-primary btn-sm">تسجيل الدخول</a>
          <a href="#booking" class="btn btn-primary btn-sm">احجز الآن</a>
        </div>
      </div>
    </div>
  </nav>

  @yield('content')

  <!-- ========== FOOTER ========== -->
  <footer class="site-footer">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-4">
          <div class="footer-brand d-flex align-items-center gap-2 mb-3">
            <span class="brand-icon">
              @if($clinicSettings->logo)
                <img src="{{ asset('storage/' . $clinicSettings->logo) }}" alt="Logo" class="img-fluid">
              @else
                <i class="bi bi-heart-pulse-fill"></i>
              @endif
            </span>
            <span class="brand-text"> {{$clinicSettings->clinic_name}}</span>
          </div>
          <p class="footer-desc">رعاية صحية متميزة بمعايير عالمية. نلتزم بتقديم أفضل الخدمات الطبية لمرضانا.</p>
        </div>
        <div class="col-6 col-lg-2">
          <h6 class="footer-heading">روابط سريعة</h6>
          <ul class="footer-links">
            <li><a href="#doctor">الطبيب</a></li>
            <li><a href="#services">الخدمات</a></li>
            <li><a href="#booking">حجز موعد</a></li>
            <li><a href="#privacy">سياسة الخصوصية</a></li>
          </ul>
        </div>
        <div class="col-6 col-lg-3">
          <h6 class="footer-heading">الخدمات</h6>
          <ul class="footer-links">
            <li><a href="#services">فحص دوري</a></li>
            <li><a href="#services">متابعة</a></li>
            <li><a href="#services">استشارة</a></li>
            <li><a href="#services">مراجعة تحاليل</a></li>
          </ul>
        </div>
        <div class="col-lg-3">
          <h6 class="footer-heading">تواصل</h6>
          <ul class="footer-links">
            <li dir="ltr">{{ $clinicSettings->phone }}</li>
            <li dir="ltr">{{ $clinicSettings->email }}</li>
          </ul>
        </div>
      </div>
      <hr class="footer-divider">
      <div class="footer-bottom text-center">
        <p>&copy; 2026 عيادة النخبة الطبية. جميع الحقوق محفوظة.</p>
      </div>
    </div>
  </footer>

  <!-- Login Modal Placeholder -->
  <div id="login" class="visually-hidden" aria-hidden="true"></div>
  <div id="privacy" class="visually-hidden" aria-hidden="true"></div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- App Scripts -->

  <script src="{{asset('front-assets')}}/js/api.js"></script>
  {{-- <script src="{{asset('front-assets')}}/js/validation.js"></script> --}}
  {{-- <script src="{{asset('front-assets')}}/js/booking.js"></script> --}}
  <script src="{{asset('front-assets')}}/js/app.js"></script>
  @stack('scripts')
</body>
</html>
