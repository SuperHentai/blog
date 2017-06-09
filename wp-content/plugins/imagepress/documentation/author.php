<?php get_header(); ?>

<section id="content-wide" role="main">
	<article <?php post_class(); ?>>
		<section class="entry-content">
			<?php
			// BEGIN AUTHOR CODE // 5.2
			if(function_exists('ip_author')) {
				ip_author();
			}
			// END IMAGEPRESS CODE
			?>
		</section>
	</article>
</section>

<?php get_footer(); ?>
