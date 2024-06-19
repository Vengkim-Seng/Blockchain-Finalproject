// JavaScript to toggle dropdown
document.querySelectorAll('.flex.flex-col.pl-0.mb-0 > li').forEach(item => {
    item.addEventListener('click', function() {
        const ul = item.querySelector('ul');
        // Close all dropdowns
        document.querySelectorAll('.flex.flex-col.pl-0.mb-0 > li ul').forEach(dropdown => {
            if (dropdown !== ul) {
                dropdown.classList.add('hidden');
            }
        });
        // Toggle the clicked dropdown
        if (ul) {
            ul.classList.toggle('hidden');
        }
    });
});