<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เครื่องมือคำนวณเงินคืน</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <link rel="stylesheet" href="/archive/views/pages/calculate-money/style.css" />

</head>

<body class="bg-gradient-to-br from-orange-50 via-white to-orange-100 min-h-screen">
    <section class="min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 animate__animated animate__fadeIn">
            <!-- Header -->
            <div class="text-center mb-12">
                <div class="floating mb-6">


                    <h1 class="text-5xl sm:text-6xl font-extrabold text-center mb-16 animate-fadeIn">
                        <span>💰</span> <span class="gradient-text">เครื่องมือคำนวณเงินคืน</span>
                    </h1>
                </div>
                <p class="text-xl text-gray-700 mt-6 glass-effect p-6 rounded-3xl shadow-xl inline-block animate__fadeIn animate__delay-1s hover-lift transition-all duration-300">
                    ระบุวันหมดอายุและวันเริ่มมีปัญหา ระบบจะคำนวณยอดเงินที่ควรคืนโดยอัตโนมัติ ✨
                </p>
            </div>

            <div class="grid lg:grid-cols-2 gap-10 animate__fadeInUp animate__animated animate__delay-1s">
                <!-- ฟอร์ม -->
                <div class="p-8 glass-effect rounded-3xl shadow-2xl border-2 border-orange-200 hover-lift transition-all duration-300">
                    <h2 class="text-3xl font-bold text-orange-600 mb-6 flex items-center gap-3">
                        🧮 ฟอร์มกรอกข้อมูล
                    </h2>
                    <form id="calculatorForm" class="space-y-6">
                        <div class="group">
                            <label for="failureDate" class="block text-sm font-medium mb-2 text-gray-700 group-hover:text-orange-600 transition-colors">
                                📅 เริ่มใช้งานไม่ได้
                            </label>
                            <input type="date" id="failureDate" class="w-full p-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-orange-300 focus:border-orange-400 text-sm input-focus transition-all duration-300" required />
                        </div>

                        <div class="group">
                            <label for="expiryDate" class="block text-sm font-medium mb-2 text-gray-700 group-hover:text-orange-600 transition-colors">
                                ⏰ วันหมดอายุ
                            </label>
                            <input type="date" id="expiryDate" class="w-full p-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-orange-300 focus:border-orange-400 text-sm input-focus transition-all duration-300" required />
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div class="group">
                                <label for="months" class="block text-sm font-medium mb-2 text-gray-700 group-hover:text-orange-600 transition-colors">
                                    📊 จำนวนเดือน
                                </label>
                                <input type="number" id="months" placeholder="เช่น 1" min="1" class="w-full p-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-orange-300 focus:border-orange-400 text-sm input-focus transition-all duration-300" required />
                            </div>
                            <div class="group">
                                <label for="price" class="block text-sm font-medium mb-2 text-gray-700 group-hover:text-orange-600 transition-colors">
                                    💳 ราคาทั้งหมด (บาท)
                                </label>
                                <input type="number" id="price" placeholder="เช่น 59" min="0" step="0.01" class="w-full p-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-orange-300 focus:border-orange-400 text-sm input-focus transition-all duration-300" required />
                            </div>
                        </div>

                        <div class="group">
                            <label for="deductPercent" class="block text-sm font-medium mb-2 text-gray-700 group-hover:text-orange-600 transition-colors">
                                📉 หักเงิน (%)
                            </label>
                            <input type="number" id="deductPercent" placeholder="เช่น 10" value="0" min="0" max="100" step="0.1" class="w-full p-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-orange-300 focus:border-orange-400 text-sm input-focus transition-all duration-300" />
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-4 px-6 rounded-2xl transition-all duration-300 flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transform hover:scale-105 animate-pulse-slow">
                            <span class="text-lg">🧾 คำนวณเลย</span>
                        </button>
                    </form>
                </div>

                <!-- ผลลัพธ์ -->
                <div class="p-8 glass-effect rounded-3xl shadow-2xl border-2 border-orange-200 hover-lift transition-all duration-300">
                    <h2 class="text-3xl font-bold text-orange-600 mb-6 flex items-center gap-3">
                        📊 ผลลัพธ์การคำนวณ
                    </h2>

                    <div id="results" class="hidden space-y-6">
                        <!-- Error Message -->
                        <div id="errorMessage" class="hidden bg-red-50 border-2 border-red-200 text-red-700 px-6 py-4 rounded-2xl text-sm shadow-lg">
                            <div class="flex items-center gap-2">
                                <span class="text-lg">⚠️</span>
                                <strong>ข้อผิดพลาด:</strong>
                                <span id="errorText"></span>
                            </div>
                        </div>

                        <!-- Summary Box -->
                        <div class="result-card shadow-2xl rounded-2xl p-6 text-center transform hover:scale-105 transition-all duration-300">
                            <div class="space-y-4">
                                <div class="bg-white/50 rounded-xl p-4">
                                    <p class="text-lg text-gray-700 mb-2 font-medium">📅 วันคงเหลือ:</p>
                                    <p class="text-4xl font-extrabold text-yellow-600" id="remainingDays">--</p>
                                </div>

                                <div class="bg-white/50 rounded-xl p-4">
                                    <p class="text-lg text-gray-700 mb-2 font-medium">💰 ยอดก่อนหัก:</p>
                                    <p class="text-2xl font-bold text-gray-800" id="refundBefore">--</p>
                                </div>

                                <div class="bg-gradient-to-r from-green-100 to-emerald-100 rounded-xl p-4 border-2 border-green-200">
                                    <p class="text-lg text-gray-700 mb-2 font-medium">💵 ยอดคืนหลังหัก:</p>
                                    <p class="text-4xl font-extrabold text-green-600" id="refundAfter">--</p>
                                </div>
                            </div>
                        </div>

                        <!-- Calculation Detail -->
                        <div id="calculationDetails" class="hidden bg-orange-50 border-2 border-orange-200 rounded-2xl p-6 shadow-lg">
                            <div class="font-bold mb-3 text-orange-700 flex items-center gap-2 text-lg">
                                🔍 วิธีคำนวณ:
                            </div>
                            <div id="refundCalc" class="text-gray-700 space-y-2 text-sm bg-white rounded-xl p-4"></div>
                        </div>
                    </div>

                    <!-- Placeholder -->
                    <div id="resultPlaceholder" class="text-center text-gray-500 py-8 animate-pulse-slow">
                        <div class="text-6xl mb-4">🤔</div>
                        <p class="text-lg">กรุณากรอกข้อมูลด้านซ้ายเพื่อเริ่มคำนวณ</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="/archive/views/pages/calculate-money/script.js"></script>

</body>

</html>