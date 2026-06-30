@extends('user.layouts.master')

@section('content')
<section class="ai-section" dir="rtl">
  <div class="ai-wrapper">

    <div class="ai-layout">

      {{-- ════════════ SIDEBAR ════════════ --}}
      <aside class="ai-sidebar">

        {{-- Bot card --}}
        <div class="ai-bot-card">
          <div class="ai-bot-banner">
            <div class="ai-bot-icon-wrap">
              <i class="bi bi-robot"></i>
            </div>
          </div>
          <div class="ai-bot-meta">
            <h2>المساعد الطبي الذكي</h2>
            <span class="ai-online-badge"><span class="ai-dot"></span> متاح الآن</span>
            <p class="ai-bot-desc">
              أخبرني بأعراضك وسأساعدك في اختيار الخدمة المناسبة وحجز موعدك مع الطبيب.
            </p>
          </div>
        </div>

        {{-- Capabilities --}}
        <div class="ai-cap-card">
          <p class="ai-cap-title"><i class="bi bi-stars"></i> أقدر أساعدك في</p>
          <ul class="ai-cap-list">
            <li><i class="bi bi-check-circle-fill"></i> تحليل الأعراض واقتراح الخدمة</li>
            <li><i class="bi bi-check-circle-fill"></i> عرض المواعيد المتاحة</li>
            <li><i class="bi bi-check-circle-fill"></i> الإجابة على أسئلتك الطبية</li>
            <li><i class="bi bi-check-circle-fill"></i> مساعدتك في إتمام الحجز</li>
          </ul>
        </div>

        {{-- Services quick list --}}
        <div class="ai-cap-card">
          <p class="ai-cap-title"><i class="bi bi-clipboard2-pulse"></i> الخدمات المتاحة</p>
          <ul class="ai-svc-list">
            @foreach($services as $svc)
              <li>
                <span>{{ $svc->name }}</span>
                <strong>{{ number_format($svc->price, 0) }} ج.م</strong>
              </li>
            @endforeach
          </ul>
        </div>

        {{-- Disclaimer --}}
        <div class="ai-disclaimer">
          <i class="bi bi-info-circle"></i>
          المساعد يقدم اقتراحات عامة فقط ولا يُغني عن الاستشارة الطبية المتخصصة.
        </div>

      </aside>

      {{-- ════════════ CHAT ════════════ --}}
      <div class="ai-chat-wrap">

        {{-- Header --}}
        <div class="ai-chat-header">
          <div class="ai-chat-header-info">
            <div class="ai-header-avatar"><i class="bi bi-robot"></i></div>
            <div>
              <p>المساعد الطبي الذكي</p>
              <span class="ai-online-badge"><span class="ai-dot"></span> متاح الآن</span>
            </div>
          </div>
          <button class="ai-clear-btn" id="clear-btn" title="محادثة جديدة">
            <i class="bi bi-arrow-counterclockwise"></i>
            <span>محادثة جديدة</span>
          </button>
        </div>

        {{-- Messages --}}
        <div class="ai-msgs" id="ai-msgs">
          <div class="ai-msg ai-msg--bot">
            <div class="ai-msg-avatar"><i class="bi bi-robot"></i></div>
            <div class="ai-msg-body">
              <div class="ai-bubble">
                أهلاً بك 👋<br>
                أنا مساعدك الطبي الذكي، مدرَّب على خدمات عيادة د. <strong>{{ $clinicSettings->doctor_name }}</strong>.<br><br>
                أخبرني بأعراضك أو ما تحتاجه وسأقترح لك الخدمة المناسبة وأعرض عليك المواعيد المتاحة.
              </div>
              <p class="ai-msg-time">الآن</p>
            </div>
          </div>
        </div>

        {{-- Suggestions --}}
        <div class="ai-suggestions" id="ai-sugs">
          <button class="ai-sug" data-text="أريد حجز كشف عام">كشف عام</button>
          <button class="ai-sug" data-text="ما هي الخدمات المتاحة وأسعارها؟">الخدمات والأسعار</button>
          <button class="ai-sug" data-text="ما هي المواعيد المتاحة هذا الأسبوع؟">المواعيد المتاحة</button>
        </div>

        {{-- Input --}}
        <div class="ai-input-row">
          <textarea
            id="ai-inp"
            placeholder="اكتب رسالتك هنا..."
            rows="1"
            maxlength="500"
          ></textarea>
          <button class="ai-send-btn" id="ai-send" aria-label="إرسال">
            <i class="bi bi-send-fill"></i>
          </button>
        </div>

      </div>{{-- /ai-chat-wrap --}}

    </div>{{-- /ai-layout --}}
  </div>{{-- /ai-wrapper --}}
</section>
@endsection

@push('styles')
  <link href="{{ asset('front-assets/css/assistant.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script>
(function () {
  'use strict';

  const CSRF        = '{{ csrf_token() }}';
  const CHAT_URL    = '{{ route("user.assistant.chat") }}';
  const BOOKING_URL = '{{ route("user.booking.index") }}';

  const msgsEl  = document.getElementById('ai-msgs');
  const inpEl   = document.getElementById('ai-inp');
  const sendBtn = document.getElementById('ai-send');
  const clearBtn= document.getElementById('clear-btn');
  const sugsEl  = document.getElementById('ai-sugs');

  let history   = [];   // [{role, content}, ...]
  let sending   = false;

  // ── Auto-resize textarea ──────────────────────────────────────────────────
  inpEl.addEventListener('input', function () {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 110) + 'px';
  });

  // ── Send on Enter (Shift+Enter = newline) ─────────────────────────────────
  inpEl.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); send(); }
  });

  sendBtn.addEventListener('click', send);

  // ── Suggestion chips ──────────────────────────────────────────────────────
  document.querySelectorAll('.ai-sug').forEach(btn => {
    btn.addEventListener('click', function () {
      inpEl.value = this.dataset.text;
      sugsEl.style.display = 'none';
      send();
    });
  });

  // ── Clear ─────────────────────────────────────────────────────────────────
  clearBtn.addEventListener('click', function () {
    history = [];
    msgsEl.innerHTML = '';
    sugsEl.style.display = 'flex';
    addBotMsg('تم بدء محادثة جديدة 🔄<br>كيف يمكنني مساعدتك؟');
  });

  // ── Send message ──────────────────────────────────────────────────────────
  function send() {
    const text = inpEl.value.trim();
    if (!text || sending) return;

    sugsEl.style.display = 'none';
    addUserMsg(text);
    history.push({ role: 'user', content: text });

    inpEl.value = '';
    inpEl.style.height = 'auto';

    sending = true;
    sendBtn.disabled = true;

    const typingId = showTyping();

    fetch(CHAT_URL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF,
        'Accept': 'application/json',
      },
      body: JSON.stringify({ message: text, history }),
    })
    .then(r => r.json())
    .then(data => {
      removeTyping(typingId);
      sending = false;
      sendBtn.disabled = false;

      if (data.error) {
        addBotMsg('عذراً، حدث خطأ ما. حاول مرة أخرى.');
        return;
      }

      addBotMsg(data.reply, data.booking_url ?? null);
      history.push({ role: 'model', content: data.reply });
    })
    .catch(() => {
      removeTyping(typingId);
      sending = false;
      sendBtn.disabled = false;
      
      addBotMsg('تعذّر الاتصال بالخادم. تحقق من الاتصال وحاول مجدداً.');
    });
  }

  // ── DOM helpers ───────────────────────────────────────────────────────────
  function now() {
    return new Date().toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' });
  }

  function addUserMsg(text) {
    const d = document.createElement('div');
    d.className = 'ai-msg ai-msg--user';
    d.innerHTML = `
      <div class="ai-msg-body">
        <div class="ai-bubble">${escHtml(text)}</div>
        <p class="ai-msg-time">${now()}</p>
      </div>`;
    msgsEl.appendChild(d);
    scroll();
  }

  function addBotMsg(html, bookingUrl) {
    const d = document.createElement('div');
    d.className = 'ai-msg ai-msg--bot';

    const bookingBtn = bookingUrl
      ? `<a href="${bookingUrl}" class="ai-booking-btn"><i class="bi bi-calendar-check"></i> احجز موعدك الآن</a>`
      : '';

    d.innerHTML = `
      <div class="ai-msg-avatar"><i class="bi bi-robot"></i></div>
      <div class="ai-msg-body">
        <div class="ai-bubble">${html}${bookingBtn}</div>
        <p class="ai-msg-time">${now()}</p>
      </div>`;
    msgsEl.appendChild(d);
    scroll();
  }

  function showTyping() {
    const id = 'typing-' + Date.now();
    const d = document.createElement('div');
    d.className = 'ai-msg ai-msg--bot';
    d.id = id;
    d.innerHTML = `
      <div class="ai-msg-avatar"><i class="bi bi-robot"></i></div>
      <div class="ai-msg-body">
        <div class="ai-bubble ai-typing">
          <span></span><span></span><span></span>
        </div>
      </div>`;
    msgsEl.appendChild(d);
    scroll();
    return id;
  }

  function removeTyping(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
  }

  function scroll() {
    msgsEl.scrollTop = msgsEl.scrollHeight;
  }

  function escHtml(str) {
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

})();
</script>
@endpush
