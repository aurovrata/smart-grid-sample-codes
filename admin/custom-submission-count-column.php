<?php // add the following lines of code to your functions.php file.

/**
* add a column to the wpcf7_contact_form custom post table.
*/
add_filter('manage_wpcf7_contact_form_posts_columns' , 'add_admin_cf7_submissions_column');
function add_admin_cf7_submissions_column($columns) {
    return array_merge($columns, array('cf7sg-submissions' => __('Count') ) );
}
/**
* print the count for each form.
*/
add_action( 'manage_posts_custom_column' , 'print_cf7_submissions_column', 10, 2 );
function print_cf7_submissions_column( $column, $post_id ) {
    switch ( $column ) {
      case 'cf7sg-submissions':
        $count = get_post_meta( $post_id, '_cf7sg-submissions', true );
        if(empty($count)) $count = 0;
        echo $count;
        break;
    }
}
/**
* keep track of each form submission, except for amdin user who is likely testing.
*/
add_action('wpcf7_before_send_mail', 'track_cf7sg_submissions');
function track_cf7sg_submissions($form){
  if(empty($form)) return;
  switch(get_current_user_id()){
    case 1: //admin user, don't count submissions.
      break;
    case 0: //no user logged;
    default: //anyone else.
      $count = get_post_meta( $form->id(), '_cf7sg-submissions', true );
      if(empty($count)) $count=0;
      $count++;
      update_post_meta($form->id(), '_cf7sg-submissions',$count);
      break;
  }
}
/**
* OPTIONAL: Enqueue your custom stylesheet if you have one.
*/
add_action('cf7sg_enqueue_admin_table_styles', 'add_form_table_css');
function add_form_table_css(){
  wp_enqueue_style('my-custom-cf7-admin-css', get_stylesheet_uri().'css/cf7-admin.css');
}
/**
* OPTIONAL: Enqueue your custom javascript if you have one.
*/
add_action('cf7sg_enqueue_admin_table_scripts', 'add_form_table_script');
function add_form_table_script(){
  wp_enqueue_style('my-custom-cf7-admin-js', get_stylesheet_uri().'js/cf7-admin.js');
}

