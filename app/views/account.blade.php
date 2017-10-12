@extends('templates.default')@section('breadcrumbs'){{ Helper::breadcrumbs('Account Management', 'My Account') }}@stop@section('left_side') {{ Helper::left_side('Account Management', 'My Account') }}@stop@section('content')<?php$user = User::find(Auth::User()->id);?><div class="row">    <div id="widget-grid" class="">        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">            <div class="jarviswidget jarviswidget-color-darken" data-widget-editbutton="false"            data-widget-colorbutton="false" data-widget-deletebutton="false">            <header>                <span class="widget-icon"> <i class="fa fa-edit"></i>                </span>                <h2>Account</h2>            </header>            <div>                <div class="widget-body no-padding">                    <form id="account_info_form" class="smart-form">                        <header>                            User Informations                        </header>                        <fieldset>                            <section>                                <label class="input"> <i class="icon-append fa fa-user"></i>                                    <input type="text" id="name" name="name" placeholder="Name"                                    value="{{ $user->name }}">                                    <b class="tooltip tooltip-bottom-right">Enter your name.</b>                                </label>                            </section>                            <section>                                <label class="input"> <i class="icon-append fa fa-envelope-o"></i>                                    <input type="email" id="email" name="email" placeholder="Email address"                                    value="{{ $user->email }}">                                    <b class="tooltip tooltip-bottom-right">Enter your email which will be used                                        as username.</b>                                    </label>                                </section>                                <section>                                    <label class="input"> <i class="icon-append fa fa-lock"></i>                                        <input type="password" id="password" name="password" placeholder="Password"                                        id="password">                                        <b class="tooltip tooltip-bottom-right">Enter your new password, leave blank                                            unless you want to change it.</b>                                        </label>                                    </section>                                    <section>                                        <label class="input"> <i class="icon-append fa fa-lock"></i>                                            <input type="password" name="passwordConfirm"                                            placeholder="Confirm password">                                            <b class="tooltip tooltip-bottom-right">Re-enter your password.</b>                                        </label>                                    </section>                                </fieldset>                                <footer>                                    <button id="submit_form" class="btn btn-primary">                                        Submit                                    </button>                                </footer>                            </form>                        </div>                    </div>                </div>            </article>        </div>    </div>    @stop    @section('pr-css')    @stop    @section('pr-scripts')    <script src="{{URL::asset('js/plugin/jquery-validate/jquery.validate.min.js')}}"></script>    @stop    @section('scripts')    <script type="text/javascript">        $( document ).ready(function() {            $('.modal_loading').hide();        });        $("#submit_form").on("click", function (e) {            $.SmartMessageBox({                title: "<i class='fa fa fa-spinner fa-spin txt-color-green'></i> Confirmation!",                content: "Do you want to save your user information?",                buttons: '[No][Yes]'            },            function (ButtonPressed) {                if (ButtonPressed === "Yes") {                    $.ajax({                        type: 'POST',                        url: '{{ URL::route('account') }}',                        data: {                            'name': $('#name').val(),                            'email': $('#email').val(),                            'password': $('#password').val(),                            'ajax_action': "save_user_information"                        },                        beforeSend: function (request) {                            return request.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));                        },                        success: function (msg) {                            if (msg.success != undefined) {                                $.each(msg.errors, function (index, error) {                                    $.smallBox({                                        title: "Token Mismatch",                                        content: "<i class='fa fa-clock-o'></i> <i>Please refresh the page.</i>",                                        color: "#C46A69",                                        iconSmall: "fa fa-times fa-2x bounce animated",                                        timeout: 4000                                    });                                });                            } else {                                $.smallBox({                                    title: "Success",                                    content: "<i class='fa fa-clock-o'></i> <i>User information saved successfully.</i>",                                    color: "#659265",                                    iconSmall: "fa fa-check fa-2x bounce animated",                                    timeout: 4000                                });                            }                        }                    });}if (ButtonPressed === "No") {    $.smallBox({        title: "Cancelled",        content: "<i class='fa fa-clock-o'></i> <i>Submmison cancelled.</i>",        color: "#C46A69",        iconSmall: "fa fa-times fa-2x bounce animated",        timeout: 4000    });}});e.preventDefault();});var $registerForm = $("#account_info_form").validate({// Rules for form validationrules: {    name: {        required: true    },    email: {        required: true,        email: true    },    password: {        required: false,        minlength: 5,        maxlength: 20    },    passwordConfirm: {        required: false,        minlength: 5,        maxlength: 20,        equalTo: '#password'    }},// Messages for form validationmessages: {    email: {        required: 'Please enter your email address',        email: 'Please enter a VALID email address'    },    password: {        required: 'Please enter your password'    },    passwordConfirm: {        required: 'Please enter your password one more time',        equalTo: 'Please enter the same password as above'    }},// Do not change code belowerrorPlacement: function (error, element) {    error.insertAfter(element.parent());}});</script>@stop