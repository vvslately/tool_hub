
<!-- Error Modal -->
<div id="errorModal" class="fixed inset-0 hidden items-center justify-center bg-black/50 z-50">
  <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full p-6 text-center animate__animated animate__fadeInDown">
    <h2 class="text-xl font-bold mb-4 text-orange-600">⚠️ แจ้งเตือน</h2>
    <p id="errorMessage" class="mb-4 text-gray-700">ข้อความแจ้งเตือน</p>
    <button onclick="closePopup()" class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-full font-semibold transition">ตกลง</button>
  </div>
</div>
<section class="min-h-screen bg-gradient-to-br from-orange-100 via-white to-orange-200 py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-5xl sm:text-6xl font-extrabold text-center mb-16 bg-gradient-to-r from-orange-600 via-orange-400 to-yellow-400 bg-clip-text text-transparent animate__animated animate__fadeIn">
      📧 ตรวจสอบ Gmail
    </h1>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 animate__animated animate__fadeInUp">
      <!-- Input Section -->
      <div class="p-8 bg-white/90 rounded-3xl shadow-2xl hover:scale-[1.02] transition-all border border-orange-100">
        <h2 class="text-2xl font-semibold text-orange-600 flex items-center gap-2 mb-4">
          <span class="text-3xl">✍️</span> กรอกอีเมล (Mail Input)
        </h2>
        <textarea id="mailInput" class="w-full h-72 p-4 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 placeholder-gray-400 text-sm sm:text-base shadow-inner resize-none" placeholder="กรอก Gmail แต่ละบรรทัด..."></textarea>
        <div class="flex justify-center mt-6 gap-4">
          <button class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-2 rounded-full font-bold shadow-md transition animate__animated animate__pulse animate__infinite animate__slow" onclick="checkEmails()">
            🚀 เริ่มตรวจสอบ
          </button>
          <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-8 py-2 rounded-full font-bold shadow-md transition" onclick="clearAll()">
            🧹 ล้างข้อมูล
          </button>
        </div>
      </div>
      <!-- Result Section -->
      <div class="p-8 bg-white/90 rounded-3xl shadow-2xl hover:scale-[1.02] transition-all border border-orange-100">
        <h2 class="text-2xl font-semibold text-orange-600 flex items-center gap-2 mb-4">
          <span class="text-3xl">📦</span> ผลลัพธ์การตรวจสอบ (Result)
        </h2>
        <div id="mailOutput" class="w-full h-72 p-4 rounded-xl bg-gray-50 border border-gray-300 overflow-y-auto text-sm whitespace-pre mb-4 shadow-inner"></div>
        <div id="progressContainer" class="w-full bg-gray-300 rounded-full overflow-hidden hidden mb-4 shadow-inner">
          <div id="progressBar" class="bg-orange-500 text-center text-xs text-white py-1 transition-all duration-300" style="width: 0%">0%</div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 text-center text-xs animate__animated animate__fadeIn animate__delay-1s">
          <div class="bg-green-500 rounded-xl p-2 cursor-pointer hover:bg-green-600 text-white shadow transition transform hover:scale-105" onclick="downloadList('good')">
            <p class="font-semibold">✅ Good</p>
            <p id="goodCount" class="text-lg font-bold">0</p>
          </div>
          <div class="bg-yellow-400 rounded-xl p-2 cursor-pointer hover:bg-yellow-500 text-white shadow transition transform hover:scale-105" onclick="downloadList('verified')">
            <p class="font-semibold">✔️ Verified</p>
            <p id="verifiedCount" class="text-lg font-bold">0</p>
          </div>
          <div class="bg-red-500 rounded-xl p-2 cursor-pointer hover:bg-red-600 text-white shadow transition transform hover:scale-105" onclick="downloadList('disabled')">
            <p class="font-semibold">❌ Disabled</p>
            <p id="disabledCount" class="text-lg font-bold">0</p>
          </div>
          <div class="bg-gray-500 rounded-xl p-2 cursor-pointer hover:bg-gray-600 text-white shadow transition transform hover:scale-105" onclick="downloadList('notExit')">
            <p class="font-semibold">⚠️ NotExist</p>
            <p id="notExitCount" class="text-lg font-bold">0</p>
          </div>
          <div class="bg-blue-500 rounded-xl p-2 cursor-pointer hover:bg-blue-600 text-white shadow transition transform hover:scale-105" onclick="downloadList('unknown')">
            <p class="font-semibold">❓ Unknown</p>
            <p id="unknownCount" class="text-lg font-bold">0</p>
          </div>
        </div>
      </div>
    </div>
    <!-- Help Section -->
    <div class="mt-16 p-8 bg-white/90 rounded-3xl shadow-2xl animate__animated animate__fadeInUp animate__delay-2s border border-orange-100">
      <h2 class="text-2xl font-semibold mb-6 text-orange-600 text-center flex items-center justify-center">
        <span class="text-3xl mr-2">ℹ️</span> คำแนะนำการใช้งาน
      </h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- วิธีใช้งาน -->
        <div class="bg-orange-50 p-4 rounded-xl shadow-inner">
          <h3 class="text-lg font-semibold text-orange-600 mb-2">📝 วิธีใช้งาน</h3>
          <ol class="list-decimal list-inside text-sm text-gray-700 space-y-1">
            <li>กรอกอีเมล Gmail (1 บรรทัดต่อ 1 อีเมล)</li>
            <li>กดปุ่ม “เริ่มตรวจสอบ”</li>
            <li>รอระบบแสดงผลลัพธ์</li>
            <li>คลิกผลลัพธ์เพื่อดาวน์โหลด</li>
          </ol>
        </div>
        <!-- ความหมายของผลลัพธ์ -->
        <div class="bg-orange-50 p-4 rounded-xl shadow-inner">
          <h3 class="text-lg font-semibold text-orange-600 mb-2">🔍 ความหมายของผลลัพธ์</h3>
          <ul class="text-sm space-y-2 text-gray-700">
            <li><span class="font-semibold text-green-600">✅ Good</span> - อีเมลใช้งานได้ปกติ</li>
            <li><span class="font-semibold text-yellow-600">✔️ Verified</span> - อีเมลต้องยืนยันตัวตน</li>
            <li><span class="font-semibold text-red-600">❌ Disabled</span> - อีเมลถูกปิดการใช้งาน</li>
            <li><span class="font-semibold text-gray-600">⚠️ NotExist</span> - ไม่พบอีเมลในระบบ</li>
            <li><span class="font-semibold text-blue-600">❓ Unknown</span> - ไม่สามารถระบุสถานะได้</li>
          </ul>
        </div>
        <!-- ข้อควรระวัง -->
        <div class="bg-orange-50 p-4 rounded-xl shadow-inner">
          <h3 class="text-lg font-semibold text-orange-600 mb-2">⚠️ ข้อควรระวัง</h3>
          <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
            <li>ใช้ได้เฉพาะอีเมล @gmail.com เท่านั้น</li>
            <li>จำกัดสูงสุด 10,000 อีเมลต่อครั้ง</li>
            <li>ผลลัพธ์อาจคลาดเคลื่อนเล็กน้อย</li>
            <li>ระบบไม่เก็บข้อมูลที่กรอก</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>