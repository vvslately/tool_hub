document.getElementById('calculatorForm').addEventListener('submit', function (e) {
    e.preventDefault();
    calculateRefund();
});

function calculateRefund() {
    // Hide previous errors and results
    document.getElementById('errorMessage').classList.add('hidden');
    document.getElementById('calculationDetails').classList.add('hidden');

    // Get form values
    const failureDate = new Date(document.getElementById('failureDate').value);
    const expiryDate = new Date(document.getElementById('expiryDate').value);
    const months = parseInt(document.getElementById('months').value);
    const price = parseFloat(document.getElementById('price').value);
    const deductPercent = parseFloat(document.getElementById('deductPercent').value) || 0;

    // Validation
    if (isNaN(failureDate.getTime()) || isNaN(expiryDate.getTime()) || isNaN(months) || isNaN(price)) {
        showError('กรุณากรอกข้อมูลให้ครบถ้วนและถูกต้อง');
        return;
    }

    if (failureDate >= expiryDate) {
        showError('วันเริ่มมีปัญหาต้องเป็นก่อนวันหมดอายุ');
        return;
    }

    if (months <= 0 || price <= 0) {
        showError('จำนวนเดือนและราคาต้องมากกว่า 0');
        return;
    }

    if (deductPercent < 0 || deductPercent > 100) {
        showError('เปอร์เซ็นต์การหักเงินต้องอยู่ระหว่าง 0-100');
        return;
    }

    // Calculate total days and remaining days
    const totalDays = Math.ceil((expiryDate - new Date(expiryDate.getFullYear(), expiryDate.getMonth() - months, expiryDate.getDate())) / (1000 * 60 * 60 * 24));
    const usedDays = Math.ceil((failureDate - new Date(expiryDate.getFullYear(), expiryDate.getMonth() - months, expiryDate.getDate())) / (1000 * 60 * 60 * 24));
    const remainingDays = totalDays - usedDays;

    if (remainingDays <= 0) {
        showError('บริการหมดอายุแล้วหรือใช้งานครบตามระยะเวลาแล้ว');
        return;
    }

    // Calculate refund
    const dailyRate = price / totalDays;
    const refundBeforeDeduction = remainingDays * dailyRate;
    const deductionAmount = refundBeforeDeduction * (deductPercent / 100);
    const finalRefund = refundBeforeDeduction - deductionAmount;

    // Display results
    document.getElementById('resultPlaceholder').classList.add('hidden');
    document.getElementById('results').classList.remove('hidden');

    document.getElementById('remainingDays').textContent = remainingDays + ' วัน';
    document.getElementById('refundBefore').textContent = '฿' + refundBeforeDeduction.toFixed(2);
    document.getElementById('refundAfter').textContent = '฿' + finalRefund.toFixed(2);

    // Show calculation details
    const calcDetails = document.getElementById('refundCalc');
    calcDetails.innerHTML = `
                <div class="space-y-2">
                    <p><strong>📋 ข้อมูลการคำนวณ:</strong></p>
                    <p>• ระยะเวลาทั้งหมด: ${totalDays} วัน</p>
                    <p>• ใช้งานไปแล้ว: ${usedDays} วัน</p>
                    <p>• คงเหลือ: ${remainingDays} วัน</p>
                    <p>• อัตราต่อวัน: ฿${dailyRate.toFixed(2)}</p>
                    <br>
                    <p><strong>💰 การคำนวณเงินคืน:</strong></p>
                    <p>• ยอดก่อนหัก: ${remainingDays} วัน × ฿${dailyRate.toFixed(2)} = ฿${refundBeforeDeduction.toFixed(2)}</p>
                    ${deductPercent > 0 ? `<p>• หักเงิน ${deductPercent}%: ฿${deductionAmount.toFixed(2)}</p>` : ''}
                    <p class="font-bold text-green-600">• <strong>ยอดคืนสุทธิ: ฿${finalRefund.toFixed(2)}</strong></p>
                </div>
            `;

    document.getElementById('calculationDetails').classList.remove('hidden');

    // Add animation
    document.getElementById('results').classList.add('animate__animated', 'animate__fadeInUp');
}

function showError(message) {
    document.getElementById('errorText').textContent = message;
    document.getElementById('errorMessage').classList.remove('hidden');
    document.getElementById('results').classList.remove('hidden');
    document.getElementById('resultPlaceholder').classList.add('hidden');
}

// Auto-calculate on input change
const inputs = document.querySelectorAll('#calculatorForm input');
inputs.forEach(input => {
    input.addEventListener('input', function () {
        // Optional: Auto-calculate when all fields are filled
        const form = document.getElementById('calculatorForm');
        const formData = new FormData(form);
        let allFilled = true;

        inputs.forEach(inp => {
            if (inp.required && !inp.value) {
                allFilled = false;
            }
        });

        if (allFilled) {
            // calculateRefund(); // Uncomment for auto-calculation
        }
    });
});

// Add Thai date formatting helper
function formatThaiDate(date) {
    const thaiMonths = [
        'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
        'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
    ];

    return `${date.getDate()} ${thaiMonths[date.getMonth()]} ${date.getFullYear() + 543}`;
}