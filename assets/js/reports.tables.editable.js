/*
Name: 			Tables / Editable - Examples
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version: 	2.0.0
*/

(function($) {

	'use strict';

    let ReportListTable = {

        options: {
            table: '#datatable-reportlist',
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
                    url: app_url + '/getReportListData',
                    type: 'post',
                    data: {
                        _token: $('meta[name=csrf-token]').attr("content"),
                    }
                },
                createdRow: function(row, data, dataIndex){
                    $(row).attr('data-item-id', data['id']);
                    $(row).attr('post-item-id', data['post-id']);
                },

                aoColumns: [
                    {data: 'id'},
                    {
                        data: null, className: 'active', bSortable: false,
                        render: function (data, type, row, meta) {
                            return data.reporter.firstname + ' ' + data.reporter.lastname;
                        }
                    },
                    {
                        data: null, className: 'active', bSortable: false,
                        render: function (data, type, row, meta) {
                            return data.reported.firstname + ' ' + data.reported.lastname;
                        }
                    },
                    {data: 'created_at'},
                    {
                        data: null, className: 'active', bSortable: false,
                        render: function (data, type, row, meta) {
                            if(data['isSeen'] == 1){
                                return 'Seen';
                            }
                            else{
                                return 'Unseen';
                            }
                        }
                    },
                    {
                        data: null, className: 'actions', bSortable: false,
                        "render": function (data, type, row, meta) {
                            return '<a href="#" class="on-default view-row"><i class="fa fa-eye"></i></a>';

                        }
                    }
                ],
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
                        reportId = $row.attr('data-item-id'),
                        postId = $row.attr('post-item-id');

                    window.location.href = app_url + '/report_detail/?report=' + reportId + '&post=' + postId;
                });
            return this;
        },

        // ==========================================================================================
        // ROW FUNCTIONS
        // ==========================================================================================
        rowAdd: function() {
            this.$addButton.attr({ 'disabled': 'disabled' });

            var actions,
                data,
                $row;

            actions = [
                '<a href="#" class="hidden on-editing save-row"><i class="fa fa-save"></i></a>',
                '<a href="#" class="hidden on-editing cancel-row"><i class="fa fa-times"></i></a>',
                '<a href="#" class="on-default edit-row"><i class="fa fa-pencil"></i></a>',
                '<a href="#" class="on-default remove-row"><i class="fa fa-trash-o"></i></a>',
                '<a href="#" class="on-default reset-password"><i class="el el-key"></i></a>'

            ].join(' ');

            data = this.datatable.row.add({id: '', name: '', email: '', description: '', actions});
            $row = this.datatable.row( data[0] ).nodes().to$();

            $row
                .addClass( 'adding' )
                .find( 'td:last' )
                .addClass( 'actions' );

            this.rowEdit( $row );
            this.$table.prepend($row);
            //this.datatable.order([0,'asc']).draw(); // always show fields
        },

        rowCancel: function( $row ) {
            var _self = this,
                $actions,
                i,
                data;

            if ( $row.hasClass('adding') ) {
                this.rowRemove( $row );
            } else {

                data = this.datatable.row( $row.get(0) ).data();
                this.datatable.row( $row.get(0) ).data( data );

                $actions = $row.find('td.actions');
                if ( $actions.get(0) ) {
                    this.rowSetActionsDefault( $row );
                }

                this.datatable.draw();
            }
        },

        rowEdit: function( $row ) {
            var _self = this,
                data;

            data = this.datatable.row( $row.get(0) ).data();

            $row.children( 'td').each(function( i ) {
                var $this = $( this );

                if ( $this.hasClass('actions') ) {
                    _self.rowSetActionsEditing( $row );
                }
                else if($this.hasClass('description')){
                    let options = '';
                    user_types.forEach(element => options += '<option data-item="'+ element.id +'">' + element.description + '</option>');
                    $this.html( '<select class="form-control mb-3">' + options + '</select>' );
                }
                else {
                    $this.html( '<input type="text" class="form-control input-block" value="' + $this.text() + '"/>' );
                }
            });
        },

        rowSave: function( $row ) {
            var _self     = this,
                $actions,
                values    = [];

            if ( $row.hasClass( 'adding' ) ) {
                this.$addButton.removeAttr( 'disabled' );
                $row.removeClass( 'adding' );
            }

            values = $row.find('td').map(function() {
                var $this = $(this);

                if ( $this.hasClass('actions') ) {
                    _self.rowSetActionsDefault( $row );
                    return _self.datatable.cell( this ).data();
                } else {
                    if($this.has('input').length > 0){
                        return $.trim( $this.find('input').val() );
                    }
                    else{
                        return $this.get(0).children[0].selectedOptions[0].attributes[0].value;
                    }

                }
            });
            values[3].name = values[0];
            values[3].email = values[1];
            values[3].description = values[2]

            // Changed to use in ajax
            let _datatable = this.datatable;
            let _row = this;
            $.ajax({
                url: app_url + '/saveUserListData',
                type: 'post',
                data: {
                    _token: $('meta[name=csrf-token]').attr("content"),
                    id: values[3].id,
                    name: values[3].name,
                    email: values[3].email,
                    description: values[3].description
                },
                success: function () {
                    //_datatable.row( $row.get(0)).data( values[4]);

                    $actions = $row.find('td.actions');
                    if ( $actions.get(0) ) {
                        _row.rowSetActionsDefault( $row );
                        _datatable.draw();
                    }
                },
                failure: function () {
                    _datatable.draw();
                },
                error: function () {
                    _datatable.draw();
                }
            });
        },

        rowRemove: function( $row ) {
            if ( $row.hasClass('adding') ) {
                this.$addButton.removeAttr( 'disabled' );
            }

            this.datatable.row( $row.get(0) ).remove().draw();
        },

        rowSetActionsEditing: function( $row ) {
            $row.find( '.on-editing' ).removeClass( 'hidden' );
            $row.find( '.on-default' ).addClass( 'hidden' );
        },

        rowSetActionsDefault: function( $row ) {
            $row.find( '.on-editing' ).addClass( 'hidden' );
            $row.find( '.on-default' ).removeClass( 'hidden' );
        }

    };

	$(function() {
        ReportListTable.initialize();
	});

}).apply(this, [jQuery]);