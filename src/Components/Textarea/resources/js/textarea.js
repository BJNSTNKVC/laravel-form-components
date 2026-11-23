export default () => ({
    /**
     * Field label.
     *
     * @type { HTMLLabelElement | HTMLElement }
     */
    $field: undefined,

    /**
     * Textarea element.
     *
     * @type { HTMLTextAreaElement | HTMLElement }
     */
    $textarea: undefined,

    /**
     * Validation error.
     *
     * @type { HTMLSpanElement | undefined }
     */
    $error: undefined,

    /**
     * Initialize the textarea.
     *
     * @return { void }
     */
    init() {
        this.$field    = this.$el;
        this.$textarea = this.$refs.textarea;
        this.$error    = this.$refs.error;

        this.addEventListeners();
    },

    /**
     * Add event listeners to the textarea.
     *
     * @return { void }
     */
    addEventListeners() {
        this.$textarea.addEventListener('input', () => {
            this.resize();
            this.validate();
        });
    },

    /**
     * Grow or shrink the textarea to fit its text.
     *
     * @return { void }
     */
    resize() {
        if (this.isNotAutoExpanding) {
            return;
        }

        this.$textarea.style.height = 'auto';
        this.$textarea.style.height = `${this.$textarea.scrollHeight + this.$textarea.offsetHeight - this.$textarea.clientHeight}px`;
    },

    /**
     * Hide a reported error while the textarea holds a value.
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
     * Set the validation state of the textarea.
     *
     * @param { 'valid' | 'invalid' } state
     *
     * @return { void }
     */
    state(state) {
        this.$field.toggleAttribute('invalid', state === 'invalid');
        this.$error.hidden = state === 'valid';

        if (state === 'invalid') {
            this.$textarea.setAttribute('aria-invalid', 'true');
            this.$textarea.setAttribute('aria-describedby', this.$error.id);
        } else {
            this.$textarea.removeAttribute('aria-invalid');
            this.$textarea.removeAttribute('aria-describedby');
        }
    },

    /**
     * Determine if the textarea holds no value.
     *
     * @return { Boolean }
     */
    get isEmpty() {
        return this.$textarea.value === '';
    },

    /**
     * Determine if the textarea grows with its text.
     *
     * @return { Boolean }
     */
    get isAutoExpanding() {
        return 'autoExpand' in this.$field.dataset;
    },

    /**
     * Determine if the textarea keeps its height.
     *
     * @return { Boolean }
     */
    get isNotAutoExpanding() {
        return !this.isAutoExpanding;
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
