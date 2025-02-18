document.addEventListener("DOMContentLoaded", function () {
    const togglePassword = document.querySelectorAll(".toggle-password");

    togglePassword.forEach(function (icon) {
        icon.addEventListener("click", function () {
            const passwordField = this.previousElementSibling;
            if (passwordField.type === "password") {
                passwordField.type = "text";
                this.textContent = "🙈"; // Change icon when visible
            } else {
                passwordField.type = "password";
                this.textContent = "👁️"; // Change back to eye icon
            }
        });
    });
});
