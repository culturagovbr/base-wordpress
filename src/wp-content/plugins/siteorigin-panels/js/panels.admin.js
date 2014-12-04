/**
 * Initial setup for the panels interface
 *
 * @copyright Greg Priday 2013
 * @license GPL 2.0 http://www.gnu.org/licenses/gpl-2.0.html
 */

jQuery( function ( $ ) {
    panels.animations = $('#panels').data('animations');

    $( window ).bind( 'resize', function ( event ) {
        // ui-resizable elements trigger resize
        if ( $( event.target ).hasClass( 'ui-resizable' ) ) return;
        
        // Resize all the grid containers
        $( '#panels-container .grid-container' ).panelsResizeCells();
    } );

    // Create a sortable for the grids
    $( '#panels-container' ).sortable( {
        items:    '> .grid-container',
        handle:   '.grid-handle',
        tolerance:'pointer',
        stop:     function () {
            $( this ).find( '.cell' ).each( function () {
                // Store which grid this is in by finding the index of the closest .grid-container
                $( this ).find( 'input[name$="[grid]"]' ).val( $( '#panels-container .grid-container' ).index( $( this ).closest( '.grid-container' ) ) );
            } );

            $( '#panels-container .panels-container' ).trigger( 'refreshcells' );
        }
    } );

    // Create the add grid dialog
    // Create the dialog that we use to add new grids
    $( '#grid-add-dialog' )
        .show()
        .dialog( {
            dialogClass: 'panels-admin-dialog',
            autoOpen: false,
            modal: false, // Disable modal so we don't mess with media editor. We'll create our own overlay.
            title:   $( '#grid-add-dialog' ).attr( 'data-title' ),
            open:    function () {
                $( this ).find( 'input' ).val( 2 ).select();
                var overlay = $('<div class="siteorigin-panels ui-widget-overlay ui-widget-overlay ui-front"></div>').css('z-index', 80001);
                $(this).data('overlay', overlay).closest('.ui-dialog').before(overlay);
            },
            close : function(){
                $(this).data('overlay').remove();
            },
            buttons: [
                {
                    // The add widget button
                    text : panels.i10n.buttons.add,
                    click: function () {
                        var num = Number( $( '#grid-add-dialog' ).find( 'input' ).val() );

                        if ( isNaN( num ) ) {
                            alert( 'Invalid Number' );
                            return false;
                        }

                        // Make sure the number is between 1 and 10.
                        num = Math.min( 10, Math.max( 1, Math.round( num ) ) );
                        var gridContainer = window.panels.createGrid( num );

                        if(panels.animations) gridContainer.hide().slideDown();
                        else gridContainer.show();

                        $( '#grid-add-dialog' ).dialog( 'close' );
                    }
                }
            ]
        })
        .on('keydown', function(e) {
            if (e.keyCode == $.ui.keyCode.ENTER) {
                // This is the same as clicking the add button
                gridAddDialogButtons[panels.i10n.buttons.add]();
                setTimeout(function(){$( '#grid-add-dialog' ).dialog( 'close' );}, 1)
            }
            else if (e.keyCode === $.ui.keyCode.ESCAPE) {
                $( '#grid-add-dialog' ).dialog( 'close' );
            }
        });
    ;

    // Create the main add widgets dialog
    $( '#panels-dialog' ).show()
        .dialog( {
            dialogClass: 'panels-admin-dialog',
            autoOpen:    false,
            resizable:   false,
            draggable:   false,
            modal:       false,
            title:       $( '#panels-dialog' ).attr( 'data-title' ),
            minWidth:    960,
            maxHeight:   Math.round($(window).height() * 0.8),
            open :       function () {
                var overlay = $('<div class="siteorigin-panels-ui-widget-overlay ui-widget-overlay ui-front"></div>').css('z-index', 80001);
                $(this).data('overlay', overlay).closest('.ui-dialog').before(overlay);
            },
            close:       function () {
                $(this).data('overlay').remove();
                if(panels.animations) $( '#panels-container .panel.new-panel' ).hide().slideDown( 1000 ).removeClass( 'new-panel' );
                else $( '#panels-container .panel.new-panel' ).show().removeClass( 'new-panel' );
            }
        } )
        .on('keydown', function(e) {
            if (e.keyCode === $.ui.keyCode.ESCAPE) {
                $(this ).dialog('close');
            }
        });
    
    $( '#so-panels-panels .handlediv' ).click( function () {
        // Trigger the resize to reorganise the columns
        setTimeout( function () {
            $( window ).resize();
        }, 150 );
    } );

    // The button for adding a panel
    $( '#panels .panels-add')
        .button( {
            icons: {primary: 'ui-icon-add'},
            text:  false
        } )
        .click( function () {
            $('#panels-text-filter-input' ).val('').keyup();
            $( '#panels-dialog' ).dialog( 'open' );
            return false;
        } );

    // The button for adding a grid
    $( '#panels .grid-add' )
        .button( {
            icons: { primary: 'ui-icon-columns' },
            text:  false
        } )
        .click( function () {
            $( '#grid-add-dialog' ).dialog( 'open' );
            return false;
        } );

    // Handle filtering in the widgets dialog
    $( '#panels-text-filter-input' )
        .keyup( function (e) {
            if( e.keyCode == 13 ) {
                // If we pressed enter and there's only one widget, click it
                var p = $( '#panels-dialog .panel-type-list .panel-type:visible' );
                if( p.length == 1 ) p.click();
                return;
            }

            var value = $( this ).val().toLowerCase();

            // Filter the panels
            $( '#panels-dialog .panel-type-list .panel-type' )
                .show()
                .each( function () {
                    if ( value == '' ) return;

                    if ( $( this ).find( 'h3' ).html().toLowerCase().indexOf( value ) == -1 ) {
                        $( this ).hide();
                    }
                } )
        } )
        .click( function () {
            $( this ).keyup()
        } );

    // Handle adding a new panel
    $( '#panels-dialog .panel-type' ).click( function () {
        var panel = $('#panels-dialog').panelsCreatePanel( $( this ).attr('data-class') );
        panels.addPanel(panel, null, null, true);

        // Close the add panel dialog
        $( '#panels-dialog' ).dialog( 'close' );
    } );


    // Either setup an initial row or load one from the panels data
    if ( typeof panelsData != 'undefined' ) panels.loadPanels(panelsData);
    else panels.createGrid( 1 );

    $( window ).resize( function () {
        // When the window is resized, we want to center any panels-admin-dialog dialogs
        $( '.panels-admin-dialog' ).filter( ':data(dialog)' ).dialog( 'option', 'position', 'center' );
    } );

    // Handle switching between the page builder and other tabs
    $( '.wp-editor-tabs' )
        .find( '.wp-switch-editor' )
        .click(function (e) {
            // e.preventDefault();

            var $$ = $(this);

            $( '#wp-content-editor-container, #post-status-info' ).show();
            $( '#so-panels-panels' ).hide();
            $( '#wp-content-wrap' ).removeClass('panels-active');

            $('#content-resize-handle' ).show();
        } ).end()
        .prepend(
            $( '<a id="content-panels" class="hide-if-no-js wp-switch-editor switch-panels">' + $( '#so-panels-panels h3.hndle span' ).html() + '</a>' )
                .click( function (e) {
                    // Switch to the Page Builder interface
                    e.preventDefault();

                    var $$ = $( this );

                    // Hide the standard content editor
                    $( '#wp-content-wrap, #post-status-info' ).hide();

                    // Show page builder and the inside div
                    $( '#so-panels-panels' ).show().find('> .inside').show();

                    // Triggers full refresh
                    $( window ).resize();
                } )
        );

    $('#so-panels-panels #add-to-panels .switch-to-standard').click(function(){
        // Switch back to the standard editor
        $( '#wp-content-wrap, #post-status-info' ).show();
        $( '#so-panels-panels' ).hide();
        $( window ).resize();
    });

    // This is for the home page builder
    $( '#panels-home-page #post-body' ).show();
    $( '#panels-home-page #post-body-wrapper' ).css('background', 'none');

    // Move the panels box into a tab of the content editor
    $( '#so-panels-panels' )
        .insertAfter( '#wp-content-wrap' )
        .hide()
        .find( '.handlediv' ).remove()
        .end()
        .find( '.hndle' ).html( '' ).append(
            $('#add-to-panels')
        );

    // When the content panels button is clicked, trigger a window resize to set up the columns
    $('#content-panels' ).click( function(){
        $(window ).resize();
    } );

    if ( typeof panelsData != 'undefined' || $('#panels-home-page' ).length ) $( '#content-panels' ).click();

    // Click again after the panels have been set up
    setTimeout(function(){
        if ( typeof panelsData != 'undefined' || $('#panels-home-page' ).length) $( '#content-panels' ).click();
        $('#so-panels-panels .hndle' ).unbind('click');
        $('#so-panels-panels .cell' ).eq(0).click();
    }, 150);

    // This is only for the home page interface
    if( $('#panels-home-page' ).length ){
        // Lets do some home page settings
        $('#content-tmce, #content-html' ).remove();
        $('#content-panels' ).hide();

        // Initialize the toggle switch
        $('#panels-toggle-switch' )
            .mouseenter(function(){
                $(this ).addClass('subtle-move');
            })
            .click(function(){
                $(this ).toggleClass('state-off').toggleClass('state-on' ).removeClass('subtle-move');
                $('#panels-home-enabled' ).val( $(this ).hasClass('state-off') ? 'false' : 'true' );
            } );

        // Handle the previews
        $('#post-preview' ).click(function(event){
            var form = $('#panels-container' ).closest('form');
            var originalAction = form.attr('action');
            form.attr('action', panels.previewUrl ).attr('target', '_blank').submit().attr('action', originalAction).attr('target', '_self');
            event.preventDefault();
        });
    }

    // Add a hidden field to show that the JS is complete. If this doesn't run we assume that JS is broken and the interface hasn't loaded properly
    $('#panels').append('<input name="panels_js_complete" type="hidden" value="1" />');
} );