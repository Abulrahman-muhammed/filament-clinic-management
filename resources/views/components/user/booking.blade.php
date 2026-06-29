  <section id="booking" class="section-padding bg-light-section">
    <div class="container">
      <div class="section-header text-center mb-5">
        <span class="section-badge">حجز المواعيد</span>
        <h2 class="section-title">احجز موعدك الآن</h2>
        <p class="section-subtitle">أكمل الخطوات التالية لحجز موعدك بسهولة</p>
      </div>

      <!-- Wizard Steps Indicator -->
      <div class="wizard-steps mb-4">
        <div class="wizard-step active" data-step="1">
          <span class="step-number">1</span>
          <span class="step-label">بيانات المريض</span>
        </div>
        <div class="wizard-step" data-step="2">
          <span class="step-number">2</span>
          <span class="step-label">اختر الخدمة</span>
        </div>
        <div class="wizard-step" data-step="3">
          <span class="step-number">3</span>
          <span class="step-label">التاريخ والوقت</span>
        </div>
        <div class="wizard-step" data-step="4">
          <span class="step-number">4</span>
          <span class="step-label">التأكيد</span>
        </div>
      </div>

      <div class="row justify-content-center">
        <div class="col-lg-9">
          <div class="booking-card card">
            <div class="card-body p-4 p-md-5">

              <!-- Step 1: Patient Information -->

              <div class="wizard-panel active" id="step1">

                <h4 class="wizard-panel-title">بيانات المريض</h4>
                <form id="patientForm" novalidate>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label for="name" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="name" required  name="name"
                             placeholder="أدخل اسمك الكامل">
                      <div class="invalid-feedback" id="nameError"></div>
                    </div>
                    <div class="col-md-6">
                      <label for="phone" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                      <input type="tel" class="form-control" id="phone" required name="phone"
                             placeholder="01xxxxxxxxx" dir="ltr">
                      <div class="invalid-feedback" id="phoneError"></div>
                    </div>
                    <div class="col-md-6">
                      <label for="patientGender" class="form-label">الجنس <span class="text-danger">*</span></label>
                      <select class="form-select" id="patientGender" required name="patientGender">
                        <option value="">اختر الجنس</option>
                        <option value="male">ذكر</option>
                        <option value="female">أنثى</option>
                      </select>
                      <div class="invalid-feedback" id="patientGenderError"></div>
                    </div>
                    <div class="col-md-6">
                      <label for="patientBirthDate" class="form-label">تاريخ الميلاد <span class="text-danger">*</span></label>
                      <input type="date" class="form-control" id="patientBirthDate" required name="patientBirthDate"
                             placeholder="YYYY-MM-DD" dir="ltr">
                      <div class="invalid-feedback" id="patientBirthDateError"></div>
                    </div>
                    <div class="col-12">
                      <label for="patientAddress" class="form-label">العنوان</label>
                      <input type="text" class="form-control" id="patientAddress" name="patientAddress"
                             placeholder="أدخل عنوانك">
                    </div>
                    <div class="col-12">
                      <label for="patientNotes" class="form-label">ملاحظات</label>
                      <textarea class="form-control" id="patientNotes" rows="3" name="patientNotes" placeholder="أي ملاحظات إضافية (اختياري)"></textarea>
                    </div>
                  </div>
                </form>
              </div>

              <!-- Step 2: Choose Service -->
              <div class="wizard-panel" id="step2">
                <h4 class="wizard-panel-title">اختر الخدمة</h4>
                <div class="row g-3" id="bookingServicesGrid">
                  <!-- Rendered by JavaScript -->
                </div>
              </div>

              <!-- Step 3: Date & Time -->
              <div class="wizard-panel" id="step3">
                <h4 class="wizard-panel-title">اختر التاريخ والوقت</h4>
                <div class="mb-4">
                  <label class="form-label fw-semibold">التاريخ</label>
                  <div class="date-picker-grid" id="datePickerGrid">
                    <!-- Rendered by JavaScript -->
                  </div>
                </div>
                <div>
                  <label class="form-label fw-semibold">الوقت المتاح</label>
                  <p class="text-muted small mb-3">ساعات العمل: 9:00 ص — 5:00 م</p>
                  <div class="slots-grid" id="slotsGrid">
                    <p class="text-muted">يرجى اختيار تاريخ أولاً</p>
                  </div>
                </div>
              </div>

              <!-- Step 4: Confirmation -->
              <div class="wizard-panel" id="step4">
                <h4 class="wizard-panel-title">تأكيد الحجز</h4>
                <div class="confirmation-summary" id="confirmationSummary">
                  <!-- Rendered by JavaScript -->
                </div>

                <!-- Payment Section -->
                <div class="payment-section mt-4">
                  <h5 class="payment-title">طريقة الدفع</h5>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="payment-radio-card" for="paymentOnline">
                        <input type="radio" name="paymentMethod" id="paymentOnline" value="online">
                        <div class="payment-card-content">
                          <i class="bi bi-credit-card-2-front"></i>
                          <span>💳 الدفع الإلكتروني</span>
                        </div>
                      </label>
                    </div>
                    <div class="col-md-6">
                      <label class="payment-radio-card" for="paymentCash">
                        <input type="radio" name="paymentMethod" id="paymentCash" value="cash" checked>
                        <div class="payment-card-content">
                          <i class="bi bi-cash-stack"></i>
                          <span>💵 الدفع نقداً في العيادة</span>
                        </div>
                      </label>
                    </div>
                  </div>

                  <div class="online-payment-info collapse" id="onlinePaymentInfo">
                    <div class="payment-info-box mt-3">
                      <p class="mb-2 fw-semibold">معلومات الدفع الإلكتروني</p>
                      <p class="text-muted small mb-3">
                        سيتم توجيهك لصفحة الدفع الآمنة بعد تأكيد الحجز.
                      </p>
                      <div class="payment-badges">
                        <span class="payment-badge">Visa</span>
                        <span class="payment-badge">MasterCard</span>
                        <span class="payment-badge">Meeza</span>
                        <span class="payment-badge">Apple Pay</span>
                        <span class="payment-badge">Google Pay</span>
                      </div>
                      <p class="text-muted small mt-3 mb-0">
                        <i class="bi bi-shield-lock-fill text-primary"></i>
                        تكامل مستقبلي: Stripe · Paymob · PayPal · Accept
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Booking Success -->
              <div class="wizard-panel" id="bookingSuccess">
                <div class="success-content text-center">
                  <div class="success-icon">
                    <i class="bi bi-check-circle-fill"></i>
                  </div>
                  <h3 class="success-title">تم حجز موعدك بنجاح!</h3>
                  <p class="success-ref">رقم الحجز: <strong id="bookingReference">CL-0000</strong></p>
                  <p class="payment-status" id="paymentStatusText">حالة الدفع: —</p>
                  <div class="success-summary mt-4" id="successSummary">
                    <!-- Rendered by JavaScript -->
                  </div>
                  <div class="success-actions d-flex flex-wrap justify-content-center gap-3 mt-4">
                    <button type="button" class="btn btn-outline-primary" id="downloadAppointment">
                      <i class="bi bi-download me-2"></i>تحميل الموعد
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="printAppointment">
                      <i class="bi bi-printer me-2"></i>طباعة الموعد
                    </button>
                    <button type="button" class="btn btn-primary" id="bookAnother">
                      <i class="bi bi-plus-circle me-2"></i>حجز موعد آخر
                    </button>
                  </div>
                </div>
              </div>

              <!-- Wizard Navigation -->
              <div class="wizard-nav d-flex justify-content-between mt-4 pt-3 border-top" id="wizardNav">
                <button type="button" class="btn btn-outline-secondary" id="prevStep" disabled>
                  <i class="bi bi-arrow-right me-2"></i>السابق
                </button>
                <button type="button" class="btn btn-primary" id="nextStep">
                  التالي<i class="bi bi-arrow-left ms-2"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


