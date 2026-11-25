<?php
//to create custom post type by script
class custom_post_types {

  var $single; //this represents the singular name of the post type
  var $plural; //this represents the plural name of the post type
  var $taxonomy_single; 
  var $taxonomy_plural;
  var $taxonomy_slug;
  var $taxonomy;
  var $type; //this is the actual type
  var $taxonomies = array();
  var $publicly_queryable = true;
  var $post_type;

	function init($options){
		foreach($options as $key => $value){
			$this->$key = $value;
		}
	}

	function add_post_type(){
		$labels = array(
			'name' => _x($this->plural, 'post type general name'),
			'singular_name' => _x($this->single, 'post type singular name'),
			'add_new' => _x('Add ' . $this->single, $this->single),
			'add_new_item' => __('Add New ' . $this->single),
			'edit_item' => __('Edit ' . $this->single),
			'new_item' => __('New ' . $this->single),
			'view_item' => __('View ' . $this->single),
			'search_items' => __('Search ' . $this->plural),
			'not_found' =>  __('No ' . $this->plural . ' Found'),
			'not_found_in_trash' => __('No ' . $this->plural . ' found in Trash'),
			'parent_item_colon' => ''
		);
		$options = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => $this->publicly_queryable,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => $this->slug,
							    'with_front' => true),
			'capability_type' => 'post',
			'hierarchical' => null,
			'menu_position' => $this->menu_position,
			//'supports' => array('title','editor','author','thumbnail','excerpt','comments'),
			'supports' => $this->supports, 
			'taxonomies' => $this->taxonomies, 
			'menu_icon' => $this->menu_icon,
			
			
		);
		register_post_type($this->type, $options);
	}
	
	function add_taxonomy(){
		$labels = array(							
			'name' => _x( $this->taxonomy_plural, 'taxonomy general name' ),
			'singular_name' => _x( $this->taxonomy_single, 'taxonomy singular name' ),
			'search_items' =>  __( 'Search ' . $this->taxonomy_plural ),
			'all_items' => __( 'All ' . $this->taxonomy_plural ),
			'parent_item' => __( 'Parent ' . $this->taxonomy_single ),
			'parent_item_colon' => __( 'Parent ' . $this->taxonomy_single . ':' ),
			'edit_item' => __( 'Edit ' .$this->taxonomy_single ), 
			'update_item' => __( 'Update ' . $this->taxonomy_single),
			'add_new_item' => __( 'Add New ' . $this->taxonomy_single ),
			'new_item_name' => __( 'New ' . $this->taxonomy_single . ' Name' ),
			'menu_name' => __( $this->taxonomy_plural ),			
		);
		$args = array(
	
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => false,
			'show_ui'           => true,
			'show_tagcloud'     => false,
			'hierarchical'      => true,
			'rewrite'           => array( 'slug' => $this->taxonomy_slug ),					
		);
	
		register_taxonomy( $this->taxonomy, $this->post_type, $args );
		
	}
	function add_messages ( $messages ) {

		$messages[$this->type] = array(
			0 => '', 
			1 => sprintf( __($this->single . ' updated. <a href="%s">View ' . $this->single . '</a>'), esc_url( get_permalink($post_ID) ) ),
			2 => __('Custom field updated.'),
			3 => __('Custom field deleted.'),
			4 => __($this->single . ' updated.'),
			5 => isset($_GET['revision']) ? sprintf( __($this->single .' restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __($this->single . ' published. <a href="%s">View ' . $this->single . '</a>'), esc_url( get_permalink($post_ID) ) ),
			7 => __('Book saved.'),
			8 => sprintf( __($this->single . ' submitted. <a target="_blank" href="%s">Preview ' . $this->single . '</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			9 => sprintf( __($this->single . ' scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview ' . $this->single . '</a>'),
			  date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( __($this->single . ' draft updated. <a target="_blank" href="%s">Preview ' . $this->single . '</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		);

		return $messages;
	}

}

################################################################################
// Add custom post types
################################################################################






?>