/**
 * API Module — Clinic Booking System
 * Frontend API layer for Laravel backend integration
 */

const API = (() => {
  const BASE_URL = '/api/v1';

  /**
   * Generic fetch wrapper with error handling
   * @param {string} endpoint
   * @param {object} options
   * @returns {Promise<object>}
   */
  async function request(endpoint, options = {}) {
    const url = `${BASE_URL}${endpoint}`;
    const config = {
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...options.headers,
      },
      ...options,
    };

    try {
      const response = await fetch(url, config);
      if (!response.ok) {
        const error = await response.json().catch(() => ({}));
        throw new Error(error.message || `HTTP ${response.status}`);
      }
      return await response.json();
    } catch (error) {
      console.warn(`API request failed: ${endpoint}`, error.message);
      throw error;
    }
  }

  // GET /api/v1/services
  async function getServices() {
    return request('/services');
  }

  // GET /api/v1/available-slots
  async function getAvailableSlots(date, serviceId) {
    const params = new URLSearchParams({ date, service_id: serviceId });
    return request(`/available-slots?${params}`);
  }

  // POST /api/v1/book-appointment
  async function bookAppointment(bookingData) {
    return request('/book-appointment', {
      method: 'POST',
      body: JSON.stringify(bookingData),
    });
  }

  // POST /api/v1/ai-assistant
  async function sendAiMessage(message, context = {}) {
    return request('/ai-assistant', {
      method: 'POST',
      body: JSON.stringify({ message, ...context }),
    });
  }

  // POST /api/v1/payments
  async function processPayment(paymentData) {
    return request('/payments', {
      method: 'POST',
      body: JSON.stringify(paymentData),
    });
  }

  /**
   * Mock services data for offline/demo mode
   * @returns {Array}
   */
  function getMockServices() {
    return [
      {
        id: 'checkup',
        name: 'فحص دوري',
        description: 'فحص شامل للصحة العامة يشمل القياسات الأساسية والتقييم الطبي.',
        duration: 30,
        price: 300,
        icon: 'bi-clipboard2-pulse',
      },
      {
        id: 'followup',
        name: 'متابعة',
        description: 'متابعة حالة طبية سابقة ومراجعة خطة العلاج الحالية.',
        duration: 20,
        price: 200,
        icon: 'bi-arrow-repeat',
      },
      {
        id: 'consultation',
        name: 'استشارة',
        description: 'استشارة طبية متخصصة لمناقشة الأعراض ووضع خطة العلاج.',
        duration: 45,
        price: 500,
        icon: 'bi-chat-heart',
      },
      {
        id: 'lab-review',
        name: 'مراجعة تحاليل',
        description: 'مراجعة وتحليل نتائج التحاليل المخبرية مع توصيات طبية.',
        duration: 25,
        price: 350,
        icon: 'bi-file-earmark-medical',
      },
    ];
  }

  /**
   * Mock booked slots for demo
   * @param {string} dateStr - YYYY-MM-DD
   * @returns {Array<string>}
   */
  function getMockBookedSlots(dateStr) {
    const seed = dateStr.split('-').reduce((a, b) => a + parseInt(b, 10), 0);
    const allSlots = generateSlotTimes();
    const booked = [];

    for (let i = 0; i < allSlots.length; i++) {
      if ((seed + i * 7) % 5 === 0) {
        booked.push(allSlots[i]);
      }
    }

    return booked;
  }

  /**
   * Generate all slot times between 09:00 and 17:00
   * @returns {Array<string>}
   */
  function generateSlotTimes() {
    const slots = [];
    for (let hour = 9; hour < 17; hour++) {
      slots.push(`${String(hour).padStart(2, '0')}:00`);
      slots.push(`${String(hour).padStart(2, '0')}:30`);
    }
    return slots;
  }

  return {
    getServices,
    getAvailableSlots,
    bookAppointment,
    sendAiMessage,
    processPayment,
    getMockServices,
    getMockBookedSlots,
    generateSlotTimes,
  };
})();
