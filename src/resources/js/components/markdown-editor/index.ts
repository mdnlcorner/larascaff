import EasyMDE from 'easymde';
import 'easymde/dist/easymde.min.css'
import './mde.css'

export default function initMarkdown(config: any) {
    return {
        markdown: null,

        init: function () {
            this.markdown = new EasyMDE({
                element: this.$refs.markdown as HTMLElement,
                autosave: {
                    enabled: false,
                    uniqueId: 'rand' + Math.random()
                },
                autoRefresh: true,
                autoDownloadFontAwesome: false,
                spellChecker: false,
                imageAccept: 'image/png, image/jpeg, image/gif, image/avif',
                // imageMaxSize: config.maxSize,
                imageUploadFunction: function (file, onSuccess, onError) {
                    if (file.size > (config.maxSize * 1000)) {
                        window['showToast']('error', 'Image size should\'t more than '+ config.maxSize)
                        return
                    }
                    const formData = new FormData();
                    formData.append('path', config.path);
                    formData.append('file', file);
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
                            onSuccess(window.location.origin + '/storage/' + res.filename)
                        },
                        error: (err: any) => {
                            window['showToast']('error', err.responseJSON?.message ?? 'Something went wrong')
                        }
                    });
                },
                previewImagesInEditor: true,
                status: [
                    {
                        className: 'upload-image',
                        defaultValue: () => { },
                        onUpdate: () => { }
                    },
                ],
                initialValue: '',
                uploadImage: true,
                minHeight: '200px',
                toolbar: renderToolbar(),
                forceSync: true
            })

            function renderToolbar() {
                let toolbars: ("|" | EasyMDE.ToolbarIcon | EasyMDE.ToolbarDropdownIcon)[] = [
                    {
                        name: 'bold',
                        action: EasyMDE.toggleBold,
                        title: 'bold',
                        className: 'bold'
                    },
                    {
                        name: 'italic',
                        action: EasyMDE.toggleItalic,
                        title: 'italic',
                        className: 'italic',
                    },
                    {
                        name: 'strikethrough',
                        action: EasyMDE.toggleStrikethrough,
                        title: 'strikethrough',
                        className: 'strikethrough'
                    },
                    {
                        name: 'link',
                        action: EasyMDE.drawLink,
                        title: 'link',
                        className: 'link'
                    },
                    '|',
                    {
                        name: 'heading',
                        action: EasyMDE.toggleHeadingSmaller,
                        title: 'heading',
                        className: 'heading'
                    },
                    {
                        name: 'quote',
                        action: EasyMDE.toggleBlockquote,
                        title: 'quote',
                        className: 'quote'
                    },
                    {
                        name: 'unordered-list',
                        action: EasyMDE.toggleUnorderedList,
                        title: 'unordered-list',
                        className: 'unordered-list'
                    },
                    {
                        name: 'ordered-list',
                        action: EasyMDE.toggleOrderedList,
                        title: 'ordered-list',
                        className: 'ordered-list'
                    },
                    {
                        name: 'drawtable',
                        action: EasyMDE.drawTable,
                        title: 'drawtable',
                        className: 'drawtable'
                    },
                    '|',
                    {
                        name: 'code',
                        action: EasyMDE.toggleCodeBlock,
                        title: 'code',
                        className: 'code'
                    },
                    {
                        name: 'upload-image',
                        action: EasyMDE.drawUploadedImage,
                        title: 'upload-image',
                        className: 'upload-image'
                    },
                    '|',
                    {
                        name: 'undo',
                        action: EasyMDE.undo,
                        title: 'undo',
                        className: 'undo'
                    },
                    {
                        name: 'redo',
                        action: EasyMDE.redo,
                        title: 'redo',
                        className: 'redo'
                    },
                ];
                toolbars = toolbars.filter(item => {
                    if (typeof item == 'object' || item != '|') {
                        return config.toolbars.includes(item.name)
                    } else {
                        return true
                    }
                })
                return toolbars
            }

        }
    }
}