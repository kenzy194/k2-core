<div class="cms-carousel owl-carousel owl-theme <?php echo esc_attr($atts['template']);?>" data-margin="<?php echo esc_attr($atts['margin']); ?>" data-loop="<?php echo esc_attr($atts['loop']); ?>" data-nav="<?php echo esc_attr($atts['nav']); ?>" data-dots="<?php echo esc_attr($atts['dots']); ?>" data-autoplay="<?php echo esc_attr($atts['autoplay']); ?>" data-large-items="<?php echo esc_attr($atts['large_items']); ?>" data-medium-items="<?php echo esc_attr($atts['medium_items']); ?>" data-small-items="<?php echo esc_attr($atts['small_items']); ?>" data-xsmall-items="<?php echo esc_attr($atts['xsmall_items']); ?>">
    <?php
    $posts = $atts['posts'];
    while($posts->have_posts()){
        $posts->the_post();
        ?>
        <div class="cms-carousel-item">
            <?php
                if(has_post_thumbnail() && !post_password_required() && !is_attachment() &&  wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full', false)):
                    $class = ' has-thumbnail';
                    $thumbnail = get_the_post_thumbnail(get_the_ID(),'medium');
                else:
                    $class = ' no-image';
                    $thumbnail = '<img src="'.CMS_IMAGES.'no-image.jpg" alt="'.get_the_title().'" />';
                endif;
                echo '<div class="cms-grid-media '.esc_attr($class).'">'.$thumbnail.'</div>';
            ?>
            <div class="cms-carousel-title">
                <?php the_title();?>
            </div>
            <div class="cms-carousel-time">
                <?php the_time('l, F jS, Y');?>
            </div>
            <div class="cms-carousel-categories">
                <?php echo get_the_term_list( get_the_ID(), 'category', 'Category: ', ', ', '' ); ?>
            </div>
        </div>
        <?php
    }
    ?>
</div>