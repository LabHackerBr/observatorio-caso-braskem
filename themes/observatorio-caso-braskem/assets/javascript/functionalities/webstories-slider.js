import Glider from "glider-js";
import { waitUntil } from "../shared/wait";

document.addEventListener('DOMContentLoaded', function () {

    waitUntil(() => document.querySelector('.web-stories-list__inner-wrapper'), () => {
        const carousels = document.querySelectorAll('.web-stories-list__carousel');
        carousels.forEach(function (carousel) {
            function initializeGlider(carousel){
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

            }

            const options = {
                root: document.body,
                threshold: 0
            }
            const callback = (entries, observer) => {
                if (entries[0].isIntersecting) {
                    observer.unobserve(carousel)
                    initializeGlider(carousel)
                }
            }

            const observer = new IntersectionObserver(callback, options)
            observer.observe(carousel)
        });

    }, 50, 5000);

});