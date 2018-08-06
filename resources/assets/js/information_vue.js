import 'babel-polyfill';
import Vue from 'vue';
import Form from './form';

new Vue({

    el: '#contact',

    data: {
        form: new Form({
            name: '',
            email: '',
            message: '',
        }),
    },

    methods: {
        /**
         * On Submit Form
         */
        onSubmit: function(e) {
            e.preventDefault();
            this.form.submit('post', '/contact_information')
            .then( () =>{
                swal("Thanks", "", "success");
            }).catch(() => {
                console.log('Error in Server')
            });
        },
        /**
         * Display only one Quote
         */
        setQuotesVisibility: function (quotes) {
            for (let quote of quotes) {
                let inner_text = quote.getElementsByTagName('p')[0].innerText;
                if (inner_text.length < 100) {
                    quote.getElementsByTagName('p')[0].setAttribute("style", "font-size: 30px; margin-top:30px;");
                }

                if (quote.style.display !== 'none') {
                    quote.style.display = 'none';
                } else {
                    quote.style.display = 'block';
                }
            }
        }
    },
    mounted() {
        const self = this;
        const quotes = document.querySelectorAll('.quote div');

        quotes[1].style.display = 'none';
        //Display a Quote every 5 seconds
        setInterval(() => {
            self.setQuotesVisibility(quotes)
        }, 5000);
    }
});