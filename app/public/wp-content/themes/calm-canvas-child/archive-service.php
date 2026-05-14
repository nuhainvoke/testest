<?php get_header(); ?>

<div style="max-width: 900px; margin: 60px auto; padding: 0 20px;">

    <h1>Our Services</h1>

    <?php
    $services = new WP_Query([
        'post_type'      => 'service',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ]);
    ?>

    <?php if ($services->have_posts()) : ?>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-top: 40px;">

            <?php while ($services->have_posts()) : $services->the_post(); ?>

                <div style="border: 1px solid #ddd; padding: 20px; border-radius: 8px;">
                    <div style="font-size: 2rem;"><?php echo esc_html(get_field('service_icon')); ?></div>
                    <h2 style="font-size: 1.2rem;"><?php the_title(); ?></h2>
                    <p><?php echo esc_html(get_field('service_tagline')); ?></p>
                    <strong><?php echo esc_html(get_field('service_price')); ?></strong>
                    <br><br>
                    <a href="<?php the_permalink(); ?>">Learn more →</a>
                </div>

            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>

        </div>

    <?php else : ?>
        <p>No services found.</p>
    <?php endif; ?>

</div>

<?php get_footer(); ?>
