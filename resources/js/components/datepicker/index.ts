import { Datepicker } from 'vanillajs-datepicker';
import { DatepickerOptions } from 'vanillajs-datepicker/Datepicker';
// import id from 'vanillajs-datepicker/locales/id'
import '../../../scss/components/_datepickervanila.scss';

export default function initDatepicker({ format = 'yyyy-mm-dd', todayHighlight = true, autohide = true, ...config }: DatepickerOptions) {
    return {
        init: function () {
            new Datepicker(this.$refs.input, {
                todayHighlight,
                format,
                autohide,
                // language: 'id',
                ...config,
            });
        },
    };
}
