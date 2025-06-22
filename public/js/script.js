/**
 * Tools Hub - Main JavaScript (Full Version)
 * Optimized for performance and maintainability
 */

// === Animation Constants ===
const ANIMATION_CLASSES = {
    FADE_IN: 'animate__fadeIn',
    FADE_OUT: 'animate__fadeOut',
    PULSE: 'animate__pulse',
    FLASH: 'animate__flash',
    RUBBER_BAND: 'animate__rubberBand'
};

// === Status Labels ===
const STATUS_LABELS = {
    good: { label: '✅ Good', class: 'text-green-600' },
    verified: { label: '✔️ Verified', class: 'text-yellow-600' },
    disabled: { label: '❌ Disabled', class: 'text-red-600' },
    notExit: { label: '⚠️ NotExist', class: 'text-gray-600' },
    unknown: { label: '❓ Unknown', class: 'text-blue-600' }
};

// === Utility Functions ===
const animateElement = (element, animation, duration = 500) => {
    if (!element) return;
    element.classList.add('animate__animated', animation);
    setTimeout(() => element.classList.remove('animate__animated', animation), duration);
};

const updateElementWithAnimation = (elementId, content, delay = 0) => {
    const element = document.getElementById(elementId);
    if (!element) return;

    setTimeout(() => {
        element.innerHTML = `<span class="animate__animated ${ANIMATION_CLASSES.FADE_IN}">${content}</span>`;
    }, delay);
};

// === Modal Functions ===
const showPopup = (message) => {
    const modal = document.getElementById('errorModal');
    const messageBox = document.getElementById('errorMessage');

    if (!modal || !messageBox) {
        alert(message);
        return;
    }

    messageBox.textContent = message;
    modal.classList.remove('hidden');
    modal.classList.add('flex', ANIMATION_CLASSES.FADE_IN);

    setTimeout(() => modal.scrollIntoView({ behavior: 'smooth' }), 100);
};

const closePopup = () => {
    const modal = document.getElementById('errorModal');
    if (!modal) return;

    modal.classList.add(ANIMATION_CLASSES.FADE_OUT);
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex', 'animate__animated', ANIMATION_CLASSES.FADE_OUT);
    }, 500);
};

// === Email Checker ===
const clearAll = () => {
    const elements = {
        input: document.getElementById('mailInput'),
        output: document.getElementById('mailOutput'),
        progress: document.getElementById('progressContainer'),
        progressBar: document.getElementById('progressBar')
    };

    elements.output.classList.add('animate__animated', ANIMATION_CLASSES.FADE_OUT, 'animate__faster');

    setTimeout(() => {
        elements.input.value = '';
        elements.output.textContent = '';
        elements.output.classList.remove('animate__animated', ANIMATION_CLASSES.FADE_OUT, 'animate__faster');
        elements.output.classList.add('animate__animated', ANIMATION_CLASSES.FADE_IN, 'animate__faster');

        elements.progressBar.style.width = '0%';
        elements.progressBar.textContent = '0%';
        elements.progress.classList.add('hidden');

        ['good', 'verified', 'disabled', 'notExit', 'unknown'].forEach(status => {
            const countElement = document.getElementById(`${status}Count`);
            animateElement(countElement, ANIMATION_CLASSES.FADE_OUT);

            setTimeout(() => {
                countElement.innerText = '0';
                animateElement(countElement, ANIMATION_CLASSES.FADE_IN);
            }, 300);
        });

        window.currentResults = { good: [], verified: [], disabled: [], notExit: [], unknown: [] };
        window.currentCounts = { good: 0, verified: 0, disabled: 0, notExit: 0, unknown: 0 };
    }, 300);
};

const mapStatus = (apiStatus) => {
    const statusMap = {
        live: 'good',
        verify: 'verified',
        disabled: 'disabled',
        unregistered: 'notExit',
        not_exit: 'notExit'
    };
    return statusMap[apiStatus.toLowerCase()] || 'unknown';
};

const updateProgressBar = (progress) => {
    const progressBar = document.getElementById('progressBar');
    if (!progressBar) return;

    progressBar.style.transition = 'width 0.5s ease-in-out';
    progressBar.style.width = `${progress}%`;
    progressBar.textContent = `${progress}%`;

    animateElement(progressBar, ANIMATION_CLASSES.PULSE);
};

const processEmailChunk = async (chunk, counts, results, outputArea) => {
    try {
        const response = await fetch('/backend/api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ mail: chunk })
        });

        const result = await response.json();
        if (!result.status || !Array.isArray(result.data)) {
            console.warn('Unexpected response:', result);
            return;
        }

        result.data.forEach(item => {
            if (!item.email || !item.status) return;

            const status = mapStatus(item.status);
            const statusInfo = STATUS_LABELS[status];
            const line = `${statusInfo.label}|${item.email}`;

            results[status].push(item.email);

            if (counts[status] !== undefined) {
                counts[status]++;
                const countElement = document.getElementById(`${status}Count`);
                countElement.textContent = counts[status];
                animateElement(countElement, ANIMATION_CLASSES.PULSE);
            }

            const resultElement = document.createElement('div');
            resultElement.className = `result-item ${statusInfo.class} mb-1`;
            resultElement.textContent = line;
            outputArea.appendChild(resultElement);

            setTimeout(() => resultElement.classList.add('show'), 10);
        });
    } catch (error) {
        console.error('Fetch error:', error);
        showPopup('ข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์');
    }
};

const checkEmails = async () => {
    const inputRaw = document.getElementById('mailInput').value.trim().split('\n').filter(x => x);
    const gmailRegex = /^[^\s@]+@gmail\.com$/i;
    const input = inputRaw.filter(email => gmailRegex.test(email));

    if (input.length === 0) {
        showPopup('ใส่ @gmail.com อย่างน้อย 1 รายการ');
        return;
    }

    if (input.length > 10000) {
        showPopup('ตรวจสอบได้ครั้งละ 10,000 รายการ');
        return;
    }

    const elements = {
        output: document.getElementById('mailOutput'),
        progress: document.getElementById('progressContainer'),
        progressBar: document.getElementById('progressBar')
    };

    elements.output.innerHTML = `
        <div class="flex flex-col items-center justify-center h-full animate__animated animate__pulse animate__infinite">
            <div class="text-4xl mb-4">🔍</div>
            <div class="text-lg font-semibold text-orange-500">ตรวจสอบ...</div>
            <div class="text-sm text-gray-500 mt-2">ตรวจสอบ ${input.length} รายการ</div>
        </div>
    `;

    elements.progress.classList.remove('hidden');
    elements.progress.classList.add('animate__animated', ANIMATION_CLASSES.FADE_IN);
    updateProgressBar(0);

    const counts = { good: 0, verified: 0, disabled: 0, notExit: 0, unknown: 0 };
    const results = { good: [], verified: [], disabled: [], notExit: [], unknown: [] };

    for (let i = 0; i < input.length; i += 200) {
        const chunk = input.slice(i, i + 200);

        if (i === 0) {
            elements.output.innerHTML = '';
            elements.output.classList.add('animate__animated', ANIMATION_CLASSES.FADE_IN);
        }

        await processEmailChunk(chunk, counts, results, elements.output);

        const progress = Math.min(100, Math.round(((i + chunk.length) / input.length) * 100));
        updateProgressBar(progress);
    }

    animateElement(elements.progressBar, ANIMATION_CLASSES.FLASH, 1000);

    window.currentResults = results;
    window.currentCounts = counts;
};

const downloadList = (type) => {
    const countElement = document.getElementById(`${type}Count`);
    const count = countElement ? parseInt(countElement.innerText) : 0;

    if (!window.currentResults?.[type] || count === 0) {
        showPopup('ไม่พบข้อมูลในหมวดนี้ ไม่สามารถดาวน์โหลดได้');
        return;
    }

    animateElement(countElement.parentElement, ANIMATION_CLASSES.RUBBER_BAND, 1000);

    const blob = new Blob([window.currentResults[type].join('\n')], { type: 'text/plain' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `${type}_emails.txt`;
    link.click();

    showPopup(`ดาวน์โหลดรายการ ${count} สำเร็จ`);
};

// === Map & IP ===
const updateMap = (lat, lon) => {
    const mapContainer = document.getElementById('mapContainer');
    if (!mapContainer) return;

    if (!lat || !lon || lat === 'N/A' || lon === 'N/A') {
        mapContainer.innerHTML = '<p class="text-gray-500">ไม่สามารถแสดงแผนที่ได้ (ไม่มีข้อมูล)</p>';
        return;
    }

    mapContainer.innerHTML = `
        <div class="flex items-center justify-center h-full animate__animated animate__pulse animate__infinite">
            <div class="text-2xl">🗺️</div>
            <div class="ml-2">โหลดแผนที่ กำลัง...</div>
        </div>
    `;

    const mapUrl = `https://www.openstreetmap.org/export/embed.html?bbox=${lon - 0.01},${lat - 0.01},${lon + 0.01},${lat + 0.01}&layer=mapnik&marker=${lat},${lon}`;

    setTimeout(() => {
        mapContainer.innerHTML = `
            <iframe 
                width="100%" 
                height="100%" 
                frameborder="0" 
                scrolling="no" 
                marginheight="0" 
                marginwidth="0"
                src="${mapUrl}" 
                style="border:1px solid #ccc; border-radius: 8px; opacity: 0; transition: opacity 1s ease;"
                onload="this.style.opacity='1'"
            ></iframe>
        `;
    }, 1000);
};

const fetchIpInfo = async () => {
    if (!window.location.pathname.includes('check-ip')) return;

    const elements = [
        'ipAddress', 'country', 'city', 'region', 'coordinates',
        'timezone', 'ispOrg', 'browser', 'engine', 'os', 'userAgent'
    ];

    elements.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.innerHTML = '<div class="animate__animated animate__pulse animate__infinite">โหลด...</div>';
        }
    });

    try {
        const response = await fetch('/backend/api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ type: 'check-ip' })
        });

        if (!response.ok) throw new Error('Network error');
        const result = await response.json();
        const info = result.data || {};

        const updates = [
            { id: 'ipAddress', content: info.ip || 'N/A' },
            { id: 'country', content: info.country || 'N/A' },
            { id: 'city', content: info.city || 'N/A' },
            { id: 'region', content: info.region || 'N/A' },
            { id: 'coordinates', content: `${info.latitude || 'N/A'}, ${info.longitude || 'N/A'}` },
            { id: 'timezone', content: info.timezone || 'N/A' },
            { id: 'ispOrg', content: `${info.isp || 'N/A'} / ${info.organization || 'N/A'}` },
            { id: 'browser', content: navigator.userAgent.split(' ')[0] },
            { id: 'engine', content: navigator.product },
            { id: 'os', content: navigator.platform },
            { id: 'userAgent', content: navigator.userAgent }
        ];

        updates.forEach((update, index) => {
            updateElementWithAnimation(update.id, update.content, 500 + (index * 200));
        });

        setTimeout(() => updateMap(info.latitude, info.longitude), 2700);

        if (!result.status) {
            console.warn('IP info partial or failed:', result.message);
        }

    } catch (err) {
        console.error('Fetch error:', err);
        showPopup('ไม่สามารถโหลดข้อมูล IP');
    }
};

const setupRefundCalculator = () => {
  flatpickr("#expiryDate", { dateFormat: "d/m/Y", locale: "th" });
  flatpickr("#failureDate", { dateFormat: "d/m/Y", locale: "th", defaultDate: new Date() });

  const parseThaiDate = (dateStr) => {
    const [d, m, y] = dateStr.split('/').map(Number);
    return new Date(y, m - 1, d);
  };

  const form = document.getElementById('calculatorForm');
  if (form) {
    form.addEventListener('submit', (event) => {
      event.preventDefault();

      const expiryStr = document.getElementById('expiryDate').value;
      const failureStr = document.getElementById('failureDate').value;
      const months = parseFloat(document.getElementById('months').value);
      const price = parseFloat(document.getElementById('price').value);
      const deductPercent = parseFloat(document.getElementById('deductPercent').value || 0);

      const results = document.getElementById('results');
      const errorBox = document.getElementById('errorMessage');
      const errorText = document.getElementById('errorText');
      const daysEl = document.getElementById('remainingDays');
      const refundBeforeEl = document.getElementById('refundBefore');
      const refundAfterEl = document.getElementById('refundAfter');
      const calcDetail = document.getElementById('refundCalc');
      const details = document.getElementById('calculationDetails');
      const placeholder = document.getElementById('resultPlaceholder');

      results.classList.add('hidden');
      errorBox.classList.add('hidden');
      details.classList.add('hidden');
      errorText.textContent = '';

      if (!expiryStr || !failureStr || isNaN(months) || isNaN(price)) {
        errorText.textContent = 'กรุณากรอกข้อมูลให้ครบถ้วน';
        errorBox.classList.remove('hidden');
        results.classList.remove('hidden');
        animateElement(errorBox, ANIMATION_CLASSES.FADE_IN);
        return;
      }

      const expiry = parseThaiDate(expiryStr);
      const failure = parseThaiDate(failureStr);
      const totalDays = months * 30;

      if (expiry < failure || price <= 0 || totalDays <= 0 || deductPercent < 0 || deductPercent > 100) {
        errorText.textContent = 'ข้อมูลไม่ถูกต้อง กรุณาตรวจสอบอีกครั้ง';
        errorBox.classList.remove('hidden');
        results.classList.remove('hidden');
        animateElement(errorBox, ANIMATION_CLASSES.FADE_IN);
        return;
      }

      const remaining = Math.max(0, Math.floor((expiry - failure) / (1000 * 60 * 60 * 24)) + 1);
      const perDay = price / totalDays;
      const rawRefund = remaining * perDay;
      const deducted = rawRefund * (deductPercent / 100);
      const finalRefund = Math.max(0, Math.min(price, rawRefund - deducted));

      daysEl.textContent = `${remaining} วัน`;
      refundBeforeEl.textContent = `${rawRefund.toFixed(2)} บาท`;
      refundAfterEl.textContent = `${finalRefund.toFixed(2)} บาท`;
      calcDetail.textContent = `(${remaining} วัน × ${perDay.toFixed(6)} บาท) = ${rawRefund.toFixed(2)} บาท - ${deductPercent}% (${deducted.toFixed(2)} บาท) = ${finalRefund.toFixed(2)} บาท`;

      placeholder.style.display = 'none';
      results.classList.remove('hidden');
      details.classList.remove('hidden');
      animateElement(results, ANIMATION_CLASSES.FADE_IN);
    });
  }
};

// === DOM Ready ===
document.addEventListener('DOMContentLoaded', () => {
    const style = document.createElement('style');
    style.textContent = `
        .result-item {
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(10px);
        }
        .result-item.show {
            opacity: 1;
            transform: translateY(0);
        }
    `;
    document.head.appendChild(style);

    fetchIpInfo();
    setupRefundCalculator();
});

function showPage(page) {
    document.getElementById('tools-page-1').classList.add('hidden');
    document.getElementById('tools-page-2').classList.add('hidden');
    document.getElementById('tools-page-' + page).classList.remove('hidden');
}