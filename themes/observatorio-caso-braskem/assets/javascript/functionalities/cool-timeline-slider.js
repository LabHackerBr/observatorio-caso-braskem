window.addEventListener('load', () => {
    if (typeof Swiper === 'undefined') return;

    document.querySelectorAll('.cool-timeline-wrapper.ctl-horizontal-wrapper').forEach((wrapper) => {
        const container = wrapper.querySelector('.ctl-slider-container');
        const swiper = container && container.swiper;
        if (!swiper) return;

        // mantém tudo do plugin (autoHeight, line-filling, navegação, RTL);
        // apenas troca o cálculo horizontal para respeitar a largura real do CSS
        // (385px / 100%), corrigindo o corte dos últimos slides
        swiper.params.breakpoints = {};
        swiper.params.slidesPerView = 'auto';
        swiper.update();

        // replica o autoHeight customizado do plugin (altura do container = maior
        // outerHeight(true) dos slides visíveis), que quebra com slidesPerView 'auto'
        // (slice com NaN no código do plugin)
        const applyHeight = () => {
            const visible = (swiper.visibleSlides && swiper.visibleSlides.length)
                ? swiper.visibleSlides
                : Array.from(swiper.slides).slice(swiper.activeIndex, swiper.activeIndex + 2);

            let height = 0;
            visible.forEach((slide) => {
                const styles = getComputedStyle(slide);
                height = Math.max(height, slide.offsetHeight + parseFloat(styles.marginTop) + parseFloat(styles.marginBottom));
            });

            if (height > 0) {
                container.style.height = height + 'px';
            }
        };

        applyHeight();
        swiper.on('slideChangeTransitionEnd', applyHeight);

        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(applyHeight, 200);
        });
    });
});
