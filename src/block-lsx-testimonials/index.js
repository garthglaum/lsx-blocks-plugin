/**
 * BLOCK: LSX Blocks Page Carousel
 */

// Import block dependencies and components
import classnames from 'classnames';
import edit from './edit';
import Slider from "react-slick";

// Import CSS
import './styles/style.scss';
import './styles/editor.scss';

// Components
const { __ } = wp.i18n;

// Extend component
const { Component } = wp.element;

// Register block controls
const {
	registerBlockType,
} = wp.blocks;

// Register alignments
const validAlignments = [ 'center', 'wide' ];

export const name = 'core/latest-posts';

// Register the block
registerBlockType( 'lsx-blocks-plugin/block-lsx-testimonials', {
	title: __( 'LSX Testimonials', 'lsx-blocks' ),
	description: __( 'Add a carousel or list of your testimonials', 'lsx-blocks' ),
	icon: 'images-alt2',
	category: 'lsx-blocks',
	keywords: [
		__( 'testimonial', 'lsx-blocks' ),
		__( 'lsx', 'lsx-blocks' ),
	],

	getEditWrapperProps( attributes ) {
		const { align } = attributes;
		if ( -1 !== validAlignments.indexOf( align ) ) {
			return { 'data-align': align };
		}
	},

	edit,

	// Render via PHP
	save() {
		return null;
	},
} );
