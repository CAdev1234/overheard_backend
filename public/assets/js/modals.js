/*
Name: 			UI Elements / Modals - Examples
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version: 	2.0.0
*/

(function($) {

	'use strict';

	$('#user_active_btn').magnificPopup({
        type: 'inline',
        preloader: false,
        modal: true
	});

    $('#delete_post').magnificPopup({
        type: 'inline',
        preloader: false,
        modal: true
    });
    /*
    Modal Dismiss
    */
    $(document).on('click', '.modal-dismiss', function (e) {
        e.preventDefault();
        $.magnificPopup.close();
    });


}).apply(this, [jQuery]);