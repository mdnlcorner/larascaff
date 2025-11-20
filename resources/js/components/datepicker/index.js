import { Datepicker } from 'vanillajs-datepicker';
import enAu from 'vanillajs-datepicker/locales/en-AU';
import id from 'vanillajs-datepicker/locales/id';
import '../../../scss/components/_datepickervanila.scss';

export default function initDatepicker(options = {}) {
    return {
        init: function () {
            Object.assign(Datepicker.locales, id, enAu);
            new Datepicker(this.$refs.input, options);
        },
    };
}
