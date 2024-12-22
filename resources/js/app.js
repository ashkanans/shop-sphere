// Select the menu toggle button and the nav menu
const toggleButton = document.querySelector('.menu-toggle');
const navMenu = document.querySelector('.nav-menu');

// Add a click event listener to the toggle button
toggleButton.addEventListener('click', () => {
    navMenu.classList.toggle('active');
});
