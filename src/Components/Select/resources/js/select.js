export default () => ({
    /**
     * Field label.
     *
     * @type { HTMLLabelElement | HTMLElement }
     */
    $field: undefined,

    /**
     * Select element.
     *
     * @type { HTMLSelectElement | HTMLElement }
     */
    $select: undefined,

    /**
     * Validation error.
     *
     * @type { HTMLSpanElement | undefined }
     */
    $error: undefined,

    /**
     * Initialize the select.
     *
     * @return { void }
     */
    init() {
        this.$field  = this.$el;
        this.$select = this.$refs.select;
        this.$error  = this.$refs.error;

        this.addEventListeners();
    },

    /**
     * Add event listeners to the select.
     *
     * @return { void }
     */
    addEventListeners() {
        this.$select.addEventListener('input', () => {
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
        this.$select.setAttribute('value', this.$select.value);
    },

    /**
     * Hide a reported error while the select holds a value.
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
     * Set the validation state of the select.
     *
     * @param { 'valid' | 'invalid' } state
     *
     * @return { void }
     */
    state(state) {
        this.$field.toggleAttribute('invalid', state === 'invalid');
        this.$error.hidden = state === 'valid';
    },

    /**
     * Determine if the select holds no value.
     *
     * @return { Boolean }
     */
    get isEmpty() {
        return this.$select.value === '';
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
