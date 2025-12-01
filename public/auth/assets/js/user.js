// Optional: sidebar toggle for mobile
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('.user-sidebar');
    const toggleBtn = document.querySelector('#sidebarToggle');

    if(toggleBtn && sidebar){
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    }
});
