<?php get_header(); ?>

<div style="max-width: 800px; margin: 60px auto; padding: 0 20px;">

    <?php while (have_posts()) : the_post(); ?>

        <h1><?php the_title(); ?></h1>

        <p><strong>Price:</strong> <?php echo esc_html(get_field('service_price')); ?></p>
        <p><strong>Tagline:</strong> <?php echo esc_html(get_field('service_tagline')); ?></p>
        <p><strong>Icon:</strong> <?php echo esc_html(get_field('service_icon')); ?></p>

        <hr>

        <div><?php the_content(); ?></div>

    <?php endwhile; ?>

</div>

<?php get_footer(); ?>
