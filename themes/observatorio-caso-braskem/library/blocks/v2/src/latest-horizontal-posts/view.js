import slick from 'slick-carousel'

document.addEventListener("DOMContentLoaded", function () {
    const horizontalSliders = document.querySelectorAll('[data-slider="horizontal-posts"]')

    function initializeSlick(slider) {
        const slides = slider.querySelector('.latest-horizontal-posts-block__slides')
        const arrows = slider.querySelector('.latest-horizontal-posts-block__arrows')
        const dots = slider.querySelector('.latest-horizontal-posts-block__dots')
        const slidesToShow = slider.dataset.slidesToShow || 3

        // Mobile
        const arrowsMobile = slider.querySelector('.medium-only .latest-horizontal-posts-block__arrows')
        const dotsMobile = slider.querySelector('.medium-only .latest-horizontal-posts-block__dots')

        let slidesToShowMobile = 1
        if (slider.classList.contains('model-specials') || slider.classList.contains('model-most-read')) {
            slidesToShowMobile = 2
        } else if (slider.classList.contains('model-collection') || slider.classList.contains('model-albums')) {
            slidesToShowMobile = 1.5
        }

        let rowsToShow = 1

        if (slider.classList.contains('model-columnists')) {
            rowsToShow = 2
        }

        jQuery(slides).slick({
            appendArrows: arrows,
            appendDots: dots,
            rows: rowsToShow,
            dots: true,
            infinite: true,
            slidesToShow: parseInt(slidesToShow),
            centerMode: true,
            slidesToScroll: 1,
            cssEase: 'linear',
            responsive: [
                {
                    breakpoint: 783,
                    settings: {
                        appendArrows: arrows,
                        appendDots: dots,
                        slidesToShow: slidesToShowMobile,
                        centerMode: false
                    }
                }
            ]
        })

    }
    horizontalSliders.forEach(slider => {
        const options = {
            root: document.body,
            threshold: 0
        }
        const callback = (entries, observer) => {
            if (entries[0].isIntersecting) {
                observer.unobserve(slider)
                initializeSlick(slider)
            }
        }

        const observer = new IntersectionObserver(callback, options)
        observer.observe(slider)
    })
})
