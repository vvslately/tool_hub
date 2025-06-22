<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>IP Checker - ตรวจสอบข้อมูล IP</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="/archive/views/pages/check-ip/style.css" />
</head>

<body>
    <section class="min-h-screen bg-gradient-to-br from-orange-100 via-white to-orange-200 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <h1 class="text-5xl sm:text-6xl font-extrabold text-center mb-16 animate-fadeIn">
                <span>🔍</span> <span class="gradient-text">ข้อมูล IP ของคุณ</span>
            </h1>



            <!-- Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 animate-fadeInUp">
                <!-- IP Info -->
                <div class="p-8 bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl text-center card-hover border border-orange-100">
                    <div class="text-5xl mb-4">🌐</div>
                    <h2 class="text-2xl font-semibold text-orange-600 mb-6">หมายเลข IP</h2>
                    <div class="text-4xl font-bold text-gray-800 mb-4 animate-pulse" id="ipAddress">
                        <span class="loading"></span>
                        <span>กำลังโหลด...</span>
                    </div>
                    <div class="text-lg text-orange-600 font-medium mb-2" id="country">
                        <span class="animate-bounce">🌍 กำลังตรวจสอบประเทศ...</span>
                    </div>
                    <p class="text-sm text-gray-500">IP ของคุณสามารถมองเห็นได้แบบสาธารณะ</p>
                    <div class="mt-4 p-3 bg-orange-50 rounded-lg">
                        <p class="text-xs text-gray-600">ประเภท IP: <span id="ipType" class="font-medium">กำลังตรวจสอบ...</span></p>
                    </div>
                </div>

                <!-- Location Info -->
                <div class="p-8 bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl card-hover border border-orange-100">
                    <div class="flex items-center mb-6">
                        <div class="text-4xl mr-3">📍</div>
                        <h2 class="text-2xl font-semibold text-orange-600">ตำแหน่งที่ตั้ง</h2>
                    </div>
                    <div class="grid grid-cols-1 gap-4">
                        <!-- City -->
                        <div class="flex items-center p-3 bg-orange-50/70 rounded-xl shadow-inner">
                            <span class="text-2xl mr-3">🏙️</span>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500">เมือง</p>
                                <p class="font-medium text-gray-800" id="city">กำลังโหลด...</p>
                            </div>
                        </div>

                        <!-- Region -->
                        <div class="flex items-center p-3 bg-orange-50/70 rounded-xl shadow-inner">
                            <span class="text-2xl mr-3">📌</span>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500">ภูมิภาค</p>
                                <p class="font-medium text-gray-800" id="region">กำลังโหลด...</p>
                            </div>
                        </div>

                        <!-- Coordinates -->
                        <div class="flex items-center p-3 bg-orange-50/70 rounded-xl shadow-inner">
                            <span class="text-2xl mr-3">🗺️</span>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500">พิกัด</p>
                                <p class="font-medium text-gray-800" id="coordinates">กำลังโหลด...</p>
                            </div>
                        </div>

                        <!-- Timezone -->
                        <div class="flex items-center p-3 bg-orange-50/70 rounded-xl shadow-inner">
                            <span class="text-2xl mr-3">⏰</span>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500">เขตเวลา</p>
                                <p class="font-medium text-gray-800" id="timezone">กำลังโหลด...</p>
                            </div>
                        </div>

                        <!-- ISP -->
                        <div class="flex items-center p-3 bg-orange-50/70 rounded-xl shadow-inner">
                            <span class="text-2xl mr-3">💼</span>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500">ผู้ให้บริการ</p>
                                <p class="font-medium text-gray-800" id="ispOrg">กำลังโหลด...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Browser Info -->
                <div class="lg:col-span-2 p-8 bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl mt-6 animate-fadeInUp animate-delay-1s border border-orange-100">
                    <div class="flex items-center mb-6">
                        <div class="text-4xl mr-3">🖥️</div>
                        <h2 class="text-2xl font-semibold text-orange-600">ข้อมูลเบราว์เซอร์</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="flex items-center p-4 bg-orange-50/70 rounded-xl shadow-inner">
                            <span class="text-2xl mr-3">🌐</span>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500">ชื่อเบราว์เซอร์</p>
                                <p class="font-medium text-gray-800" id="browser">กำลังโหลด...</p>
                            </div>
                        </div>
                        <div class="flex items-center p-4 bg-orange-50/70 rounded-xl shadow-inner">
                            <span class="text-2xl mr-3">⚙️</span>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500">เวอร์ชัน</p>
                                <p class="font-medium text-gray-800" id="version">กำลังโหลด...</p>
                            </div>
                        </div>
                        <div class="flex items-center p-4 bg-orange-50/70 rounded-xl shadow-inner">
                            <span class="text-2xl mr-3">💻</span>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500">ระบบปฏิบัติการ</p>
                                <p class="font-medium text-gray-800" id="os">กำลังโหลด...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Screen Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div class="flex items-center p-4 bg-orange-50/70 rounded-xl shadow-inner">
                            <span class="text-2xl mr-3">📱</span>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500">ความละเอียดหน้าจอ</p>
                                <p class="font-medium text-gray-800" id="screenResolution">กำลังโหลด...</p>
                            </div>
                        </div>
                        <div class="flex items-center p-4 bg-orange-50/70 rounded-xl shadow-inner">
                            <span class="text-2xl mr-3">🔍</span>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500">ภาษาเบราว์เซอร์</p>
                                <p class="font-medium text-gray-800" id="language">กำลังโหลด...</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-orange-50/70 rounded-xl shadow-inner">
                        <p class="text-sm text-gray-500 flex items-center">
                            <span class="text-xl mr-2">🔎</span> User Agent
                        </p>
                        <p class="text-sm mt-2 break-words font-mono bg-gray-100 p-3 rounded text-gray-700" id="userAgent">กำลังโหลด...</p>
                    </div>
                </div>
            </div>

            <!-- Map Section -->
            <div class="mt-20 p-8 bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl animate-fadeInUp animate-delay-2s border border-orange-100">
                <div class="flex items-center justify-center mb-6">
                    <div class="text-4xl mr-3">🗺️</div>
                    <h2 class="text-2xl font-semibold text-orange-600">แผนที่ตำแหน่งโดยประมาณ</h2>
                </div>
                <div class="aspect-video map-container rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden" id="mapContainer">
                    <div class="text-center">
                        <div class="loading mb-4"></div>
                        <p class="text-gray-600">กำลังโหลดแผนที่...</p>
                    </div>
                </div>
                <p class="text-center text-sm text-gray-500 mt-4">หมายเหตุ: ตำแหน่งอาจคลาดเคลื่อนเพราะใช้การคาดคะเนจาก IP</p>
            </div>

            <!-- Additional Info -->
            <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-6 bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-orange-100">
                    <h3 class="text-lg font-semibold text-orange-600 mb-4 flex items-center">
                        <span class="text-2xl mr-2">🔒</span>
                        ความปลอดภัย
                    </h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p>• IP ของคุณมองเห็นได้จากเว็บไซต์ทั่วไป</p>
                        <p>• ใช้ VPN เพื่อซ่อนตำแหน่งที่แท้จริง</p>
                        <p id="connectionSecurity">• การเชื่อมต่อ: กำลังตรวจสอบ...</p>
                    </div>
                </div>

                <div class="p-6 bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-orange-100">
                    <h3 class="text-lg font-semibold text-orange-600 mb-4 flex items-center">
                        <span class="text-2xl mr-2">⚡</span>
                        ข้อมูลการเชื่อมต่อ
                    </h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p>เวลาปัจจุบัน: <span id="currentTime" class="font-medium">กำลังโหลด...</span></p>
                        <p>Online: <span id="onlineStatus" class="font-medium">กำลังตรวจสอบ...</span></p>
                        <p>การเชื่อมต่อ: <span id="connectionType" class="font-medium">กำลังตรวจสอบ...</span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="/archive/views/pages/check-ip/script.js"></script>

</body>

</html>