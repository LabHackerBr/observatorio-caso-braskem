document.addEventListener('DOMContentLoaded', function() {
    // Scroll
    function throttle(func, delay) {
        let lastFunc;
        let lastRan;
        return function() {
            const context = this;
            const args = arguments;
            if (!lastRan) {
                func.apply(context, args);
                lastRan = Date.now();
            } else {
                clearTimeout(lastFunc);
                lastFunc = setTimeout(function() {
                    if ((Date.now() - lastRan) >= delay) {
                        func.apply(context, args);
                        lastRan = Date.now();
                    }
                }, delay - (Date.now() - lastRan));
            }
        }
    }

    const header = document.querySelector(".main-header")
    let isScrolled = false

    const detectScroll = throttle(function() {
        const scroll = window.scrollY || document.documentElement.scrollTop;
        const threshold = 100
        const returnPoint = 10
        if (scroll > threshold && !isScrolled) {
            header.classList.add("scrolled")
            isScrolled = true
        } else if (scroll < returnPoint && isScrolled) {
            header.classList.remove("scrolled")
            isScrolled = false
        }
    }, 200)

    document.addEventListener('wheel', detectScroll, { passive: true });
    document.addEventListener('touchmove', detectScroll, { passive: true });
    document.addEventListener('scroll', detectScroll, { passive: true });

    // Helper functions
    function searchFieldFocus(element) {
        let searchField = document.querySelector(element)
        if(searchField) {
            setTimeout(function() {
                searchField.focus()
            }, 100)
        }
    }
})