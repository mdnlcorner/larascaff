import Modal from './components/modal';

export default function searchContent() {
    const modalSearchWrapper = document.querySelector('#header-search-modal');
    if (!modalSearchWrapper) {
        return;
    }
    const searchInput = document.querySelector('#searching');
    const list = document.querySelectorAll('#list-search a');
    let filteredList = [];

    let metaKey, K;
    const modal = new Modal(document.querySelector('#header-search-modal'));
    let active = -1;

    function keydownEventHandler(e) {
        // arrow handler
        if (e.key == 'ArrowDown' && active < filteredList.length - 1) {
            active++;
        } else if (e.key == 'ArrowUp' && active > 0) {
            active--;
        }

        filteredList.forEach((item) => item.removeAttribute('data-active'));

        if (e.key == 'ArrowDown' || e.key == 'ArrowUp') {
            filteredList[active]?.setAttribute('data-active', 'true');
        }

        if (e.key == 'Enter') {
            filteredList[active]?.click();
            modal.hide();
        }
    }

    modal.updateOnShow(() => {
        document.addEventListener('keydown', keydownEventHandler);
    });

    modal.updateOnHide(() => {
        active = -1;
        document.removeEventListener('keydown', keydownEventHandler);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key == 'Control') {
            metaKey = true;
        }
        if (e.metaKey) {
            metaKey = true;
        }
        if (e.key == 'k') {
            K = true;
        }

        if (metaKey && K) {
            modal.show();
            searchInput.focus();
            e.preventDefault();
        }
    });

    document.addEventListener('keyup', function (e) {
        metaKey = K = false;
    });

    document.querySelectorAll('.search-modal').forEach(function (item) {
        item.addEventListener('click', function () {
            modal.show();
            searchInput.focus();
        });
    });

    searchInput.addEventListener('keyup', function (e) {
        const val = e.target?.value;
        if (e.key != 'ArrowUp' && e.key != 'ArrowDown') {
            active = -1;
        }

        filteredList = [];

        list.forEach(function (item) {
            if (item.innerText.toLocaleLowerCase().indexOf(val) == -1) {
                item.classList.add('hidden');
            } else {
                item.classList.remove('hidden');
                filteredList.push(item);
            }
        });
    });
}
