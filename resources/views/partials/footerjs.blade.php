{{-- <script>
document.addEventListener('DOMContentLoaded', function() {
    // Uptime Counter
    let startTime = Date.now();
    const uptimeCounter = document.getElementById('uptime-counter');
    
    function updateUptime() {
        const currentTime = Date.now();
        const uptime = currentTime - startTime;
        
        const hours = Math.floor(uptime / (1000 * 60 * 60));
        const minutes = Math.floor((uptime % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((uptime % (1000 * 60)) / 1000);
        
        if (uptimeCounter) {
            uptimeCounter.textContent = 
                `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }
    }
    
    // Update uptime every second
    setInterval(updateUptime, 1000);
    updateUptime();
    
    // Online Users Simulation
    const onlineUsers = document.getElementById('online-users');
    if (onlineUsers) {
        // Simulate random online users between 1-5
        setInterval(() => {
            const randomUsers = Math.floor(Math.random() * 5) + 1;
            onlineUsers.textContent = randomUsers;
        }, 30000);
    }
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Scroll to Top Function
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Toggle Footer Collapse
function toggleFooter() {
    const footer = document.querySelector('.main-footer');
    const icon = document.getElementById('footer-toggle-icon');
    
    if (footer && icon) {
        footer.classList.toggle('collapsed');
        icon.classList.toggle('mdi-chevron-down');
        icon.classList.toggle('mdi-chevron-up');
    }
}

// Show scroll to top button when scrolling
window.addEventListener('scroll', function() {
    const scrollTopBtn = document.querySelector('.scroll-top');
    if (scrollTopBtn) {
        if (window.pageYOffset > 300) {
            scrollTopBtn.style.display = 'block';
        } else {
            scrollTopBtn.style.display = 'none';
        }
    }
});

// Performance monitoring
window.addEventListener('load', function() {
    const loadTime = document.getElementById('load-time');
    if (loadTime) {
        const loadTimeMs = performance.now();
        loadTime.textContent = `${loadTimeMs.toFixed(2)}ms`;
    }
});
</script> --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Add subtle hover animation to footer
   /*  const footer = document.querySelector('.main-footer');
    
    if (footer) {
        footer.addEventListener('mouseenter', function() {
            this.style.background = 'rgba(15, 23, 42, 0.98)';
        });
        
        footer.addEventListener('mouseleave', function() {
            this.style.background = 'rgba(15, 23, 42, 0.95)';
        });
    } */

    // Update online status indicator
    function updateOnlineStatus() {
        const statusIndicator = document.querySelector('.footer-stats i');
        if (statusIndicator && navigator.onLine) {
            statusIndicator.className = 'mdi mdi-circle text-success';
            statusIndicator.style.fontSize = '6px';
        } else if (statusIndicator) {
            statusIndicator.className = 'mdi mdi-circle text-danger';
            statusIndicator.style.fontSize = '6px';
        }
    }

    // Listen for online/offline events
    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);
    updateOnlineStatus();

    // Add click effect to contact links
    const contactLinks = document.querySelectorAll('.contact-link');
    contactLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Add subtle feedback
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });
});

// Simple version check (could be expanded with API call)
function checkVersion() {
    const currentVersion = '2.0';
    // This would typically make an API call to check for updates
    console.log(`Sistema versão ${currentVersion} - Em execução`);
}

// Initialize version check
checkVersion();
</script>