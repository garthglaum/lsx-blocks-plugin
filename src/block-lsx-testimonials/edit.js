/**
 * External dependencies
 */

import get from 'lodash/get';
import isUndefined from 'lodash/isUndefined';
import pickBy from 'lodash/pickBy';
import moment from 'moment';
import classnames from 'classnames';
import { stringify } from 'querystringify';

const { Component, Fragment } = wp.element;

const { __ } = wp.i18n;

const { decodeEntities } = wp.htmlEntities;

const { apiFetch } = wp;

const {
	registerStore,
	withSelect,
} = wp.data;

const {
	PanelBody,
	Placeholder,
	QueryControls,
	RangeControl,
	SelectControl,
	Spinner,
	TextControl,
	ToggleControl,
	Toolbar,
	withAPIData,
} = wp.components;

const {
	InspectorControls,
	BlockAlignmentToolbar,
	BlockControls,
} = wp.editor;

const MAX_POSTS_COLUMNS_CAROUSEL = 4;

class LatestPostsBlockCarousel extends Component {
	constructor() {
		super( ...arguments );

		this.toggledisplayTestimonialDateCarousel = this.toggledisplayTestimonialDateCarousel.bind( this );
		this.toggledisplayTestimonialExcerptCarousel = this.toggledisplayTestimonialExcerptCarousel.bind( this );
		this.toggledisplayTestimonialAuthorCarousel = this.toggledisplayTestimonialAuthorCarousel.bind( this );
		this.toggledisplayTestimonialImageCarousel = this.toggledisplayTestimonialImageCarousel.bind( this );
		this.toggledisplayTestimonialLinkCarousel = this.toggledisplayTestimonialLinkCarousel.bind( this );
	}

	toggledisplayTestimonialDateCarousel() {
		const { displayTestimonialDateCarousel } = this.props.attributes;
		const { setAttributes } = this.props;

		setAttributes( { displayTestimonialDateCarousel: ! displayTestimonialDateCarousel } );
	}

	toggledisplayTestimonialExcerptCarousel() {
		const { displayTestimonialExcerptCarousel } = this.props.attributes;
		const { setAttributes } = this.props;

		setAttributes( { displayTestimonialExcerptCarousel: ! displayTestimonialExcerptCarousel } );
	}

	toggledisplayTestimonialAuthorCarousel() {
		const { displayTestimonialAuthorCarousel } = this.props.attributes;
		const { setAttributes } = this.props;

		setAttributes( { displayTestimonialAuthorCarousel: ! displayTestimonialAuthorCarousel } );
	}

	toggledisplayTestimonialImageCarousel() {
		const { displayTestimonialImageCarousel } = this.props.attributes;
		const { setAttributes } = this.props;

		setAttributes( { displayTestimonialImageCarousel: ! displayTestimonialImageCarousel } );
	}

	toggledisplayTestimonialLinkCarousel() {
		const { displayTestimonialLinkCarousel } = this.props.attributes;
		const { setAttributes } = this.props;

		setAttributes( { displayTestimonialLinkCarousel: ! displayTestimonialLinkCarousel } );
	}

	customizeReadMoreText() {
		const { readMoreText } = this.props.attributes;
		const { setAttributes } = this.props;

		setAttributes( { readMoreText: ! readMoreText } );
	}

	render() {
		const { attributes, categoriesList, setAttributes, latestPosts } = this.props;
		const { customTaxonomy, customTermID, displayTestimonialDateCarousel, displayTestimonialExcerptCarousel, displayTestimonialAuthorCarousel, displayTestimonialImageCarousel, displayTestimonialLinkCarousel, alignCarousel, columnsCarousel, orderCarousel, orderByCarousel, categories, postsToShowCarousel, width, imageCrop, readMoreText } = attributes;

		// Thumbnail options
		const imageCropOptions = [
			{ value: 'landscape', label: __( 'Landscape' ) },
			{ value: 'square', label: __( 'Square' ) },
		];

		const isLandscape = imageCrop === 'landscape';

		const inspectorControls = (
			<InspectorControls>
				<PanelBody title={ __( 'Testimonials Settings' ) }>
					<QueryControls
						{ ...{ orderCarousel, orderByCarousel } }
						numberOfItems={ postsToShowCarousel }
						categoriesList={ categoriesList }
						selectedCategoryId={ categories }
						onOrderChange={ ( value ) => setAttributes( { orderCarousel: value } ) }
						onOrderByChange={ ( value ) => setAttributes( { orderByCarousel: value } ) }
						onCategoryChange={ ( value ) => setAttributes( { categories: '' !== value ? value : undefined } ) }
                        onNumberOfItemsChange={ ( value ) => setAttributes( { postsToShowCarousel: value } ) }
					/>
					<RangeControl
						label={ __( 'Columns' ) }
						value={ columnsCarousel }
						onChange={ ( value ) => setAttributes( { columnsCarousel: value } ) }
						min={ 2 }
						max={ ! hasPosts ? MAX_POSTS_COLUMNS_CAROUSEL : Math.min( MAX_POSTS_COLUMNS_CAROUSEL, latestPosts.length ) }
					/>
					<ToggleControl
						label={ __( 'Display Featured Image' ) }
						checked={ displayTestimonialImageCarousel }
						onChange={ this.toggledisplayTestimonialImageCarousel }
					/>
					{ displayTestimonialImageCarousel &&
						<SelectControl
							label={ __( 'Featured Image Style' ) }
							options={ imageCropOptions }
							value={ imageCrop }
							onChange={ ( value ) => this.props.setAttributes( { imageCrop: value } ) }
						/>
					}
					<ToggleControl
						label={ __( 'Display Post Author' ) }
						checked={ displayTestimonialAuthorCarousel }
						onChange={ this.toggledisplayTestimonialAuthorCarousel }
					/>
					<ToggleControl
						label={ __( 'Display Post Date' ) }
						checked={ displayTestimonialDateCarousel }
						onChange={ this.toggledisplayTestimonialDateCarousel }
					/>
					<ToggleControl
						label={ __( 'Display Post Excerpt' ) }
						checked={ displayTestimonialExcerptCarousel }
						onChange={ this.toggledisplayTestimonialExcerptCarousel }
					/>
					<ToggleControl
						label={ __( 'Display Continue Reading Link' ) }
						checked={ displayTestimonialLinkCarousel }
						onChange={ this.toggledisplayTestimonialLinkCarousel }
					/>
					{ displayTestimonialLinkCarousel &&
					<TextControl
						label={ __( 'Customize Read More Link' ) }
						type="text"
						value={ readMoreText }
						onChange={ ( value ) => this.props.setAttributes( { readMoreText: value } ) }
					/>
					}

				</PanelBody>
                <PanelBody title={ __( 'Custom Taxonomy' ) }>
                    <TextControl
                        label={ __( 'Taxonomy Slug' ) }
                        type="text"
                        value={ customTaxonomy }
                        onChange={ ( value ) => this.props.setAttributes( { customTaxonomy: value } ) }
                        help={ __( 'Enter the slug of your custom taxonomy (e.g post_tag)' ) }
                    />
                    { customTaxonomy &&
                    <TextControl
                        label={ __( 'Term' ) }
                        type="text"
                        value={ customTermID }
                        onChange={ ( value ) => this.props.setAttributes( { customTermID: value } ) }
                        help={ __( 'Enter the term slug or term_id, you can enter several items separated by a comma.' ) }
                    />
                    }
                </PanelBody>
			</InspectorControls>
		);

		const hasPosts = Array.isArray( latestPosts ) && latestPosts.length;
		if ( ! hasPosts ) {
			return (
				<Fragment>
					{ inspectorControls }
					<Placeholder
						icon="admin-post"
						label={ __( 'LSX Blocks Post Carousel' ) }
					>
						{ ! Array.isArray( latestPosts ) ?
							<Spinner /> :
							__( 'No posts found.' )
						}
					</Placeholder>
				</Fragment>
			);
		}

		// Removing posts from display should be instant.
		console.log(latestPosts);
		const displayTestimonialsCarousel = latestPosts.length > postsToShowCarousel ?
			latestPosts.slice( 0, postsToShowCarousel ) :
			latestPosts;

		const articleCounter = 0;

		return (
			<Fragment>
				{ inspectorControls }
				<BlockControls>
					<BlockAlignmentToolbar
						value={ alignCarousel }
						onChange={ ( value ) => {
							setAttributes( { align: value } );
						} }
						controls={ [ 'center', 'wide' ] }
					/>
				</BlockControls>
				<div
					className={ classnames(
						this.props.className,
						'block-lsx-testimonials',
                        [ `columns-${ columnsCarousel }` ]
					) }
				>
					<div
						className={ classnames( 'lsx-post-carousel-items slick-slider slick-initialized slick-dotted' ) }
					>

                        <button
							type="button"
							className="slick-prev slick-arrow slick-disabled"
                            style={{display: 'block'}}>
							{ __( '(Previous)' ) }
                        </button>

                        <div
                            className="slick-list"
                        >

                            { displayTestimonialsCarousel.map( ( post, i ) =>
                                <article
                                    key={ i }
                                    className={ classnames(
                                        post.featured_image_src && displayTestimonialImageCarousel ? 'has-thumb' : 'no-thumb'
                                    ) }
                                >
                                    {
                                        displayTestimonialImageCarousel && post.featured_image_src !== undefined && post.featured_image_src ? (
                                            <div className="lsx-block-post-grid-image">
                                                <a href={ post.link } target="_blank" rel="bookmark">
                                                    <img
                                                        src={ isLandscape ? post.featured_image_src : post.featured_image_src_square }
                                                        alt={ decodeEntities( post.title.rendered.trim() ) || __( '(Untitled)' ) }
                                                    />
                                                </a>
                                            </div>
                                        ) : (
                                            null
                                        )
                                    }

                                    <div className="lsx-block-post-grid-text">
                                        <h2 className="entry-title"><a href={ post.link } target="_blank" rel="bookmark">{ decodeEntities( post.title.rendered.trim() ) || __( '(Untitled)' ) }</a></h2>

                                        <div className="lsx-block-post-grid-byline">
                                            { displayTestimonialAuthorCarousel && post.author_info.display_name &&
                                                <div className="lsx-block-post-grid-author"><a className="lsx-text-link" target="_blank" href={ post.author_info.author_link }>{ post.author_info.display_name }</a></div>
                                            }

                                            { displayTestimonialDateCarousel && post.date_gmt &&
                                                <time dateTime={ moment( post.date_gmt ).utc().format() } className={ 'lsx-block-post-grid-date' }>
                                                    { moment( post.date_gmt ).local().format( 'MMMM DD, Y' ) }
                                                </time>
                                            }
                                        </div>

                                        <div className="lsx-block-post-grid-excerpt">
                                            { displayTestimonialExcerptCarousel && post.excerpt &&
                                                <div dangerouslySetInnerHTML={ { __html: post.excerpt.rendered } } />
                                            }

                                            { displayTestimonialLinkCarousel &&
                                                <p><a className="lsx-block-post-grid-link lsx-text-link" href={ post.link } target="_blank" rel="bookmark">{ readMoreText }</a></p>
                                            }
                                        </div>
                                    </div>
                                </article>
                            ) }
                        </div>

                        <button
                            type="button"
                            className="slick-next slick-arrow slick-disabled"
                            style={{display: 'block'}}>
                            { __( '(Next)' ) }
                        </button>

                        <ul
                            className="slick-dots"
                        >
                            <li className="slick-active">
                                <button type="button">1</button>
                            </li>
                            <li>
                                <button type="button">2</button>
                            </li>
                            <li>
                                <button type="button">3</button>
                            </li>
                            <li>
                                <button type="button">4</button>
                            </li>
                        </ul>
					</div>
				</div>
			</Fragment>
		);
	}
}

export default withSelect( ( select, props ) => {
	const { postsToShowCarousel, orderCarousel, orderByCarousel, categories } = props.attributes;
	const { getEntityRecords } = select( 'core' );

	const latestPostsQueryCarousel = pickBy( {
		categories,
		orderCarousel,
		orderby: orderByCarousel,
		per_page: postsToShowCarousel,
	}, ( value ) => ! isUndefined( value ) );
	const categoriesListQueryCarousel = {
		per_page: 100,
	};
	return {
		latestPosts: getEntityRecords( 'postType', 'post', latestPostsQueryCarousel ),
		categoriesList: getEntityRecords( 'taxonomy', 'category', categoriesListQueryCarousel ),
		testing: '{1,2,3}',
	};
} )( LatestPostsBlockCarousel );
