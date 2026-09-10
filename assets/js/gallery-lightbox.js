/**
 * Custom Interactive Image Lightbox
 * Features: Zoom In, Zoom Out, Previous, Next, Close, Touch & Keyboard controls
 */

document.addEventListener('DOMContentLoaded', function() {
    const galleryItems = document.querySelectorAll('.lightbox-trigger');
    
    if (galleryItems.length === 0) return;

    // Create Lightbox Container if not present
    let lightbox = document.getElementById('customLightbox');
    if (!lightbox) {
        const lightboxHtml = `
            <div id="customLightbox" class="custom-lightbox" role="dialog" aria-modal="true" aria-label="Image Lightbox">
                <div class="lightbox-controls">
                    <button class="lightbox-btn" id="lbZoomIn" title="Zoom In"><i class="fas fa-search-plus"></i></button>
                    <button class="lightbox-btn" id="lbZoomOut" title="Zoom Out"><i class="fas fa-search-minus"></i></button>
                    <button class="lightbox-btn" id="lbResetZoom" title="Reset Zoom"><i class="fas fa-expand"></i></button>
                    <button class="lightbox-btn" id="lbClose" title="Close Lightbox (Esc)"><i class="fas fa-times"></i></button>
                </div>
                <button class="lightbox-nav-btn lightbox-prev" id="lbPrev" title="Previous Image (Left Arrow)"><i class="fas fa-chevron-left"></i></button>
                <button class="lightbox-nav-btn lightbox-next" id="lbNext" title="Next Image (Right Arrow)"><i class="fas fa-chevron-right"></i></button>
                <div class="lightbox-content">
                    <div class="lightbox-img-wrapper">
                        <img id="lbImage" src="" alt="Gallery Image">
                    </div>
                    <div id="lbCaption" class="lightbox-caption"></div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', lightboxHtml);
        lightbox = document.getElementById('customLightbox');
    }

    const lbImage = document.getElementById('lbImage');
    const lbCaption = document.getElementById('lbCaption');
    const lbPrev = document.getElementById('lbPrev');
    const lbNext = document.getElementById('lbNext');
    const lbClose = document.getElementById('lbClose');
    const lbZoomIn = document.getElementById('lbZoomIn');
    const lbZoomOut = document.getElementById('lbZoomOut');
    const lbResetZoom = document.getElementById('lbResetZoom');

    let currentIndex = 0;
    let currentZoom = 1;
    const imagesList = [];

    galleryItems.forEach((item, index) => {
        const imgSrc = item.getAttribute('data-image') || item.querySelector('img')?.src;
        const title = item.getAttribute('data-title') || item.querySelector('.gallery-title')?.innerText || '';
        imagesList.push({ src: imgSrc, title: title });

        item.addEventListener('click', function(e) {
            e.preventDefault();
            openLightbox(index);
        });
    });

    function openLightbox(index) {
        currentIndex = index;
        currentZoom = 1;
        updateLightbox();
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
        currentZoom = 1;
        if (lbImage) lbImage.style.transform = 'scale(1)';
    }

    function updateLightbox() {
        if (!imagesList[currentIndex]) return;
        lbImage.src = imagesList[currentIndex].src;
        lbCaption.innerText = imagesList[currentIndex].title;
        currentZoom = 1;
        lbImage.style.transform = 'scale(1)';
    }

    function nextImage() {
        currentIndex = (currentIndex + 1) % imagesList.length;
        updateLightbox();
    }

    function prevImage() {
        currentIndex = (currentIndex - 1 + imagesList.length) % imagesList.length;
        updateLightbox();
    }

    function zoomIn() {
        if (currentZoom < 2.5) {
            currentZoom += 0.25;
            lbImage.style.transform = `scale(${currentZoom})`;
        }
    }

    function zoomOut() {
        if (currentZoom > 0.75) {
            currentZoom -= 0.25;
            lbImage.style.transform = `scale(${currentZoom})`;
        }
    }

    function resetZoom() {
        currentZoom = 1;
        lbImage.style.transform = 'scale(1)';
    }

    // Event Listeners
    if (lbPrev) lbPrev.addEventListener('click', prevImage);
    if (lbNext) lbNext.addEventListener('click', nextImage);
    if (lbClose) lbClose.addEventListener('click', closeLightbox);
    if (lbZoomIn) lbZoomIn.addEventListener('click', zoomIn);
    if (lbZoomOut) lbZoomOut.addEventListener('click', zoomOut);
    if (lbResetZoom) lbResetZoom.addEventListener('click', resetZoom);

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (!lightbox.classList.contains('active')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowRight') nextImage();
        if (e.key === 'ArrowLeft') prevImage();
        if (e.key === '+' || e.key === '=') zoomIn();
        if (e.key === '-') zoomOut();
    });

    // Close when clicking background outside image
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox || e.target.classList.contains('lightbox-content')) {
            closeLightbox();
        }
    });
});
