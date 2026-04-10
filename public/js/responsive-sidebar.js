function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const overlay = document.querySelector('.sidebar-overlay');
    const toggleButton = document.querySelector('.mobile-toggle');
    
    if (!sidebar || !mainContent || !toggleButton) {
        console.error('Sidebar elements not found');
        return;
    }
    
    // Toggle sidebar state
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('expanded');
    overlay.classList.toggle('show');
    
    // Update toggle button icon
    const barsIcon = toggleButton.querySelector('.fa-bars');
    const timesIcon = toggleButton.querySelector('.fa-times');
    
    if (sidebar.classList.contains('collapsed')) {
        barsIcon.classList.remove('d-none');
        timesIcon.classList.add('d-none');
    } else {
        barsIcon.classList.add('d-none');
        timesIcon.classList.remove('d-none');
    }
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const toggleButton = document.querySelector('.mobile-toggle');
    
    if (window.innerWidth <= 768 && 
        !sidebar.contains(event.target) && 
        !toggleButton.contains(event.target) &&
        !sidebar.classList.contains('collapsed')) {
        toggleSidebar();
    }
});

// Handle window resize
window.addEventListener('resize', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const overlay = document.querySelector('.sidebar-overlay');
    const toggleButton = document.querySelector('.mobile-toggle');
    const barsIcon = toggleButton ? toggleButton.querySelector('.fa-bars') : null;
    const timesIcon = toggleButton ? toggleButton.querySelector('.fa-times') : null;
    
    if (window.innerWidth > 768) {
        // Desktop: Ensure sidebar is always expanded
        sidebar.classList.remove('collapsed');
        mainContent.classList.remove('expanded');
        overlay.classList.remove('show');
        
        // Hide both icons on desktop
        if (barsIcon) barsIcon.classList.add('d-none');
        if (timesIcon) timesIcon.classList.add('d-none');
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const overlay = document.querySelector('.sidebar-overlay');
    const toggleButton = document.querySelector('.mobile-toggle');
    
    if (!sidebar || !mainContent || !toggleButton) {
        console.error('Sidebar elements not found');
        return;
    }
    
    const barsIcon = toggleButton.querySelector('.fa-bars');
    const timesIcon = toggleButton.querySelector('.fa-times');
    
    // Set initial state based on screen size
    if (window.innerWidth <= 768) {
        // Mobile: Start collapsed
        sidebar.classList.add('collapsed');
        mainContent.classList.remove('expanded');
        overlay.classList.remove('show');
        
        // Show hamburger, hide close
        if (barsIcon) barsIcon.classList.remove('d-none');
        if (timesIcon) timesIcon.classList.add('d-none');
    } else {
        // Desktop: Start expanded
        sidebar.classList.remove('collapsed');
        mainContent.classList.remove('expanded');
        overlay.classList.remove('show');
        
        // Hide both icons
        if (barsIcon) barsIcon.classList.add('d-none');
        if (timesIcon) timesIcon.classList.add('d-none');
    }
});
