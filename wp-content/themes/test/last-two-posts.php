<?php

    $args = array(
        'posts_per_page'=>2,
        'order'=>'DESC',
        'post_status'      => 'publish',
        'suppress_filters' => true,
        'orderby' => 'date',
    );

    $result = new WP_Query( $args );

?>

<div class="row mb-2">
    <?php
        foreach( $result->posts as $post ){ setup_postdata( $post );
        /**
         * @var $post WP_Post
         */
    ?>
    <div class="col-md-6">
        <div class="card flex-md-row mb-4 shadow-sm h-md-250">
            <div class="card-body d-flex flex-column align-items-start">
                <strong class="d-inline-block mb-2 text-primary">
                    <?php the_category(); ?>
                </strong>
                <h3 class="mb-0">
                    <a class="text-dark" href="#">
                        <?php the_title() ?>
                    </a>
                </h3>
                <div class="mb-1 text-muted">
                    <?php the_date() ?>
                </div>
                <p class="card-text mb-auto">
                    <?php the_excerpt() ?>
                </p>
                <a href="<?php the_permalink() ?>">hochu dalshe chitatt</a>
            </div>
            <img style="display: block; width: 30%;" src="<?php the_post_thumbnail_url(); ?>"/>
        </div>
    </div>
    <?php } wp_reset_postdata(); ?>
</div>