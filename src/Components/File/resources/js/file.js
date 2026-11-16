export default () => ({
    /**
     * Field label.
     *
     * @type { HTMLLabelElement | HTMLElement }
     */
    $field: undefined,

    /**
     * File input element.
     *
     * @type { HTMLInputElement | HTMLElement }
     */
    $input: undefined,

    /**
     * Field showing the chosen files.
     *
     * @type { HTMLInputElement | HTMLElement }
     */
    $holder: undefined,

    /**
     * Validation error.
     *
     * @type { HTMLSpanElement | undefined }
     */
    $error: undefined,

    /**
     * Initialize the file.
     *
     * @return { void }
     */
    init() {
        this.$field  = this.$el;
        this.$input  = this.$refs.input;
        this.$holder = this.$refs.holder;
        this.$error  = this.$refs.error;

        this.addEventListeners();
    },

    /**
     * Add event listeners to the file.
     *
     * @return { void }
     */
    addEventListeners() {
        this.$input.addEventListener('input', () => {
            this.describe();
            this.validate();
        });
    },

    /**
     * Show the name of the chosen file, or how many were chosen.
     *
     * @return { void }
     */
    describe() {
        const files = this.$input.files;

        this.$holder.value = files.length > 1 ? `${files.length} files` : (files[0]?.name ?? '');
    },

    /**
     * Hide a reported error while the file holds a value.
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
     * Set the validation state of the file.
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
     * Determine if the file holds no value.
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
