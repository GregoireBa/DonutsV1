if (window.matchMedia("(min-width: 768px)").matches) {
    var swiper = new Swiper(".mySwiper", {
    slidesPerView: 4,
    spaceBetween: 20,
    pagination: {
    el: ".swiper-pagination",
    clickable: true
    }
    });
    } else {
    var swiper = new Swiper(".mySwiper", {
    slidesPerView: 1,
    spaceBetween: 20,
    pagination: {
    el: ".swiper-pagination",
    clickable: true
    }
    });
    }