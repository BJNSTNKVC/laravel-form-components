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
     * Validation error.
     *
     * @type { HTMLSpanElement | undefined }
     */
    $error: undefined,

    /**
     * Initialize the date.
     *
     * @return { void }
     */
    init() {
        this.$field = this.$el;
        this.$input = this.$refs.input;
        this.$error = this.$refs.error;

        this.addEventListeners();
    },

    /**
     * Add event listeners to the date.
     *
     * @return { void }
     */
    addEventListeners() {
        this.$input.addEventListener('input', () => {
            this.mirror();
            this.validate();
        });
    },

    /**
     * Copy the current value into the value attribute styles rely on.
     *
     * @return { void }
     */
    mirror() {
        this.$input.setAttribute('value', this.$input.value);
    },

    /**
     * Hide a reported error while the date holds a value.
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
     * Set the validation state of the date.
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
     * Determine if the date holds no value.
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
