import "@babel/polyfill";
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
        setQuotesVisibility: function (quotes, i, length) {

            let inner_text = quotes[i].getElementsByTagName('p')[0].innerText;
            if (inner_text.length < 100) {
                quotes[i].getElementsByTagName('p')[0].setAttribute("style", "font-size: 30px; margin-top:30px;");
            }
            quotes[i].style.display = 'none';
            quotes[(i+1) % length].style.display = 'block';
        }
    },
    mounted() {
        const self = this;
        const quotes = document.querySelectorAll('.quote div');
        let cont = 0
        let length = quotes.length;
        quotes[1].style.display = 'none';
        quotes[2].style.display = 'none';
        quotes[3].style.display = 'none';
        //Display a Quote every 10 seconds
        setInterval(() => {
            self.setQuotesVisibility(quotes,cont, length);
            cont = (cont + 1) % length;
        }, 10000);
    }
});