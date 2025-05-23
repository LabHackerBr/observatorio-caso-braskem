document.addEventListener('DOMContentLoaded', function() {
    const orderBySelect = document.getElementById('organize_by');
    const hiddenOrderInput = document.getElementById('hidden_order_input');

    function updateHiddenOrder() {
        if (!orderBySelect || !hiddenOrderInput) {
            return;
        }

        const selectedValue = orderBySelect.value;
        let newOrderValue = 'DESC';

        if (selectedValue.includes('_')) {
            const parts = selectedValue.split('_');
            const direction = parts.pop().toUpperCase();
            if (direction === 'ASC' || direction === 'DESC') {
                newOrderValue = direction;
            }
        } else if (selectedValue === 'relevance') {
            newOrderValue = 'DESC';
        }

        hiddenOrderInput.value = newOrderValue;
    }

    if (orderBySelect) {
        orderBySelect.addEventListener('change', function() {
            updateHiddenOrder();
        });
        updateHiddenOrder();
    } else {
        console.log('Order by select not found.');
    }
});