/*
Name: 			Tables / Ajax - Examples
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version: 	2.0.0
*/

(function($) {

	'use strict';

	$('#community-info-update').on('click', function () {
		let communityname = $('#input-communityname').val();
		let lat = $('#input-lat').val();
		let lng = $('#input-lng').val();
		let radius = $('#input-radius').val();
		let adsprice = $('#input-adsprice').val();

		$.ajax({
			url: app_url + '/updateCommunitySetting',
			data: {
                _token: $('meta[name=csrf-token]').attr("content"),
                id: community_id,
                community_name: communityname,
                lat: lat,
                lng: lng,
                radius: radius,
                ads_price: adsprice
			},
			type: 'post',
            success: function() {
                new PNotify({
                    title: '',
                    text: 'Community Updated Successfully',
                    type: 'success',
                    shadow: true
                });
            },
            failure: function () {
                new PNotify({
                    title: '',
                    text: 'Community Update Failed',
                    type: 'error',
                    shadow: true
                });
            }
		});
    });

    $('#community-create').on('click', function () {
        let communityname = $('#input-communityname').val();
        let lat = $('#input-lat').val();
        let lng = $('#input-lng').val();
        let radius = $('#input-radius').val();
        let adsprice = $('#input-adsprice').val();

        $.ajax({
            url: app_url + '/createCommunitySetting',
            data: {
                _token: $('meta[name=csrf-token]').attr("content"),
                community_name: communityname,
                lat: lat,
                lng: lng,
                radius: radius,
                ads_price: adsprice
            },
            type: 'post',
            success: function(data) {
                if(data.status){
                    new PNotify({
                        title: 'Community',
                        text: 'Community Created Successfully',
                        type: 'success',
                        shadow: true
                    });
                }
                else{
                    new PNotify({
                        title: 'Community',
                        text: status.message,
                        type: 'error',
                        shadow: true
                    });
                }
            },
            failure: function () {
                new PNotify({
                    title: 'Community',
                    text: 'Community Creating Failed',
                    type: 'error',
                    shadow: true
                });
            }
        });
    });

}).apply(this, [jQuery]);