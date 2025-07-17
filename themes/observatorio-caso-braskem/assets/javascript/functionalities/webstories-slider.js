import Glider from "glider-js";

document.addEventListener('DOMContentLoaded', function () {
    const carousels = document.querySelectorAll('.web-stories-list__carousel');
    setTimeout( function () {
        carousels.forEach(function (carousel) {
            if(carousel.classList.contains('glider')){
                const wrapper = carousel.parentElement;
                const dots = document.createElement('div');
                dots.classList.add('dots');
                wrapper.appendChild(dots);
                const glider = carousel._glider
                const options = {
                    slidesToShow: 1.4,
                    slidesToScroll: 1,
                    dots: dots,
                    draggable: true,
                    responsive: [
                        {
                            breakpoint: 600,
                            settings: {
                                slidesToShow: 4.6,

                            },
                        },
                    ],
                }
                glider.setOption(options);
                glider.refresh(true);
            }
            else{
                new Glider(carousel);
                //console.log(carousel);
                const wrapper = carousel.parentElement;
                const dots = document.createElement('div');
                dots.classList.add('dots');
                wrapper.appendChild(dots);
                const glider = carousel._glider
                const options = {
                    slidesToShow: 1.4,
                    slidesToScroll: 1,
                    dots: dots,
                    draggable: true,
                    responsive: [
                        {
                            breakpoint: 600,
                            settings: {
                                slidesToShow: 4.6,

                            },
                        },
                    ],
                }
                glider.setOption(options);
                glider.refresh(true);
            }
        });
    }, 100);

});