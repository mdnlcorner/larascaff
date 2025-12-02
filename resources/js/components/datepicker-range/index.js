import { Datepicker, DateRangePicker } from 'vanillajs-datepicker';
import enAu from 'vanillajs-datepicker/locales/en-AU';
import id from 'vanillajs-datepicker/locales/id';

export default function initDatepickerRange(options = {}) {
    return {
        init: function () {
            Object.assign(Datepicker.locales, id, enAu);
            new DateRangePicker(this.$refs.datepicker, options);
        },
    };
}
