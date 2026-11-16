import password from './components/password.js';
import search from './components/search.js';

document.addEventListener('alpine:init', () => {
    window.Alpine.data('form_password', password);
    window.Alpine.data('form_search', search);
});
