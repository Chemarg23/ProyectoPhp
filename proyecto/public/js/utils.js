document.addEventListener("DOMContentLoaded", function () {
    
    function adjustFormPosition() {
        const form = document.getElementById("searchForm");

        let errorHeight = 0;
        const errorElements = document.querySelectorAll(".alert");
        errorElements.forEach(function (error) {
            errorHeight += 13;
        });
        form.style.top = `calc(70% + ${errorHeight}px)`;
    }
    adjustFormPosition();    
   
});
