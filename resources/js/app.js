import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;
const $ = window.$;

import select2 from 'select2';
select2($);

import Swal from 'sweetalert2';
window.Swal = Swal;

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
  });
  window.Toast = Toast;

import flatpickr from "flatpickr";
import monthSelectPlugin from "flatpickr/dist/plugins/monthSelect/index.js";
import "flatpickr/dist/plugins/monthSelect/style.css";
window.flatpickr = flatpickr;
window.monthSelectPlugin = monthSelectPlugin;

import 'datatables.net-bs5';
import 'datatables.net-buttons-bs5';

import 'summernote/dist/summernote-lite.css';
import 'summernote/dist/summernote-lite.js';

import('./global.js');
import './expense-modal.js';
import { ExpenseManager } from './expense-manager';
window.ExpenseManager = ExpenseManager;
