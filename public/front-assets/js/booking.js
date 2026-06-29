/**
 * Booking Module — Clinic Booking System
 * Handles wizard flow, service/date/slot rendering, and submission
 */

const Booking = (() => {
  // State variables
  let currentStep = 1;
  let selectedService = null;
  let selectedDate = null;
  let selectedSlot = null;
  let paymentMethod = 'cash';
  let services = [];
  let patientData = {};

  const ARABIC_DAYS = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
  const ARABIC_MONTHS = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
  const GENDER_LABELS = { male: 'ذكر', female: 'أنثى' };

  /**
   * Initialize booking module
   */
  function init() {
    services = API.getMockServices();
    renderServices();
    renderBookingServices();
    renderDates();
    bindEvents();
    updateWizardUI();
  }

  /**
   * Bind wizard event listeners
   */
  function bindEvents() {
    document.getElementById('nextStep').addEventListener('click', handleNext);
    document.getElementById('prevStep').addEventListener('click', handlePrev);
    document.getElementById('bookAnother').addEventListener('click', resetBooking);
    document.getElementById('downloadAppointment').addEventListener('click', downloadAppointment);
    document.getElementById('printAppointment').addEventListener('click', () => window.print());

    document.querySelectorAll('input[name="paymentMethod"]').forEach((radio) => {
      radio.addEventListener('change', (e) => {
        paymentMethod = e.target.value;
        toggleOnlinePaymentInfo();
        if (currentStep === 4) {
          renderConfirmation();
        }
      });
    });
  }

  /**
   * Toggle online payment info panel
   */
  function toggleOnlinePaymentInfo() {
    const panel = document.getElementById('onlinePaymentInfo');
    if (paymentMethod === 'online') {
      panel.classList.add('show');
    } else {
      panel.classList.remove('show');
    }
  }

  /**
   * Navigate to a specific wizard step
   * @param {number} step
   */
  function goStep(step) {
    currentStep = step;
    updateWizardUI();
  }

  /**
   * Update wizard UI based on current step
   */
  function updateWizardUI() {
    document.querySelectorAll('.wizard-panel').forEach((panel) => {
      panel.classList.remove('active');
    });

    const activePanel = document.getElementById(`step${currentStep}`);
    if (activePanel) {
      activePanel.classList.add('active');
    }

    document.querySelectorAll('.wizard-step').forEach((el) => {
      const stepNum = parseInt(el.dataset.step, 10);
      el.classList.remove('active', 'completed');
      if (stepNum === currentStep) {
        el.classList.add('active');
      } else if (stepNum < currentStep) {
        el.classList.add('completed');
      }
    });

    const prevBtn = document.getElementById('prevStep');
    const nextBtn = document.getElementById('nextStep');
    const wizardNav = document.getElementById('wizardNav');

    prevBtn.disabled = currentStep <= 1;
    wizardNav.style.display = currentStep >= 5 ? 'none' : 'flex';

    if (currentStep === 4) {
      nextBtn.innerHTML = '<i class="bi bi-check-lg me-2"></i>تأكيد الحجز';
      renderConfirmation();
    } else {
      nextBtn.innerHTML = 'التالي<i class="bi bi-arrow-left ms-2"></i>';
    }

    if (currentStep === 3 && selectedDate) {
      renderSlots();
    }
  }

  /**
   * Handle next step button
   */
  function handleNext() {
    const state = { selectedService, selectedDate, selectedSlot, paymentMethod };

if (currentStep === 1) {
    collectPatientData();
}

if (currentStep === 4) {
    submitBooking();
    return;
}

goStep(currentStep + 1);

    if (currentStep === 1) {
      collectPatientData();
    }

    if (currentStep === 4) {
      submitBooking();
      return;
    }

    goStep(currentStep + 1);
  }

  /**
   * Handle previous step button
   */
  function handlePrev() {
    if (currentStep > 1) {
      goStep(currentStep - 1);
    }
  }

  /**
   * Collect patient form data
   */
  function collectPatientData() {
    patientData = {
      name: document.getElementById('patientName').value.trim(),
      phone: document.getElementById('patientPhone').value.trim(),
      gender: document.getElementById('patientGender').value,
      birthDate: document.getElementById('patientBirthDate').value,
      address: document.getElementById('patientAddress').value.trim(),
      notes: document.getElementById('patientNotes').value.trim(),
    };
  }

  /**
   * Render services on main services section
   */
  function renderServices() {
    const grid = document.getElementById('servicesGrid');
    if (!grid) return;

    grid.innerHTML = services.map((service) => createServiceCardHTML(service, 'main')).join('');

    grid.querySelectorAll('.service-card').forEach((card) => {
      card.addEventListener('click', () => selectServiceFromMain(card.dataset.serviceId));
    });

    grid.querySelectorAll('.btn-select-service').forEach((btn) => {
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        selectServiceFromMain(btn.dataset.serviceId);
      });
    });
  }

  /**
   * Render services in booking wizard step 2
   */
  function renderBookingServices() {
    const grid = document.getElementById('bookingServicesGrid');
    if (!grid) return;

    grid.innerHTML = services.map((service) => createServiceCardHTML(service, 'booking')).join('');

    grid.querySelectorAll('.service-card').forEach((card) => {
      card.addEventListener('click', () => selectService(card.dataset.serviceId));
    });
  }

  /**
   * Create service card HTML
   * @param {object} service
   * @param {string} context
   * @returns {string}
   */
  function createServiceCardHTML(service, context) {
    const isSelected = selectedService && selectedService.id === service.id ? 'selected' : '';
    const btnLabel = context === 'main' ? 'اختر الخدمة' : (isSelected ? 'تم الاختيار ✓' : 'اختر');

    return `
      <div class="col-md-6 col-lg-3">
        <div class="service-card ${isSelected}" data-service-id="${service.id}">
          <div class="service-icon"><i class="bi ${service.icon}"></i></div>
          <h5 class="service-name">${service.name}</h5>
          <p class="service-desc">${service.description}</p>
          <div class="service-meta">
            <span class="service-duration"><i class="bi bi-clock"></i> ${service.duration} دقيقة</span>
            <span class="service-price">${service.price} ج.م</span>
          </div>
          <button type="button" class="btn btn-outline-primary btn-sm btn-select-service" data-service-id="${service.id}">
            ${btnLabel}
          </button>
        </div>
      </div>
    `;
  }

  /**
   * Select service from main section and scroll to booking
   * @param {string} serviceId
   */
  function selectServiceFromMain(serviceId) {
    selectService(serviceId);
    renderServices();
    document.getElementById('booking').scrollIntoView({ behavior: 'smooth' });
    if (currentStep < 2) {
      goStep(2);
    }
  }

  /**
   * Select a service
   * @param {string} serviceId
   */
  function selectService(serviceId) {
    selectedService = services.find((s) => s.id === serviceId) || null;
    renderBookingServices();
    renderServices();

    if (selectedDate) {
      renderSlots();
    }
  }

  /**
   * Render next 14 days date picker
   */
  function renderDates() {
    const grid = document.getElementById('datePickerGrid');
    if (!grid) return;

    const today = new Date();
    today.setHours(0, 0, 0, 0);
    let html = '';

    for (let i = 0; i < 14; i++) {
      const date = new Date(today);
      date.setDate(today.getDate() + i);

      const dayOfWeek = date.getDay();
      const isFriday = dayOfWeek === 5;
      const dateStr = formatDateISO(date);
      const isSelected = selectedDate === dateStr ? 'selected' : '';
      const disabledClass = isFriday ? 'disabled' : '';

      html += `
        <div class="date-item ${isSelected} ${disabledClass}"
             data-date="${dateStr}"
             ${isFriday ? 'aria-disabled="true"' : ''}>
          <div class="date-day">${ARABIC_DAYS[dayOfWeek]}</div>
          <div class="date-num">${date.getDate()}</div>
          <div class="date-month">${ARABIC_MONTHS[date.getMonth()]}</div>
        </div>
      `;
    }

    grid.innerHTML = html;

    grid.querySelectorAll('.date-item:not(.disabled)').forEach((item) => {
      item.addEventListener('click', () => {
        selectedDate = item.dataset.date;
        selectedSlot = null;
        renderDates();
        renderSlots();
      });
    });
  }

  /**
   * Render time slots for selected date
   */
  function renderSlots() {
    const grid = document.getElementById('slotsGrid');
    if (!grid) return;

    if (!selectedDate) {
      grid.innerHTML = '<p class="text-muted">يرجى اختيار تاريخ أولاً</p>';
      return;
    }

    const allSlots = API.generateSlotTimes();
    const bookedSlots = API.getMockBookedSlots(selectedDate);
    const duration = selectedService ? selectedService.duration : 30;
    const slotsNeeded = Math.ceil(duration / 30);

    let html = '';

    allSlots.forEach((slot, index) => {
      const isBooked = isSlotBlocked(allSlots, bookedSlots, index, slotsNeeded);
      const isSelected = selectedSlot === slot ? 'selected' : '';
      let slotClass = 'slot-item';

      if (isBooked) {
        slotClass += ' booked';
      } else {
        slotClass += ' available';
        if (isSelected) slotClass += ' selected';
      }

      html += `
        <div class="${slotClass}" data-slot="${slot}" ${isBooked ? 'aria-disabled="true"' : ''}>
          ${formatTimeDisplay(slot)}
        </div>
      `;
    });

    grid.innerHTML = html;

    grid.querySelectorAll('.slot-item.available').forEach((item) => {
      item.addEventListener('click', () => {
        selectedSlot = item.dataset.slot;
        renderSlots();
      });
    });
  }

  /**
   * Check if a slot is blocked by booking or insufficient remaining time
   * @param {Array} allSlots
   * @param {Array} bookedSlots
   * @param {number} index
   * @param {number} slotsNeeded
   * @returns {boolean}
   */
  function isSlotBlocked(allSlots, bookedSlots, index, slotsNeeded) {
    if (index + slotsNeeded > allSlots.length) return true;

    for (let i = 0; i < slotsNeeded; i++) {
      if (bookedSlots.includes(allSlots[index + i])) {
        return true;
      }
    }

    return false;
  }

  /**
   * Render confirmation summary on step 4
   */
  function renderConfirmation() {
    const container = document.getElementById('confirmationSummary');
    if (!container) return;

    collectPatientData();

    const rows = [
      { label: 'المريض', value: patientData.name },
      { label: 'الهاتف', value: patientData.phone },
      { label: 'الخدمة', value: selectedService ? selectedService.name : '—' },
      { label: 'السعر', value: selectedService ? `${selectedService.price} ج.م` : '—' },
      { label: 'المدة', value: selectedService ? `${selectedService.duration} دقيقة` : '—' },
      { label: 'التاريخ', value: selectedDate ? formatDateArabic(selectedDate) : '—' },
      { label: 'الوقت', value: selectedSlot ? formatTimeDisplay(selectedSlot) : '—' },
      { label: 'طريقة الدفع', value: paymentMethod === 'online' ? 'الدفع الإلكتروني' : 'نقداً في العيادة' },
    ];

    if (patientData.notes) {
      rows.push({ label: 'ملاحظات', value: patientData.notes });
    }

    container.innerHTML = rows.map((row) => `
      <div class="summary-row">
        <span class="summary-label">${row.label}</span>
        <span class="summary-value">${row.value}</span>
      </div>
    `).join('');
  }

  /**
   * Generate booking reference number
   * @returns {string}
   */
  function generateReference() {
    const num = Math.floor(1000 + Math.random() * 9000);
    return `CL-${num}`;
  }

  /**
   * Submit booking
   */
  async function submitBooking() {
    const reference = generateReference();
    const bookingPayload = {
      reference,
      patient: patientData,
      service: selectedService,
      date: selectedDate,
      time: selectedSlot,
      paymentMethod,
    };

    try {
      // POST /api/v1/book-appointment
      await API.bookAppointment(bookingPayload);
    } catch {
      // Demo mode — proceed with mock submission
    }

    if (paymentMethod === 'online') {
      try {
        // POST /api/v1/payments
        await API.processPayment({ reference, method: 'online', amount: selectedService.price });
      } catch {
        // Demo mode
      }
    }

    showSuccess(reference);
  }

  /**
   * Show booking success panel
   * @param {string} reference
   */
  function showSuccess(reference) {
    document.getElementById('bookingReference').textContent = reference;

    const paymentStatus = paymentMethod === 'online'
      ? 'في انتظار الدفع الإلكتروني'
      : 'الدفع نقداً عند الزيارة';
    document.getElementById('paymentStatusText').textContent = `حالة الدفع: ${paymentStatus}`;

    const summaryEl = document.getElementById('successSummary');
    summaryEl.innerHTML = `
      <div class="summary-row"><span class="summary-label">المريض</span><span class="summary-value">${patientData.name}</span></div>
      <div class="summary-row"><span class="summary-label">الخدمة</span><span class="summary-value">${selectedService.name}</span></div>
      <div class="summary-row"><span class="summary-label">التاريخ</span><span class="summary-value">${formatDateArabic(selectedDate)}</span></div>
      <div class="summary-row"><span class="summary-label">الوقت</span><span class="summary-value">${formatTimeDisplay(selectedSlot)}</span></div>
      <div class="summary-row"><span class="summary-label">السعر</span><span class="summary-value">${selectedService.price} ج.م</span></div>
    `;

    document.querySelectorAll('.wizard-panel').forEach((p) => p.classList.remove('active'));
    document.getElementById('bookingSuccess').classList.add('active');
    document.getElementById('wizardNav').style.display = 'none';

    document.querySelectorAll('.wizard-step').forEach((el) => {
      el.classList.add('completed');
      el.classList.remove('active');
    });

    document.getElementById('booking').scrollIntoView({ behavior: 'smooth' });
  }

  /**
   * Reset booking wizard for another appointment
   */
  function resetBooking() {
    currentStep = 1;
    selectedService = null;
    selectedDate = null;
    selectedSlot = null;
    paymentMethod = 'cash';
    patientData = {};

    document.getElementById('patientForm').reset();
    document.getElementById('paymentCash').checked = true;
    toggleOnlinePaymentInfo();

    renderServices();
    renderBookingServices();
    renderDates();
    renderSlots();
    updateWizardUI();
  }

  /**
   * Download appointment as text file
   */
  function downloadAppointment() {
    const ref = document.getElementById('bookingReference').textContent;
    const content = `
عيادة النخبة الطبية — تأكيد الموعد
=====================================
رقم الحجز: ${ref}
المريض: ${patientData.name}
الهاتف: ${patientData.phone}
الخدمة: ${selectedService?.name || '—'}
التاريخ: ${formatDateArabic(selectedDate)}
الوقت: ${formatTimeDisplay(selectedSlot)}
السعر: ${selectedService?.price || '—'} ج.م
طريقة الدفع: ${paymentMethod === 'online' ? 'إلكتروني' : 'نقداً'}
=====================================
    `.trim();

    const blob = new Blob([content], { type: 'text/plain;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `${ref}.txt`;
    link.click();
    URL.revokeObjectURL(url);
  }

  /**
   * Format date as ISO string YYYY-MM-DD
   * @param {Date} date
   * @returns {string}
   */
  function formatDateISO(date) {
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, '0');
    const d = String(date.getDate()).padStart(2, '0');
    return `${y}-${m}-${d}`;
  }

  /**
   * Format date for Arabic display
   * @param {string} dateStr
   * @returns {string}
   */
  function formatDateArabic(dateStr) {
    const date = new Date(dateStr + 'T00:00:00');
    return `${ARABIC_DAYS[date.getDay()]} ${date.getDate()} ${ARABIC_MONTHS[date.getMonth()]} ${date.getFullYear()}`;
  }

  /**
   * Format 24h time to 12h Arabic display
   * @param {string} time - HH:MM
   * @returns {string}
   */
  function formatTimeDisplay(time) {
    const [h, m] = time.split(':').map(Number);
    const period = h >= 12 ? 'م' : 'ص';
    const hour12 = h > 12 ? h - 12 : h === 0 ? 12 : h;
    return `${hour12}:${String(m).padStart(2, '0')} ${period}`;
  }

  return {
    init,
    goStep,
    renderServices,
    renderDates,
    renderSlots,
    validateStep: (step) => Validation.validateStep(step, { selectedService, selectedDate, selectedSlot, paymentMethod }),
    generateReference,
    submitBooking,
    getState: () => ({ currentStep, selectedService, selectedDate, selectedSlot, paymentMethod }),
  };
})();

document.addEventListener('DOMContentLoaded', () => Booking.init());
