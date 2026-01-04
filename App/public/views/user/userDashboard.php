<style>
    :root {
        --pad: clamp(12px, 2.5vw, 20px);
        --radius-lg: 20px;
        --radius-md: 16px;
        --shadow-1: 0 8px 32px rgba(0, 0, 0, 0.2);
        --shadow-2: 0 8px 32px rgba(0, 0, 0, 0.15);
    }
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Body styling is handled by layout for user pages */

    .dashboard-container { width: 100%; padding: var(--pad); }

    /* Header Section */
    .header {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        border-radius: var(--radius-lg);
        padding: clamp(14px, 2vw, 24px);
        margin-bottom: 16px;
        box-shadow: var(--shadow-1);
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 12px;
        align-items: center;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .user-avatar {
        width: clamp(52px, 8vw, 64px);
        height: clamp(52px, 8vw, 64px);
        border-radius: 50%;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: clamp(18px, 3.5vw, 26px);
        font-weight: bold;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
    }

    .user-details h2 {
        color: white;
        font-size: clamp(18px, 2.5vw, 24px);
        margin-bottom: 5px;
    }

    .user-level {
        background: rgba(255, 255, 255, 0.3);
        padding: 5px 15px;
        border-radius: 20px;
        color: white;
        font-weight: bold;
    }

    .stats { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 10px; }

    .stat-item { text-align: center; background: rgba(255, 255, 255, 0.2); padding: 10px 12px; border-radius: 14px; backdrop-filter: blur(10px); }

    .stat-value {
        color: white;
        font-size: clamp(16px, 2.2vw, 22px);
        font-weight: bold;
        display: block;
    }

    .stat-label {
        color: rgba(255, 255, 255, 0.9);
        font-size: 12px;
        display: block;
        margin-top: 5px;
    }

    /* Progress Section */
    .progress-section {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        border-radius: var(--radius-lg);
        padding: clamp(14px, 2.2vw, 24px);
        margin-bottom: 16px;
        box-shadow: var(--shadow-1);
    }

    .progress-header {
        color: white;
        font-size: clamp(18px, 3vw, 28px);
        font-weight: bold;
        text-align: center;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .progress-bar-container { background: rgba(255, 255, 255, 0.3); border-radius: 999px; height: clamp(28px, 5vw, 40px); overflow: hidden; position: relative; margin-bottom: 10px; }

    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #56ab2f 0%, #a8e063 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        transition: width 0.5s ease;
    }

    .level-info {
        text-align: center;
        color: white;
        font-size: 18px;
        font-weight: bold;
    }

    /* Cards Grid */
    .cards-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; margin-bottom: 16px; }

    .card {
        background: white;
        border-radius: var(--radius-lg);
        padding: clamp(14px, 2vw, 22px);
        box-shadow: var(--shadow-2);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    }

    .card-icon {
        width: clamp(64px, 10vw, 80px);
        height: clamp(64px, 10vw, 80px);
        margin: 0 auto 14px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: clamp(28px, 6vw, 40px);
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
    }

    .card-title {
        font-size: clamp(14px, 2.3vw, 18px);
        font-weight: bold;
        color: #333;
        text-align: center;
        margin-bottom: 10px;
    }

    .card-value {
        font-size: clamp(20px, 4.5vw, 28px);
        font-weight: bold;
        color: #667eea;
        text-align: center;
        margin-bottom: 8px;
    }

    .card-description {
        font-size: 14px;
        color: #666;
        text-align: center;
    }

    /* Achievements Section */
    .achievements-section { background: white; border-radius: var(--radius-lg); padding: clamp(14px, 2.2vw, 24px); box-shadow: var(--shadow-2); margin-bottom: 16px; }

    .section-title {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .achievements-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 12px; }

    .achievement {
        text-align: center;
        padding: 20px;
        background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        border-radius: 15px;
        transition: transform 0.3s ease;
        cursor: pointer;
    }

    .achievement:hover {
        transform: scale(1.05);
    }

    .achievement.locked {
        opacity: 0.5;
        filter: grayscale(100%);
    }

    .achievement-icon {
        font-size: 48px;
        margin-bottom: 10px;
    }

    .achievement-name {
        font-size: 14px;
        font-weight: bold;
        color: #333;
    }

    /* Activities Section */
    .activities-section { background: white; border-radius: var(--radius-lg); padding: clamp(14px, 2.2vw, 24px); box-shadow: var(--shadow-2); }

    .activity-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.3s ease;
    }

    .activity-item:hover {
        background: #f9f9f9;
        border-radius: 10px;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
    }

    .activity-details {
        flex: 1;
    }

    .activity-title {
        font-weight: bold;
        color: #333;
        margin-bottom: 5px;
    }

    .activity-time {
        font-size: 12px;
        color: #999;
    }

    .activity-points {
        font-size: 18px;
        font-weight: bold;
        color: #4caf50;
    }

    @media (max-width: 900px) { .stats { grid-template-columns: repeat(2, minmax(0,1fr)); } }
    @media (max-width: 480px) { .stats { grid-template-columns: 1fr; } .cards-grid { grid-template-columns: 1fr; } }
</style>

<div class="dashboard-container">
    <!-- Header -->
    <div class="header">
        <div class="user-info">
            <div class="user-avatar">👤</div>
            <div class="user-details">
                <h2 id="userName">สมชาย ใจดี</h2>
                <span class="user-level">🏆 Level 38</span>
            </div>
        </div>
        <div class="stats">
            <div class="stat-item">
                <span class="stat-value">⚡ 180/30</span>
                <span class="stat-label">พลังงาน</span>
            </div>
            <div class="stat-item">
                <span class="stat-value">💰 21.3K</span>
                <span class="stat-label">เหรียญ</span>
            </div>
            <div class="stat-item">
                <span class="stat-value">💎 1135</span>
                <span class="stat-label">เพชร</span>
            </div>
        </div>
    </div>

    <!-- Progress Section -->
    <div class="progress-section">
        <div class="progress-header">🎯 ดูดคาล 14</div>
        <div class="progress-bar-container">
            <div class="progress-bar" style="width: 20%;">
                20%
            </div>
        </div>
        <div class="level-info">ผ่านด่านแล้ว 1 / 5 ด่าน</div>
    </div>

    <!-- Main Cards -->
    <div class="cards-grid">
        <div class="card">
            <div class="card-icon">♻️</div>
            <div class="card-title">ขยะที่รีไซเคิล</div>
            <div class="card-value">24.5 กก.</div>
            <div class="card-description">เดือนนี้</div>
        </div>

        <div class="card">
            <div class="card-icon">🌱</div>
            <div class="card-title">คาร์บอนที่ลดได้</div>
            <div class="card-value">12.3 กก.</div>
            <div class="card-description">ช่วยโลกแล้ว!</div>
        </div>

        <div class="card">
            <div class="card-icon">💰</div>
            <div class="card-title">รายได้สะสม</div>
            <div class="card-value">1,850 บาท</div>
            <div class="card-description">ทั้งหมด</div>
        </div>

        <div class="card">
            <div class="card-icon">🎁</div>
            <div class="card-title">ของรางวัล</div>
            <div class="card-value">8 ชิ้น</div>
            <div class="card-description">รอรับ</div>
        </div>
    </div>

    <!-- Achievements Section -->
    <div class="achievements-section">
        <div class="section-title">
            <span>🏆</span>
            <span>ความสำเร็จ</span>
        </div>
        <div class="achievements-grid">
            <div class="achievement">
                <div class="achievement-icon">🏆</div>
                <div class="achievement-name">เริ่มต้น</div>
            </div>
            <div class="achievement">
                <div class="achievement-icon">⚡</div>
                <div class="achievement-name">มือใหม่</div>
            </div>
            <div class="achievement">
                <div class="achievement-icon">👑</div>
                <div class="achievement-name">ระดับ 10</div>
            </div>
            <div class="achievement">
                <div class="achievement-icon">💎</div>
                <div class="achievement-name">นักสะสม</div>
            </div>
            <div class="achievement locked">
                <div class="achievement-icon">🔒</div>
                <div class="achievement-name">ปริศนา</div>
            </div>
            <div class="achievement locked">
                <div class="achievement-icon">🔒</div>
                <div class="achievement-name">ล็อก</div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="activities-section">
        <div class="section-title">
            <span>📊</span>
            <span>กิจกรรมล่าสุด</span>
        </div>
        
        <div class="activity-item">
            <div class="activity-icon">♻️</div>
            <div class="activity-details">
                <div class="activity-title">รีไซเคิลขยะพลาสติก</div>
                <div class="activity-time">2 ชั่วโมงที่แล้ว</div>
            </div>
            <div class="activity-points">+50 แต้ม</div>
        </div>

        <div class="activity-item">
            <div class="activity-icon">🎁</div>
            <div class="activity-details">
                <div class="activity-title">รับของรางวัล</div>
                <div class="activity-time">5 ชั่วโมงที่แล้ว</div>
            </div>
            <div class="activity-points">-100 แต้ม</div>
        </div>

        <div class="activity-item">
            <div class="activity-icon">⬆️</div>
            <div class="activity-details">
                <div class="activity-title">เลื่อนระดับ 38</div>
                <div class="activity-time">เมื่อวาน</div>
            </div>
            <div class="activity-points">+200 แต้ม</div>
        </div>

        <div class="activity-item">
            <div class="activity-icon">🌱</div>
            <div class="activity-details">
                <div class="activity-title">ลดคาร์บอน 10 กก.</div>
                <div class="activity-time">2 วันที่แล้ว</div>
            </div>
            <div class="activity-points">+75 แต้ม</div>
        </div>
    </div>
</div>

<script>
    // Animation for progress bar
    window.addEventListener('load', () => {
        const progressBar = document.querySelector('.progress-bar');
        setTimeout(() => {
            progressBar.style.width = '20%';
        }, 500);
    });

    // Add click handlers for cards
    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 200);
        });
    });

    // Achievement click handler
    document.querySelectorAll('.achievement:not(.locked)').forEach(achievement => {
        achievement.addEventListener('click', function() {
            alert('คุณได้รับความสำเร็จ: ' + this.querySelector('.achievement-name').textContent);
        });
    });
</script>
