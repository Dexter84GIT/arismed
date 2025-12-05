const slider = () => {
    const goodsSlider = new Swiper('.goodsSlider', {
        spaceBetween: 20,
        slidesPerView: 4,
        loop: true,

        navigation: {
            nextEl: '.goods .swiper-button-next',
            prevEl: '.goods .swiper-button-prev',
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
    });
}

export default slider