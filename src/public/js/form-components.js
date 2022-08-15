<!-- Form Interactivity Script -->

// Get all invalid inputs.
let inputs    = document.querySelectorAll('label[invalid] > *[interactive="true"]');

// Assign an Event Listeners to each Input.
for (let i = 0; i < inputs.length; i++) {
    inputs[i].addEventListener('input', validate);
}

/**
 * Validate the field.
 */
function validate() {
    let label = this.closest('label');
    let error = label.querySelector('.form-group__error');

    if (this.value === '' || ((this.type === 'checkbox' || this.type === 'radio') && ! this.checked)) {
        label.setAttribute('invalid', '');
        error.style.display = 'block';

        return;
    }

    label.removeAttribute('invalid');
    error.style.display = 'none';
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

/**
 * Update Element value.
 */
function updateValue() {
    this.setAttribute('value', this.value);
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
    this.style.height = this.scrollHeight + 4 + 'px';
}
