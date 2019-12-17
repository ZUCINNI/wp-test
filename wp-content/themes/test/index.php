<?php get_header(); ?>

<?php
    // параметры по умолчанию
    $args = array(
        'numberposts' => 6,
        'category' => 0,
        'orderby' => 'date',
        'order' => 'DESC',
        'include' => array(),
        'exclude' => array(),
        'meta_key' => '',
        'meta_value' => '',
        'post_type' => 'post',
        'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
    );
    $query = new WP_Query( $args );
?>

    <div class="jumbotron p-4 p-md-5 text-white rounded bg-dark">
        <div class="col-md-6 px-0">
            <h1 class="display-4 font-italic">Title of a longer featured blog post</h1>
            <p class="lead my-3">Multiple lines of text that form the lede, informing new readers quickly and
                efficiently
                about what’s most interesting in this post’s contents.</p>
            <p class="lead mb-0"><a href="#" class="text-white font-weight-bold">Continue reading...</a></p>
        </div>
    </div>

    <?php get_template_part('last-two-posts'); ?>

    <?php if ( $query->have_posts() ) : ?>

    <main role="main" class="container">
        <div class="row">
            <div class="col-md-8 blog-main">
                <h3 class="pb-4 mb-4 font-italic border-bottom">
                    From the Firehose
                </h3>
                <ul class="blog-post">
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                   <li>
                       <h2 class="blog-post-title"><?php the_title() ?></h2>
                       <p class="blog-post-meta"><?php the_date() ?><a href="#"> <?php the_author() ?></a></p>
                       <p><?php the_content(); ?></p>
                   </li>
                <?php endwhile; ?>
                </ul>

    <?php endif; ?>
<!--                <nav class="blog-pagination">-->
<!--                    <a class="btn btn-outline-primary" href="#">Older</a>-->
<!--                    <a class="btn btn-outline-secondary disabled" href="#" tabindex="-1" aria-disabled="true">Newer</a>-->
<!--                </nav>-->

            </div><!-- /.blog-main -->

            <aside class="col-md-4 blog-sidebar">
                <div class="p-4 mb-3 bg-light rounded">
                    <h4 class="font-italic">About</h4>
                    <p class="mb-0">Etiam porta <em>sem malesuada magna</em> mollis euismod. Cras mattis consectetur
                        purus sit
                        amet fermentum. Aenean lacinia bibendum nulla sed consectetur.</p>
                </div>

                <div class="p-4">
                    <h4 class="font-italic">Categories</h4>
                    <?php $categories = get_categories(); ?>
                        <ol class="list-unstyled mb-0">
                            <?php
                                foreach($categories as $category) {
                                    echo '  <li><a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a></li>';
                                }
                            ?>
                        </ol>
                </div>
            </aside><!-- /.blog-sidebar -->

        </div><!-- /.row -->

    </main><!-- /.container -->

<?php get_footer(); ?>