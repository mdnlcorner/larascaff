import Trix from 'trix';
Trix.config.blockAttributes.default.tagName = 'p'

Trix.config.blockAttributes.default.breakOnReturn = true

Trix.config.blockAttributes.h1 = {
    tagName: 'h1',
    terminal: true,
    breakOnReturn: true,
    group: false,
}

Trix.config.blockAttributes.h2 = {
    tagName: 'h2',
    terminal: true,
    breakOnReturn: true,
    group: false,
}

Trix.config.blockAttributes.h3 = {
    tagName: 'h3',
    terminal: true,
    breakOnReturn: true,
    group: false,
}

Trix.config.textAttributes.underline = {
    style: { textDecoration: 'underline' },
    inheritable: true,
    parser: (element: any) => {
        const style = window.getComputedStyle(element)

        return style.textDecoration.includes('underline')
    },
}

Trix.Block.prototype.breaksOnReturn = function () {
    const lastAttribute = this.getLastAttribute()
    const blockConfig =
        Trix.config.blockAttributes[lastAttribute ? lastAttribute : 'default']

    return blockConfig?.breakOnReturn ?? false
}

Trix.LineBreakInsertion.prototype.shouldInsertBlockBreak = function () {
    if (
        this.block.hasAttributes() &&
        this.block.isListItem() &&
        !this.block.isEmpty()
    ) {
        return this.startLocation.offset > 0
    } else {
        return !this.shouldBreakFormattedBlock() ? this.breaksOnReturn : false
    }
}

export default function initRichEditor(config: any) {
    return {
        init: async function () {
            this.$el.addEventListener('trix-attachment-add', (e: any) => {
                let attachment = e.attachment;
                if (attachment.file.size > (config.maxSize * 1000)) {
                    window['showToast']('error', 'Image size should\'t more than '+ config.maxSize)
                    attachment.remove()
                    return
                }
                
                let regex = new RegExp(/[^\s]+(.*?).(jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF)$/);
                if (regex.test(attachment.file.type) === false) {
                    window['showToast']('error', 'Only accept images')
                    attachment.remove()
                    return
                }
                const formData = new FormData();
                formData.append('path', config.path);
                formData.append('file', attachment.file);
                window['$'].ajax({
                    url: config.url,
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name=csrf_token]').attr('content')
                    },
                    data: formData,
                    success: function (res: any) {
                        attachment.setAttributes({
                            url: window.location.origin + '/storage/' + res.filename,
                            href: window.location.origin + '/storage/' + res.filename,
                        })
                    },
                    error: (err: any) => {
                        window['showToast']('error', err.responseJSON?.message ?? 'Something went wrong')
                        attachment.releaseFile()
                    }
                });
            })
        }
    }
}

window['initRichEditor'] = initRichEditor