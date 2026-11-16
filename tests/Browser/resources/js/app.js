import Alpine from './alpine.js';
import checkbox from './components/checkbox.js';
import date from './components/date.js';
import dateTime from './components/date-time.js';
import email from './components/email.js';
import file from './components/file.js';
import input from './components/input.js';
import password from './components/password.js';
import radio from './components/radio.js';
import search from './components/search.js';
import select from './components/select.js';
import textarea from './components/textarea.js';
import time from './components/time.js';

Alpine.data('form_checkbox', checkbox);
Alpine.data('form_date', date);
Alpine.data('form_date_time', dateTime);
Alpine.data('form_email', email);
Alpine.data('form_file', file);
Alpine.data('form_input', input);
Alpine.data('form_password', password);
Alpine.data('form_radio', radio);
Alpine.data('form_search', search);
Alpine.data('form_select', select);
Alpine.data('form_textarea', textarea);
Alpine.data('form_time', time);

Alpine.start();
