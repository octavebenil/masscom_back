import './bootstrap';

import.meta.glob(["../images/**"]);

import './admin'

import {Notyf} from "notyf";
window.notify = new Notyf();

import 'bootstrap-datepicker'
