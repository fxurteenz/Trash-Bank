// User Dashboard JavaScript
console.log('User Dashboard Loaded!');

// Animation for progress bar
window.addEventListener('load', () => {
    const progressBar = document.querySelector('.progress-bar');
    if (progressBar) {
        setTimeout(() => {
            const currentProgress = progressBar.getAttribute('data-progress') || 20;
            progressBar.style.width = currentProgress + '%';
        }, 500);
    }
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
