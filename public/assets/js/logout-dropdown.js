function toggleDropdown() {
    var dropdownToggle = document.getElementById("dropdownToggle");
    var dropdownMenu = document.getElementById("dropdownMenu");
    dropdownMenu.classList.toggle("hidden");
    // Dynamically set the width of the dropdown to match the toggle button
    dropdownMenu.style.width = `${dropdownToggle.offsetWidth}px`;
}