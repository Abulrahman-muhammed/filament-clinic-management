@extends('user.layouts.master')
@section('content')


  <!-- ========== HERO ========== -->
  <section id="home" class="hero-section">
    <div class="hero-pattern"></div>
    <div class="container">
      <div class="row align-items-center min-vh-100 py-5">
        <div class="col-lg-6 order-lg-1 order-2">
          <div class="hero-content">
            <span class="hero-badge">رعاية صحية متميزة</span>
            <h1 class="hero-title">صحتك في أيدٍ<br> أمينة ومحترفة</h1>
            <p class="hero-subtitle">
              نقدم لك أفضل الرعاية الطبية مع فريق متخصص ومواعيد مرنة.
              احجز موعدك الآن بسهولة وأمان.
            </p>
            <div class="hero-actions d-flex flex-wrap gap-3">
              <a href="#booking" class="btn btn-primary btn-lg">
                <i class="bi bi-calendar-check me-2"></i>احجز موعد
              </a>
              <a href="#services" class="btn btn-outline-primary btn-lg">
                <i class="bi bi-grid me-2"></i>عرض الخدمات
              </a>
            </div>
          </div>
        </div>
        <div class="col-lg-6 order-lg-2 order-1">
          <div class="hero-illustration">
            <img src="{{asset('front-assets')}}/images/hero-medical.svg" alt="رسم توضيحي طبي" class="img-fluid">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========== STATISTICS ========== -->
  <section id="statistics" class="stats-section">
    <div class="container">
      <div class="row g-4">
        <div class="col-6 col-md-3">
          <div class="stat-card text-center">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div class="stat-number">{{ $patientsCount }}+</div>
            <div class="stat-label">مريض</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="stat-card text-center">
            <div class="stat-icon"><i class="bi bi-award-fill"></i></div>
            <div class="stat-number">{{ $clinicSettings->experience_years }}+</div>
            <div class="stat-label">سنوات خبرة</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="stat-card text-center">
            <div class="stat-icon"><i class="bi bi-clock-fill"></i></div>
            <div class="stat-number">10 د</div>
            <div class="stat-label">متوسط الاستجابة</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="stat-card text-center">
            <div class="stat-icon"><i class="bi bi-star-fill"></i></div>
            <div class="stat-number">98%</div>
            <div class="stat-label">رضا المرضى</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========== DOCTOR ========== -->
  <section id="doctor" class="section-padding">
    <div class="container">
      <div class="section-header text-center mb-5">
        <span class="section-badge">فريقنا الطبي</span>
        <h2 class="section-title">طبيبك المختص</h2>
        <p class="section-subtitle">خبرة واسعة ورعاية شخصية لكل مريض</p>
      </div>
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="doctor-card">
            <div class="row g-0 align-items-center">
              <div class="col-md-4">
                <div class="doctor-photo">
                  {{-- DOCTOR PHOTO --}}
                    @if($clinicSettings->doctor_image)
                        <img src="{{ asset('storage/' . $clinicSettings->doctor_image) }}" 
                            alt="د. {{ $clinicSettings->doctor_name }}" class="img-fluid">
                    @else
                        <img src="{{ asset('front-assets') }}/images/doctor-placeholder.svg" 
                            alt="د. {{ $clinicSettings->doctor_name }}" class="img-fluid">
                    @endif
                  <span class="availability-badge">
                    @if($doctorSchedule->is_available_today)
                      <i class="bi bi-circle-fill text-success"></i> 
                      {{ $doctorSchedule->start_time_formatted }}  {{ $doctorSchedule->end_time_formatted }} متاح اليوم 
                    @else
                      <i class="bi bi-circle-fill text-danger"></i> غير متاح اليوم
                    @endif
                  </span>
                </div>
              </div>
              <div class="col-md-8">
                <div class="doctor-info">
                  <h3 class="doctor-name">د.  {{ $clinicSettings->doctor_name }}</h3>
                  <p class="doctor-specialization"> {{ $clinicSettings->specialization }}</p>
                  <div class="doctor-details">
                    <div class="detail-item">
                      <i class="bi bi-briefcase"></i>
                      <span>{{ $clinicSettings->experience_years }}+ سنوات خبرة</span>
                    </div>
                    <div class="detail-item">
                      <i class="bi bi-clock"></i>
                      <span>{{ $clinicSettings->working_hours }} ساعات عمل</span>
                    </div>
                    <div class="detail-item">
                      <i class="bi bi-translate"></i>
                      <span>العربية · English</span>
                    </div>
                    <div class="detail-item">
                      <i class="bi bi-geo-alt"></i>
                      <span>{{ $clinicSettings->address }}</span>
                    </div>
                    <div class="detail-item">
                      <i class="bi bi-cash-coin"></i>
                      <span>رسوم الاستشارة: {{ $clinicSettings->consultation_fee }} ج.م</span>
                    </div>
                  </div>
                  <a href="#booking" class="btn btn-primary mt-3">
                    <i class="bi bi-calendar-plus me-2"></i>احجز مع هذا الطبيب
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========== SERVICES ========== -->
  <section id="services" class="section-padding bg-light-section">
    <div class="container">
      <div class="section-header text-center mb-5">
        <span class="section-badge">خدماتنا</span>
        <h2 class="section-title">خدماتنا الطبية</h2>
        <p class="section-subtitle">نقدم مجموعة متنوعة من الخدمات الطبية المتخصصة</p>
      </div>
      <div class="row g-4">
        @foreach($services as $service)
          <div class="col-lg-4 col-md-6">
            <div class="service-card h-100">
              <div class="service-icon">
                <i class="bi bi-clipboard2-pulse"></i>
              </div>
              <h4>{{ $service->name }}</h4>
              <p>{{ $service->description }}</p>
              <div class="d-flex justify-content-between align-items-center mt-3">
                <span class="fw-bold text-primary">{{ $service->price }} ج.م</span>
                <span class="text-muted">{{ $service->duration }} دقيقة</span>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- ========== AI ASSISTANT ========== -->
  <section id="ai-assistant" class="section-padding">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-lg-6">
          <div class="section-header mb-4">
            <span class="section-badge">مساعد ذكي</span>
            <h2 class="section-title">المساعد الطبي الذكي</h2>
            <p class="section-subtitle">
              يساعدك الذكاء الاصطناعي قبل الحجز لاختيار الخدمة والموعد الأنسب لك
            </p>
          </div>
          <ul class="ai-features-list">
            <li><i class="bi bi-check-circle-fill"></i> يقترح الخدمة الأنسب لحالتك</li>
            <li><i class="bi bi-check-circle-fill"></i> يساعدك في اختيار أقرب موعد متاح</li>
            <li><i class="bi bi-check-circle-fill"></i> يجيب على الأسئلة الشائعة عن العيادة</li>
            <li><i class="bi bi-check-circle-fill"></i> يشرح خدمات الطبيب بالتفصيل</li>
            <li><i class="bi bi-check-circle-fill"></i> يقدم إرشادات خطوة بخطوة للحجز</li>
          </ul>
          <div class="ai-notice">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <p>
              <strong>تنبيه مهم:</strong> المساعد الذكي لا يقدم تشخيصاً طبياً.
              دوره يقتصر على مساعدتك أثناء عملية الحجز فقط.
            </p>
          </div>
          <button type="button" class="btn btn-primary btn-lg mt-3" id="startAiAssistant">
            <i class="bi bi-robot me-2"></i>ابدأ المساعد الذكي
          </button>
        </div>
        <div class="col-lg-6">
          <div class="ai-card">
            <div class="ai-card-header">
              <div class="ai-avatar">
                <i class="bi bi-robot"></i>
              </div>
              <div>
                <h4>مساعد العيادة الذكي</h4>
                <span class="ai-status"><i class="bi bi-circle-fill"></i> متصل الآن</span>
              </div>
            </div>
            <div class="ai-card-body">
              <div class="ai-message ai-message-bot">
                <p>مرحباً! كيف يمكنني مساعدتك في حجز موعدك اليوم؟</p>
              </div>
              <div class="ai-message ai-message-user">
                <p>أريد استشارة طبية عامة</p>
              </div>
              <div class="ai-message ai-message-bot">
                <p>بناءً على طلبك، أنصح بخدمة <strong>الاستشارة الطبية</strong> — مدتها 45 دقيقة. هل تريد المتابعة للحجز؟</p>
              </div>
            </div>
            <div class="ai-card-footer">
              <div class="ai-input-placeholder">
                <i class="bi bi-chat-dots"></i>
                <span>اكتب رسالتك هنا...</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========== BOOKING WIZARD ========== -->



  <!-- ========== CONTACT ========== -->
  <section id="contact" class="section-padding">
    <div class="container">
      <div class="section-header text-center mb-5">
        <span class="section-badge">تواصل معنا</span>
        <h2 class="section-title">نحن هنا لمساعدتك</h2>
        <p class="section-subtitle">تواصل معنا في أي وقت خلال ساعات العمل</p>
      </div>
      <div class="row g-4">
        <div class="col-lg-5">
          <div class="contact-info-card">
            <div class="contact-item">
              <div class="contact-icon"><i class="bi bi-telephone-fill"></i></div>
              <div>
                <h5>الهاتف</h5>
                <p dir="ltr">{{ $clinicSettings->phone }}</p>
              </div>
            </div>
            <div class="contact-item">
              <div class="contact-icon"><i class="bi bi-envelope-fill"></i></div>
              <div>
                <h5>البريد الإلكتروني</h5>
                <p dir="ltr">{{ $clinicSettings->email }}</p>
              </div>
            </div>
            <div class="contact-item">
              <div class="contact-icon"><i class="bi bi-geo-alt-fill"></i></div>
              <div>
                <h5>الموقع</h5>
                <p>{{ $clinicSettings->address }}</p>
              </div>
            </div>
            <div class="contact-item">
              <div class="contact-icon"><i class="bi bi-clock-fill"></i></div>
              <div>
                <h5>ساعات العمل</h5>
                <p>{{ $clinicSettings->working_hours }}</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-7">
          <div class="map-placeholder">
            {{-- GOOGLE MAPS --}}
            @if($clinicSettings->google_maps)
                <iframe src="{{ $clinicSettings->google_maps }}" 
                        width="100%" height="350" allowfullscreen="" 
                        loading="lazy" title="موقع العيادة على الخريطة">
                </iframe>
            @else
                <iframe src="https://maps.google.com/maps?q=Cairo%20Egypt&t=&z=13&ie=UTF8&iwloc=&output=embed"
                        width="100%" height="350" allowfullscreen="" loading="lazy">
                </iframe>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection