var pswpElement = document.querySelectorAll('.pswp')[0];
var galleryElement = document.querySelectorAll('.portlet-content')[0];
var galleryStart = 0;
var options = { index: galleryStart };
var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
var KlikLinks = document.querySelectorAll(itemclass);
Array.from(KlikLinks).forEach(link => {
    link.addEventListener('click', function(event) {
    event.preventDefault();
    galleryStart = this.getAttribute('data-index') -1;     // align zero-based gallery index with 1-based picture index
    options = { index: galleryStart };
    gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
    gallery.init();
    });
});


