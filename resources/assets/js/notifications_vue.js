import Vue from 'vue';

new Vue({
    el: '#header_app',

    data: {
        background_color: 'bg-yellow-saffron',
    },
    methods: {
        onNotificationsSeen: function (e) {
            var self = this;
            self.clearNotificationsPromise()
                .then(services => {})
                .catch(err => {
                    console.log(err);
                });
        },
        clearNotificationsPromise: function () {
            //Create a new promise
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: '/user/clearNotifications',
                    method: 'GET',
                    success: function () {
                        resolve();
                    },
                    error: function (error) {
                        reject(Error('Cannot update notifications'));
                    }
                });
            });
        },
        checkUrlParams: function () {
            const self = this;

            if (self.getUrlParameter('dashboard')) {
                let message_el = document.getElementById(self.getUrlParameter('dashboard'));
                message_el.classList.add(self.background_color);
            }
        },
        //Function taken from https://stackoverflow.com/questions/19491336/get-url-parameter-jquery-or-how-to-get-query-string-values-in-js
        //var tech = getUrlParameter('dashboard');
        getUrlParameter: function (sParam) {
            var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : sParameterName[1];
                }
            }
        }
    },
    mounted() {
        const self = this;
        self.checkUrlParams();
    }
});