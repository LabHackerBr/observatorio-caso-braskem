import 'dragscroll'

document.addEventListener("DOMContentLoaded", function() {
    const mosaics = document.querySelectorAll('.stories-mosaic-block');
    const modal = document.querySelectorAll('#stories-modal');

    let iframe = document.createElement('iframe');
    iframe.classList.add('stories-iframe');

    const closeButton = document.querySelectorAll('.close-modal');

    const previousPreview = document.querySelectorAll('.previous-story');
    const nextPreview = document.querySelectorAll('.next-story');

    modal.forEach(modal =>{
        modal.appendChild(iframe);
    });

    mosaics.forEach(mosaic =>{
        let links = mosaic.querySelectorAll('a');
        links.forEach(link =>{

            link.addEventListener('click', (e) => {
                e.preventDefault();
                openModal(link);
            })
        })

        closeButton.forEach(closeBtn =>{
            closeBtn.addEventListener('click', (e) => {
                if(closeBtn.classList.contains('open')){
                    closeBtn.classList.remove('open');
                }
                if(iframe.classList.contains('open')){
                    iframe.classList.remove('open');
                    iframe.setAttribute('src', 'about:blank');
                }
                modal.forEach(modal =>{
                    if(modal.classList.contains('open')){
                        modal.classList.remove('open');
                    }
                })
            })
        })

        function openModal(link){
            const url = link.getAttribute( 'href' );

            iframe.classList.add('open');
            iframe.setAttribute('src', url);
            iframe.setAttribute('allowFullscreen', 'true');

            const array = Array.prototype.slice.call(links);
            const index = array.indexOf(link);
            let length = array.length;
            let previewLink = '';

            if(index > 0){
                let i = index - 1;
                previewLink = array[i];
                generatePreview(previewLink, previousPreview);
            }
            if(index < (length - 1)){
                let i = index + 1;
                console.log(i);
                previewLink = array[i];
                generatePreview(previewLink, nextPreview);
            }

            closeButton.forEach(closeBtn =>{
                setTimeout(function(){
                    closeBtn.classList.add('open');
                }, 1000);
            })

            modal.forEach(modal =>{
                setTimeout(function(){
                    modal.classList.add('open');
                }, 500);
            })

        }

        function generatePreview(link, element){

            const image = link.querySelectorAll('img');
            const src = image[0].getAttribute('src');
            const height = image[0].getBoundingClientRect().height;
            const width = image[0].getBoundingClientRect().width;

            const preview = document.createElement('img')
            preview.setAttribute('src', src);
            preview.setAttribute('height', height);
            preview.setAttribute('width', width);

            element.forEach( elem =>{
                if(elem.hasChildNodes()){
                    elem.removeChild(elem.childNodes[0]);
                    setTimeout(function(){
                        elem.appendChild(preview);
                    }, 1100);
                }
                else{
                    setTimeout(function(){
                        elem.appendChild(preview);
                    }, 1100);
                }
                preview.addEventListener('click', (e) => {
                    e.preventDefault();
                    openModal(link);
                })

            });

        }

    })

})
