export default class Errors {

    /**
     * Initialize Errors
     */
    constructor() {
        this.errors = {}
    }

    /**
     * Get Errors by field
     * @param {string} field Name of the field
     */
    get(field) {
        if (this.errors[field]) {
            return this.errors[field][0];
        }
    }

    /**
     * Add error to object
     * @param {*} errors Error object
     */
    record(errors) {
        this.errors = errors;
    }

    /**
     * Remove error from field name
     * @param {string} field Field name
     */
    clear(field) {
        if (field) {
            delete this.errors[field];
            return;
        }

        this.errors = {};
    }

    /**
     * Check if a field has error
     * @param {string} field Field name
     */
    has(field) {
        return this.errors.hasOwnProperty(field);
    }

    /**
     * Check if there are errors in the Form
     */
    any() {
        return Object.keys(this.errors).length > 0;
    }
}