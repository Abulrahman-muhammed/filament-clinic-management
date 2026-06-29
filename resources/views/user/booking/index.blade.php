@extends('user.layouts.master')

@section('content')

<section class="bk-section" dir="rtl">
  <div class="bk-wrapper">

    {{-- ════ ALERTS ════ --}}
    @if ($errors->any())
      <div class="bk-alert bk-alert--error">
        <i class="bi bi-exclamation-circle-fill"></i>
        <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif
    @if (session('success'))
      <div class="bk-alert bk-alert--success">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
      </div>
    @endif
    @if (session('error'))
      <div class="bk-alert bk-alert--error">
        <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
      </div>
    @endif

    <div class="bk-layout">

      {{-- ════════════ SIDEBAR ════════════ --}}
      <aside class="bk-sidebar">

        {{-- Doctor card --}}
        <div class="bk-card">
          <div class="bk-doc-banner">
            @if($clinicSettings->doctor_image)
              <img src="{{ asset('storage/' . $clinicSettings->doctor_image) }}" alt="د. {{ $clinicSettings->doctor_name }}">
            @else
              <div class="bk-doc-initials">
                <span>{{ strtoupper(mb_substr($clinicSettings->doctor_name, 0, 1)) }}</span>
              </div>
            @endif
          </div>
          <div class="bk-doc-meta">
            <h2 class="bk-doc-name">د. {{ $clinicSettings->doctor_name }}</h2>
            <span class="bk-badge">{{ $clinicSettings->specialization }}</span>
            <div class="bk-stars">
              @for($i = 1; $i <= 5; $i++)<i class="bi bi-star-fill{{ $i === 5 ? ' half' : '' }}"></i>@endfor
              <small>4.8</small>
            </div>
            <div class="bk-exp-pill">
              <i class="bi bi-briefcase-fill"></i>
              <strong>{{ $clinicSettings->experience_years }}+</strong>
              <span>سنوات خبرة</span>
            </div>
          </div>
        </div>

        {{-- Working schedule --}}
        @if($schedules->count())
        <div class="bk-card bk-card--pad">
          <p class="bk-label"><i class="bi bi-clock"></i> أيام العمل</p>
          @foreach($schedules as $s)
            <div class="bk-row">
              <span class="fw-bold">{{ \App\Enums\DayOfWeek::from($s->day_of_week)->arLabel() }}</span>
              <span class="text-muted small">
                {{ $s->start_time_formatted }}
                – {{ $s->end_time_formatted }}
              </span>
            </div>
          @endforeach
        </div>
        @endif

        {{-- Fee --}}
        <div class="bk-card bk-card--pad">
          <div class="bk-row">
            <span class="text-muted"><i class="bi bi-cash-coin text-primary"></i> رسوم الكشف</span>
            <strong>{{ number_format($clinicSettings->consultation_fee, 0) }} ج.م</strong>
          </div>
        </div>

        {{-- Trust --}}
        <div class="bk-card bk-card--pad bk-trust">
          <div class="bk-trust-item"><i class="bi bi-shield-lock-fill text-primary"></i> دفع آمن ومشفّر</div>
          <div class="bk-trust-item"><i class="bi bi-headset text-primary"></i> دعم على مدار الساعة</div>
        </div>

      </aside>

      {{-- ════════════ MAIN FORM ════════════ --}}
      <div class="bk-main">
        <form action="{{ route('user.booking.store') }}" method="POST" id="bk-form">
          @csrf

          {{-- Hidden fields filled by JS --}}
          <input type="hidden" name="appointment_date" id="hidden-date" value="{{ old('appointment_date') }}">
          <input type="hidden" name="appointment_time" id="hidden-time" value="{{ old('appointment_time') }}">

          {{-- ① Date & Time --}}
          <div class="bk-block">
            <div class="bk-block-title">
              <div class="bk-num">١</div>
              <div>
                <h3>اختر التاريخ والوقت</h3>
                <p class="text-muted small">اختر أحد الأيام المتاحة للطبيب</p>
              </div>
            </div>

            @error('appointment_date')
              <p class="bk-field-error"><i class="bi bi-x-circle"></i> {{ $message }}</p>
            @enderror
            @error('appointment_time')
              <p class="bk-field-error"><i class="bi bi-x-circle"></i> {{ $message }}</p>
            @enderror

            {{-- Display fields (readonly) --}}
            <div class="bk-grid-2 mb-3" id="selection-display" style="display:none">
              <div class="bk-field">
                <label>التاريخ المختار</label>
                <div class="bk-inp">
                  <i class="bi bi-calendar-check text-primary"></i>
                  <input type="text" id="display-date" readonly value="" placeholder="—">
                </div>
              </div>
              <div class="bk-field">
                <label>الوقت المختار</label>
                <div class="bk-inp">
                  <i class="bi bi-clock text-primary"></i>
                  <input type="text" id="display-time" readonly value="" placeholder="—">
                </div>
              </div>
            </div>

            <p class="bk-label mb-2"><i class="bi bi-calendar3 text-primary"></i> الأيام المتاحة</p>
            <div class="bk-day-grid" id="day-grid">
              @foreach($availableDates as $avail)
                <button
                  type="button"
                  class="bk-day-btn {{ old('appointment_date') === $avail['date'] ? 'active' : '' }}"
                  data-date="{{ $avail['date'] }}"
                  data-day-ar="{{ $avail['day_name'] }}"
                  data-formatted="{{ $avail['day_name'] }} {{ \Carbon\Carbon::parse($avail['date'])->format('j/m') }}"
                  data-start-fmt="{{ $avail['start_fmt'] }}"
                  data-end-fmt="{{ $avail['end_fmt'] }}"
                >
                  <span class="bk-day-name">{{ $avail['day_name'] }}</span>
                  <span class="bk-day-hours">{{ $avail['start_fmt'] }} – {{ $avail['end_fmt'] }}</span>
                  <small class="bk-day-date">{{ \Carbon\Carbon::parse($avail['date'])->format('j M') }}</small>
                </button>
              @endforeach
            </div>

            {{-- Schedule info bar --}}
            <div id="sched-info" class="bk-sched-info" style="display:none">
              <i class="bi bi-clock"></i>
              <span>د. {{ $clinicSettings->doctor_name }} متاح يوم
                <strong id="sched-day"></strong> من
                <strong id="sched-from"></strong> حتى
                <strong id="sched-to"></strong>
              </span>
            </div>

            {{-- Time slots --}}
            <div id="slots-area" style="display:none; margin-top:18px">
              <p class="bk-label mb-2"><i class="bi bi-alarm text-primary"></i> المواعيد المتاحة</p>
              <div class="bk-time-grid" id="slots-grid"></div>
              <p id="no-slots-msg" class="text-muted small" style="display:none; margin-top:8px">
                <i class="bi bi-calendar-x"></i> لا توجد مواعيد متاحة في هذا اليوم.
              </p>
            </div>
          </div>

          {{-- ② Patient Information --}}
          <div class="bk-block">
            <div class="bk-block-title">
              <div class="bk-num">٢</div>
              <div>
                <h3>بيانات المريض</h3>
                <p class="text-muted small">أدخل بيانات المريض بدقة</p>
              </div>
            </div>

            <div class="bk-grid-2">

              <div class="bk-field">
                <label for="patient_name">الاسم الكامل <span class="req">*</span></label>
                <div class="bk-inp @error('patient_name') is-invalid @enderror">
                  <i class="bi bi-person"></i>
                  <input type="text" id="patient_name" name="patient_name" required
                    placeholder="اسم المريض"
                    value="{{ old('patient_name', auth()->user()->name ?? '') }}">
                </div>
                @error('patient_name')
                  <p class="bk-field-error"><i class="bi bi-x-circle"></i> {{ $message }}</p>
                @enderror
              </div>

              <div class="bk-field">
                <label for="patient_phone">رقم الهاتف <span class="req">*</span></label>
                <div class="bk-inp @error('patient_phone') is-invalid @enderror">
                  <i class="bi bi-telephone"></i>
                  <input type="tel" id="patient_phone" name="patient_phone" required
                    placeholder="01xxxxxxxxx" dir="ltr"
                    value="{{ old('patient_phone', auth()->user()->phone ?? '') }}">
                </div>
                @error('patient_phone')
                  <p class="bk-field-error"><i class="bi bi-x-circle"></i> {{ $message }}</p>
                @enderror
              </div>

              <div class="bk-field col-span-2">
                <label for="patient_email">البريد الإلكتروني <span class="req">*</span></label>
                <div class="bk-inp @error('patient_email') is-invalid @enderror">
                  <i class="bi bi-envelope"></i>
                  <input type="email" id="patient_email" name="patient_email" required
                    placeholder="patient@email.com" dir="ltr"
                    value="{{ old('patient_email', auth()->user()->email ?? '') }}">
                </div>
                @error('patient_email')
                  <p class="bk-field-error"><i class="bi bi-x-circle"></i> {{ $message }}</p>
                @enderror
              </div>

              <div class="bk-field">
                <label for="patient_dob">تاريخ الميلاد</label>
                <div class="bk-inp @error('patient_dob') is-invalid @enderror">
                  <i class="bi bi-calendar"></i>
                  <input type="date" id="patient_dob" name="patient_dob"
                    max="{{ now()->subDay()->format('Y-m-d') }}"
                    value="{{ old('patient_dob') }}">
                </div>
                @error('patient_dob')
                  <p class="bk-field-error"><i class="bi bi-x-circle"></i> {{ $message }}</p>
                @enderror
              </div>

              <div class="bk-field">
                <label for="patient_gender">الجنس</label>
                <div class="bk-inp bk-inp--select @error('patient_gender') is-invalid @enderror">
                  <i class="bi bi-gender-ambiguous"></i>
                  <select id="patient_gender" name="patient_gender">
                    <option value="">— اختر —</option>
                    <option value="male"   {{ old('patient_gender') === 'male'   ? 'selected' : '' }}>ذكر</option>
                    <option value="female" {{ old('patient_gender') === 'female' ? 'selected' : '' }}>أنثى</option>
                  </select>
                </div>
                @error('patient_gender')
                  <p class="bk-field-error"><i class="bi bi-x-circle"></i> {{ $message }}</p>
                @enderror
              </div>

              <div class="bk-field">
                <label for="patient_blood_type">فصيلة الدم</label>
                <div class="bk-inp bk-inp--select">
                  <i class="bi bi-droplet-fill"></i>
                  <select id="patient_blood_type" name="patient_blood_type">
                    <option value="">— اختر —</option>
                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bt)
                      <option value="{{ $bt }}" {{ old('patient_blood_type') === $bt ? 'selected' : '' }}>{{ $bt }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="bk-field col-span-2">
                <label for="service_id">الخدمة المطلوبة <span class="req">*</span></label>
                <div class="bk-inp bk-inp--select @error('service_id') is-invalid @enderror">
                  <i class="bi bi-clipboard2-pulse"></i>
                  <select id="service_id" name="service_id" required>
                    <option value="">— اختر الخدمة —</option>
                    @foreach($services as $service)
                      <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                        {{ $service->name }} — {{ $service->price }} ج.م
                      </option>
                    @endforeach
                  </select>
                </div>
                @error('service_id')
                  <p class="bk-field-error"><i class="bi bi-x-circle"></i> {{ $message }}</p>
                @enderror
              </div>

              <div class="bk-field col-span-2">
                <label for="patient_notes">ملاحظات طبية <span class="text-muted fw-normal">(اختياري)</span></label>
                <div class="bk-inp bk-inp--textarea">
                  <i class="bi bi-sticky"></i>
                  <textarea id="patient_notes" name="patient_notes" rows="3"
                    placeholder="الحساسية، الأمراض المزمنة، أي ملاحظات للطبيب...">{{ old('patient_notes') }}</textarea>
                </div>
              </div>

            </div>
          </div>

          {{-- ③ Payment Method --}}
          <div class="bk-block">
            <div class="bk-block-title">
              <div class="bk-num">٣</div>
              <div>
                <h3>طريقة الدفع</h3>
                <p class="text-muted small">اختر طريقة الدفع المناسبة</p>
              </div>
            </div>

            <div class="bk-pay-tabs">

              <label class="bk-pay-tab active" for="pay-card">
                <input type="radio" name="payment_method" id="pay-card" value="card" checked hidden>
                <i class="bi bi-credit-card"></i>
                <span>دفع إلكتروني</span>
                <small class="text-muted">فيزا / ماستركارد</small>
              </label>

              <label class="bk-pay-tab" for="pay-clinic">
                <input type="radio" name="payment_method" id="pay-clinic" value="at_clinic" hidden>
                <i class="bi bi-hospital"></i>
                <span>دفع في العيادة</span>
                <small class="text-muted">يوم الموعد</small>
              </label>

            </div>

            <div id="card-notice" class="bk-notice bk-notice--blue">
              <i class="bi bi-shield-check"></i>
              <div>سيتم تحويلك إلى بوابة الدفع الآمن لسداد
                <strong>{{ number_format($clinicSettings->consultation_fee, 0) }} ج.م</strong>.
                تدعم فيزا وماستركارد وميزة.
              </div>
            </div>

            <div id="clinic-notice" class="bk-notice bk-notice--green" style="display:none">
              <i class="bi bi-check-circle"></i>
              ستدفع <strong>{{ number_format($clinicSettings->consultation_fee, 0) }} ج.م</strong>
              مباشرةً في العيادة يوم موعدك. لا حاجة لأي دفع الآن.
            </div>

          </div>

          {{-- Submit Row --}}
          <div class="bk-submit-row">
            <div class="bk-submit-total">
              الإجمالي: <strong>{{ number_format($clinicSettings->consultation_fee, 0) }} ج.م</strong>
            </div>
            <button type="submit" class="bk-btn" id="submit-btn" disabled>
              <i class="bi bi-calendar-check"></i>
              <span id="btn-label">تأكيد الحجز والدفع</span>
            </button>
          </div>

        </form>
      </div>{{-- /bk-main --}}

    </div>{{-- /bk-layout --}}
  </div>{{-- /bk-wrapper --}}
</section>

@endsection

{{-- ════════════════════════════════════════ STYLES ════════════════════════════════════════ --}}
@push('styles')
  <link href="{{ asset('front-assets/css/booking.css') }}" rel="stylesheet">
@endpush

{{-- ════════════════════════════════════════ SCRIPTS ════════════════════════════════════════ --}}
@push('scripts')
<script>
(function () {
  'use strict';

  const $ = id => document.getElementById(id);
  const SLOTS_URL = '{{ route("user.booking.slots") }}';
  const CSRF      = '{{ csrf_token() }}';

  let selectedDate = null;
  let selectedTime = null;

  const oldDate = $('hidden-date').value;
  const oldTime = $('hidden-time').value;

  // ── Day buttons ───────────────────────────────────────────────────────────
  document.querySelectorAll('.bk-day-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      document.querySelectorAll('.bk-day-btn').forEach(b => b.classList.remove('active'));
      this.classList.add('active');

      selectedDate = this.dataset.date;   // ← من السيرفر مباشرة، مفيش حسابات
      selectedTime = null;

      $('hidden-date').value = selectedDate;
      $('hidden-time').value = '';

      // Display fields
      $('display-date').value = this.dataset.formatted;
      $('display-time').value = '';
      $('selection-display').style.display = 'grid';

      // Info bar
      $('sched-day').textContent  = this.dataset.dayAr;
      $('sched-from').textContent = this.dataset.startFmt;
      $('sched-to').textContent   = this.dataset.endFmt;
      $('sched-info').style.display = 'flex';

      fetchSlots(selectedDate);
      checkSubmit();
    });

    // Restore after validation fail
    if (oldDate && btn.dataset.date === oldDate) {
      btn.click();
    }
  });

  // ── Fetch slots ───────────────────────────────────────────────────────────
  function fetchSlots(date) {
    const area  = $('slots-area');
    const grid  = $('slots-grid');
    const noMsg = $('no-slots-msg');

    area.style.display = 'block';
    grid.innerHTML = '<span class="bk-slots-loading"><i class="bi bi-arrow-repeat bk-spin"></i> جاري تحميل المواعيد...</span>';
    noMsg.style.display = 'none';

    fetch(`${SLOTS_URL}?date=${date}`, {
      headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(({ slots }) => {
      grid.innerHTML = '';
      if (!slots.length) { noMsg.style.display = 'block'; return; }

      slots.forEach(({ value, label }) => {
        const btn = document.createElement('button');
        btn.type      = 'button';
        btn.className = 'bk-slot';
        btn.dataset.time = value;
        btn.textContent  = label;

        // Restore selected slot after validation fail
        if (oldTime && value === oldTime) {
          btn.classList.add('selected');
          selectedTime = value;
          $('hidden-time').value  = value;
          $('display-time').value = label;
          checkSubmit();
        }

        btn.addEventListener('click', function () {
          document.querySelectorAll('.bk-slot').forEach(s => s.classList.remove('selected'));
          this.classList.add('selected');
          selectedTime = value;
          $('hidden-time').value  = value;
          $('display-time').value = label;
          checkSubmit();
        });

        grid.appendChild(btn);
      });
    })
    .catch(() => {
      grid.innerHTML = '<span class="bk-slots-error"><i class="bi bi-wifi-off"></i> تعذّر تحميل المواعيد، حاول مجدداً.</span>';
    });
  }

  // ── Submit guard ──────────────────────────────────────────────────────────
  function checkSubmit() {
    $('submit-btn').disabled = !(selectedDate && selectedTime);
  }

  // ── Payment tabs ──────────────────────────────────────────────────────────
  document.querySelectorAll('.bk-pay-tab').forEach(tab => {
    tab.addEventListener('click', function () {
      document.querySelectorAll('.bk-pay-tab').forEach(t => t.classList.remove('active'));
      this.classList.add('active');
      this.querySelector('input').checked = true;

      const isCard = this.querySelector('input').value === 'card';
      $('card-notice').style.display   = isCard  ? 'flex' : 'none';
      $('clinic-notice').style.display = !isCard ? 'flex' : 'none';
      $('btn-label').textContent = isCard ? 'تأكيد الحجز والدفع' : 'تأكيد الحجز';
    });
  });

})();
</script>
@endpush