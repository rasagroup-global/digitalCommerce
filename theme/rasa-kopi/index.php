<?php
/**
 * index.php — fallback template (blog posts / pages default)
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();
?>
<main id="MainContent" class="main-content color-scheme-1 gradient">
	<div class="page-width section-padding">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article class="article color-scheme-1 gradient">
				<h1 class="h1 article__title"><?php the_title(); ?></h1>
				<div class="article__content rte"><?php the_content(); ?></div>
			</article>
			<?php
		endwhile;
		?>
	</div>
</main>
<?php
get_footer();
