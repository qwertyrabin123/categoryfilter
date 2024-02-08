 <?php 
/* Template Name: gallery Template */ 
get_header(); ?><?php 
$image = get_field('breadcrumb_image');
if( !empty( $image ) ): ?>

<div class="page-header title-area" style="background:url(<?php echo esc_url($image['url']); ?>);background-size: cover;
  background-position: center;
  background-attachment: fixed;">
<?php endif; ?>
                <div class="container">
                    <div class="header-title ">
                        <div class="row">
                            <div class="col-md-12">
                                <h1 class="page-title">Gallery</h1>
                            </div>
                        </div>
                    </div>
                    <div class="breadcrumb_area">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <!-- start breadcrumbs -->
                                <div class="breadcrumbs">
                                    <ul class="bread-crumb clearfix">
                                        <li><a href="<?php echo esc_url(home_url()); ?>">Home</a></li>
                                        <li>Gallery</li>
                                    </ul>
                                </div>
                                <!-- end breadcrumbs -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<style>

/* Style for filter buttons */
.filter-buttons {
    text-align: center;
    margin-bottom: 20px;
}

.filter-button {
    display: inline-block;
    margin: 5px;
    padding: 8px 16px;
    background-color: #007BFF;
    color: #fff;
    border: none;
    cursor: pointer;
    border-radius: 4px;
}

.filter-button.active {
    background-color: #0056b3;
}

/* Style for gallery images */
.images {
    display: flex;
    flex-wrap: wrap;
}



.image img {
    max-width: 100%;
    height: 16rem;
    width: 100%;
    object-fit: cover;
}
.fancybox__container {
  --fancybox-color: #0d0c22;
  --fancybox-bg: #fff;
  --f-spinner-color-1: rgba(0, 0, 0, 0.1);
  --f-spinner-color-2: rgba(17, 24, 28, 0.8);

  flex-direction: column-reverse;
}

.fancybox__toolbar {
  --f-button-bg: #f3f3f4;
  --f-button-hover-bg: #e7e7e9;
  --f-button-active-bg: #e7e7e9;
  --f-button-color: #0d0c22;
  --f-button-hover-color: #0d0c22;

  --f-button-border-radius: 8px;

  --f-button-svg-width: 20px;
  --f-button-svg-height: 20px;

  --f-button-svg-stroke-width: 1.5;
  --f-button-svg-filter: none;

  padding: 30px;
  z-index: 10;
}

.fancybox__slide {
  padding: 12px 0px;
}

.fancybox__thumbs.is-classic {
  --f-thumb-gap: 3px;
  --f-thumb-width: 88px;
  --f-thumb-height: 66px;
  --f-thumb-opacity: 1;
  --f-thumb-outline: 3px;
  --f-thumb-outline-color: #ea4c89;
  --f-thumb-border-radius: 6px;
  --f-thumb-offset: 4px;

  padding: 10px 100px 10px 26px;
  border-bottom: 2px solid #f3f3f4;
}

/* Align thumbnails on right side if not draggable */
.f-thumbs__viewport:not(.is-draggable) .f-thumbs__track {
  justify-content: flex-end;
}

.f-thumbs__slide__img {
  border-radius: 7px;
}
</style>
<div class="gallery container">
    <div class="filter-buttons">
        <?php
        $categories = get_terms(array(
            'taxonomy' => 'gallery_category',
            'hide_empty' => false,
        ));
$activeTerm = 'landscaping';
        foreach ($categories as $category) {
            // Add "active" class to the first category
            $activeClass = ($category === reset($categories)) ? 'active' : '';
            ?>
            <button class="filter-button <?php echo esc_attr($activeClass); ?>" data-filter="<?php echo esc_attr($category->slug); ?>">
                <?php echo esc_html($category->name); ?>
            </button>
            <?php
        }
        ?>
    </div>


    <div class="images">
    <?php
    $args = array(
        'post_type' => 'gallery',
        'posts_per_page' => -1,
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $categories = get_the_terms(get_the_ID(), 'gallery_category');
            
            if ($categories) {
                $categoryClasses = array_map(function ($cat) {
                    return $cat->slug;
                }, $categories);
                ?>

                <div class="image <?php echo esc_attr(implode(' ', $categoryClasses)); ?>">
                    <div class="row">
                        <?php
                        $postData = get_post_meta(get_the_ID());
                        $photos_query = $postData['gallery_data'][0];
                        $photos_array = unserialize($photos_query);
                        $url_array = $photos_array['image_url'];

                        if (!empty($url_array) && is_array($url_array)) {
                            foreach ($url_array as $url) {
                                ?>
                                <div class="col-md-3">
                                    <div class="gallery-main text-center">
                                        <div class="gallery">
                                            <div class="gallery-image">
                                                  <a data-fancybox="gallery" href="<?php echo esc_url($url); ?>">
          <img class="rounded" src="<?php echo esc_url($url); ?>" />
        </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>

                <?php
            }
        }
        wp_reset_postdata();
    } else {
        echo 'No images found.';
    }
    ?>
</div>

</div>

<script>
 document.addEventListener("DOMContentLoaded", function () {
    const filterButtons = document.querySelectorAll(".filter-button");
    const images = document.querySelectorAll(".image");

    filterButtons.forEach((button) => {
        button.addEventListener("click", function () {
            // Remove the "active" class from all buttons
            filterButtons.forEach((btn) => btn.classList.remove("active"));

            // Add the "active" class to the clicked button
            this.classList.add("active");

            const filter = this.getAttribute("data-filter");

            images.forEach((image) => {
                const imageFilters = image.classList;
                if (filter === "all" || imageFilters.contains(filter)) {
                    image.style.display = "block";
                } else {
                    image.style.display = "none";
                }
            });
        });
    });

    // Trigger a click event on the "landscaping" button to activate it by default
    const activeButton = document.querySelector(".filter-button[data-filter='" + <?php echo json_encode($activeTerm); ?> + "']");
    if (activeButton) {
        activeButton.click();
    }
});


</script>
            <?php get_footer(); ?>