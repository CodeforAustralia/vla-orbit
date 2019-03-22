import Vue from 'vue';
import axios from 'axios';

new Vue({

    el: '#service_booking',

    data: {
        activate_service_url: '/service_booking/activate_service'
    },

    methods: {
        /**
         * Submit form, can be improved by creating indpendent functions for post, get, patch....
         * @param {string} requestType post, get, patch, update
         * @param {string} url End point to submit from
         */
        submitServiceId(requestType, url, id) {
            return new Promise((resolve, reject) => {
                //Do Ajax or Axios request
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest'
                };
                axios[requestType](url, {id})
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        console.log(error);
                        reject(error.response.data);
                });
            });
        },
        activateService: function (id) {
            $('#contentLoading').modal('show');
            this.submitServiceId('post', this.activate_service_url, id)
                .then(function (response) {
                    $('#contentLoading').modal('hide');
                    if (response.success == 'success') {
                        swal("Done!", "Your service has been activated, now you need to set availability in the manage bookings page.", "success");
                        document.querySelector('.service_booking_conditions').classList.remove('hidden');
                        document.querySelector('#service_booking').classList.add('hidden');
                    } else {
                        console.log('Error: ', response.message);
                        swal("Error", 'Unfortunately we cannot process your request right now. Please contact a system admin for futher information.', "error");
                    }
                }).catch(function (error) {
                    $('#contentLoading').modal('hide');
                    console.log('Error: ', error)
                });
        },
    }
});