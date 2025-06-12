import Swiper from 'swiper';
import 'swiper/css';
import 'swiper/css/free-mode';
import 'swiper/css/navigation';

import { FreeMode, Navigation } from 'swiper/modules';

function initSwiper() {
    const _swipers: NodeListOf<HTMLDivElement> = document.querySelectorAll('.swiper');

    const pagination: NodeListOf<HTMLDivElement> = document.querySelectorAll('[data-swiper-page]');
    _swipers.forEach((swiper) => {
        const config = swiper?.querySelector('.swiper-config')?.innerHTML.trim();
        let swiperObj = new Swiper(swiper, {
            ...JSON.parse(config ?? ''),
            modules: [
                FreeMode,
                Navigation,
                // Pagination,
            ],
            grabCursor: true,
        });

        if (swiper.classList.contains('swiper-product')) {
            swiperObj.on('activeIndexChange', function (swipe) {
                pagination.forEach((paginate) => {
                    paginate.classList.remove('border');
                    if (paginate.dataset.swiperPage == swipe.activeIndex.toString()) {
                        paginate.classList.add('border');
                    }
                });
            });

            pagination.forEach((paginate) => {
                paginate.addEventListener('click', function (this: HTMLDivElement, e) {
                    swiperObj.slideTo(parseInt(this.dataset?.swiperPage ?? '0'));
                    pagination.forEach((paginate) => {
                        paginate.classList.remove('border');
                    });
                    this.classList.add('border');
                });
            });
        }
    });
}

initSwiper();
