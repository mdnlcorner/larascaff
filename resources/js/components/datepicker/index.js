import { Datepicker } from 'vanillajs-datepicker';
import id from 'vanillajs-datepicker/locales/id';
import enAu from 'vanillajs-datepicker/locales/en-AU';
import '../../../scss/components/_datepickervanila.scss';

export default function initDatepicker(options = {}) {
    return {
        init: function () {
            Object.assign(Datepicker.locales, id, enAu)
            new Datepicker(this.$refs.input, options);
        },
    };
}
