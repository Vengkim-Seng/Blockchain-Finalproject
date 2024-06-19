document.addEventListener('DOMContentLoaded', function () {
    const loginLink = document.querySelector('.relative');
    const dropdownMenu = document.querySelector('.absolute');

    loginLink.addEventListener('click', function () {
        dropdownMenu.classList.toggle('hidden');
    });

    // Close the dropdown menu when clicking outside of it
    document.addEventListener('click', function (event) {
        if (!loginLink.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.add('hidden');
        }
    });
});
