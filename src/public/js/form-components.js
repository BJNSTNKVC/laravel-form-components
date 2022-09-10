<!-- Form Interactivity Script -->

// Get all invalid inputs.
let inputs = document.querySelectorAll('label[invalid] > *[interactive="true"]');

// Assign an Event Listeners to each Input.
for (let i = 0; i < inputs.length; i++) {
    inputs[i].addEventListener('input', validate);
}

/**
 * Validate the field.
 */
function validate() {
    let label = this.closest('label');
    let title = label.querySelector('.form-group__title');
    let error = label.querySelector('.form-group__error');

    if (this.value === '' || ((this.type === 'checkbox' || this.type === 'radio') && ! this.checked)) {
        label.setAttribute('invalid', '');

        if (this.getAttribute('invalidated-title') === 'true') {
            title.classList.add('fc-is-invalid');
        }

        error.style.display = 'block';

        return;
    }

    label.removeAttribute('invalid');
    title.classList.remove('fc-is-invalid');
    error.style.display = 'none';
}

<!-- Password Visibility Script -->

let passwords = document.querySelectorAll('input[type=password]');

for (let i = 0; i < passwords.length; i++) {
    let label = passwords[i].closest('label');

    let elements = {
        label   : label,
        password: label.querySelector('input'),
        showBtn : label.querySelector('.form-group__icon--show'),
        hideBtn : label.querySelector('.form-group__icon--hide'),
    };

    elements.showBtn?.addEventListener('click', showPassword.bind(elements, true));
    elements.hideBtn?.addEventListener('click', showPassword.bind(elements, false));
}

/**
 * Show/Hide typed password.
 *
 * @param { Boolean } state Password visibility state.
 */
function showPassword(state) {
    this.showBtn.style.display = state ? 'none' : 'block';
    this.hideBtn.style.display = state ? 'block' : 'none ';
    this.password.type         = state ? 'text' : 'password';
}

<!-- Search Clearing Script -->

let searches = document.querySelectorAll('input[type=search]');

for (let i = 0; i < searches.length; i++) {
    let label = searches[i].closest('label');

    let elements = {
        label   : label,
        search  : label.querySelector('input'),
        clearBtn: label.querySelector('.form-group__icon--clear'),
    };

    if (elements.clearBtn) {
        elements.search.addEventListener('input', toggleClearButton.bind(event, elements.search, elements.clearBtn));
        elements.clearBtn.addEventListener('click', clearSearch.bind(event, elements.search, elements.clearBtn));

        toggleClearButton(elements.search, elements.clearBtn);
    }
}

/**
 * Show/Hide Search clear button.
 *
 * @param { HTMLInputElement } search Search input field.
 * @param { SVGSVGElement|HTMLImageElement } button Search clear button.
 */
function toggleClearButton(search, button) {
    if (search.value.length === 0) {
        button.style.display = 'none';

        return;
    }

    button.style.display = 'block';
}

/**
 * Clear Search input.
 *
 * @param { HTMLInputElement } search Search input field.
 * @param { SVGSVGElement|HTMLImageElement } button Search clear button.
 */
function clearSearch(search, button) {
    search.value = '';
    search.setAttribute('value', '');
    button.style.display = 'none';
}

<!-- Select Element Script -->

// Get all Select elements.
let selects = document.querySelectorAll('select');

// Assign an Event Listener to each Select.
for (let i = 0; i < selects.length; i++) {
    selects[i].addEventListener('input', updateValue);
}

<!-- Date/DateTime Element Script -->

// Get all Date/DateTime elements.
let dates = document.querySelectorAll('input[type^="date"]');

// Assign an Event Listener to each Date/DateTime.
for (let i = 0; i < dates.length; i++) {
    dates[i].addEventListener('input', updateValue);
}

<!-- Time Element Script -->

// Get all Time elements.
let times = document.querySelectorAll('input[type="time"]');

// Assign an Event Listener to each Date/DateTime.
for (let i = 0; i < times.length; i++) {
    times[i].addEventListener('input', updateValue);
}

<!-- Text Area Script -->

// Get all Textarea elements.
let textareas = document.querySelectorAll('textarea[autoexpand="true"]');

// Assign an Event Listener to each Textarea.
for (let i = 0; i < textareas.length; i++) {
    textareas[i].addEventListener('input', resize);
}

/**
 * Resize the element.
 */
function resize() {
    this.style.height = 'auto';
    this.style.height = this.scrollHeight + (this.getAttribute('border') === 'full' ? 4 : 2) + 'px';
}

<!-- File Element Script -->

// Get all Date/DateTime elements.
let files = document.querySelectorAll('input[type="file"]');

// Assign an Event Listener to each Date/DateTime.
for (let i = 0; i < files.length; i++) {
    files[i].addEventListener('input', updateValue);
}

/**
 * Update Element value.
 */
function updateValue() {
    if (this.type === 'file') {
        let label = this.closest('label');
        let title = label.querySelector('.form-group__file-holder');
        let file  = this.files.length >= 2 ? this.files.length + ' files' : this.value.split('\\').pop();

        title.value = file;
        title.setAttribute('value', file);
    }

    this.setAttribute('value', this.value);
}
