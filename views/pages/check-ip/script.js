// ข้อมูลจำลอง
const mockData = {
    ip: "203.154.83.15",
    country: "Thailand",
    countryCode: "TH",
    city: "Bangkok",
    region: "Bangkok",
    regionCode: "BKK",
    lat: 13.7563,
    lon: 100.5018,
    timezone: "Asia/Bangkok",
    isp: "True Internet",
    org: "True Corporation",
    as: "AS17552 True Internet"
};

async function loadIPData() {
    try {
        const response = await fetch('https://ipapi.co/json/');
        const data = await response.json();
        const ipData = response.ok ? data : mockData;

        document.getElementById('ipAddress').innerHTML = `<span class="text-orange-500">${ipData.ip || mockData.ip}</span>`;
        document.getElementById('country').innerHTML = `🌍 ${ipData.country || mockData.country}`;
        document.getElementById('city').textContent = ipData.city || mockData.city;
        document.getElementById('region').textContent = ipData.region || mockData.region;
        document.getElementById('coordinates').textContent = `${ipData.latitude || mockData.lat}, ${ipData.longitude || mockData.lon}`;
        document.getElementById('timezone').textContent = ipData.timezone || mockData.timezone;
        document.getElementById('ispOrg').textContent = ipData.org || ipData.isp || mockData.org;
        document.getElementById('ipType').textContent = ipData.ip.includes(':') ? 'IPv6' : 'IPv4';

        loadMap(ipData.latitude || mockData.lat, ipData.longitude || mockData.lon);
    } catch (error) {
        console.error('Error loading IP data:', error);
        loadMockData();
    }
}

function loadMockData() {
    document.getElementById('ipAddress').innerHTML = `<span class="text-orange-500">${mockData.ip}</span>`;
    document.getElementById('country').innerHTML = `🌍 ${mockData.country}`;
    document.getElementById('city').textContent = mockData.city;
    document.getElementById('region').textContent = mockData.region;
    document.getElementById('coordinates').textContent = `${mockData.lat}, ${mockData.lon}`;
    document.getElementById('timezone').textContent = mockData.timezone;
    document.getElementById('ispOrg').textContent = mockData.org;
    document.getElementById('ipType').textContent = 'IPv4';

    loadMap(mockData.lat, mockData.lon);
}

function loadBrowserData() {
    const ua = navigator.userAgent;
    let browser = 'Unknown', version = '';

    if (ua.includes('Chrome') && !ua.includes('Edge')) {
        browser = 'Google Chrome';
        version = ua.match(/Chrome\/([0-9.]+)/)?.[1] || '';
    } else if (ua.includes('Firefox')) {
        browser = 'Mozilla Firefox';
        version = ua.match(/Firefox\/([0-9.]+)/)?.[1] || '';
    } else if (ua.includes('Safari') && !ua.includes('Chrome')) {
        browser = 'Safari';
        version = ua.match(/Version\/([0-9.]+)/)?.[1] || '';
    } else if (ua.includes('Edge')) {
        browser = 'Microsoft Edge';
        version = ua.match(/Edge\/([0-9.]+)/)?.[1] || '';
    }

    let os = 'Unknown';
    if (ua.includes('Windows')) os = 'Windows';
    else if (ua.includes('Mac')) os = 'macOS';
    else if (ua.includes('Linux')) os = 'Linux';
    else if (ua.includes('Android')) os = 'Android';
    else if (ua.includes('iOS')) os = 'iOS';

    document.getElementById('browser').textContent = browser;
    document.getElementById('version').textContent = version || 'ไม่ระบุ';
    document.getElementById('os').textContent = os;
    document.getElementById('screenResolution').textContent = `${screen.width} x ${screen.height}`;
    document.getElementById('language').textContent = navigator.language || 'ไม่ระบุ';
    document.getElementById('userAgent').textContent = ua;
}

function loadMap(lat, lon) {
    const mapContainer = document.getElementById('mapContainer');
    setTimeout(() => {
        mapContainer.innerHTML = `
            <div class="w-full h-full bg-gradient-to-br from-blue-100 to-green-100 rounded-lg relative overflow-hidden">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <div class="text-6xl mb-4">📍</div>
                        <h3 class="text-xl font-bold text-gray-700 mb-2">ตำแหน่งโดยประมาณ</h3>
                        <p class="text-gray-600">พิกัด: ${lat}, ${lon}</p>
                        <div class="mt-4 inline-block bg-white/80 px-4 py-2 rounded-full shadow">
                            <p class="text-sm text-gray-600">🗺️ คลิกเพื่อดูในแผนที่จริง</p>
                        </div>
                    </div>
                </div>
                <div class="absolute top-4 right-4 bg-white/90 px-3 py-1 rounded-full text-xs text-gray-600">
                    Google Maps
                </div>
            </div>`;
        mapContainer.style.cursor = 'pointer';
        mapContainer.onclick = () => window.open(`https://www.google.com/maps?q=${lat},${lon}`, '_blank');
    }, 2000);
}

function loadAdditionalData() {
    const updateTime = () => {
        const now = new Date();
        document.getElementById('currentTime').textContent = now.toLocaleString('th-TH', {
            timeZone: 'Asia/Bangkok',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
    };
    updateTime();
    setInterval(updateTime, 1000);

    document.getElementById('onlineStatus').textContent = navigator.onLine ? '🟢 เชื่อมต่อแล้ว' : '🔴 ไม่เชื่อมต่อ';

    const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
    document.getElementById('connectionType').textContent = connection?.effectiveType?.toUpperCase() || 'ไม่ระบุ';

    const isSecure = location.protocol === 'https:';
    document.getElementById('connectionSecurity').innerHTML = `• การเชื่อมต่อ: ${isSecure ? '🔒 ปลอดภัย (HTTPS)' : '⚠️ ไม่ปลอดภัย (HTTP)'}`;
}

document.addEventListener('DOMContentLoaded', () => {
    loadIPData();
    loadBrowserData();
    loadAdditionalData();
});

window.addEventListener('online', () => {
    document.getElementById('onlineStatus').textContent = '🟢 เชื่อมต่อแล้ว';
});
window.addEventListener('offline', () => {
    document.getElementById('onlineStatus').textContent = '🔴 ไม่เชื่อมต่อ';
});
