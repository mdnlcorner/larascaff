import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.min.css';
import * as FilePond from 'filepond';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImageCrop from 'filepond-plugin-image-crop';
import FilePondPluginImageEdit from 'filepond-plugin-image-edit';
import 'filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css';
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
import FilePondPluginImageResize from 'filepond-plugin-image-resize';
import FilePondPluginImageTransform from 'filepond-plugin-image-transform';
import 'filepond/dist/filepond.min.css';
import '../../../scss/components/_filepond.scss';
// import FilePondPluginMediaPreview from 'filepond-plugin-media-preview'
// import 'filepond-plugin-media-preview/dist/filepond-plugin-media-preview.css';

const blobToBase64 = (blob) => {
    const reader = new FileReader();
    reader.readAsDataURL(blob);

    return new Promise((resolve) => {
        reader.onloadend = () => {
            resolve(reader.result);
        };
    });
};


function initUploader({ files = [], ...config }) {
    return {
        FilePond: FilePond,
        init: async function () {
            const csrfToken = document.querySelector('meta[name=csrf_token]')?.getAttribute('content') ?? '';
            // Create a FilePond instance
            this.FilePond = FilePond;
            this.FilePond.registerPlugin(FilePondPluginFileValidateType);
            this.FilePond.registerPlugin(FilePondPluginImageExifOrientation);
            this.FilePond.registerPlugin(FilePondPluginImagePreview);
            this.FilePond.registerPlugin(FilePondPluginImageCrop);
            this.FilePond.registerPlugin(FilePondPluginImageResize);
            this.FilePond.registerPlugin(FilePondPluginImageTransform);
            this.FilePond.registerPlugin(FilePondPluginImageEdit);
            // this.FilePond.registerPlugin(FilePondPluginMediaPreview)

            const path = config.path;

            if (config.imageEditor) {
                config.imageEditEditor = {
                    open: async (imageToEdit) => {
                        if (config.imageEditor) {
                            let cropperWrapper = document.createElement('div');
                            cropperWrapper.className =
                                'fixed inset-0 z-[9999] w-full h-screen p-2 sm:p-10 md:p-20 cropper-wrapper dark:bg-dark-900/30';

                            let coppier = document.querySelector('.cropper-wrapper-coppier');
                            if (coppier) {
                                cropperWrapper.innerHTML = coppier.innerHTML;
                                document.body.append(cropperWrapper);
                            }

                            let imageEl = cropperWrapper.querySelector('.editor');
                            imageEl?.setAttribute('src', (await blobToBase64(imageToEdit)));

                            let acpectRatio = function () {
                                if (config.imageCropAspectRatio) {
                                    let ratio = config?.imageCropAspectRatio.split(':');
                                    return parseInt(ratio[0]) / parseInt(ratio[1]);
                                }
                                return 1 / 1;
                            };
                            let cropper = (window['cropper'] = new Cropper(imageEl, {
                                aspectRatio: acpectRatio(),
                                autoCropArea: 1,
                                center: true,
                                crop(ev) {},
                                modal: true,
                                cropBoxResizable: true,
                                guides: true,
                                highlight: true,
                                responsive: true,
                                toggleDragModeOnDblclick: true,
                                viewMode: 1,
                                wheelZoomRatio: 0.02,
                                ...config.cropperOptions,
                            }));

                            window['closeCropper'] = () => {
                                cropperWrapper.remove();
                                cropper.destroy();
                            };
                            window['cropImage'] = () => {
                                cropper.getCroppedCanvas({}).toBlob((croppedImage) => {
                                    pond.removeFile(
                                        pond.getFiles().find((uploaded) => {
                                            return imageToEdit.name === uploaded.filename;
                                        }),
                                    );

                                    if (croppedImage) {
                                        pond.addFile(
                                            new File([croppedImage], 'edited-' + imageToEdit.name, {
                                                type: croppedImage.type,
                                            }),
                                        ).then(() => {
                                            window['closeCropper']();
                                        });
                                    }
                                });
                            };
                        }
                    },
                };
            }

            console.log(config)
            const pond = this.FilePond.create(this.$refs.input, {
                // resize options
                allowImageResize: config.allowImageResize ?? false,
                imageResizeMode: config.imageResizeMode ?? 'cover',
                imageResizeTargetWidth: config.imageResizeTargetWidth,
                imageResizeTargetHeight: config.imageResizeTargetHeight,
                imageResizeUpscale: config.imageResizeUpscale ?? true,
                // crop options
                allowImageCrop: config.allowImageCrop ?? false,
                imageCropAspectRatio: config.imageCropAspectRatio ?? '1:1',
                // preview options
                allowImagePreview: config.allowImagePreview ?? true,
                imagePreviewHeight: config.imagePreviewHeight ?? 170,
                allowReorder: config?.allowReorder ?? true,
                allowImageTransform: true,
                // allowAudioPreview: true,
                // allowVideoPreview: true,
                allowRemove: config?.allowRemove ?? true,
                credits: false,
                server: {
                    process: {
                        url: config?.tempUploadUrl ?? '',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        onload: (res) => {
                            return JSON.parse(res)?.filename;
                        },
                    },
                    load: '/' + path,
                },
                files: [
                    ...files.map((item) => {
                        return {
                            source: item,
                            options: {
                                type: 'local',
                            },
                        };
                    }),
                ],
                ...config,
            });

            if (config.linkPreview) {
                pond.on('init', function () {
                    pond.element.querySelector(`input`).setAttribute('data-input-name', config.name);
                    pond.element.querySelectorAll('.filepond--file-info-main').forEach((el) => {
                        const filename = el.innerHTML;
                        el.innerHTML = `<a target="_blank" class="pointer-events-auto" href="/${path + filename}"> [<u class="mr-2">Preview</u>]</a> ${filename} `;
                    });
                });
            }

            var btnAction;
            var btnActionLabel;
            pond.on('processfilestart', function () {
                btnAction = pond.element.closest('form').querySelector('button[type=submit]');
                if (btnAction) {
                    btnActionLabel = btnAction.innerHTML;
                    btnAction.setAttribute('disabled', 'true');
                    btnAction.innerHTML = 'Loading...';
                }
            });
            pond.on('processfile', function () {
                if (btnAction) {
                    btnAction.removeAttribute('disabled');
                    btnAction.innerHTML = btnActionLabel;
                }
            });
        },
        registerPlugin: function (plugin) {
            if (plugin) {
                this.FilePond.registerPlugin(plugin);
            }
            return this;
        },
    };
}

window['initUploader'] = initUploader;
export default initUploader;
