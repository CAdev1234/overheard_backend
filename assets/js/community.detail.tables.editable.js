/*
Name: 			Tables / Editable - Examples
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version: 	2.0.0
*/

(function($) {

	'use strict';

    function getExtension(filename) {
        var parts = filename.split('.');
        return parts[parts.length - 1];
    }

    function isImage(filename) {
        var ext = getExtension(filename);
        switch (ext.toLowerCase()) {
            case 'jpg':
            case 'gif':
            case 'bmp':
            case 'png':
                //etc
                return true;
        }
        return false;
    }

    function isVideo(filename) {
        var ext = getExtension(filename);
        switch (ext.toLowerCase()) {
            case 'm4v':
            case 'avi':
            case 'mpg':
            case 'mp4':
                // etc
                return true;
        }
        return false;
    }

    let CommunityPostListTable = {

        options: {
            table: '#datatable-communityposts',
            dialog: {
                wrapper: '#dialog',
                reset_password_wrapper: '#password-reset-dialog',
                cancelButton: '#dialogResetCancel',
                confirmButton: '#dialogResetConfirm',
            }
        },

        initialize: function() {
            this
                .setVars()
                .build()
                .events();
        },

        setVars: function() {
            this.$table				= $( this.options.table );
            this.$addButton			= $( this.options.addButton );

            // dialog
            this.dialog				= {};
            this.dialog.$wrapper	= $( this.options.dialog.wrapper );
            this.dialog.$reset_password_wrapper = $( this.options.dialog.reset_password_wrapper );
            this.dialog.$cancel		= $( this.options.dialog.cancelButton );
            this.dialog.$confirm	= $( this.options.dialog.confirmButton );

            return this;
        },

        build: function() {

            let columns = [];

            this.datatable = this.$table.DataTable({
                dom: '<"row"<"col-lg-6"l><"col-lg-6"f>><"table-responsive"t>p',
                order: [[ 0, "asc" ]],
                aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                bProcessing: true,
                bServerSide: true,
                sEmptyTable: "No Users Found",
                sZeroRecords: "No Users Found",
                ajax: {
                    url: app_url + '/getCommunityPostListData',
                    type: 'post',
                    data: {
                        _token: $('meta[name=csrf-token]').attr("content"),
                        community_id: community_id
                    }
                },
                createdRow: function(row, data, dataIndex){
                    $(row).attr('data-item-id', data['id']);
                },

                aoColumns: [
                    {
                        data: null, className: 'active media-carousel', bSortable: false,
                        render: function (data, type, row, meta) {
                            let media_part = '';
                            media_part += '<section class="card">' +
                                '	<div class="row">' +
                                '		<div class="col-xl-4">' +
                                '			<figure class="image rounded">';
                            if(data.user.avatar == null){
                                media_part += '<img style="width: 50px; height: 50px;" src="' + app_url + '/assets/img/avatars/avatar.png' +
                                    '" alt="Joseph Doe Junior" class="rounded-circle">';
                            }
                            else{
                                media_part += '<img style="width: 50px; height: 50px;" src="' + data.user.avatar +
                                    '" alt="Joseph Doe Junior" class="rounded-circle">';
                            }
                            media_part += '			</figure>' +
                                '		</div>' +
                                '		<div class="col-xl-8" style="padding-top: 10px;">' +
                                data.user.firstname + ' ' + data.user.lastname +
                                '		</div>' +
                                '	</div>';
                            media_part += '	<div class="card-body">' +
                                '		<div class="owl-carousel owl-theme" data-plugin-carousel data-plugin-options=\'{ "dots": true, "nav": true, "items": 1 }\'>';
                            for(let i = 0; i < data.attaches.length; i++){
                                if(isImage(data.attaches[i].url)){
                                    media_part += '<div class="item"><img class="img-thumbnail" style="height: 150px;" src="';
                                    media_part += data.attaches[i].url;
                                    media_part += '" alt=""></div>';
                                }
                                else if(isVideo(data.attaches[i].url)){
                                    media_part += '<video controls width="200" class="item img-thumbnail" style="height: 150px;"><source src="';
                                    media_part += data.attaches[i].url;
                                    media_part += '"></video>';
                                }

                            }
                            media_part += '		</div>' +
                                '	</div>';

                            media_part += '</section>';
                            return media_part;
                        }
                    },
                    {
                        data: null, className: 'actions', bSortable: false,
                        render: function (data, type, row, meta) {
                            let post_content = '<section class="card">' +
                                '	<header class="card-header" style="padding-bottom: 10px;">' +
                                '		<h2 class="card-title">' + data.title + '</h2>' +
                                '		<div style="margin-top: 8px;">' +
                                '			<span style="margin-right: 10px;"><i class="far fa-thumbs-up" style="margin-right: 5px;"></i>' + data.upvotes + '</span>' +
                                '			<span style="margin-right: 10px;"><i class="far fa-thumbs-down" style="margin-right: 5px;"></i>' + data.downvotes + '</span>' +
                                '			<span style="margin-right: 10px;"><i class="far fa-eye" style="margin-right: 5px;"></i>' + data.seen_count + '</span>' +
                                '			<span style="margin-right: 10px;"><i class="far fa-comment-alt" style="margin-right: 5px;"></i>' + data.comments_count + '</span>' +
                                '			<span style="margin-right: 10px;"><i class="far fa-clock" style="margin-right: 5px;"></i>' + data.post_datetime + '</span>' +
                                '		</div>' +
                                '	</header>' +
                                '	<div class="card-body">' +
                                '		<div class="scrollable" data-plugin-scrollable style="height: 150px;">' +
                                '			<div class="scrollable-content">' +
                                data.content +
                                '			</div>' +
                                '		</div>' +
                                '	</div>' +
                                '</section>';
                            return post_content;
                        }
                    },
                    {
                        data: null, className: 'active action-td', bSortable: false,
                        render: function (data, type, row, meta) {
                            return '<button post-id="' + data.id + '" type="button" class="mb-1 mt-1 mr-1 btn btn-danger vh-center" onclick="delete_post($(this))">DELETE</button>';
                        }
                    }
                ],
                initComplete: function (settings, json) {

                },
                drawCallback: function (settings) {
                    $('.owl-carousel').each(function() {
                        $(this).owlCarousel({
                            items: 1,
                            nav: true,
                            dots: false,
                            singleItem:true,
                            video: true,
                            navText: ['', '']
                        });
                    });
                }
            });

            window.dt = this.datatable;
            return this;
        },

        events: function() {
            var _self = this;

            this.$table
                .on( 'click', 'a.view-row', function( e ) {
                    e.preventDefault();

                    var $row = $(this).closest( 'tr' ),
                        userId = $row.attr('data-item-id');

                    window.location.href = app_url + '/profile_detail/' + userId;
                });
            return this;
        }
    };

    let CommunityUserListTable = {

        options: {
            table: '#datatable-communityusers',
            dialog: {
                wrapper: '#dialog',
                reset_password_wrapper: '#password-reset-dialog',
                cancelButton: '#dialogResetCancel',
                confirmButton: '#dialogResetConfirm',
            }
        },

        initialize: function() {
            this
                .setVars()
                .build()
                .events();
        },

        setVars: function() {
            this.$table				= $( this.options.table );
            this.$addButton			= $( this.options.addButton );

            // dialog
            this.dialog				= {};
            this.dialog.$wrapper	= $( this.options.dialog.wrapper );
            this.dialog.$reset_password_wrapper = $( this.options.dialog.reset_password_wrapper );
            this.dialog.$cancel		= $( this.options.dialog.cancelButton );
            this.dialog.$confirm	= $( this.options.dialog.confirmButton );

            return this;
        },

        build: function() {

            let columns = [];

            this.datatable = this.$table.DataTable({
                dom: '<"row"<"col-lg-6"l><"col-lg-6"f>><"table-responsive"t>p',
                order: [[ 0, "asc" ]],
                aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                bProcessing: true,
                bServerSide: true,
                sEmptyTable: "No Users Found",
                sZeroRecords: "No Users Found",
                ajax: {
                    url: app_url + '/getCommunityUserListData',
                    type: 'post',
                    data: {
                        _token: $('meta[name=csrf-token]').attr("content"),
                        community_id: community_id
                    }
                },
                createdRow: function(row, data, dataIndex){
                    $(row).attr('data-item-id', data['id']);
                },

                aoColumns: [
                    {data: 'firstname'},
                    {data: 'lastname'},
                    {data: 'name'},
                    {data: 'email'},
                    {
                        data: null, className: 'active', bSortable: false,
                        render: function (data, type, row, meta) {
                            if(data['isActive'] == 1){
                                return 'Active';
                            }
                            else{
                                return 'Blocked';
                            }
                        }
                    },
                    {
                        data: null, className: 'actions', bSortable: false,
                        "render": function (data, type, row, meta) {
                            let element = '<div class="row" style="margin: 0px;">';
                            element += '<div class="col-xl-12" style="text-align: center">'
                            element += '<a style="width: 100px;" type="button" class="view-row mb-1 mt-1 mr-1 btn btn-info">View</a>';
                            element += '</div>';
                            element += '</div';
                            return element;

                        }
                    }
                ],
                initComplete: function (settings, json) {

                },
                drawCallback: function (settings) {
                    $('.owl-carousel').each(function() {
                        $(this).owlCarousel({
                            items: 1,
                            nav: true,
                            dots: false,
                            singleItem:true,
                            video: true,
                            navText: ['', '']
                        });
                    });
                }
            });

            window.dt = this.datatable;
            return this;
        },

        events: function() {
            var _self = this;

            this.$table
                .on( 'click', 'a.view-row', function( e ) {
                    e.preventDefault();

                    var $row = $(this).closest( 'tr' ),
                        userId = $row.attr('data-item-id');

                    window.location.href = app_url + '/profile_detail/' + userId;
                });
            return this;
        }
    };

	$(function() {
        CommunityPostListTable.initialize();
        CommunityUserListTable.initialize();
	});

    $('#post_delete_btn').click(function () {
        $.ajax({
            url: app_url + '/delete_post',
            type: 'post',
            data: {
                _token: $('meta[name=csrf-token]').attr("content"),
                post_id: post_id
            },
            success: function () {

            }
        });
    });

}).apply(this, [jQuery]);