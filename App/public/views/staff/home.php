<div class="container mx-auto px-4 py-8" x-data="facultyDashboard()" x-init="init()">
    <h1 class="text-3xl font-bold text-gray-800 mb-2" x-text="title">แดชบอร์ด</h1>
    <p class="text-lg text-gray-600 mb-6">ภาพรวมข้อมูลสำหรับคณะของคุณ</p>

    <template x-if="isLoading">
        <div class="flex items-center justify-center py-10">
            <div class="flex items-center space-x-2">
                <svg class="animate-spin h-8 w-8 text-lime-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <span class="text-xl text-gray-700">กำลังโหลดข้อมูล...</span>
            </div>
        </div>
    </template>

    <template x-if="error">
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
            <p class="font-bold">เกิดข้อผิดพลาด</p>
            <p x-text="error"></p>
        </div>
    </template>

    <template x-if="!isLoading && !error">
        <div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-lime-500">
                    <h2 class="text-lg font-semibold text-gray-600 mb-1">ปริมาณขยะสะสม</h2>
                    <p class="text-4xl font-bold text-lime-700"
                        x-html="`${formatNumber(summary.total_weight)} <span class='text-xl font-medium'>กก.</span>`">
                    </p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-cyan-500">
                    <h2 class="text-lg font-semibold text-gray-600 mb-1">ลดคาร์บอนไปแล้ว</h2>
                    <p class="text-4xl font-bold text-cyan-700"
                        x-html="`${formatNumber(summary.total_co2)} <span class='text-xl font-medium'>kgCO₂e</span>`">
                    </p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-yellow-500">
                    <h2 class="text-lg font-semibold text-gray-600 mb-1">มูลค่าขยะสะสม</h2>
                    <p class="text-4xl font-bold text-yellow-700"
                        x-html="`${formatNumber(summary.total_value)} <span class='text-xl font-medium'>บาท</span>`">
                    </p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-green-500">
                    <h2 class="text-lg font-semibold text-gray-600 mb-1">แต้มสะสมของคณะ</h2>
                    <p class="text-4xl font-bold text-green-700"
                        x-html="`${formatNumber(summary.faculty_fraction, 0)} <span class='text-xl font-medium'>คะแนน</span>`">
                    </p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-indigo-500">
                    <h2 class="text-lg font-semibold text-gray-600 mb-1">สมาชิกที่มีในระบบ</h2>
                    <p class="text-4xl font-bold text-indigo-700"
                        x-html="`${formatNumber(summary.member_participation, 0)} <span class='text-xl font-medium'>คน</span>`">
                    </p>
                </div>

            </div>

            <div class="mt-8 bg-white p-6 rounded-xl shadow-lg">
                <h2 class="text-xl font-bold text-gray-800 mb-4">ข้อมูลเพิ่มเติม</h2>
                <p class="text-gray-600">ส่วนนี้สำหรับแสดงข้อมูลเพิ่มเติมในอนาคต เช่น กราฟแนวโน้มการฝากขยะ หรือ
                    leaderboard
                    ของสมาชิกในคณะ</p>
            </div>
        </div>
    </template>
</div>
<script>
    function facultyDashboard() {
        return {
            isLoading: true,
            error: null,
            facultyId: <?php echo json_encode($user["user_data"]->faculty_id ?? null); ?>,
            faculty: {
                faculty_name: ''
            },
            summary: {
                total_weight: 0,
                faculty_fraction: 0,
                total_value: 0,
                total_co2: 0,
                member_participation: 0
            },
            get title() {
                return this.faculty.faculty_name ? `แดชบอร์ดคณะ ${this.faculty.faculty_name}` : 'แดชบอร์ด';
            },
            formatNumber(value, fractionDigits = 2) {
                const num = parseFloat(value || 0);
                return num.toLocaleString('th-TH', {
                    minimumFractionDigits: fractionDigits,
                    maximumFractionDigits: fractionDigits
                });
            },
            async init() {
                if (!this.facultyId) {
                    this.error = 'ไม่สามารถระบุคณะได้';
                    this.isLoading = false;
                    return;
                }

                try {
                    const response = await fetch(`/api/reports/faculty/${this.facultyId}`);
                    const result = await response.json();
                    console.log(result);

                    this.faculty = result.data.faculty;
                    this.summary = result.data.summary;
                } catch (error) {
                    console.error('Error fetching dashboard data:', error);
                    this.error = 'ไม่สามารถโหลดข้อมูลแดชบอร์ดได้ โปรดลองอีกครั้ง';
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'ไม่สามารถโหลดข้อมูลแดชบอร์ดได้',
                        confirmButtonText: 'ตกลง'
                    });
                } finally {
                    this.isLoading = false;
                }
            }
        }
    }
</script>