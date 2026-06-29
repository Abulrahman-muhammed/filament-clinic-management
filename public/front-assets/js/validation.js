/**
 * Validation Module — Clinic Booking System
 */

const Validation = (() => {
  /**
   * Validate Egyptian mobile phone number
   * Accepts: 01xxxxxxxxx (11 digits)
   * @param {string} phone
   * @returns {boolean}
   */
  function isValidEgyptianPhone(phone) {
    const cleaned = phone.replace(/[\s\-()]/g, '');
    const pattern = /^01[0125]\d{8}$/;
    return pattern.test(cleaned);
  }

  /**
   * Validate patient name (min 3 chars, Arabic or Latin)
   * @param {string} name
   * @returns {boolean}
   */
  function isValidName(name) {
    const trimmed = name.trim();
    return trimmed.length >= 3 && /^[\u0600-\u06FFa-zA-Z\s]+$/.test(trimmed);
  }

  /**
   * Validate birth date (not in future, reasonable age)
   * @param {string} dateStr
   * @returns {boolean}
   */
  function isValidBirthDate(dateStr) {
    if (!dateStr) return false;
    const date = new Date(dateStr);
    const now = new Date();
    if (date > now) return false;

    const age = (now - date) / (365.25 * 24 * 60 * 60 * 1000);
    return age >= 0 && age <= 120;
  }

  /**
   * Show field error
   * @param {HTMLElement} field
   * @param {string} message
   */
  function showError(field, message) {
    field.classList.add('is-invalid');
    const errorEl = document.getElementById(`${field.id}Error`);
    if (errorEl) {
      errorEl.textContent = message;
    }
  }

  /**
   * Clear field error
   * @param {HTMLElement} field
   */
  function clearError(field) {
    field.classList.remove('is-invalid');
    const errorEl = document.getElementById(`${field.id}Error`);
    if (errorEl) {
      errorEl.textContent = '';
    }
  }

  /**
   * Validate Step 1 — Patient Information
   * @returns {boolean}
   */
  function validateStep1() {
    let isValid = true;

    const nameField = document.getElementById('patientName');
    const phoneField = document.getElementById('patientPhone');
    const genderField = document.getElementById('patientGender');
    const birthField = document.getElementById('patientBirthDate');

    [nameField, phoneField, genderField, birthField].forEach(clearError);

    if (!isValidName(nameField.value)) {
      showError(nameField, 'يرجى إدخال اسم صحيح (3 أحرف على الأقل)');
      isValid = false;
    }

    if (!isValidEgyptianPhone(phoneField.value)) {
      showError(phoneField, 'يرجى إدخال رقم هاتف مصري صحيح (مثال: 01012345678)');
      isValid = false;
    }

    if (!genderField.value) {
      showError(genderField, 'يرجى اختيار الجنس');
      isValid = false;
    }

    if (!isValidBirthDate(birthField.value)) {
      showError(birthField, 'يرجى إدخال تاريخ ميلاد صحيح');
      isValid = false;
    }

    return isValid;
  }

  /**
   * Validate Step 2 — Service Selection
   * @param {object|null} selectedService
   * @returns {boolean}
   */
  function validateStep2(selectedService) {
    if (!selectedService) {
      alert('يرجى اختيار خدمة قبل المتابعة');
      return false;
    }
    return true;
  }

  /**
   * Validate Step 3 — Date & Time
   * @param {string|null} selectedDate
   * @param {string|null} selectedSlot
   * @returns {boolean}
   */
  function validateStep3(selectedDate, selectedSlot) {
    if (!selectedDate) {
      alert('يرجى اختيار تاريخ الموعد');
      return false;
    }
    if (!selectedSlot) {
      alert('يرجى اختيار وقت الموعد');
      return false;
    }
    return true;
  }

  /**
   * Validate Step 4 — Payment Method
   * @param {string|null} paymentMethod
   * @returns {boolean}
   */
  function validateStep4(paymentMethod) {
    if (!paymentMethod) {
      alert('يرجى اختيار طريقة الدفع');
      return false;
    }
    return true;
  }

  /**
   * Route validation by step number
   * @param {number} step
   * @param {object} state
   * @returns {boolean}
   */
  function validateStep(step, state) {
    switch (step) {
      case 1:
        return validateStep1();
      case 2:
        return validateStep2(state.selectedService);
      case 3:
        return validateStep3(state.selectedDate, state.selectedSlot);
      case 4:
        return validateStep4(state.paymentMethod);
      default:
        return true;
    }
  }

  return {
    isValidEgyptianPhone,
    isValidName,
    isValidBirthDate,
    validateStep,
    validateStep1,
    validateStep2,
    validateStep3,
    validateStep4,
    showError,
    clearError,
  };
})();
