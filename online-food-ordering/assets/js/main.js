// Ask for confirmation before deleting food records.
document.querySelectorAll('.delete-link').forEach(function (link) {
    link.addEventListener('click', function (event) {
        if (!confirm('Are you sure you want to delete this item?')) {
            event.preventDefault();
        }
    });
});

// Ask for confirmation before removing an item from the cart.
document.querySelectorAll('.remove-link').forEach(function (link) {
    link.addEventListener('click', function (event) {
        if (!confirm('Remove this item from your cart?')) {
            event.preventDefault();
        }
    });
});

// Beginner-friendly real-time validation hints for registration form.
var registerForm = document.getElementById('registerForm');
if (registerForm) {
    registerForm.addEventListener('input', function () {
        var password = registerForm.querySelector('input[name="password"]');
        var confirmPassword = registerForm.querySelector('input[name="confirm_password"]');

        if (password && confirmPassword && confirmPassword.value !== '') {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Passwords do not match');
            } else {
                confirmPassword.setCustomValidity('');
            }
        }
    });
}
