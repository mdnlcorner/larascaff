import DataTable from 'datatables.net-dt';
import Accordion, { initAccordions } from '../components/accordion';
import Dropdown, { initDropdowns } from '../components/dropdown';
import Modal from '../components/modal';
import { AjaxAction, confirmation, handleCheckMenu } from '../main';
declare global {
    interface Window {
        DataTable: DataTable<any>;
        $: JQueryStatic;
        jQuery: JQueryStatic;
        Dropdown: typeof Dropdown;
        initDropdowns: typeof initDropdowns;
        LaravelDataTables: any;
        Modal: Modal;
        initModals: Function;
        modal: Modal;
        modalEl: HTMLElement;
        Accordion: typeof Accordion;
        initAccordions: typeof initAccordions;
        confirmation: typeof confirmation;
        datatableId: string;
        handleCheckMenu: typeof handleCheckMenu;
        AjaxAction: typeof AjaxAction;
    }
}
