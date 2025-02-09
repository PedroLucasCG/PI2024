document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menuToggle');
    const navLinks = document.querySelector('.nav-links');
    const profilePic = document.getElementById('profilePic');
    const profileDropdown = document.getElementById('profileDropdown');

    // Menu hambúrguer toggle
    menuToggle.addEventListener('click', () => {
        navLinks.classList.toggle('active');
    });

    // Dropdown do perfil
    profilePic.addEventListener('click', () => {
        profileDropdown.classList.toggle('active');
    });
});
