import { initModals } from './components/modal';
import { initActionByUrl, initActionModal, initGlobalEvent, initNProgress } from './main';
import searchContent from './search-content';
import sidebar from './sidebar';
import './themeToggle';

initNProgress();
initActionModal();
initActionByUrl();
initGlobalEvent();
initModals();
searchContent();
sidebar.init();

const shadowHeader = document.querySelector('.shadow-header') as HTMLDivElement;
window.addEventListener('scroll', function () {
    if (this.scrollY > 20) {
        shadowHeader.classList.remove('hidden');
    } else {
        shadowHeader.classList.add('hidden');
    }
});
