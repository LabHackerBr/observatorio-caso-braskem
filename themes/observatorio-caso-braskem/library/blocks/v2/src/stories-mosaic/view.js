import Masonry from 'masonry-layout/dist/masonry.pkgd.min.js'
import 'dragscroll'

document.addEventListener("DOMContentLoaded", function() {
    // var mosaics = document.querySelectorAll('.inner-msnry')
    // mosaics.forEach(mosaic =>{
    //     var msnry = new Masonry( mosaic, {
    //         itemSelector: '.grid-item',
    //         columnWidth: 180,
    //         horizontalOrder: true,
    //         percentPosition: true
    //         // gutter: 5
    //     });
    //     msnry.layout();
    //     //console.log(msnry);

    // })

    const mosaics = document.querySelectorAll('.stories-mosaic-block');
    const modal = document.querySelectorAll('#stories-modal');
    let iframe = document.createElement('iframe');
    const closeButton = document.querySelectorAll('.close-modal');
    iframe.classList.add('stories-iframe');
    console.log(closeButton);

    mosaics.forEach(mosaic =>{
        console.log(mosaic);
        let links = mosaic.querySelectorAll('a');
        links.forEach(link =>{
            link.addEventListener('click', (e) => {
                e.preventDefault();
                openModal(link);
            })
        })
    })

    modal.forEach(modal =>{
        modal.appendChild(iframe);
    });

    closeButton.forEach(closeBtn =>{
        closeBtn.addEventListener('click', (e) => {
            if(closeBtn.classList.contains('open')){
                closeBtn.classList.remove('open');
            }
            if(iframe.classList.contains('open')){
                iframe.classList.remove('open');
                iframe.setAttribute('src', '');
            }
        })
    })

    function openModal(link){
        const url = link.getAttribute( 'href' );
        iframe.classList.add('open');
        iframe.setAttribute('src', url);
        iframe.setAttribute('allowFullscreen', 'true');
        closeButton.forEach(closeBtn =>{
            setTimeout(function(){
                closeBtn.classList.add('open');
            }, 1000);
        })
    }


})
