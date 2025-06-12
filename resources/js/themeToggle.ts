const btnToggle: NodeListOf<HTMLDivElement> = document.querySelectorAll('[data-theme-mode]');
btnToggle.forEach(function (item) {
    if (item.dataset.themeMode == 'dark' && localStorage.getItem('color-theme') === 'dark') {
        item.classList.add('text-primary');
    } else if (item.dataset.themeMode == 'light' && localStorage.getItem('color-theme') === 'light') {
        item.classList.add('text-primary');
    } else if (
        (item.dataset.themeMode == 'system' && localStorage.getItem('color-theme') === 'system') ||
        (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
    ) {
        item.classList.add('text-primary');
    }

    item.addEventListener('click', function (e) {
        btnToggle.forEach((i) => i.classList.remove('text-primary'));
        this.classList.add('text-primary');

        const theme = this.dataset.themeMode;
        if (theme == 'dark') {
            localStorage.setItem('color-theme', 'dark');
            document.documentElement.classList.add('dark');
        } else if (theme == 'system') {
            localStorage.setItem('color-theme', 'system');
            document.documentElement.classList.add('dark');
        } else {
            localStorage.setItem('color-theme', 'light');
            document.documentElement.classList.remove('dark');
        }
    });
});
