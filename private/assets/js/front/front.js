import naja from 'naja';
import '@/css/front/style.css';

document.addEventListener('DOMContentLoaded', () => {
    naja.initialize({ history: false });
    console.log('Frontend initialized with Naja');
});