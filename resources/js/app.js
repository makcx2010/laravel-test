/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

$(document).ready(function(){
    var csrf = $('meta[name="csrf-token"]').attr('content');

    reply();

    $('#load-more').click(function() {
        var url = $(this).attr('href');

        event.preventDefault();
        $.ajax({
            url: url,
            method: 'post',
            data: {_token: csrf},
            success: function (obj) {
                $('#load-more-block').remove();
                for (key in obj.comments) {
                    $('#comments-block').append(obj.comments[key]);
                }

                reply();
            },
            error: function (xhr, status, error) {
                console.log(status);
                console.log(error);
            },
        });
    });
});

function reply() {
    $('.reply').click(function() {
        $('#destination').html($(this).data('name'));
        $('#parent-id').val($(this).data('id'));

    });
}
