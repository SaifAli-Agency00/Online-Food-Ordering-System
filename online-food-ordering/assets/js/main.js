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
