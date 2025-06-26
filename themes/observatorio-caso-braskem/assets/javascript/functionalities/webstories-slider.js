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
                const wrapper = carousel.parentElement;
                const slides = carousel.querySelectorAll('.web-stories-list__story');

                const dots = document.createElement('div');
                dots.classList.add('dots');
                dots.setAttribute('role', 'tablist');
                wrapper.appendChild(dots);
                wrapper.setAttribute('data-pos', 0);

                const array = Array.prototype.slice.call(slides);

                function deactivateDots(){
                    dot = dots.querySelectorAll('.dot');
                    dot.forEach((dot) =>{
                        if(dot.classList.contains('active')){
                            dot.classList.remove('active');
                        }
                    })

                }

                for (var i = 0; i < (array.length - 4); i++){
                    const dot = document.createElement('button');
                    dot.classList.add('dot');
                    dot.setAttribute('role', 'tab');
                    dot.setAttribute('data-pos', parseInt(i));
                    dots.appendChild(dot);

                    const dotPos = dot.getAttribute('data-pos');

                    if(dotPos == 0){
                        dot.classList.add('active');
                    }

                    dot.addEventListener('click', (e)=>{
                        e.preventDefault();
                        deactivateDots();
                        setTimeout( function(){
                            dot.classList.add('active');
                        }, 10);
                        let currentPos = parseInt(wrapper.getAttribute('data-pos'));
		                let newPos = parseInt(dot.getAttribute('data-pos'));
                        let newDirection     = (newPos > currentPos ? 'right' : 'left');
		                let currentDirection = (newPos < currentPos ? 'right' : 'left');

                    })
                }
            }
        });
    }, 100);

});