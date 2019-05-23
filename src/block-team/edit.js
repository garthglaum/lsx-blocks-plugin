/**
 * External dependencies
 */

//import get from 'lodash/get';
import isUndefined from 'lodash/isUndefined';
import pickBy from 'lodash/pickBy';
import moment from 'moment';
import classnames from 'classnames';
//import { stringify } from 'querystringify';

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

class LatestMembersBlockCarousel extends Component {
	constructor() {
		super( ...arguments );

		this.toggledisplayMemberExcerptCarousel = this.toggledisplayMemberExcerptCarousel.bind( this );
		this.toggledisplayMemberImageCarousel = this.toggledisplayMemberImageCarousel.bind( this );
	}

	toggledisplayMemberExcerptCarousel() {
		const { displayMemberExcerptCarousel } = this.props.attributes;
		const { setAttributes } = this.props;

		setAttributes( { displayMemberExcerptCarousel: ! displayMemberExcerptCarousel } );
	}

	toggledisplayMemberImageCarousel() {
		const { displayMemberImageCarousel } = this.props.attributes;
		const { setAttributes } = this.props;

		setAttributes( { displayMemberImageCarousel: ! displayMemberImageCarousel } );
	}

	render() {
		const { attributes, categoriesList, setAttributes, latestPosts } = this.props;
		const { displayMemberExcerptCarousel, displayMemberImageCarousel, alignCarousel, columnsCarousel, orderCarousel, orderByCarousel, categories, postsToShowCarousel, width, imageCrop } = attributes;

		// Thumbnail options
		const imageCropOptions = [
			{ value: 'landscape', label: __( 'Landscape' ) },
			{ value: 'square', label: __( 'Square' ) },
		];

		const isLandscape = imageCrop === 'landscape';

		const inspectorControls = (
			<InspectorControls>
				<PanelBody title={ __( 'Team Carousel Settings' ) }>
					<QueryControls
						{ ...{ orderCarousel, orderByCarousel } }
						numberOfItems={ postsToShowCarousel }
						categoriesList={ categoriesList }
						selectedCategoryId={ categories }
						onOrderChange={ ( value ) => setAttributes( { orderCarousel: value } ) }
						onOrderByChange={ ( value ) => setAttributes( { orderByCarousel: value } ) }
						onCategoryChange={ ( value ) => setAttributes( { categories: '' !== value ? value : undefined } ) }onNumberOfItemsChange={ ( value ) => setAttributes( { postsToShowCarousel: value } ) }
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
						checked={ displayMemberImageCarousel }
						onChange={ this.toggledisplayMemberImageCarousel }
					/>
					{ displayMemberImageCarousel &&
						<SelectControl
							label={ __( 'Featured Image Style' ) }
							options={ imageCropOptions }
							value={ imageCrop }
							onChange={ ( value ) => this.props.setAttributes( { imageCrop: value } ) }
						/>
					}
					<ToggleControl
						label={ __( 'Display Post Excerpt' ) }
						checked={ displayMemberExcerptCarousel }
						onChange={ this.toggledisplayMemberExcerptCarousel }
					/>
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
						label={ __( 'LSX Blocks Team Carousel' ) }
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
		const displayPostsCarousel = latestPosts.length > postsToShowCarousel ?
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
						'lsx-block-post-carousel',
						[ `columns-${ columnsCarousel }` ]
					) }
				>
					<div
						className={ classnames( 'lsx-post-carousel-items lsx-team-carousel-items slick-slider slick-initialized slick-dotted' ) }
					>
						<button
							type="button"
							className="slick-prev slick-arrow slick-disabled"
							style={ { display: 'block' } }>
							{ __( '(Previous)' ) }
						</button>

						<div
							className="slick-list"
						>

							{ displayPostsCarousel.map( ( post, i ) =>
								<article
									key={ i }
									className={ classnames(
										post.featured_image_src && displayMemberImageCarousel ? 'has-thumb' : 'no-thumb'
									) }
								>
									{
										displayMemberImageCarousel && post.featured_image_src !== undefined && post.featured_image_src ? (
											<div className="lsx-block-post-grid-image">
												<a href={ post.link } target="_blank" rel="noopener noreferrer">
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
										<h2 className="entry-title"><a href={ post.link } target="_blank" rel="noopener noreferrer">{ decodeEntities( post.title.rendered.trim() ) || __( '(Untitled)' ) }</a></h2>

										<div className="lsx-block-post-grid-excerpt">
											{ displayMemberExcerptCarousel && post.excerpt &&
												<div dangerouslySetInnerHTML={ { __html: post.excerpt.rendered } } />
											}
										</div>
									</div>
								</article>
							) }
						</div>

						<button
							type="button"
							className="slick-next slick-arrow slick-disabled"
							style={ { display: 'block' } }>
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
		latestPosts: getEntityRecords( 'postType', 'team', latestPostsQueryCarousel ),
		categoriesList: getEntityRecords( 'taxonomy', 'category', categoriesListQueryCarousel ),
	};
} )( LatestMembersBlockCarousel );
