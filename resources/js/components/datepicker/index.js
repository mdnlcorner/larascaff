import { Datepicker } from 'vanillajs-datepicker';
import '../../../scss/components/_datepickervanila.scss';

export default function initDatepicker(config) {
    function matchLang(language) {
        if (config[language] == 'id') {
            return 'id';
        }

        return 'en';
    }

    return {
        init: function () {
            new Datepicker(this.$refs.input, {
                language: 'en',
                ...config,
            });
        },
    };
}
