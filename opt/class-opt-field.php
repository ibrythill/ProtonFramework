<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;


if(!class_exists('_Proton__Field')):

class _Proton__Field{

	/**
	 * Holds the instance of this class.
	 */
	protected static $instance = null;

	/**
	 * Holds the id of field
	 */
	private static $tID;

	/**
	 * Field Constructor.
	 */
	protected function __construct( ){
	}

	/**
	 * Returns the instance.
	 *
	 * @since  1.1.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		if ( null == static::$instance ) {
            static::$instance = new static;
        }
        return static::$instance;
	}


	/**
	 * make function.
	 *
	 * @access public
	 * @static
	 * @param mixed $options
	 * @return void
	 */
	public static function make( $options ){

		return $options;
	}


	/**
	 * Field underscore template.
	*/
	public static function template(){

		return $options;
	}



	/**
	 * sanitize function.
	 *
	 * @access public
	 * @static
	 * @param mixed $value
	 * @return void
	 */
	public static function sanitize($value){
		if(is_array($value)){
			return self::sanitize_array($value);
		}else{
			return sanitize_text_field($value);
		}
	}

	/**
	 * queue function.
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	public static function queue(){

	}

	/**
	 * save_handle function.
	 *
	 * @access public
	 * @static
	 * @param mixed $args
	 * @return void
	 */
	public static function save_handle($args){

		extract( wp_parse_args( $args,
			array (
				'post_id' => '',
				'meta_type' => 'post',
				'custom_id' => '',
				'opt_field' => array()
			) )
		);

		$field_id = array_key_exists('name', $opt_field) ? $opt_field['name'] : $opt_field['id'];
		$current_value = '';

	    $current_value = get_metadata( $meta_type, $post_id, $field_id, true );


	    // Sanitize the input.
		$posted_value = array_key_exists($custom_id, $_POST) ? $_POST[$custom_id] : '';

		$posted_value = self::sanitize($posted_value);


	    if ( isset( $posted_value ) ) {
			// Otherwise, if no value is set, delete the post meta.
			if($posted_value == "") {
				//delete meta
				delete_metadata( $meta_type, $post_id, $field_id, get_metadata( $meta_type, $post_id, $field_id, true ) );
			}else{
				//update meta
				update_metadata( $meta_type, $post_id, $field_id, $posted_value );
			}// End IF Statement
		} else {
			//delete meta
			delete_metadata( $meta_type, $post_id, $field_id, $current_value ); // Deletes opt

		} // End IF Statement
	}

	/**
	 * Sanitize a multidimensional array
	 *
	 * @access public
	 * @static
	 * @param array $data (default: array())
	 * @return (array) the sanitized array
	 */
	public static function sanitize_array ($data = array()) {
		if (!is_array($data) || !count($data)) {
			return array();
		}
		foreach ($data as $k => $v) {
			if (!is_array($v) && !is_object($v)) {
				$data[$k] = sanitize_text_field($v);//htmlspecialchars(trim($v));
			}
			if (is_array($v)) {
				$data[$k] = self::sanitize_array($v);
			}
		}
		return $data;
	}

	/**
	 * ->get_options(); This is used to get options from the database
	 *
	 * @since ReduxFramework 3.0.0
	 */
	public static function get_wordpress_data($type = false, $args = array()) {
		$data = "";
		if ( !empty($type) ) {

            //$data = apply_filters( 'redux/options/'.$this->args['opt_name'].'/wordpress_data/'.$type.'/', $data ); // REMOVE LATER
            //$data = apply_filters( 'redux/options/'.$this->args['opt_name'].'/data/'.$type, $data );

			/**
				Use data from Wordpress to populate options array
			**/
			if (!empty($type) && empty($data)) {
				if (empty($args)) {
					$args = array();
				}
				$data = array();
				$args = wp_parse_args($args, array());
				if ($type == "categories" || $type == "category") {
					$cats = get_categories($args);
					if (!empty($cats)) {
						foreach ( $cats as $cat ) {
							$data[$cat->term_id] = $cat->name;
						}//foreach
					} // If
				} else if ($type == "menus" || $type == "menu") {
					$menus = wp_get_nav_menus($args);
					if(!empty($menus)) {
						foreach ($menus as $item) {
							$data[$item->term_id] = $item->name;
						}//foreach
					}//if
				} else if ($type == "pages" || $type == "page") {
					$pages = get_pages($args);
					if (!empty($pages)) {
						foreach ( $pages as $page ) {
							$data[$page->ID] = $page->post_title;
						}//foreach
					}//if
                } else if ($type == "terms" || $type == "term") {
                    $taxonomies = $args['taxonomies'];
                    unset($args['taxonomies']);
                    if (empty($args)) {
                        $args = array();
                    }
                    if (empty($args['args'])) {
                        $args['args'] = array();
                    }
                    $terms = get_terms($taxonomies, $args['args']); // this will get nothing
                    if (!empty($terms)) {
                        foreach ( $terms as $term ) {
                            $data[$term->term_id] = $term->name;
                        }//foreach
                    } // If
                } else if ($type == "posts" || $type == "post") {
					$posts = get_posts($args);
					if (!empty($posts)) {
						foreach ( $posts as $post ) {
							$data[$post->ID] = $post->post_title;
						}//foreach
					}//if
				} else if ($type == "post_type" || $type == "post_types") {
                    global $wp_post_types;
                    $defaults = array(
                        'public' => true,
                        'publicly_queryable' => true,
                        'exclude_from_search' => false,
                        '_builtin' => false,
                    );
                    $args = wp_parse_args( $args, $defaults );
                    $output = 'names';
                    $operator = 'and';
                    $post_types = get_post_types($args, $output, $operator);
                    $post_types['page'] = 'page';
                    $post_types['post'] = 'post';
                    ksort($post_types);

                    foreach ( $post_types as $name => $title ) {
                        if ( isset($wp_post_types[$name]->labels->menu_name) ) {
                            $data[$name] = $wp_post_types[$name]->labels->menu_name;
                        } else {
                            $data[$name] = ucfirst($name);
                        }
                    }
				} else if ($type == "tags" || $type == "tag") { // NOT WORKING!
					$tags = get_tags($args);
					if (!empty($tags)) {
						foreach ( $tags as $tag ) {
							$data[$tag->term_id] = $tag->name;
						}//foreach
					}//if
				} else if ($type == "menu_location" || $type == "menu_locations") {
					global $_wp_registered_nav_menus;
					foreach($_wp_registered_nav_menus as $k => $v) {
	           			$data[$k] = $v;
	        		}
				}//if
				else if ($type == "elusive-icons" || $type == "elusive-icon" || $type == "elusive" ||
						 $type == "font-icon" || $type == "font-icons" || $type == "icons") {
					$font_icons = apply_filters('redux-font-icons',array()); // REMOVE LATER
                    $font_icons = apply_filters('redux/font-icons',$font_icons);
					foreach($font_icons as $k) {
	           			$data[$k] = $k;
	        		}
				}else if ($type == "roles") {
					/** @global WP_Roles $wp_roles */
					global $wp_roles;
                    $data = $wp_roles->get_names();
				}else if ($type == "sidebars" || $type == "sidebar") {
                    /** @global array $wp_registered_sidebars */
                    global $wp_registered_sidebars;
                    foreach ($wp_registered_sidebars as $key=>$value) {
                        $data[$key] = $value['name'];
                    }
                }else if ($type == "capabilities") {
					/** @global WP_Roles $wp_roles */
					global $wp_roles;
                    foreach( $wp_roles->roles as $role ){
                        foreach( $role['capabilities'] as $key => $cap ){
                            $data[$key] = ucwords(str_replace('_', ' ', $key));
                        }
                    }
				}/*else if ($type == "callback") {
					$data = call_user_func($args[0]);
				}*///if
			}//if
		}//if

		return $data;
	}


} // End Class

endif;
