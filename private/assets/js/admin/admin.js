import naja from 'naja';
import '@tabler/core/dist/css/tabler.min.css';
import '@tabler/core/dist/js/tabler.min.js';
import { createFullDatagrids } from '@contributte/datagrid';
import { NajaAjax } from '@contributte/datagrid/assets/ajax';
import '@contributte/datagrid/assets/css/datagrid.css';
import '@/css/admin/admin-style.css';
import netteForms from 'nette-forms';

document.addEventListener('DOMContentLoaded', () => {
    naja.initialize({ history: false });
    createFullDatagrids(new NajaAjax(naja));
    netteForms.initOnLoad();
});

