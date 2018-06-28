import axios from 'axios';
import Errors from './errors';

export default class Form {

    /**
     * Initialize Form values
     * @param {*} data values
     */
    constructor(data) {
        this.originalData = data;

        for (let field in data) {
            this[field] = data[field];
        }

        this.errors = new Errors();
    }

    /**
     * Get data from form
     */
    data() {
        let data = {};
        for (let propety in this.originalData) {
            data[propety] = this[propety];
        }
        return data;
    }

    /**
     * Clear data from Form
     */
    reset() {
        for (let field in this.originalData) {
            this[field] = '';
        }
        this.errors.clear();
    }

    /**
     * Submit form, can be improved by creating indpendent functions for post, get, patch....
     * @param {string} requestType post, get, patch, update
     * @param {string} url End point to submit from
     */
    submit(requestType, url) {
        return new Promise((resolve, reject) => {
            //Do Ajax or Axios request
            axios.defaults.headers.common = {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="_token"]').getAttribute('content')
            };

            axios[requestType](url, this.data())
                .then(response => {
                    this.onSuccess(response.data)
                    resolve(response.data);
                })
                .catch(error => {
                    this.onFail(error.response.data);
                    reject(error.response.data);
                });
        });
    }

    /**
     * Reset form
     */
    onSuccess() {
        this.reset();
    }

    /**
     * Track errors in Errors object
     * @param {*} errors Errors object
     */
    onFail(errors) {
        this.errors.record(errors);
    }
}