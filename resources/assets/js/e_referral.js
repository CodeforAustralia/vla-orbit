import 'babel-polyfill';
import Vue from 'vue';
import VueMce from 'vue-mce';

Vue.use(VueMce);

const config = {
    theme: 'modern',
    menubar: false,
    fontsize_formats: "8px 10px 12px 14px 16px 18px 20px 22px 24px 26px 28px",
    plugins: 'lists paste',
    paste_as_text: true,
    toolbar1: 'formatselect fontsizeselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
    content_css: [
        '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
        '//www.tinymce.com/css/codepen.min.css'
    ],
};

new Vue({
        el: '#editor',
        data: {
            config,
            text_in_editor: 'test'
        },
        created() {
            this.text_in_editor = document.querySelector('#body_text').innerText;
        }
    });