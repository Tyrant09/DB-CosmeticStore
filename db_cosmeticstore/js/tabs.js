document.addEventListener("DOMContentLoaded", function() {
    const tabs = document.querySelectorAll(".tabs ul li");
    const tabContents = document.querySelectorAll(".tab-content");

    tabs.forEach(tab => {
        tab.addEventListener("click", function() {
            const target = this.getAttribute("data-tab");

            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove("active"));
            // Add active class to the clicked tab
            this.classList.add("active");

            // Hide all tab contents
            tabContents.forEach(content => content.classList.remove("active"));
            // Show the content corresponding to the clicked tab
            document.getElementById(target).classList.add("active");
        });
    });
});
