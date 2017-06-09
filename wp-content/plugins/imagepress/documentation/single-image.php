<?php get_header(); ?>

<section id="content" role="main" class="ip-main">
    <?php if(have_posts()) : while(have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php
            // BEGIN IMAGEPRESS CODE // 5.2
			if(function_exists('ip_main')) {
				ip_main(get_the_ID());
				ip_related(get_the_ID());
			}
            // END IMAGEPRESS CODE
            ?>
        </article>

        <?php comments_template('', true); ?>
    <?php endwhile; endif; ?>
</section>

<?php get_sidebar('hub'); ?>
<?php get_footer(); ?>
