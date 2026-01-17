import naja from 'naja';
import '@/css/front/style.css';
import netteForms from 'nette-forms';

document.addEventListener('DOMContentLoaded', () => {
    naja.initialize({ history: false });
    netteForms.initOnLoad();
});