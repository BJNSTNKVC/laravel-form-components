export default () => ({
    /**
     * Field label.
     *
     * @type { HTMLLabelElement | HTMLElement }
     */
    $field: undefined,

    /**
     * Input element.
     *
     * @type { HTMLInputElement | HTMLElement }
     */
    $input: undefined,

    /**
     * Button that clears the search.
     *
     * @type { HTMLButtonElement | undefined }
     */
    $clear: undefined,

    /**
     * Validation error.
     *
     * @type { HTMLSpanElement | undefined }
     */
    $error: undefined,

    /**
     * Initialize the search.
     *
     * @return { void }
     */
    init() {
        this.$field = this.$el;
        this.$input = this.$refs.input;
        this.$clear = this.$refs.clear;
        this.$error = this.$refs.error;

        this.clearable();

        this.addEventListeners();
    },

    /**
     * Add event listeners to the search.
     *
     * @return { void }
     */
    addEventListeners() {
        this.$input.addEventListener('input', () => {
            this.clearable();
            this.validate();
        });
        this.$clear?.addEventListener('click', () => this.clear());
    },

    /**
     * Empty the search as if its text was deleted.
     *
     * @return { void }
     */
    clear() {
        this.$input.value = '';
        this.$input.dispatchEvent(new Event('input', { bubbles: true }));
        this.$input.focus();
    },

    /**
     * Show the clear button while the search holds text.
     *
     * @return { void }
     */
    clearable() {
        if (this.$clear) {
            this.$clear.hidden = this.isEmpty;
        }
    },

    /**
     * Hide a reported error while the search holds a value.
     *
     * @return { void }
     */
    validate() {
        if (this.isNotInteractive || !this.$error) {
            return;
        }

        this.state(this.isEmpty ? 'invalid' : 'valid');
    },

    /**
     * Set the validation state of the search.
     *
     * @param { 'valid' | 'invalid' } state
     *
     * @return { void }
     */
    state(state) {
        this.$field.toggleAttribute('invalid', state === 'invalid');
        this.$error.hidden = state === 'valid';

        if (state === 'invalid') {
            this.$input.setAttribute('aria-invalid', 'true');
            this.$input.setAttribute('aria-describedby', this.$error.id);
        } else {
            this.$input.removeAttribute('aria-invalid');
            this.$input.removeAttribute('aria-describedby');
        }
    },

    /**
     * Determine if the search holds no value.
     *
     * @return { Boolean }
     */
    get isEmpty() {
        return this.$input.value === '';
    },

    /**
     * Determine if errors disappear on input.
     *
     * @return { Boolean }
     */
    get isInteractive() {
        return 'interactive' in this.$field.dataset;
    },

    /**
     * Determine if errors stay on input.
     *
     * @return { Boolean }
     */
    get isNotInteractive() {
        return !this.isInteractive;
    }
});
