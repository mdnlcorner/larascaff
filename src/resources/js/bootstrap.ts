import './themeToggle'
import sidebar from './sidebar'
import { initModals } from './components/modal';
import searchContent from './search-content';
import { initActionModal, initActionByUrl, initFilter, initGlobalEvent, initNProgress} from './main'

initNProgress();
initActionModal();
initActionByUrl();
initFilter();
initGlobalEvent();
initModals()
searchContent()
sidebar.init()

const shadowHeader = document.querySelector('.shadow-header') as HTMLDivElement
window.addEventListener('scroll', function () {
    if (this.scrollY > 20) {
        shadowHeader.classList.remove('hidden')
    } else {
        shadowHeader.classList.add('hidden')
    }
})