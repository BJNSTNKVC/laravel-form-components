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
     * Button that shows the password.
     *
     * @type { HTMLButtonElement | undefined }
     */
    $show: undefined,

    /**
     * Button that hides the password.
     *
     * @type { HTMLButtonElement | undefined }
     */
    $hide: undefined,

    /**
     * Validation error.
     *
     * @type { HTMLSpanElement | undefined }
     */
    $error: undefined,

    /**
     * Initialize the password.
     *
     * @return { void }
     */
    init() {
        this.$field = this.$el;
        this.$input = this.$refs.input;
        this.$show  = this.$refs.show;
        this.$hide  = this.$refs.hide;
        this.$error = this.$refs.error;

        this.addEventListeners();
    },

    /**
     * Add event listeners to the password.
     *
     * @return { void }
     */
    addEventListeners() {
        this.$input.addEventListener('input', () => this.validate());
        this.$show?.addEventListener('click', () => this.reveal());
        this.$hide?.addEventListener('click', () => this.conceal());
    },

    /**
     * Show the typed password.
     *
     * @return { void }
     */
    reveal() {
        this.visibility('visible');
        this.$hide.focus();
    },

    /**
     * Hide the typed password.
     *
     * @return { void }
     */
    conceal() {
        this.visibility('hidden');
        this.$show.focus();
    },

    /**
     * Set the visibility of the typed password.
     *
     * @param { 'visible' | 'hidden' } visibility
     *
     * @return { void }
     */
    visibility(visibility) {
        this.$input.type  = visibility === 'visible' ? 'text' : 'password';
        this.$show.hidden = visibility === 'visible';
        this.$hide.hidden = visibility === 'hidden';
    },

    /**
     * Hide a reported error while the password holds a value.
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
     * Set the validation state of the password.
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
     * Determine if the password holds no value.
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
