document.addEventListener("DOMContentLoaded", function() {
    const share = document.querySelector(".share-links");
    const footer = document.querySelector("footer");

    if (!share || !footer) return;

    function stickShare() {
      const shareRect = share.getBoundingClientRect();
      const footerRect = footer.getBoundingClientRect();
      const windowHeight = window.innerHeight;

      const overlap = windowHeight - footerRect.top;

      if (overlap > 0) {
        share.style.position = "absolute";
        share.style.bottom = (document.body.offsetHeight - footer.offsetTop + 20) + "px";
      } else {
        share.style.position = "fixed";
        share.style.bottom = "20px";
      }
    }

    window.addEventListener("scroll", stickShare);
    window.addEventListener("resize", stickShare);
    stickShare();
});