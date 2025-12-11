const ChartJs = await import(
    "https://cdn.jsdelivr.net/npm/chart.js@4.5.1/+esm"
);

console.log(ChartJs);

const centerTextPlugin = {
    id: "centerText",
    beforeDraw(chart, args, options) {
        if (chart.config.type !== "doughnut" && chart.config.type !== "pie") {
            return;
        }

        const {
            ctx,
            chartArea: { top, width, height },
        } = chart;
        ctx.save();

        // 1. กำหนดรูปแบบข้อความ
        ctx.font = options.font || "bolder 24px Arial";
        ctx.fillStyle = options.color || "#333";
        ctx.textAlign = "center";
        ctx.textBaseline = "middle";

        // 2. คำนวณหาจุดศูนย์กลางของกราฟ
        const centerX = width / 2;
        const centerY = top + height / 2;

        // 3. เตรียมข้อความรวม
        // ดึงผลรวมที่เราคำนวณไว้ก่อนหน้า (หรือคำนวณใหม่)
        const total =
            chart._total ||
            chart.data.datasets[0].data.reduce((sum, val) => sum + val, 0);

        // 4. วาดข้อความ (ข้อความหลัก: Total)
        // ctx.fillText("รวม", centerX, centerY - 15);

        // 5. วาดค่า (ค่าตัวเลข)
        ctx.font = options.valueFont || "bold 30px Arial";
        const unit = options.valueUnit || "กิโล";
        ctx.fillText(total.toLocaleString() + " " + unit, centerX, centerY);

        ctx.restore();
    },
};

ChartJs.Chart.register(
    ChartJs.LineController,
    ChartJs.LineElement,
    ChartJs.PointElement,
    ChartJs.BarController,
    ChartJs.BarElement,
    ChartJs.ArcElement,
    ChartJs.DoughnutController,

    ChartJs.LinearScale,
    ChartJs.CategoryScale,

    ChartJs.Title,
    ChartJs.Tooltip,
    ChartJs.Legend,

    centerTextPlugin
);

const backgroundColors = [
    "#FF6384", // แดง
    "#36A2EB", // ฟ้า
    "#FFCE56", // เหลือง
    "#4BC0C0", // เขียวน้ำทะเล
    "#9966FF", // ม่วง
    "#FF9F40", // ส้ม
];

async function drawYearChart(elementId, data) {
    const ctx = document.getElementById(elementId);
    if (!ctx) {
        console.error(
            `Cannot find element with ID: ${elementId}. Chart not rendered.`
        );
        return;
    }
    try {
        await new ChartJs.Chart(ctx, {
            type: "line", // หรือ "bar" ก็ได้
            data: {
                labels: data.dummyYear.map((row) => row.year),
                datasets: [
                    {
                        label: "ทั้งหมด",
                        data: data.dummyYear.map((row) => row.count),
                        backgroundColor: "rgba(255, 123, 0, 0.6)",
                        borderColor: "rgba(255, 0, 0, 1)",
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: "x",

                plugins: {
                    title: {
                        display: true,
                        text: "สถิติปริมาณขยะ", // หัวข้อ
                        // อาจเพิ่มสีและขนาดตัวอักษรเพื่อยืนยันการแสดงผล
                        font: {
                            size: 16,
                            weight: "bold",
                        },
                    },
                    legend: {
                        display: false,
                    },
                },

                scales: {
                    x: {
                        beginAtZero: true,
                    },
                },
            },
        });
    } catch (error) {
        console.error(error);
    }
}

async function drawFacultyChart(elementId, data) {
    const ctx = document.getElementById(elementId);
    if (!ctx) {
        console.error(
            `Cannot find element with ID: ${elementId}. Chart not rendered.`
        );
        return;
    }

    try {
        await new ChartJs.Chart(ctx, {
            type: "bar", // ใช้ Bar Chart เพราะแกน X เป็นหมวดหมู่
            data: {
                // ใช้ชื่อคณะเป็น Labels
                labels: data.dummyFaculty.map((row) => row.faculty_name),
                datasets: [
                    {
                        label: "ปริมาณขยะ",
                        data: data.dummyFaculty.map((row) => row.count),
                        backgroundColor: backgroundColors,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: "x",

                plugins: {
                    title: {
                        display: true,
                        text: "สถิติปริมาณขยะ",
                        font: {
                            size: 16,
                            weight: "bold",
                        },
                    },
                    legend: {
                        display: false,
                    },
                },

                scales: {
                    x: {
                        beginAtZero: true,
                    },
                },
            },
        });
    } catch (error) {
        console.error(error);
    }
}

async function drawFacultyCarbonChart(elementId, data) {
    const ctx = document.getElementById(elementId);
    if (!ctx) {
        console.error(
            `Cannot find element with ID: ${elementId}. Chart not rendered.`
        );
        return;
    }

    try {
        await new ChartJs.Chart(ctx, {
            type: "bar",
            data: {
                labels: data.map((row) => row.faculty_name),
                datasets: [
                    {
                        label: "การลดคาร์บอน",
                        data: data.map((row) => row.count),
                        backgroundColor: backgroundColors,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: "x",

                plugins: {
                    title: {
                        display: true,
                        text: "การลดคาร์บอน",
                        font: {
                            size: 16,
                            weight: "bold",
                        },
                    },
                    legend: {
                        display: false,
                        // position: "right",
                    },
                },

                scales: {
                    x: {
                        beginAtZero: true,
                    },
                },
            },
        });
    } catch (error) {
        console.error(error);
    }
}

async function drawYearlyCarbonChart(elementId, data) {
    const ctx = document.getElementById(elementId);
    if (!ctx) {
        console.error(
            `Cannot find element with ID: ${elementId}. Chart not rendered.`
        );
        return;
    }
    try {
        await new ChartJs.Chart(ctx, {
            type: "line", // หรือ "bar" ก็ได้
            data: {
                labels: data.map((row) => row.year),
                datasets: [
                    {
                        label: "ทั้งหมด",
                        data: data.map((row) => row.count),
                        backgroundColor: "rgba(0, 255, 30, 0.6)",
                        borderColor: "rgba(0, 255, 55, 1)",
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: "x",

                plugins: {
                    title: {
                        display: true,
                        text: "สถิติการลดคาร์บอน", // หัวข้อ
                        // อาจเพิ่มสีและขนาดตัวอักษรเพื่อยืนยันการแสดงผล
                        font: {
                            size: 16,
                            weight: "bold",
                        },
                    },
                    legend: {
                        display: false,
                    },
                },

                scales: {
                    x: {
                        beginAtZero: true,
                    },
                },
            },
        });
    } catch (error) {
        console.error(error);
    }
}

async function drawCarbonReductionPieChart(elementId, data) {
    const ctx = document.getElementById(elementId);
    if (!ctx) {
        console.error(
            `Cannot find element with ID: ${elementId}. Chart not rendered.`
        );
        return;
    }

    const backgroundColors = [
        "#FF6384", // แดง
        "#36A2EB", // ฟ้า
        "#FFCE56", // เหลือง
        "#4BC0C0", // เขียวน้ำทะเล
        "#9966FF", // ม่วง
        "#FF9F40", // ส้ม
    ];

    try {
        await new ChartJs.Chart(ctx, {
            type: "doughnut",
            data: {
                labels: data.map((row) => row.year.toString()),
                datasets: [
                    {
                        label: "ปริมาณลดคาร์บอน (ตัน)",
                        data: data.map((row) => row.count),
                        backgroundColor: backgroundColors,
                        hoverOffset: 10,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: "การลดคาร์บอนรวมรายปี (2020 - 2025)",
                        font: { size: 16, weight: "bold" },
                    },

                    legend: {
                        position: "right",
                    },

                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                let label = context.dataset.label || "";
                                if (label) {
                                    label += ": ";
                                }
                                let total = context.chart._total;
                                let currentValue = context.raw;
                                let percentage = Math.floor(
                                    (currentValue / total) * 100 + 0.5
                                );
                                return (
                                    label +
                                    currentValue +
                                    " ตัน (" +
                                    percentage +
                                    "%)"
                                );
                            },
                        },
                    },

                    centerText: {
                        color: "#333",
                        font: "bold 20px Arial",
                        valueFont: "bold 20px Arial",
                    },
                },
            },
        });
    } catch (error) {
        console.error(error);
    }
}

async function drawRecycledWastePieChart(elementId, data) {
    const ctx = document.getElementById(elementId);
    if (!ctx) {
        console.error(
            `Cannot find element with ID: ${elementId}. Chart not rendered.`
        );
        return;
    }

    // สีสำหรับ Pie Chart
    const backgroundColors = [
        "#4BC0C0", // เขียวน้ำทะเล (2020)
        "#A17A7A", // น้ำตาลแดง (2021)
        "#36A2EB", // ฟ้า (2022)
        "#FF9F40", // ส้ม (2023)
        "#9966FF", // ม่วง (2024)
        "#FFCD56", // เหลือง (2025)
    ];

    // คำนวณผลรวมเพื่อใช้ใน Tooltip
    const totalRecycled = data.reduce((sum, item) => sum + item.count, 0);

    try {
        const myPieChart = new ChartJs.Chart(ctx, {
            type: "doughnut",
            data: {
                // Labels คือ ปี
                labels: data.map((row) => row.year.toString()),
                datasets: [
                    {
                        label: "ปริมาณรีไซเคิล (ตัน)",
                        data: data.map((row) => row.count),
                        backgroundColor: backgroundColors,
                        hoverOffset: 10,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: "สัดส่วนปริมาณขยะรีไซเคิลรวมรายปี (2020 - 2025)",
                        font: { size: 16, weight: "bold" },
                    },

                    legend: {
                        position: "right",
                    },

                    tooltip: {
                        callbacks: {
                            // แสดงปริมาณและเปอร์เซ็นต์ใน Tooltip
                            label: function (context) {
                                let label = context.label || "";
                                if (label) {
                                    label += ": ";
                                }
                                let currentValue = context.raw;
                                // คำนวณเปอร์เซ็นต์
                                let percentage = (
                                    (currentValue / totalRecycled) *
                                    100
                                ).toFixed(1);
                                return ` ${label} ${currentValue} ตัน (${percentage}%)`;
                            },
                        },
                    },

                    centerText: {
                        color: "#333",
                        font: "bold 18px Arial",
                        valueFont: "bold 18px Arial",
                        valueUnit: "ตัน",
                    },
                },
            },
        });
        // กำหนด _total สำหรับการคำนวณใน Tooltip (ถ้าไม่ได้ใช้ totalRecycled โดยตรง)
        myPieChart._total = totalRecycled;
    } catch (error) {
        console.error(error);
    }
}

const dummyYear = [
    { year: 2020, count: 120 },
    { year: 2021, count: 180 },
    { year: 2022, count: 250 },
    { year: 2023, count: 320 },
    { year: 2024, count: 400 },
    { year: 2025, count: 450 },
];

const dummyFaculty = [
    { faculty_name: "วิทยาศาสตร์", count: 123 },
    { faculty_name: "ครุศาสตร์", count: 361 },
    { faculty_name: "ศิลปกรรมศาสตร์", count: 346 },
    { faculty_name: "มนุษย์ศาสตร์", count: 326 },
    { faculty_name: "นิเทศศาสตร์", count: 836 },
    { faculty_name: "สังคมศาสตร์", count: 753 },
];

const dummyYearlyCarbon = [
    { year: 2020, count: 85 },
    { year: 2021, count: 110 },
    { year: 2022, count: 145 },
    { year: 2023, count: 160 },
    { year: 2024, count: 190 },
    { year: 2025, count: 220 },
];

const dummyFacultyCarbon = [
    { faculty_name: "วิทยาศาสตร์", count: 2.3 },
    { faculty_name: "ครุศาสตร์", count: 1.7 },
    { faculty_name: "ศิลปกรรมศาสตร์", count: 1.4 },
    { faculty_name: "มนุษย์ศาสตร์", count: 2.4 },
    { faculty_name: "นิเทศศาสตร์", count: 1.8 },
    { faculty_name: "สังคมศาสตร์", count: 1.5 },
];

const data = { dummyYear: dummyYear, dummyFaculty: dummyFaculty };
const totalCarbon = dummyYearlyCarbon.reduce(
    (sum, item) => sum + item.count,
    0
);
ChartJs.Chart.prototype._total = totalCarbon;

drawYearChart("waste-by-year", data);
drawFacultyChart("waste-by-faculty", data);
drawFacultyCarbonChart("carbon-by-faculty", dummyFacultyCarbon);
drawYearlyCarbonChart("carbon-by-year", dummyYearlyCarbon);
drawCarbonReductionPieChart("carbon-total", dummyYearlyCarbon);
drawRecycledWastePieChart("waste-total", dummyYear);
