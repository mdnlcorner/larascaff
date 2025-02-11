import Datepicker, { DateRangePickerOptions } from 'vanillajs-datepicker/DateRangePicker';
// import id from 'vanillajs-datepicker/locales/id'
import '../../../scss/components/_datepickervanila.scss'

export default function initDatepickerRange({
format = 'yyyy-mm-dd',
todayHighlight = true,
autohide = true,
...config
}: DateRangePickerOptions) {
    return {
        init: function() {
            new Datepicker(this.$refs.datepicker, {
                todayHighlight,
                format,
                autohide,
                // language: 'id',
                ...config
            });
        }
    }
}