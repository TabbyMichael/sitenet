<?php
/**
 * site_story archive - editorial blog/archive at /stories/.
 *
 * Presents the Stories archive as a real editorial publication rather than a
 * set of marketing cards: a compact hero, program filter pills, a 50/50
 * featured story, a 3-column story grid with search + load more, and a closing
 * CTA. Stories are read from the site_story CPT via a self-contained WP_Query
 * so the `program` filter and `story_search` query stay on this URL and page
 * correctly.
 *
 * Locked boundaries: header (get_header) and footer (get_footer) are untouched.
 * Styling lives in assets/css/stories-archive.css (scoped to
 * .site-stories-archive); behaviour in assets/js/stories-archive.js.
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$sa_archive_url = get_post_type_archive_link( 'site_story' );
$sa_program     = isset( $_GET['program'] ) ? sanitize_text_field( wp_unslash( $_GET['program'] ) ) : '';
$sa_search      = isset( $_GET['story_search'] ) ? sanitize_text_field( wp_unslash( $_GET['story_search'] ) ) : '';
$sa_paged       = max( 1, (int) get_query_var( 'paged' ) );

$sa_args = array(
	'post_type'      => 'site_story',
	'post_status'    => 'publish',
	'posts_per_page' => 9,
	'paged'          => $sa_paged,
	'orderby'        => 'date',
	'order'          => 'DESC',
);

if ( $sa_program ) {
	$sa_args['tax_query'] = array(
		array(
			'taxonomy' => 'site_program',
			'field'    => 'slug',
			'terms'    => $sa_program,
		),
	);
}

if ( $sa_search ) {
	$sa_args['s'] = $sa_search;
}

$sa_query = new WP_Query( $sa_args );

/**
 * Build the responsive <img> for one story from its hero_image field.
 * Image HTML is generated (and escaped) by wp_get_attachment_image().
 */
$sa_story_image = static function ( array $sa_story, string $sa_size, string $sa_sizes, string $sa_loading ) {
	if ( empty( $sa_story['hero_id'] ) ) {
		return '';
	}

	return wp_get_attachment_image(
		$sa_story['hero_id'],
		$sa_size,
		false,
		array(
			'class'    => 'sa-media-img',
			'alt'      => $sa_story['title'],
			'sizes'    => $sa_sizes,
			'loading'  => $sa_loading,
			'decoding' => 'async',
		)
	);
};

$sa_stories = array();

if ( $sa_query->have_posts() ) {
	while ( $sa_query->have_posts() ) {
		$sa_query->the_post();

		// Prefer a hand-written excerpt, then the impact statement, then content.
		$sa_excerpt = '';
		if ( has_excerpt( get_the_ID() ) ) {
			$sa_excerpt = wp_strip_all_tags( get_the_excerpt() );
		} else {
			$sa_impact = get_field( 'impact_statement' );
			if ( $sa_impact ) {
				$sa_excerpt = wp_strip_all_tags( $sa_impact );
			} else {
				$sa_excerpt = wp_trim_words( wp_strip_all_tags( get_the_content() ), 28 );
			}
		}

		$sa_words   = str_word_count( wp_strip_all_tags( get_the_content() ) );
		$sa_minutes = max( 1, (int) ceil( $sa_words / 200 ) );

		$sa_program_name  = '';
		$sa_program_terms = get_the_terms( get_the_ID(), 'site_program' );
		if ( $sa_program_terms && ! is_wp_error( $sa_program_terms ) ) {
			$sa_program_name = $sa_program_terms[0]->name;
		}

		$sa_year = get_field( 'story_year' );
		$sa_year = $sa_year ? (string) $sa_year : get_the_date( 'Y' );

		$sa_hero_id = 0;
		$sa_hero    = get_field( 'hero_image' );
		if ( is_array( $sa_hero ) ) {
			$sa_hero_id = (int) ( isset( $sa_hero['ID'] ) ? $sa_hero['ID'] : 0 );
		} elseif ( $sa_hero ) {
			$sa_hero_id = (int) $sa_hero;
		}

		$sa_stories[] = array(
			'title'        => get_the_title(),
			'permalink'    => get_permalink(),
			'excerpt'      => $sa_excerpt,
			'program'      => $sa_program_name,
			'reading_time' => $sa_minutes . ' min read',
			'year'         => $sa_year,
			'hero_id'      => $sa_hero_id,
		);
	}
	wp_reset_postdata();
}

// The featured slot only appears on the default (unfiltered) first page.
$sa_show_featured = ( '' === $sa_program && '' === $sa_search && 1 === $sa_paged );

$sa_featured = null;
if ( $sa_show_featured && ! empty( $sa_stories ) ) {
	$sa_featured = array_shift( $sa_stories );
}
$sa_grid = $sa_stories;

$sa_programs = get_terms(
	array(
		'taxonomy'   => 'site_program',
		'hide_empty' => true,
	)
);

$sa_active_label = '';
if ( $sa_program && $sa_programs && ! is_wp_error( $sa_programs ) ) {
	foreach ( $sa_programs as $sa_term ) {
		if ( $sa_term->slug === $sa_program ) {
			$sa_active_label = $sa_term->name;
			break;
		}
	}
}

if ( $sa_program && $sa_active_label ) {
	$sa_list_title = $sa_active_label;
} elseif ( $sa_search ) {
	/* translators: %s: the search query. */
	$sa_list_title = sprintf( __( 'Results for "%s"', 'site-child' ), $sa_search );
} else {
	$sa_list_title = __( 'Latest stories', 'site-child' );
}

$sa_count = (int) $sa_query->found_posts;

$sa_load_more_url = '';
if ( $sa_query->max_num_pages > $sa_paged ) {
	$sa_next = array( 'paged' => $sa_paged + 1 );
	if ( $sa_program ) {
		$sa_next['program'] = $sa_program;
	}
	if ( $sa_search ) {
		$sa_next['story_search'] = $sa_search;
	}
	$sa_load_more_url = add_query_arg( $sa_next, $sa_archive_url );
}
?>
<main id="primary" class="site-stories-archive">

	<!-- HERO + FILTERS -->
	<section class="sa-hero" aria-labelledby="sa-hero-title">
		<div class="sa-container">
			<span class="sa-kicker"><?php esc_html_e( 'Stories', 'site-child' ); ?></span>
			<h1 id="sa-hero-title" class="sa-hero__title">Real people.<br><span class="sa-hero__title-second">Real change.</span></h1>
			<p class="sa-hero__lead"><?php esc_html_e( 'Stories, ideas and experiences from the entrepreneurs, women, youth and communities working with SITE.', 'site-child' ); ?></p>

			<nav class="sa-filters" aria-label="<?php esc_attr_e( 'Filter stories by program', 'site-child' ); ?>">
				<a class="sa-filter<?php echo '' === $sa_program ? ' is-active' : ''; ?>" href="<?php echo esc_url( $sa_archive_url ); ?>"<?php echo '' === $sa_program ? ' aria-current="page"' : ''; ?>><?php esc_html_e( 'All', 'site-child' ); ?></a>
				<?php if ( $sa_programs && ! is_wp_error( $sa_programs ) ) : ?>
					<?php foreach ( $sa_programs as $sa_term ) : ?>
						<?php $sa_is_active = ( $sa_program === $sa_term->slug ); ?>
						<a class="sa-filter<?php echo $sa_is_active ? ' is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( 'program', $sa_term->slug, $sa_archive_url ) ); ?>"<?php echo $sa_is_active ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $sa_term->name ); ?></a>
					<?php endforeach; ?>
				<?php endif; ?>
			</nav>
		</div>
	</section>

	<?php if ( $sa_featured || ! empty( $sa_grid ) ) : ?>

		<?php if ( $sa_featured ) : ?>
			<!-- FEATURED STORY -->
			<section class="sa-featured" aria-label="<?php esc_attr_e( 'Featured story', 'site-child' ); ?>">
				<div class="sa-container">
					<article class="sa-featured-card">
						<div class="sa-featured-card__media">
							<?php if ( $sa_featured_image = $sa_story_image( $sa_featured, 'large', '(max-width: 1023px) 100vw, 55vw', 'eager' ) ) : ?>
								<?php echo $sa_featured_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WP generated markup. ?>
							<?php else : ?>
								<span class="sa-media-fallback" aria-hidden="true"><i class="fa fa-quote-left"></i></span>
							<?php endif; ?>
						</div>
						<div class="sa-featured-card__body">
							<span class="sa-kicker"><?php esc_html_e( 'Featured story', 'site-child' ); ?></span>
							<h2 class="sa-featured-card__title"><a class="sa-card-link" href="<?php echo esc_url( $sa_featured['permalink'] ); ?>"><?php echo esc_html( $sa_featured['title'] ); ?></a></h2>
							<p class="sa-featured-card__excerpt"><?php echo esc_html( wp_trim_words( $sa_featured['excerpt'], 40 ) ); ?></p>
							<div class="sa-meta">
								<span class="sa-meta__cat"><?php echo esc_html( $sa_featured['program'] ? $sa_featured['program'] : __( 'Story', 'site-child' ) ); ?></span>
								<span class="sa-meta__sep" aria-hidden="true">·</span>
								<span><?php echo esc_html( $sa_featured['reading_time'] ); ?> · <?php echo esc_html( $sa_featured['year'] ); ?></span>
							</div>
							<span class="sa-cta" aria-hidden="true"><?php esc_html_e( 'Read story', 'site-child' ); ?> <span class="sa-cta-arrow"><?php echo site_child_svg_arrow( 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></span>
						</div>
					</article>
				</div>
			</section>
		<?php endif; ?>

		<!-- STORY LISTING -->
		<section class="sa-listing" aria-label="<?php echo esc_attr( $sa_list_title ); ?>">
			<div class="sa-container">
				<div class="sa-listing__head">
					<div>
						<h2 class="sa-listing__title"><?php echo esc_html( $sa_list_title ); ?></h2>
						<p class="sa-listing__count"><?php echo esc_html( sprintf( _n( '%s story', '%s stories', $sa_count, 'site-child' ), number_format_i18n( $sa_count ) ) ); ?></p>
					</div>

					<?php if ( $sa_program || $sa_search ) : ?>
						<a class="sa-clear" href="<?php echo esc_url( $sa_archive_url ); ?>"><?php esc_html_e( 'Clear filter', 'site-child' ); ?></a>
					<?php endif; ?>

					<form class="sa-search" role="search" method="get" action="<?php echo esc_url( $sa_archive_url ); ?>">
						<label class="sa-visually-hidden" for="sa-search-input"><?php esc_html_e( 'Search stories', 'site-child' ); ?></label>
						<?php if ( $sa_program ) : ?>
							<input type="hidden" name="program" value="<?php echo esc_attr( $sa_program ); ?>" />
						<?php endif; ?>
						<span class="sa-search__icon" aria-hidden="true"><i class="fa fa-search"></i></span>
						<input id="sa-search-input" class="sa-search__input" type="search" name="story_search" value="<?php echo esc_attr( $sa_search ); ?>" placeholder="<?php esc_attr_e( 'Search stories…', 'site-child' ); ?>" />
						<button class="sa-search__submit" type="submit"><?php esc_html_e( 'Search', 'site-child' ); ?></button>
					</form>
				</div>

				<div class="sa-grid">
					<?php foreach ( $sa_grid as $sa_item ) : ?>
						<article class="sa-card">
							<div class="sa-card__media">
								<?php if ( $sa_card_image = $sa_story_image( $sa_item, 'large', '(max-width: 767px) 100vw, (max-width: 1023px) 50vw, 33vw', 'lazy' ) ) : ?>
									<?php echo $sa_card_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WP generated markup. ?>
								<?php else : ?>
									<span class="sa-media-fallback" aria-hidden="true"><i class="fa fa-quote-left"></i></span>
								<?php endif; ?>
							</div>
							<div class="sa-card__body">
								<span class="sa-card__label"><?php echo esc_html( $sa_item['program'] ? $sa_item['program'] : __( 'Story', 'site-child' ) ); ?></span>
								<h3 class="sa-card__title"><a class="sa-card-link" href="<?php echo esc_url( $sa_item['permalink'] ); ?>"><?php echo esc_html( $sa_item['title'] ); ?></a></h3>
								<p class="sa-card__excerpt"><?php echo esc_html( wp_trim_words( $sa_item['excerpt'], 22 ) ); ?></p>
								<div class="sa-card__meta"><?php echo esc_html( $sa_item['reading_time'] ); ?> · <?php echo esc_html( $sa_item['year'] ); ?></div>
								<span class="sa-card__cta" aria-hidden="true"><?php esc_html_e( 'Read story', 'site-child' ); ?> <span class="sa-cta-arrow"><?php echo site_child_svg_arrow( 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></span>
							</div>
						</article>
					<?php endforeach; ?>
				</div>

				<?php if ( $sa_load_more_url ) : ?>
					<div class="sa-loadmore">
						<a class="sa-loadmore__button" href="<?php echo esc_url( $sa_load_more_url ); ?>"><?php esc_html_e( 'Load more stories', 'site-child' ); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</section>

	<?php else : ?>
		<section class="sa-listing">
			<div class="sa-container">
				<div class="sa-empty">
					<div class="sa-empty__icon" aria-hidden="true"><i class="fa fa-book"></i></div>
					<h2 class="sa-empty__title"><?php esc_html_e( 'No stories found.', 'site-child' ); ?></h2>
					<p class="sa-empty__text"><?php esc_html_e( 'Try a different search or filter, or check back soon for new stories from the field.', 'site-child' ); ?></p>
					<?php if ( $sa_program || $sa_search ) : ?>
						<a class="sa-clear" href="<?php echo esc_url( $sa_archive_url ); ?>"><?php esc_html_e( 'Clear filter', 'site-child' ); ?></a>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<!-- CLOSING CTA -->
	<section class="sa-cta-panel" aria-labelledby="sa-cta-title">
		<div class="sa-container">
			<div class="sa-cta-panel__inner">
				<h2 class="sa-cta-panel__title" id="sa-cta-title"><?php esc_html_e( 'Have a story to share?', 'site-child' ); ?></h2>
				<p class="sa-cta-panel__text"><?php esc_html_e( 'We’d love to hear how SITE’s work has shaped your journey, business or community.', 'site-child' ); ?></p>
				<a class="sa-cta-panel__button" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>"><?php esc_html_e( 'Start a conversation', 'site-child' ); ?> <span class="sa-cta-arrow" aria-hidden="true"><?php echo site_child_svg_arrow( 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></a>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
