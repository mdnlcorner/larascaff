<fieldset class="px-4 pt-2 pb-4 m-4 mb-3 border rounded-lg">
    <legend class="px-2 text-sm">Filter</legend>
    <div x-data="{
        init() 
        {
            const pageUrl = new URL(window['location'].href);
            $el.querySelectorAll('[data-filter]').forEach(el => {
                el.addEventListener('change', function () {
                    if (this.type == 'checkbox') {
                        if (this.checked) {
                            pageUrl.searchParams.set(this.name, '1');
                        } else {
                            pageUrl.searchParams.set(this.name, '0');
                        }
                    } else {
                        pageUrl.searchParams.set(this.name, this.value);
                    }
            
                    window['history'].pushState({}, '', pageUrl);
                    window['LaravelDataTables'][this.dataset.filter ?? '']?.ajax.url(pageUrl.href).load();
                });

                el.addEventListener('changeDate', function () {
                    pageUrl.searchParams.set(this.name, this.value);
                    window['history'].pushState({}, '', pageUrl);
                    window['LaravelDataTables'][this.dataset.filter ?? '']?.ajax.url(pageUrl.href).load();
                })
            })
        },
    }" 
        class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4"
    >
        {!! $filterTable !!}
    </div>
</fieldset>