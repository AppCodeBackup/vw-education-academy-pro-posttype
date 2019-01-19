<?php 
/*
 Plugin Name: VW Education Academy Pro Posttype
 lugin URI: https://www.vwthemes.com/
 Description: Creating new post type for VW Education Academy Pro Theme.
 Author: VW Themes
 Version: 1.0
 Author URI: https://www.vwthemes.com/
*/

define( 'VW_EDUCATION_ACADEMY_PRO_POSTTYPE_VERSION', '1.0' );
add_action( 'init', 'vw_education_academy_pro_posttype_create_post_type' );

function vw_education_academy_pro_posttype_create_post_type() {

  register_post_type( 'services',
    array(
        'labels' => array(
            'name' => __( 'Services','vw-education-academy-pro-posttype' ),
            'singular_name' => __( 'Services','vw-education-academy-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-tag',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
  );

  register_post_type( 'courses',
    array(
        'labels' => array(
            'name' => __( 'Courses','vw-education-academy-pro-posttype' ),
            'singular_name' => __( 'Courses','vw-education-academy-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-welcome-learn-more',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
  );
  register_post_type( 'events',
    array(
        'labels' => array(
            'name' => __( 'Events','vw-education-academy-pro-posttype' ),
            'singular_name' => __( 'Events','vw-education-academy-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-tag',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
  );
  register_post_type( 'students',
    array(
      'labels' => array(
        'name' => __( 'Students','vw-education-academy-pro-posttype' ),
        'singular_name' => __( 'Students','vw-education-academy-pro-posttype' )
      ),
      'capability_type' => 'post',
      'menu_icon'  => 'dashicons-businessman',
      'public' => true,
      'supports' => array(
        'title',
        'editor',
        'thumbnail'
      )
    )
  );
  register_post_type( 'teachers',
    array(
      'labels' => array(
        'name' => __( 'Teachers','vw-education-academy-pro-posttype' ),
        'singular_name' => __( 'Teachers','vw-education-academy-pro-posttype' )
      ),
        'capability_type' => 'post',
        'menu_icon'  => 'dashicons-businessman',
        'public' => true,
        'supports' => array( 
          'title',
          'editor',
          'thumbnail'
      )
    )
  );
}

// ------------------- Services -----------------------

function vw_education_academy_pro_posttype_images_metabox_enqueue($hook) {
  if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
    wp_enqueue_script('vw-education-academy-pro-posttype-images-metabox', plugin_dir_url( __FILE__ ) . '/js/img-metabox.js', array('jquery', 'jquery-ui-sortable'));

    global $post;
    if ( $post ) {
      wp_enqueue_media( array(
          'post' => $post->ID,
        )
      );
    }

  }
}
add_action('admin_enqueue_scripts', 'vw_education_academy_pro_posttype_images_metabox_enqueue');
// Services Meta
function vw_education_academy_pro_posttype_bn_custom_meta_services() {

    add_meta_box( 'bn_meta', __( 'Services Meta', 'vw-education-academy-pro-posttype' ), 'vw_education_academy_pro_posttype_bn_meta_callback_services', 'services', 'normal', 'high' );
}
/* Hook things in for admin*/
if (is_admin()){
  add_action('admin_menu', 'vw_education_academy_pro_posttype_bn_custom_meta_services');
}

function vw_education_academy_pro_posttype_bn_meta_callback_services( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    $service_icon = get_post_meta( $post->ID, 'meta-image', true );
    ?>
  <div id="property_stuff">
    <table id="list-table">     
      <tbody id="the-list" data-wp-lists="list:meta">
        <tr id="meta-1">
          <p>
            <label for="meta-image"><?php echo esc_html('Icon Image'); ?></label><br>
            <input type="text" name="meta-image" id="meta-image" class="meta-image regular-text" value="<?php echo esc_html($service_icon); ?>">
            <input type="button" class="button image-upload" value="Browse">
          </p>
          <div class="image-preview"><img src="<?php echo $bn_stored_meta['meta-image']; ?>" style="max-width: 250px;"></div>
        </tr>
        
      </tbody>
    </table>
  </div>
  <?php
}

function vw_education_academy_pro_posttype_bn_meta_save_services( $post_id ) {



  if (!isset($_POST['bn_nonce']) || !wp_verify_nonce($_POST['bn_nonce'], basename(__FILE__))) {
    return;
  }

  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }
  // Save Image
  if( isset( $_POST[ 'meta-image' ] ) ) {
      update_post_meta( $post_id, 'meta-image', esc_url_raw($_POST[ 'meta-image' ]) );
  }
  if( isset( $_POST[ 'meta-url' ] ) ) {
      update_post_meta( $post_id, 'meta-url', esc_url_raw($_POST[ 'meta-url' ]) );
  }
}
add_action( 'save_post', 'vw_education_academy_pro_posttype_bn_meta_save_services' );

/* Services shortcode */
function vw_education_academy_pro_posttype_services_func( $atts ) {
  $services = '';
  $services = '<div class="row all-services">';
  $query = new WP_Query( array( 'post_type' => 'services') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=services');
  while ($new->have_posts()) : $new->the_post();
        $custom_url ='';
        $post_id = get_the_ID();
        $excerpt = wp_trim_words(get_the_excerpt(),25);
        $services_image= get_post_meta(get_the_ID(), 'meta-image', true);
        if(get_post_meta($post_id,'meta-services-url',true !='')){$custom_url =get_post_meta($post_id,'meta-services-url',true); } else{ $custom_url = get_permalink(); }
        $services .= '

            <div class="our_services_outer col-md-6 col-sm-6">
              <div class="services_inner">
                <div class="row hover_border">
                  <div class="col-md-3 pra-img-box">
                     <img src="'.esc_url($services_image).'" class="pra-img">
                  </div>
                  <div class="col-md-9">
                    <h4 class="pra-title"> <a href="'.esc_url($custom_url).'">'.esc_html(get_the_title()) .'</a></h4>
                    <div class="short_text">'.$excerpt.'</div>
                  </div>
                </div>
              </div>
            </div>';
    if($k%2 == 0){
      $services.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $services = '<h2 class="center">'.esc_html__('Post Not Found','vw_education_academy_pro_posttype').'</h2>';
  endif;
  return $services;
}

add_shortcode( 'vw-education-academy-services', 'vw_education_academy_pro_posttype_services_func' );


// ------------------ courses --------------------


function vw_education_academy_pro_posttype_bn_courses_meta() {
    add_meta_box( 'vw_education_academy_pro_posttype_bn_meta', __( 'Enter Courses Details','vw-education-academy-pro-posttype' ), 'vw_education_academy_pro_posttype_bn_meta_courses', 'courses', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'vw_education_academy_pro_posttype_bn_courses_meta');
}
/* Adds a meta box for custom post */
function vw_education_academy_pro_posttype_bn_meta_courses( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'vw_education_academy_pro_posttype_bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    $class_price = get_post_meta( $post->ID, 'meta-class-price', true );
    $class_size = get_post_meta( $post->ID, 'meta-class-size', true );
    $class_duration = get_post_meta( $post->ID, 'meta-class-durations', true );
    $class_instuctor = get_post_meta( $post->ID, 'meta-class-instructor', true );
    $class_time = get_post_meta( $post->ID, 'meta-class_time', true );
    ?>
    <div id="courses_custom_stuff">
        <table id="list-table">         
            <tbody id="the-list" data-wp-lists="list:meta">
                <tr id="meta-1">
                  <td class="left">
                      <?php esc_html_e( 'Price', 'vw-education-academy-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="number" name="meta-class-price" id="meta-class-price" value="<?php echo esc_html($class_price); ?>" />
                  </td>
                </tr> 
                <tr id="meta-2">
                  <td class="left">
                    <?php esc_html_e( 'No Of Seats', 'vw-education-academy-pro-posttype' )?>
                  </td>
                  <td class="left" >
                     <input type="text" name="meta-class-size" id="meta-class-size" value="<?php echo esc_html($class_size); ?>" />
                  </td>
                </tr>
                <tr id="meta-3">
                    <td class="left">
                        <?php esc_html_e( 'Duration', 'vw-education-academy-pro-posttype' )?>
                    </td>
                    <td class="left" >
                        <input type="text" name="meta-class-durations" id="meta-class-durations" value="<?php echo esc_html($class_duration); ?>" />
                    </td>
                </tr>              
                <tr id="meta-4">
                    <td class="left">
                        <?php esc_html_e( 'Instructor', 'vw-education-academy-pro-posttype' )?>
                    </td>
                    <td class="left" >
                        <input type="text" name="meta-class-instructor" id="meta-class-instructor" value="<?php echo esc_html($class_instuctor); ?>" />
                    </td>
                </tr> 
                <tr id="meta-6">
                    <td class="left">
                        <?php esc_html_e( 'Class Time', 'vw-education-academy-pro-posttype' )?>
                    </td>
                    <td class="left" >
                        <input type="text" name="meta-class_time" id="meta-class_time" value="<?php echo esc_html($class_time); ?>" />
                    </td>
                </tr>     
            </tbody>
        </table>
    </div>
    <?php
}
/* Saves the custom fields meta input */
function vw_education_academy_pro_posttype_bn_metadesig_courses_save( $post_id ) {
  
    if( isset( $_POST[ 'meta-class-price' ] ) ) {
        update_post_meta( $post_id, 'meta-class-price', sanitize_text_field($_POST[ 'meta-class-price' ]) );
    }
    
    if( isset( $_POST[ 'meta-class-size' ] ) ) {
        update_post_meta( $post_id, 'meta-class-size', sanitize_text_field($_POST[ 'meta-class-size' ]) );
    }
    if( isset( $_POST[ 'meta-class-durations' ] ) ) {
        update_post_meta( $post_id, 'meta-class-durations', sanitize_text_field($_POST[ 'meta-class-durations' ]) );
    }
    if( isset( $_POST[ 'meta-class-instructor' ] ) ) {
        update_post_meta( $post_id, 'meta-class-instructor', sanitize_text_field($_POST[ 'meta-class-instructor' ]) );
    }
    if( isset( $_POST[ 'meta-class_time' ] ) ) {
        update_post_meta( $post_id, 'meta-class_time', sanitize_text_field($_POST[ 'meta-class_time' ]) );
    }
    
}
add_action( 'save_post', 'vw_education_academy_pro_posttype_bn_metadesig_courses_save' );

/* courses shortcode */
function vw_education_academy_pro_posttype_courses_func( $atts ) {
  $courses = '';
  $courses = '<div class="row all-courses">';
  $query = new WP_Query( array( 'post_type' => 'courses') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=courses');
  while ($new->have_posts()) : $new->the_post();

        $post_id = get_the_ID();
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        $url = $thumb['0'];
        $custom_url ='';
        $excerpt = wp_trim_words(get_the_excerpt(),10);
        $size= get_post_meta($post_id,'meta-class-size',true);
        $duration= get_post_meta($post_id,'meta-class-durations',true);
        $time= get_post_meta($post_id,'meta-class_time',true);
        $price= get_post_meta($post_id,'meta-class-price',true);
        
        if(get_post_meta($post_id,'meta-courses-url',true !='')){$custom_url =get_post_meta($post_id,'meta-courses-url',true); } else{ $custom_url = get_permalink(); }
        $courses .= '

            <div class="col-lg-6 our_courses_outer">
              <div class="row hover_border">
                <div class="col-lg-6 courses-img-box">
                  <img class="courses-img" src="'.esc_url($thumb_url).'" alt="attorney-thumbnail" />
                </div>
                <div class="col-lg-6">
                  <h4><a href="'.esc_url($custom_url).'">'.esc_html(get_the_title()) .'</a></h4>
                  <div class="short_text">'.$excerpt.'</div>
                  <div class="course-meta">
                    <span>
                      <i class="far fa-calendar-alt"></i>
                      '.$duration.'
                    </span>
                    <span>
                      <i class="fas fa-user"></i>
                      '.$size.'
                    </span>
                    <span>
                      <i class="far fa-clock"></i>
                      '.$time.'
                    </span>
                  </div>
                </div>
              </div>
            </div>';
    if($k%2 == 0){
      $courses.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $courses = '<h2 class="center">'.esc_html__('Post Not Found','vw_education_academy_pro_posttype').'</h2>';
  endif;
  return $courses;
}

add_shortcode( 'vw-education-academy-courses', 'vw_education_academy_pro_posttype_courses_func' );


// --------------------------- Events --------------------------

// Events Meta
function vw_education_academy_pro_posttype_bn_custom_meta_events() {

    add_meta_box( 'bn_meta', __( 'Events Meta', 'vw-education-academy-pro-posttype' ), 'vw_education_academy_pro_posttype_bn_meta_callback_events', 'events', 'normal', 'high' );
}
/* Hook things in for admin*/
if (is_admin()){
  add_action('admin_menu', 'vw_education_academy_pro_posttype_bn_custom_meta_events');
}

function vw_education_academy_pro_posttype_bn_meta_callback_events( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    $event_date = get_post_meta( $post->ID, 'events-date', true );
    $event_time = get_post_meta( $post->ID, 'events-time', true );
    $event_location = get_post_meta( $post->ID, 'events-location', true );
    ?>
  <div id="property_stuff">
    <table id="list-table">     
      <tbody id="the-list" data-wp-lists="list:meta">
        <tr id="meta-1">
          <td class="left">
            <?php esc_html_e( 'Event Date', 'vw-education-academy-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="text" name="events-date" id="events-date" class="meta-duration regular-text" value="<?php echo esc_html($event_date); ?>">
          </td>
        </tr>
        <tr id="meta-2">
          <td class="left">
            <?php esc_html_e( 'Event Time', 'vw-education-academy-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="text" name="events-time" id="events-time" class="regular-text" value="<?php echo esc_html($event_time); ?>">
          </td>
        </tr>
        <tr id="meta-3">
          <td class="left">
            <?php esc_html_e( 'Event Location', 'vw-education-academy-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="text" name="events-location" id="events-location" class="regular-text" value="<?php echo esc_html($event_location); ?>">
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <?php
}

function vw_education_academy_pro_posttype_bn_meta_save_events( $post_id ) {



  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }
  if( isset( $_POST[ 'events-date' ] ) ) {
    update_post_meta( $post_id, 'events-date', sanitize_text_field($_POST[ 'events-date' ]) );
  }
  if( isset( $_POST[ 'events-time' ] ) ) {
    update_post_meta( $post_id, 'events-time', sanitize_text_field($_POST[ 'events-time' ]) );
  }
  if( isset( $_POST[ 'events-location' ] ) ) {
    update_post_meta( $post_id, 'events-location', sanitize_text_field($_POST[ 'events-location' ]) );
  }
}
add_action( 'save_post', 'vw_education_academy_pro_posttype_bn_meta_save_events' );


/* Events shortcode */
function vw_education_academy_pro_posttype_events_func( $atts ) {
  $events = '';
  $events = '<div class="row all-events">';
  $query = new WP_Query( array( 'post_type' => 'events') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=events');
  while ($new->have_posts()) : $new->the_post();

        $post_id = get_the_ID();
         $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        $url = $thumb['0'];
        $custom_url ='';
        $excerpt = wp_trim_words(get_the_excerpt(),10);
        $events_date= get_post_meta($post_id,'events-date',true);
        $events_time= get_post_meta($post_id,'events-time',true);
        $events_location= get_post_meta($post_id,'events-location',true);
        if(get_post_meta($post_id,'meta-events-url',true !='')){$custom_url =get_post_meta($post_id,'meta-events-url',true); } else{ $custom_url = get_permalink(); }
        $events .= '

            <div class="our_events_outer col-lg-6 col-md-4 col-sm-6">
              <div class="events_inner">
                <div class="row hover_border">
                  <div class="col-lg-6">
                    <img class="courses-img" src="'.esc_url($thumb_url).'" alt="attorney-thumbnail" />
                  </div>
                  <div class="col-lg-6">
                    <h4><a href="'.esc_url($custom_url).'">'.esc_html(get_the_title()) .'</a></h4>
                    <div class="short_text">'.$excerpt.'</div>
                  </div>
                </div>
                <div class="event-meta">
                  <span>
                    <i class="far fa-calendar-alt"></i>
                    '.$events_date.'
                  </span>
                  <span>
                    <i class="fas fa-map-marker-alt"></i>
                    '.$events_location.'
                  </span>
                  <span>
                    <i class="far fa-clock"></i>
                    '.$events_time.'
                  </span>
                </div>
              </div>
            </div>';
    if($k%2 == 0){
      $events.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $events = '<h2 class="center">'.esc_html__('Post Not Found','vw_education_academy_pro_posttype').'</h2>';
  endif;
  return $events;
}

add_shortcode( 'vw-education-academy-events', 'vw_education_academy_pro_posttype_events_func' );


/*---------------------------------- Testimonial section -------------------------------------*/
/* Adds a meta box to the Testimonial editing screen */
function vw_education_academy_pro_posttype_bn_testimonial_meta_box() {
  add_meta_box( 'vw-education-academy-pro-posttype-testimonial-meta', __( 'Enter Details', 'vw-education-academy-pro-posttype' ), 'vw_education_academy_pro_posttype_bn_testimonial_meta_callback', 'students', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'vw_education_academy_pro_posttype_bn_testimonial_meta_box');
}

/* Adds a meta box for custom post */
function vw_education_academy_pro_posttype_bn_testimonial_meta_callback( $post ) {
  wp_nonce_field( basename( __FILE__ ), 'vw_education_academy_pro_posttype_posttype_testimonial_meta_nonce' );
  $bn_stored_meta = get_post_meta( $post->ID );
  $desigstory = get_post_meta( $post->ID, 'vw_education_academy_pro_posttype_testimonial_desigstory', true );
  ?>
  <div id="students_custom_stuff">
    <table id="list">
      <tbody id="the-list" data-wp-lists="list:meta">
        <tr id="meta-1">
          <td class="left">
            <?php _e( 'Designation', 'vw-education-academy-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="text" name="vw_education_academy_pro_posttype_testimonial_desigstory" id="vw_education_academy_pro_posttype_testimonial_desigstory" value="<?php echo esc_attr( $desigstory ); ?>" />
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <?php
}

/* Saves the custom meta input */
function vw_education_academy_pro_posttype_bn_metadesig_save( $post_id ) {
  if (!isset($_POST['vw_education_academy_pro_posttype_posttype_testimonial_meta_nonce']) || !wp_verify_nonce($_POST['vw_education_academy_pro_posttype_posttype_testimonial_meta_nonce'], basename(__FILE__))) {
    return;
  }

  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

  // Save desig.
  if( isset( $_POST[ 'vw_education_academy_pro_posttype_testimonial_desigstory' ] ) ) {
    update_post_meta( $post_id, 'vw_education_academy_pro_posttype_testimonial_desigstory', sanitize_text_field($_POST[ 'vw_education_academy_pro_posttype_testimonial_desigstory']) );
  }

}

add_action( 'save_post', 'vw_education_academy_pro_posttype_bn_metadesig_save' );

/*---------------------------------- students shortcode --------------------------------------*/
function vw_education_academy_pro_posttype_testimonial_func( $atts ) {
  $testimonial = '';
  $testimonial = '<div class="row all-testimonial">';
  $query = new WP_Query( array( 'post_type' => 'students') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=students');
  while ($new->have_posts()) : $new->the_post();

        $post_id = get_the_ID();
         $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        $url = $thumb['0'];
        $custom_url ='';
        
        $excerpt = wp_trim_words(get_the_excerpt(),25);
        $tdegignation= get_post_meta($post_id,'vw_education_academy_pro_posttype_testimonial_desigstory',true);
        if(get_post_meta($post_id,'meta-testimonial-url',true !='')){$custom_url =get_post_meta($post_id,'meta-testimonial-url',true); } else{ $custom_url = get_permalink(); }
        $testimonial .= '

            <div class="our_testimonial_outer col-lg-4 col-md-4 col-sm-6">
              <div class="testimonial_inner">
                <div class="row hover_border">
                  <div class="col-md-12">
                     <img class="classes-img" src="'.esc_url($thumb_url).'" alt="attorney-thumbnail" />
                    <h4><a href="'.esc_url($custom_url).'">'.esc_html(get_the_title()) .'</a></h4>
                    <div class="tdesig">'.$tdegignation.'</div>
                    <div class="short_text">'.$excerpt.'</div>
                  </div>
                </div>
              </div>
            </div>';
    if($k%2 == 0){
      $testimonial.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $testimonial = '<h2 class="center">'.esc_html__('Post Not Found','vw_education_academy_pro_posttype').'</h2>';
  endif;
  return $testimonial;
}

add_shortcode( 'vw-education-academy-students', 'vw_education_academy_pro_posttype_testimonial_func' );

/*-------------------------------------- Teacher-------------------------------------------*/
/* Adds a meta box for Designation */
function vw_education_academy_pro_posttype_bn_teachers_meta() {
    add_meta_box( 'vw_education_academy_pro_posttype_bn_meta', __( 'Enter Details','vw-education-academy-pro-posttype' ), 'vw_education_academy_pro_posttype_ex_bn_meta_callback', 'teachers', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'vw_education_academy_pro_posttype_bn_teachers_meta');
}
/* Adds a meta box for custom post */
function vw_education_academy_pro_posttype_ex_bn_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'vw_education_academy_pro_posttype_bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    $teacher_email = get_post_meta( $post->ID, 'meta-teacher-email', true );
    $teacher_facebook = get_post_meta( $post->ID, 'meta-tfacebookurl', true );
    $teacher_linkedin = get_post_meta( $post->ID, 'meta-tlinkdenurl', true );
    $teacher_twitter = get_post_meta( $post->ID, 'meta-ttwitterurl', true );
    $teacher_gplus = get_post_meta( $post->ID, 'meta-tgoogleplusurl', true );
    $teacher_desig = get_post_meta( $post->ID, 'meta-designation', true );
    $teacher_instagram = get_post_meta( $post->ID, 'meta-tinstagram', true );
    $teacher_pinterest = get_post_meta( $post->ID, 'meta-pinterest', true );
    ?>
  
    <div id="agent_custom_stuff">
        <table id="list-table">         
            <tbody id="the-list" data-wp-lists="list:meta">
                <tr id="meta-1">
                  <td class="left">
                      <?php _e( 'Email', 'vw-education-academy-pro-posttype' )?>
                  </td>
                  <td class="left" >
                      <input type="text" name="meta-teacher-email" id="meta-teacher-email" value="<?php echo esc_html($teacher_email); ?>" />
                  </td>
                </tr>
                <tr id="meta-3">
                  <td class="left">
                    <?php _e( 'Facebook Url', 'vw-education-academy-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-tfacebookurl" id="meta-tfacebookurl" value="<?php echo esc_html($teacher_facebook); ?>" />
                  </td>
                </tr>
                <tr id="meta-4">
                  <td class="left">
                    <?php _e( 'Linkedin Url', 'vw-education-academy-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-tlinkdenurl" id="meta-tlinkdenurl" value="<?php echo esc_html($teacher_linkedin); ?>" />
                  </td>
                </tr>
                <tr id="meta-5">
                  <td class="left">
                    <?php _e( 'Twitter Url', 'vw-education-academy-pro-posttype' ); ?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-ttwitterurl" id="meta-ttwitterurl" value="<?php echo esc_html($teacher_twitter); ?>" />
                  </td>
                </tr>
                <tr id="meta-6">
                  <td class="left">
                    <?php _e( 'GooglePlus Url', 'vw-education-academy-pro-posttype' ); ?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-tgoogleplusurl" id="meta-tgoogleplusurl" value="<?php echo esc_html($teacher_gplus); ?>" />
                  </td>
                </tr>
                <tr id="meta-7">
                  <td class="left">
                    <?php _e( 'Instagram Url', 'vw-education-academy-pro-posttype' ); ?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-tinstagram" id="meta-tinstagram" value="<?php echo esc_html($teacher_instagram); ?>" />
                  </td>
                </tr>
                <tr id="meta-8">
                  <td class="left">
                    <?php _e( 'Pinterest Url', 'vw-education-academy-pro-posttype' ); ?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-pinterest" id="meta-pinterest" value="<?php echo esc_html($teacher_pinterest); ?>" />
                  </td>
                </tr>
                <tr id="meta-9">
                  <td class="left">
                    <?php _e( 'Designation', 'vw-education-academy-pro-posttype' ); ?>
                  </td>
                  <td class="left" >
                    <input type="text" name="meta-designation" id="meta-designation" value="<?php echo esc_html($teacher_desig); ?>" />
                  </td>
                </tr>

            </tbody>
        </table>
    </div>
    <?php
}
/* Saves the custom Designation meta input */
function vw_education_academy_pro_posttype_ex_bn_metadesig_save( $post_id ) {
    if( isset( $_POST[ 'meta-teacher-email' ] ) ) {
        update_post_meta( $post_id, 'meta-teacher-email', esc_html($_POST[ 'meta-teacher-email' ]) );
    }
    
    // Save facebookurl
    if( isset( $_POST[ 'meta-tfacebookurl' ] ) ) {
        update_post_meta( $post_id, 'meta-tfacebookurl', esc_url($_POST[ 'meta-tfacebookurl' ]) );
    }
    // Save linkdenurl
    if( isset( $_POST[ 'meta-tlinkdenurl' ] ) ) {
        update_post_meta( $post_id, 'meta-tlinkdenurl', esc_url($_POST[ 'meta-tlinkdenurl' ]) );
    }
    if( isset( $_POST[ 'meta-ttwitterurl' ] ) ) {
        update_post_meta( $post_id, 'meta-ttwitterurl', esc_url($_POST[ 'meta-ttwitterurl' ]) );
    }
    // Save googleplusurl
    if( isset( $_POST[ 'meta-tgoogleplusurl' ] ) ) {
        update_post_meta( $post_id, 'meta-tgoogleplusurl', esc_url($_POST[ 'meta-tgoogleplusurl' ]) );
    }

    // Save Instagram
    if( isset( $_POST[ 'meta-tinstagram' ] ) ) {
        update_post_meta( $post_id, 'meta-tinstagram', esc_url($_POST[ 'meta-tinstagram' ]) );
    }

    // Save Pinterest
    if( isset( $_POST[ 'meta-pinterest' ] ) ) {
        update_post_meta( $post_id, 'meta-pinterest', esc_url($_POST[ 'meta-pinterest' ]) );
    }
    // Save designation
    if( isset( $_POST[ 'meta-designation' ] ) ) {
        update_post_meta( $post_id, 'meta-designation', esc_html($_POST[ 'meta-designation' ]) );
    }
}
add_action( 'save_post', 'vw_education_academy_pro_posttype_ex_bn_metadesig_save' );

add_action( 'save_post', 'bn_meta_save' );
/* Saves the custom meta input */
function bn_meta_save( $post_id ) {
  if( isset( $_POST[ 'vw_education_academy_pro_posttype_teachers_featured' ] )) {
      update_post_meta( $post_id, 'vw_education_academy_pro_posttype_teachers_featured', esc_attr(1));
  }else{
    update_post_meta( $post_id, 'vw_education_academy_pro_posttype_teachers_featured', esc_attr(0));
  }
}
/*------------------------------------- SHORTCODES -------------------------------------*/

/*------------------------------------- Teachers Shorthcode -------------------------------------*/
function vw_education_academy_pro_posttype_teachers_func( $atts ) {
  $teachers = '';
  $teachers = '<div class="row all-teachers">';
  $query = new WP_Query( array( 'post_type' => 'teachers') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=teachers');
  while ($new->have_posts()) : $new->the_post();
        $post_id = get_the_ID();
         $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        $url = $thumb['0'];
        $custom_url ='';
        $excerpt = wp_trim_words(get_the_excerpt(),10);
        $teachers_desig= get_post_meta($post_id,'meta-designation',true);
        $facebookurl= get_post_meta($post_id,'meta-tfacebookurl',true);
        $linkedin=get_post_meta($post_id,'meta-tlinkdenurl',true);
        $twitter=get_post_meta($post_id,'meta-ttwitterurl',true);
        $googleplus=get_post_meta($post_id,'meta-tgoogleplusurl',true);
        if(get_post_meta($post_id,'meta-teachers-url',true !='')){$custom_url =get_post_meta($post_id,'meta-teachers-url',true); } else{ $custom_url = get_permalink(); }
        $teachers .= '

            <div class="our_teachers_outer col-lg-4 col-md-4 col-sm-6">
              <div class="teachers_inner">
                <div class="row hover_border">
                  <div class="col-md-12">
                     <img class="classes-img" src="'.esc_url($thumb_url).'" alt="attorney-thumbnail" />
                     <div class="tdesig">'.$teachers_desig.'</div>
                    <h4><a href="'.esc_url($custom_url).'">'.esc_html(get_the_title()) .'</a></h4>
                    <div class="short_text">'.$excerpt.'</div>
                    <div class="att_socialbox">';
                        if($facebookurl != ''){
                          $teachers .= '<a class="" href="'.esc_url($facebookurl).'" target="_blank"><i class="fab fa-facebook-f"></i></a>';
                        } if($twitter != ''){
                          $teachers .= '<a class="" href="'.esc_url($twitter).'" target="_blank"><i class="fab fa-twitter"></i></a>';
                        } if($googleplus != ''){
                          $teachers .= '<a class="" href="'.esc_url($googleplus).'" target="_blank"><i class="fab fa-google-plus-g"></i></a>';
                        } if($linkedin != ''){
                          $teachers .= '<a class="" href="'.esc_url($linkedin).'" target="_blank"><i class="fab fa-linkedin-in"></i></a>';
                        }
                      $teachers .= '</div>
                  </div>
                </div>
              </div>
            </div>';
    if($k%2 == 0){
      $teachers.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $teachers = '<h2 class="center">'.esc_html__('Post Not Found','vw_education_academy_pro_posttype').'</h2>';
  endif;
  return $teachers;
}

add_shortcode( 'vw-education-academy-teachers', 'vw_education_academy_pro_posttype_teachers_func' );
