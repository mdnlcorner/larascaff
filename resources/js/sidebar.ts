import SimpleBar from 'simplebar';

const sidebar = {
    wrapper: document.querySelector('.wrapper') as HTMLDivElement,
    sidebar: document.querySelector('.sidebar') as HTMLDivElement,
    sidebarToggle: document.querySelector('.sidebar-toggle') as HTMLDivElement,
    content: document.querySelector('.sidebar-content') as HTMLDivElement,
    menuItems: document.querySelectorAll('.sidebar-menu') as NodeListOf<HTMLDivElement>,
    subMenu: document.querySelectorAll('.sidebar-submenu') as NodeListOf<HTMLDivElement>,
    timeout: 0,

    init() {
        this.initMenuItems();
        this.initSidebarToggle();
        this.initWrapper();
        this.initOverlay();
        this.handleWindowResize();
        this.initSidebarHover();
        this.initScrollBar();
    },

    initMenuItems() {
        if (this.menuItems.length) {
            this.menuItems.forEach((menuItem) => {
                const parent = menuItem.parentElement as HTMLDivElement;

                const submenu = parent.querySelector('.sidebar-submenu') as HTMLDivElement;
                const arrow = menuItem.querySelector('.sidebar-menu-arrow') as HTMLDivElement;

                if (submenu) {
                    const hasSub = submenu.querySelector('.sidebar-menu');
                    menuItem.addEventListener('click', (e) => {
                        e.preventDefault();

                        if (!hasSub) {
                            this.toggleHeight(submenu, arrow, submenu.scrollHeight, false);
                        } else {
                            this.toggleHeight(submenu, arrow, submenu.scrollHeight, true);
                        }
                    });

                    if (menuItem.classList.contains('active')) {
                        if (hasSub) {
                            this.toggleHeight(submenu, arrow, submenu.scrollHeight, true);
                        } else {
                            this.toggleHeight(submenu, arrow, submenu.scrollHeight, false);
                        }
                        arrow.classList.add('rotate');
                    }
                }
            });
        }
    },

    toggleHeight(element: HTMLDivElement, arrow: HTMLDivElement, height: number, hasSub = false) {
        if (this.timeout) {
            clearTimeout(this.timeout);
        }
        if (element.style.height === '0px' || element.style.height === '') {
            if (!element.parentElement?.parentElement?.classList.contains('sidebar-submenu')) {
                this.subMenu.forEach((item) => {
                    if (item.classList.contains('open')) {
                        item.classList.remove('open');
                        item.parentElement?.querySelector('.sidebar-menu-arrow')?.classList.remove('rotate');
                        item.style.height = '0px';
                    }
                });
            }

            element.classList.add('open');
            const multiSub = element.querySelector<HTMLDivElement>('.sidebar-submenu') as HTMLDivElement;
            if (multiSub) {
                element.style.height = `${element.scrollHeight}px`;
                setTimeout(() => {
                    element.style.height = 'unset';
                }, 100);
            } else {
                element.style.height = `${height}px`;
            }
            arrow.classList.add('rotate');

            if (hasSub && element.style.height != '0px') {
                this.timeout = setTimeout(() => {
                    element.style.height = 'unset';
                }, 200);
            }
        } else {
            arrow.classList.remove('rotate');
            element.classList.remove('open');
            if (element.style.height == 'unset') {
                element.style.height = `${height}px`;
            }
            this.timeout = setTimeout(() => {
                element.style.height = '0px';
                element.querySelectorAll<HTMLDivElement>('.sidebar-submenu').forEach((item) => {
                    item.style.height = '0px';
                });
            }, 10);
        }
    },

    initSidebarToggle() {
        if (this.sidebarToggle) {
            this.sidebarToggle.addEventListener('click', () => this.toggleSidebar());
        }
    },

    toggleSidebar() {
        const windowWidth = window.innerWidth;

        if (windowWidth < 1024) {
            this.sidebar.classList.toggle('expanded');
            document.querySelector('.sidebar-overlay')?.classList.toggle('active');
        } else {
            this.sidebar.classList.toggle('collapsed');
            this.wrapper.classList.toggle('expanded');
        }
    },

    initWrapper() {
        if (this.sidebar) {
            if (this.sidebar.classList.contains('collapsed')) {
                this.wrapper.classList.add('expanded');
            } else {
                this.wrapper.classList.remove('expanded');
            }
        }
    },

    initOverlay() {
        const overlay = document.createElement('div');
        overlay.classList.add('sidebar-overlay');
        document.body.appendChild(overlay);

        overlay.addEventListener('click', () => {
            this.sidebar.classList.remove('expanded');
            overlay.classList.remove('active');
        });
    },

    handleWindowResize() {
        if (this.sidebar) {
            window.addEventListener('resize', () => {
                if (window.innerWidth < 1024) {
                    this.sidebar.classList.remove('collapsed');
                    this.wrapper.classList.remove('expanded');
                } else {
                    this.sidebar.classList.remove('expanded');
                }
            });
        }
    },

    initSidebarHover() {
        if (this.sidebar) {
            this.sidebar.addEventListener('mouseenter', () => {
                if (window.innerWidth > 1024) {
                    this.sidebar.classList.add('hovered');
                }
            });

            this.sidebar.addEventListener('mouseleave', () => {
                if (window.innerWidth > 1024) {
                    this.sidebar.classList.remove('hovered');
                }
            });
        }
    },

    initScrollBar() {
        if (this.sidebar) {
            const simpleBar = new SimpleBar(this.content);
            const activeMenu = this.content.querySelector('.sidebar-menu.active') as HTMLDivElement;
            const activeSubmenu = this.content.querySelector('.sidebar-submenu-item.active');
            window.addEventListener('load', () => {
                if (activeSubmenu) {
                    activeSubmenu.scrollIntoView({ block: 'center', behavior: 'smooth' });
                }
            });
            const shadow = document.querySelector('.shadow-sidebar');

            simpleBar.getScrollElement()?.addEventListener('scroll', function (e) {
                const target = e.target as HTMLDivElement;
                if (target.scrollTop > 10) {
                    shadow?.classList.remove('hidden');
                } else {
                    shadow?.classList.add('hidden');
                }
            });
        }
    },
};

export default sidebar;
