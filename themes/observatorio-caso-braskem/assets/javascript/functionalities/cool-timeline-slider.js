window.addEventListener('load', () => {
    if (typeof Swiper === 'undefined') return;

    document.querySelectorAll('.ctl-horizontal-wrapper').forEach((wrapper) => {
        const container = wrapper.querySelector('.ctl-slider-container');
        if (!container) return;

        // destrói a instância do plugin (calculada com larguras erradas)
        if (container.swiper) container.swiper.destroy(true, true);

        new Swiper(container, {
            slidesPerView: 'auto',
            slidesPerGroup: 1,
            spaceBetween: 0,
            watchOverflow: true,
            navigation: {
                nextEl: wrapper.querySelector('.ctl-button-next'),
                prevEl: wrapper.querySelector('.ctl-button-prev'),
            },
        });
    });
});
