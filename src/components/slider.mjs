const slider = () => {
    const goodsSlider = new Swiper('.goodsSlider', {
        spaceBetween: 20,
        slidesPerView: 4,
        loop: true,

        navigation: {
            nextEl: '.goods .swiper-button-next',
            prevEl: '.goods .swiper-button-prev',
        },

        breakpoints: {
            0: {
                enabled: false,
                slidesPerView: 'auto',
                spaceBetween: 8,
            },
            768: {
                enabled: true,
                slidesPerView: 4,
                spaceBetween: 20,
            },
        },
    });
    const neswSlider = new Swiper('.newsSlider', {
        spaceBetween: 30,
        slidesPerView: 3,
        loop: true,

        navigation: {
            nextEl: '.mainNews .swiper-button-next',
            prevEl: '.mainNews .swiper-button-prev',
        },

        breakpoints: {
            0: {
                enabled: false,
                slidesPerView: 'auto',
                spaceBetween: 8,
            },
            768: {
                enabled: true,
                slidesPerView: 3,
                spaceBetween: 30,
            },
        },
    });
    const productSlider = () => {
        const mainWrapper = document.querySelector('.cardMainSlider .swiper-wrapper');
        const thumbsWrapper = document.querySelector('.cardThumbsSlider .swiper-wrapper');

        if (!mainWrapper || !thumbsWrapper) return;

        mainWrapper.innerHTML = '';

        thumbsWrapper.querySelectorAll('.swiper-slide').forEach((thumbSlide) => {
            const clone = thumbSlide.cloneNode(true);
            const img = clone.querySelector('img');
            if (img && img.dataset.full) {
                img.src = img.dataset.full;
            }
            mainWrapper.appendChild(clone);
        });

        const thumbs = new Swiper('.cardThumbsSlider', {
            slidesPerView: 4,
            spaceBetween: 30,
            freeMode: true,
            watchSlidesProgress: true,
            watchSlidesVisibility: true,
            loop: true
        });

        const main = new Swiper('.cardMainSlider', {
            slidesPerView: 1,
            spaceBetween: 0,
            thumbs: {
                swiper: thumbs,
            },
            loop: true
        });
    };

    productSlider();
}

export default slider