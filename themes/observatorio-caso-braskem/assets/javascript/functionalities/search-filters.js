document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.search-filters__form');
    if (!form) return;

    function initializeCustomDropdown(wrapperElement) {
        if (!wrapperElement) return;

        const trigger = wrapperElement.querySelector('.custom-dropdown-trigger');
        const optionsList = wrapperElement.querySelector('.custom-dropdown-options');
        const mainHiddenInputName = wrapperElement.classList.contains('search-filters__filter-by') ? 'filter_by_taxonomy' : 'orderby';
        const mainHiddenInput = form.querySelector(`input[name="${mainHiddenInputName}"]`);

        const orderHiddenInput = form.querySelector('input[name="order"]');
        const triggerLabelSpan = trigger.querySelector('.custom-dropdown-label');
        const placeholderText = trigger.dataset.placeholder || triggerLabelSpan.textContent;

        if (!trigger || !optionsList || !mainHiddenInput || !triggerLabelSpan) {
            console.error('Elementos do dropdown customizado não encontrados em:', wrapperElement);
            return;
        }

        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            const isExpanded = this.getAttribute('aria-expanded') === 'true';

            document.querySelectorAll('.custom-dropdown-trigger[aria-expanded="true"]').forEach(otherTrigger => {
                if (otherTrigger !== this) {
                    otherTrigger.setAttribute('aria-expanded', 'false');
                    otherTrigger.nextElementSibling.style.display = 'none';
                }
            });

            this.setAttribute('aria-expanded', !isExpanded);
            optionsList.style.display = isExpanded ? 'none' : 'block';
        });

        optionsList.addEventListener('click', function(e) {
            if (e.target.tagName === 'LI') {
                const selectedLi = e.target;
                const value = selectedLi.getAttribute('data-value');
                const label = selectedLi.getAttribute('data-label') || selectedLi.textContent;

                triggerLabelSpan.textContent = label;
                mainHiddenInput.value = value;

                if (wrapperElement.classList.contains('search-filters__organize-by') && orderHiddenInput) {
                    let newOrderValue = 'DESC';
                    if (value.includes('_asc')) {
                        newOrderValue = 'ASC';
                    } else if (value.includes('_desc')) {
                        newOrderValue = 'DESC';
                    } else if (value === 'relevance') {
                        newOrderValue = 'DESC';
                    }
                    orderHiddenInput.value = newOrderValue;
                }

                optionsList.querySelectorAll('li').forEach(li => li.classList.remove('selected'));
                selectedLi.classList.add('selected');

                trigger.setAttribute('aria-expanded', 'false');
                optionsList.style.display = 'none';

                if (form) {
                    form.submit();
                }
            }
        });
    }

    document.querySelectorAll('.custom-dropdown-wrapper').forEach(wrapper => {
        initializeCustomDropdown(wrapper);
    });

    document.addEventListener('click', function() {
        document.querySelectorAll('.custom-dropdown-trigger[aria-expanded="true"]').forEach(openTrigger => {
            openTrigger.setAttribute('aria-expanded', 'false');
            if (openTrigger.nextElementSibling.classList.contains('custom-dropdown-options')) {
                 openTrigger.nextElementSibling.style.display = 'none';
            }
        });
    });
});