<?php
/**
 * woocommerce/archive-product.php — Dawn Collection Page (/shop/)
 * Match 1:1 dengan https://shop.rasakopi.coffee/collections/all
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

$title = is_product_category() ? single_term_title( '', false ) : rasa_tr( 'Produkte', 'Products' );
$orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( wp_unslash( $_GET['orderby'] ) ) : 'menu_order';
$total = isset( $GLOBALS['wp_query']->found_posts ) ? (int) $GLOBALS['wp_query']->found_posts : 0;
?>

<div class="collection-hero color-scheme-2 gradient">
	<div class="collection-hero__inner page-width">
		<div class="collection-hero__text-wrapper">
			<h1 class="collection-hero__title">
				<span class="visually-hidden">Kollektion: </span><?php echo esc_html( $title ); ?>
			</h1>
		</div>
	</div>
</div>

<div class="section-collection-product-grid color-scheme-2 gradient" style="padding-bottom: 52px;">
	<div class="facets-vertical page-width">
		<aside class="facets-wrapper" id="main-collection-filters" style="margin-bottom: 2rem;">
			<div class="facets-container" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1.5rem;font-size:1.4rem;color:rgba(0,0,0,0.75);">
				<div class="facets__form" style="display:flex;align-items:center;gap:2rem;flex-wrap:wrap;">
					<span class="facets__heading" style="margin:0;font-size:1.4rem;color:rgba(0,0,0,0.75);">Filter:</span>
					<span><?php echo esc_html( rasa_tr( 'Verfügbarkeit', 'Availability' ) ); ?></span>
					<span><?php echo esc_html( rasa_tr( 'Preis', 'Price' ) ); ?></span>
				</div>
				<form method="get" class="facets__form" style="display:flex;align-items:center;gap:1.2rem;margin:0;">
					<label for="SortBy" style="font-size:1.4rem;color:rgba(0,0,0,0.75);"><?php echo esc_html( rasa_tr( 'Sortieren nach:', 'Sort by:' ) ); ?></label>
					<select name="orderby" id="SortBy" class="facet-filters__sort select__select caption-large" onchange="this.form.submit()" style="border:0;background:transparent;font-family:inherit;font-size:1.4rem;color:#000;cursor:pointer;padding-right:1.5rem;">
						<option value="menu_order" <?php selected( $orderby, 'menu_order' ); ?>>Ausgewählt</option>
						<option value="popularity" <?php selected( $orderby, 'popularity' ); ?>>Meistverkauft</option>
						<option value="title" <?php selected( $orderby, 'title' ); ?>>Alphabetisch, A-Z</option>
						<option value="price" <?php selected( $orderby, 'price' ); ?>>Preis, niedrig nach hoch</option>
						<option value="price-desc" <?php selected( $orderby, 'price-desc' ); ?>>Preis, hoch nach niedrig</option>
						<option value="date" <?php selected( $orderby, 'date' ); ?>>Datum, neu nach alt</option>
					</select>
					<span id="ProductCountDesktop" class="product-count__text" style="font-size:1.4rem;color:rgba(0,0,0,0.7);">
						<?php echo (int) $total; ?> <?php echo 1 === $total ? 'Produkt' : 'Produkte'; ?>
					</span>
				</form>
			</div>
		</aside>

		<div class="product-grid-container" id="ProductGridContainer">
			<?php if ( have_posts() ) : ?>
				<ul id="product-grid" class="grid product-grid grid--2-col-tablet-down grid--4-col-desktop" role="list">
					<?php $grid_idx = 0; while ( have_posts() ) : the_post(); ?>
						<?php
						$product = wc_get_product( get_the_ID() );
						if ( ! $product ) continue;
						$grid_idx++;
						?>
						<li class="grid__item scroll-trigger animate--slide-in" data-cascade style="--animation-order: <?php echo (int) $grid_idx; ?>;">
							<?php echo rasa_product_card( $product, array( 'section_id' => 'collection' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</li>
					<?php endwhile; ?>
				</ul>
				<?php the_posts_pagination(); ?>
			<?php else : ?>
				<div class="collection collection--empty page-width">
					<div class="title-wrapper center">
						<h2 class="title title--primary">Keine Produkte gefunden</h2>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>

