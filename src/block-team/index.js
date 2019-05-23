/**
 * BLOCK: LSX Team Block
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

// Register block
const { registerBlockType } = wp.blocks;

// Register alignments
const validAlignments = [ 'center', 'wide' ];

registerBlockType( 'lsx-block-plugin/block-team', {
	// Block name.
	title: __( 'LSX Team Members', 'lsx-blocks-plugin' ), // Block title.
	description: __( 'Add a carousel or team members to your page.', 'lsx-blocks-plugin' ), // Block description
	icon: 'groups', // Block icon.
	//category: 'lsx-blocks', // Block category.
	category: 'lsx-blocks-plugin', // Block category.
	keywords: [
		__( 'team', 'lsx-blocks-plugin' ),
		__( 'lsx', 'lsx-blocks-plugin' ),
	],

	getEditWrapperProps( attributes ) {
		const { align } = attributes;
		if ( -1 !== validAlignments.indexOf( align ) ) {
			return { 'data-align': align };
		}
	},

	// Render the block components
	edit,

	// Render via PHP
	save() {
		return null;
	},
} );
