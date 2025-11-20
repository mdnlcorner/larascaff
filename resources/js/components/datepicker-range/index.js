import Datepicker from 'vanillajs-datepicker/DateRangePicker';
import enAu from 'vanillajs-datepicker/locales/en-AU';
import id from 'vanillajs-datepicker/locales/id';
import '../../../scss/components/_datepickervanila.scss';

export default function initDatepickerRange({ format = 'yyyy-mm-dd', todayHighlight = true, autohide = true, ...config }) {
    return {
        init: function () {
            Object.assign(Datepicker.locales, id, enAu);
            new Datepicker(this.$refs.datepicker, config);
        },
    };
}
