import $ from 'jquery'
import DataTable from 'datatables.net-dt'
// import 'datatables.net-dt/css/jquery.dataTables.min.css'
import 'datatables.net-responsive-dt';
import 'datatables.net-responsive-dt/css/responsive.dataTables.min.css';
import '../../../scss/components/_datatable.scss'

if (typeof window !== 'undefined') {
    window.$ = window.jQuery = $
    window.DataTable = DataTable
}

export default DataTable