// User Dashboard JavaScript
console.log('User Dashboard Loaded!');

// Get member ID from URL or session (adjust based on your authentication system)
const getMemberId = () => {
    // You can get this from URL, cookie, or session storage
    // For now, we'll check if it's passed in the page
    return window.memberData?.memberId || 1; // Default to 1 for testing
};

// Fetch dashboard data from API
async function loadDashboardData() {
    try {
        const memberId = getMemberId();
        const response = await fetch(`/api/members/dashboard/${memberId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
            credentials: 'include' // Include cookies for authentication
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        
        if (result.success) {
            updateDashboard(result.data);
        } else {
            showNotification('ไม่สามารถโหลดข้อมูลได้: ' + result.message, 'error');
        }
    } catch (error) {
        console.error('Error loading dashboard:', error);
        showNotification('เกิดข้อผิดพลาดในการโหลดข้อมูล', 'error');
    }
}

// Update dashboard with API data
function updateDashboard(data) {
    const { member_info, statistics, recent_activities, badges, rewards_available } = data;
    
    // Update member info
    updateMemberInfo(member_info);
    
    // Update statistics cards
    updateStatistics(statistics);
    
    // Update recent activities
    updateActivities(recent_activities);
    
    // Update badges
    updateBadges(badges);
    
    // Update level progress
    updateLevelProgress(member_info.level_progress);
}

// Update member information
function updateMemberInfo(info) {
    const userNameElement = document.querySelector('.user-name');
    const userDetailsElement = document.querySelector('.user-details');
    const pointsElement = document.querySelector('.points-value');
    
    if (userNameElement) userNameElement.textContent = info.name;
    if (userDetailsElement) userDetailsElement.textContent = `${info.faculty || 'ไม่ระบุคณะ'} • Level ${info.level}`;
    if (pointsElement) pointsElement.textContent = info.points.toLocaleString();
}

// Update statistics cards
function updateStatistics(stats) {
    const cards = document.querySelectorAll('.card');
    
    cards.forEach(card => {
        const title = card.querySelector('.card-title')?.textContent;
        const valueElement = card.querySelector('.card-value');
        
        if (!valueElement) return;
        
        if (title?.includes('น้ำหนักขยะ')) {
            valueElement.textContent = `${stats.total_weight.toFixed(2)} กก.`;
        } else if (title?.includes('คาร์บอน')) {
            valueElement.textContent = `${stats.carbon_saved.toFixed(2)} กก.`;
        } else if (title?.includes('ธุรกรรม')) {
            valueElement.textContent = stats.total_transactions.toString();
        }
    });
}

// Update recent activities
function updateActivities(activities) {
    const activitiesContainer = document.querySelector('.activities-list');
    
    if (!activitiesContainer || activities.length === 0) return;
    
    activitiesContainer.innerHTML = activities.map(activity => {
        const icon = activity.activity_type === 'waste' ? '♻️' : '🎁';
        const pointsClass = activity.points >= 0 ? '' : 'text-red-500';
        const pointsText = activity.points >= 0 ? `+${activity.points}` : activity.points;
        const timeAgo = formatTimeAgo(activity.activity_date);
        
        return `
            <div class="activity-item">
                <div class="activity-icon">${icon}</div>
                <div class="activity-details">
                    <div class="activity-title">${activity.description || 'กิจกรรม'}</div>
                    <div class="activity-time">${timeAgo}</div>
                </div>
                <div class="activity-points ${pointsClass}">${pointsText} แต้ม</div>
            </div>
        `;
    }).join('');
}

// Update badges display
function updateBadges(badges) {
    const badgesContainer = document.querySelector('.achievements-grid');
    
    if (!badgesContainer) return;
    
    // Show earned badges
    if (badges.length > 0) {
        const badgesHTML = badges.slice(0, 6).map(badge => `
            <div class="achievement">
                <div class="achievement-icon">${badge.badge_image || '🏆'}</div>
                <div class="achievement-name">${badge.badge_name}</div>
            </div>
        `).join('');
        
        badgesContainer.innerHTML = badgesHTML;
    }
}

// Update level progress bar
function updateLevelProgress(progress) {
    const progressBar = document.querySelector('.progress-bar');
    if (progressBar) {
        setTimeout(() => {
            progressBar.style.width = `${progress}%`;
            progressBar.setAttribute('data-progress', progress);
        }, 500);
    }
}

// Format time ago
function formatTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);
    
    if (diffMins < 1) return 'เมื่อสักครู่';
    if (diffMins < 60) return `${diffMins} นาทีที่แล้ว`;
    if (diffHours < 24) return `${diffHours} ชั่วโมงที่แล้ว`;
    if (diffDays === 1) return 'เมื่อวาน';
    if (diffDays < 7) return `${diffDays} วันที่แล้ว`;
    
    return date.toLocaleDateString('th-TH');
}

// Load data when page loads
window.addEventListener('load', () => {
    loadDashboardData();
});

// Add click handlers for cards with animation
document.querySelectorAll('.card').forEach(card => {
    card.addEventListener('click', function() {
        // Scale animation
        this.style.transform = 'scale(0.95)';
        setTimeout(() => {
            this.style.transform = '';
        }, 200);
        
        // Get card title for action
        const cardTitle = this.querySelector('.card-title').textContent;
        console.log('Card clicked:', cardTitle);
        
        // You can add routing or modal logic here
        // Example: showDetailModal(cardTitle);
    });
});

// Achievement click handler
document.querySelectorAll('.achievement:not(.locked)').forEach(achievement => {
    achievement.addEventListener('click', function() {
        const achievementName = this.querySelector('.achievement-name').textContent;
        showNotification('ความสำเร็จ: ' + achievementName, 'success');
    });
});

// Locked achievement click handler
document.querySelectorAll('.achievement.locked').forEach(achievement => {
    achievement.addEventListener('click', function() {
        showNotification('ความสำเร็จนี้ยังล็อกอยู่ ทำภารกิจให้สำเร็จเพื่อปลดล็อก!', 'info');
    });
});

// Activity item hover effect
document.querySelectorAll('.activity-item').forEach(item => {
    item.addEventListener('mouseenter', function() {
        this.style.transform = 'translateX(5px)';
    });
    
    item.addEventListener('mouseleave', function() {
        this.style.transform = '';
    });
});

// Notification system
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Styling
    Object.assign(notification.style, {
        position: 'fixed',
        top: '20px',
        right: '20px',
        background: type === 'success' ? '#4caf50' : type === 'error' ? '#f44336' : '#2196F3',
        color: 'white',
        padding: '15px 25px',
        borderRadius: '10px',
        boxShadow: '0 4px 15px rgba(0,0,0,0.2)',
        zIndex: '10000',
        animation: 'slideIn 0.3s ease',
        maxWidth: '300px',
        fontWeight: 'bold'
    });
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    
    .activity-item {
        transition: transform 0.3s ease;
    }
`;
document.head.appendChild(style);

// Stats counter animation
function animateCounter(element, target, duration = 1000) {
    const start = 0;
    const increment = target / (duration / 16);
    let current = start;
    
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        element.textContent = Math.floor(current);
    }, 16);
}

// Initialize counter animations on load
window.addEventListener('load', () => {
    const statValues = document.querySelectorAll('.stat-value');
    statValues.forEach(stat => {
        const text = stat.textContent;
        const numbers = text.match(/[\d,]+/);
        if (numbers) {
            const value = parseInt(numbers[0].replace(/,/g, ''));
            // Extract just the number part
            const prefix = text.split(numbers[0])[0];
            const suffix = text.split(numbers[0])[1];
            
            stat.textContent = prefix + '0' + suffix;
            setTimeout(() => {
                let current = 0;
                const increment = value / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= value) {
                        current = value;
                        clearInterval(timer);
                    }
                    stat.textContent = prefix + Math.floor(current).toLocaleString() + suffix;
                }, 20);
            }, 500);
        }
    });
});

// Export functions for external use
window.UserDashboard = {
    showNotification,
    animateCounter
};
