/**
 *
 * -----------------------------------------------------------
 *
 * APPGLUT Framework Gutenberg Block
 * A Simple and Lightweight WordPress Option Framework
 *
 * -----------------------------------------------------------
 *
 */
( function( blocks, blockEditor, element, components ) {

  if ( !window.agl_gutenberg_blocks ) { return; }

  Object.values(window.agl_gutenberg_blocks).forEach( function( block ) {

    var registerBlockType = blocks.registerBlockType;
    var PlainText         = blockEditor.PlainText;
    var createElement     = element.createElement;
    var RawHTML           = element.RawHTML;
    var Button            = components.Button;

    registerBlockType('agl-gutenberg-block/block-'+block.hash, {
      title: block.gutenberg.title,
      description: block.gutenberg.description,
      icon: block.gutenberg.icon || 'screenoptions',
      category: block.gutenberg.category || 'widgets',
      keywords: block.gutenberg.keywords,
      supports: {
        html: false,
        className: false,
        customClassName: false,
      },
      attributes: {
        shortcode: {
          string: 'string',
          source: 'text',
        }
      },
      edit: function (props) {
        return (
          createElement('div', {className: 'agl-shortcode-block'},

            createElement(Button, {
              'data-modal-id': block.modal_id,
              'data-gutenberg-id': block.hash,
              className: 'is-secondary agl-shortcode-button',
              onClick: function () {
                window.agl_gutenberg_props = props;
              },
            }, block.button_title ),

            createElement(PlainText, {
              placeholder: block.gutenberg.placeholder,
              className: 'input-control blocks-shortcode__textarea',
              onChange: function (value) {
                props.setAttributes({
                  shortcode: value
                });
              },
              value: props.attributes.shortcode
            })

          )
        );
      },
      save: function (props) {
        return createElement(RawHTML, {}, props.attributes.shortcode);
      }
    });

  });

})(
  window.wp.blocks,
  window.wp.blockEditor,
  window.wp.element,
  window.wp.components
);
