( function( element, blocks, components, editor, $, settings ) {

	var el = wp.element.createElement;
	var registerBlockType = wp.blocks.registerBlockType;
	var ServerSideRender = wp.components.ServerSideRender;
	var PanelBody = wp.components.PanelBody;
	var SelectControl = wp.components.SelectControl;
	var Placeholder = wp.components.Placeholder;
	var Notice = wp.components.Notice;
	var Button = wp.components.Button;
	var Icon = wp.components.Icon;
	var InspectorControls = wp.editor.InspectorControls;

	var blockID = 'thethemefoundry/happyforms';
	var options = Object
		.keys( settings.forms )
		.map( function( key ) {
			return { label: settings.forms[key], value: key };
		} )
	options.reverse().unshift( { label: settings.i18n.select_default, value: '' } );

	var getEditLink = function( id ) {
		var returnUrl = encodeURIComponent( document.location.href );
		var link = settings.editLink.replace( 'ID', id ).replace( 'URL', returnUrl );

		return link;
	}

	var ComponentPlaceholder = function( props ) {
		var component =
			el( Placeholder, {
					icon: settings.icon,
					label: settings.i18n.placeholder_text,
				},
				el( SelectControl, {
					value: '',
					options: options,
					onChange: function( value ) {
						props.setAttributes( { id: value } );
					}
				} )
			);

		return component;
	};

	var ComponentForm = function( props ) {
		var shortcode = el( ServerSideRender, {
				block: blockID,
				attributes: props.attributes,
			} 
		);

		var component = [ shortcode ];
		var formID = props.attributes.id;
		var noticeID = 'happyforms_gutenberg_' + formID + '_upgrade';
		var isDismissed = false;

		for ( var property in settings.dismissed_notices ) {
			if ( settings.dismissed_notices.hasOwnProperty( property ) ) {
				if ( noticeID === settings.dismissed_notices[property] ) {
					isDismissed = true;
				}
			}
		}

		var show = settings.upgrade_notice.show && ! isDismissed;

		if ( show ) {
			component = [
				el( 'div',
					{
						id: 'happyforms_notice_' + noticeID
					},
					el( Notice, {
						status: 'warning',
						isDismissible: true,
						onRemove: function(e, b) {
							$.post( settings.ajaxurl, {
								action: 'happyforms_hide_notice',
								nid: noticeID,
								nonce: settings.upgrade_notice.nonces[formID]
							}, function( response ) {
								$( '#happyforms_notice_' + noticeID ).hide();
							} );
						}
					}, el( 'p',
							null,
							settings.upgrade_notice.i18n.text,
							el( 'a', { href: 'https://happyforms.me/upgrade', target: '_blank' }, ' ' + settings.upgrade_notice.i18n.link_text )
						)
					),
				),
				shortcode
			];
		}

		return component;
	};

	var ComponentInspector = function( props ) {
		var component =
			el( InspectorControls, {},
				el( PanelBody, { title: settings.i18n.settings_title },
					el( SelectControl, {
						value: props.attributes.id,
						options: options,
						onChange: function( value ) {
							props.setAttributes( { id: value } );
						},
					} ),

					props.attributes.id && el( Button, {
						href: getEditLink( props.attributes.id ),
						isLink: true,
						icon: 'external'
					}, settings.i18n.edit_form, el( Icon, { icon: 'external' } ) )
				),
			);

		return component;
	};

	registerBlockType( blockID, {
		title: settings.block.title,
		description: settings.block.description,
		category: settings.block.category,
		icon: settings.block.icon,
		keywords: settings.block.keywords,
		supports: {
			html: false
		},

		edit: function( props ) {
			if ( props.attributes.id ) {
				return [ ComponentForm( props ), ComponentInspector( props ) ];
			}

			return [ ComponentPlaceholder( props ), ComponentInspector( props ) ];
		},

		save: function() {
			return null;
		},
	} );

} )(
	window.wp.element,
	window.wp.blocks,
	window.wp.components,
	window.wp.editor,
	jQuery,
	_happyFormsBlockSettings,
);