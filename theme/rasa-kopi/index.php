<?php
/**
 * index.php — fallback template (blog posts / pages default)
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();
?>
<div class="rasa-page-wrap color-scheme-1 gradient" style="padding: 36px 0 64px;">
	<div class="page-width">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article class="rasa-page-article color-scheme-1 gradient" style="display:block;width:100%;">
				<h1 class="h1 article__title" style="margin:0 0 2.4rem;"><?php the_title(); ?></h1>
				<div class="article__content rte" style="width:100%;display:block;"><?php the_content(); ?></div>
			</article>
			<?php
		endwhile;
		?>
	</div>
</div>
<?php
get_footer();
