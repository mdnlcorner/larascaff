<fieldset class="px-4 pt-2 pb-4 m-4 mb-3 border rounded-lg">
    <legend class="px-2 text-sm">Filter</legend>
    <div x-data="{
        init() 
        {
            const reloader = (pageUrl, filter) => {
                window['history'].pushState({}, '', pageUrl);
                window['LaravelDataTables'][filter ?? '']?.ajax.url(pageUrl.href).load();
            }

            $el.querySelectorAll('[data-filter]').forEach(el => {
                el.addEventListener('change', function () {
                    const pageUrl = new URL(window.location.href);

                    if (this.type == 'checkbox') {
                        if (this.checked) {
                            pageUrl.searchParams.set(this.name, '1');
                        } else {
                            pageUrl.searchParams.set(this.name, '0');
                        }
                    } else {
                        pageUrl.searchParams.set(this.name, this.value);
                    }
            
                    reloader(pageUrl, this.dataset.filter)
                });

                el.addEventListener('changeDate', function () {
                    const pageUrl = new URL(window.location.href);

                    pageUrl.searchParams.set(this.name, this.value);

                    reloader(pageUrl, this.dataset.filter)
                })
            })
        },
    }" 
        class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4"
    >
        {!! $filterTable !!}
    </div>
</fieldset>