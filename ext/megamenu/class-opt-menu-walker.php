<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;

/** Custom menu walker **/
class _Proton__Opt_Menu_Walker extends Walker_Nav_Menu {

    protected $index = 0;
	protected $menuItemOptions = 0;
	protected $activelink = 'active';

	var $tree_type = array( 'post_type', 'taxonomy', 'custom' );

	var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );
	private $pmm_item_count = 0;

    /**
	 * Traverse elements to create list from elements.
	 *
	 * Calls parent function in wp-includes/class-wp-walker.php
	 */
	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
//var_dump($element);
		if ( !$element )
			return;
		//Find parent menu item's id
		$parent_id 		= get_post_meta($element->ID, '_menu_item_menu_item_parent', 1);
		$pmm_mega 		= get_post_meta($element->ID, 'pmm_mega', 1);
		$pmm_trigger 	= get_post_meta($element->ID, 'pmm_trigger', 1);


		//Add indicators for top level menu items with submenus
		$id_field = $this->db_fields['id'];
		if ( $depth == 0 && !empty( $children_elements[ $element->$id_field ] ) ) {
			if('true' === $pmm_mega){
				$class_prefix = 'mega-';
			}else{
				$class_prefix = 'simple-';
				$element->classes[] = 'pmm_on_hover';
			}
			$element->classes[] = $class_prefix.'with-sub';
			$element->classes[] = 'children-'.count($children_elements[ $element->$id_field ]);
		}
		if('true' === $pmm_mega){
			if('click' === $pmm_trigger){
				$element->classes[] = 'pmm_on_click';
			}else{
				$element->classes[] = 'pmm_on_hover';
			}
		}

		$element->classes[] = 'item-count-'.$element->menu_order;

		if($depth == 2 && !empty( $children_elements[ $element->$id_field ] ) ){
			$element->classes[] = 'pmm-has-children';
		}


		if ( is_object( $args[0] ) ) {
			$args[0]->mega_child 	= $depth == 0 ? $pmm_mega : ($args[0]->mega_child ? $args[0]->mega_child : 'false') ;
            $args[0]->has_children 	= !empty( $children_elements[$element->$id_field] );
            $args[0]->parent_id 	= $element->ID;
            if(!empty( $children_elements[$element->$id_field] )){$args[0]->count_children = count($children_elements[$element->$id_field]);}
        }

		//Generate columns
		if($depth == 1 /*&& !empty( $children_elements[ $element->$id_field ] )*/){
			//$element->classes[] = 'col-xs-4';
			$user_col = get_post_meta($parent_id, 'pmm_user_col', 1) ? get_post_meta($parent_id, 'pmm_user_col', 1) : 'auto';
			$this->pmm_item_count++;
			//automatic column count, max 12
			if($user_col === 'auto'){
				//get children count - max 12
				$childcol = count($children_elements[ $parent_id ]) > 12 ? 12 : count($children_elements[ $parent_id ]);
				//calculate width
				$childcol = 100/($childcol > 0 ? $childcol : 1);
			}else{
				//calculate width
				$childcol = floor(100/$user_col);
				//clear columns float
				if($this->pmm_item_count > $user_col){
					$this->pmm_item_count = 1;
					$element->column_last = 1;
				}
			}
			$element->column_width = $childcol;



		}



		Walker_Nav_Menu::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent 		= str_repeat("\t", $depth);
		$pmm_mega 		= get_post_meta($args->parent_id, 'pmm_mega', 1);
		$pmm_image 		= get_post_meta($args->parent_id, 'pmm_image', 1);
		$pmm_background = get_post_meta($args->parent_id, 'pmm_background', 1);
		$pmm_padding 	= get_post_meta($args->parent_id, 'pmm_padding', 1);
		$pmm_width 		= get_post_meta($args->parent_id, 'pmm_fullwidth', 1);



		if(is_array($pmm_background)){
			$item_bg = 'background: white '.( '' !== $pmm_background['image'][0] ? 'url('._Proton__Images::thumbnail(array("source"=>$pmm_background['image'][0], "echo"=>0, "src"=>1)).') ' : '').( '' !== $pmm_background['repeat'][0] ? $pmm_background['repeat'][0].' ' : '').( '' !== $pmm_background['position'][0] ? $pmm_background['position'][0] : '').';';
		}else{
			$item_bg = '';
		}

		if($depth==0){
			if('true' === $pmm_mega){
				$output .= '<div class="pmm_container '.('true' === $pmm_width ? 'fullwidth': '').' children-'.$args->count_children.'"';
				if($pmm_background){
					$output .= 'style="'.$item_bg.'"';
				}
				$output .= '>';
			}else{
				$output .= '<div class="pmm_simple">';
			}
		}


		$output .= "\n$indent<ul ". ('true' === $args->mega_child ? "style=\"padding:". ($pmm_padding ? $pmm_padding : 0).";\"" : "" )." class=\"sub-menu ".(($depth==0) ? "row" : "" )." sub-menu-".($depth+1)."\">\n";
		//if($depth==0){$output .= "\n$indent\t<div class=\"row\">\n";}
		if($depth==2){$output .= "\n$indent\t<li class=\"pmm-go-back\"><a href=\"#goback\"></a></li>\n";}

	}

	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		//if($depth==0){$output .= "$indent\t</div>\n";}
		$output .= "$indent</ul>\n";
		if($depth===0){$output .= '</div>';}
	}

    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		$this->menuItemOptions = $item->ID;
		$pmm_mega 		= get_post_meta($item->ID, 'pmm_mega', 1);
		$pmm_image 		= get_post_meta($item->ID, 'pmm_image', 1);
		$pmm_padding 	= get_post_meta($item->ID, 'pmm_padding', 1);
		$pmm_header 	= get_post_meta($item->ID, 'pmm_header', 1);
		$pmm_link 		= get_post_meta($item->ID, 'pmm_link', 1);
		$pmm_icon 		= get_post_meta($item->ID, 'pmm_icon', 1);
		$pmm_label 		= get_post_meta($item->ID, 'pmm_label', 1);


		$indent 	= ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$classes 	= empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[]	= 'menu-item-' . $item->ID;
		$classes[] 	= 'object-' . $item->object_id;
		$classes[] 	= 'pmm-child-'.$args->mega_child;
		$classes[] 	= 'depth-'.$depth;

		//if($depth==1){$classes[] = 'col-xs-1';}

		if('true' === $pmm_header){
				$classes[] = 'pmm_header';
			}
		if('' !== $pmm_icon){
				$classes[] = 'pmm_icon';
			}


		//$classes[] = get_post_meta($item->ID, 'costam', 1);
		//$classes[] = get_post_meta($item->ID, 'columns', 1);


		if(strlen($pmm_image)>10){
			$classes[] = 'pmm_img_box';
		}


		/**
		 * Filter the CSS class(es) applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
		 * @param object $item    The current menu item.
		 * @param array  $args    An array of {@see wp_nav_menu()} arguments.
		 * @param int    $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filter the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param object $item    The current menu item.
		 * @param array  $args    An array of {@see wp_nav_menu()} arguments.
		 * @param int    $depth   Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
		if($depth == 1 && 'true' === $args->mega_child){
			if($item->column_last){
				$output .= $indent . '<div class="pmm_vspacer"></div>';
			}
			$output .= $indent . '<div class="pmm_column" style="width:'.$item->column_width.'%"><li' . $id . $class_names .'>';


		}else{
			$output .= $indent . '<li' . $id . $class_names .'>';
		}

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		/**
		 * Filter the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title  Title attribute.
		 *     @type string $target Target attribute.
		 *     @type string $rel    The rel attribute.
		 *     @type string $href   The href attribute.
		 * }
		 * @param object $item  The current menu item.
		 * @param array  $args  An array of {@see wp_nav_menu()} arguments.
		 * @param int    $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}
		if("true" !== $pmm_label){
		$element_title = $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		}

		$item_output = $args->before;
		if('true' === $pmm_header && 'true' === $args->mega_child){
			$item_output .= '<div class="pmm_h_line">';

			if('true' !== $pmm_link){
					$item_output .= '<a'. $attributes .'>';
					$item_output .= '<h2>';
					/** This filter is documented in wp-includes/post-template.php */
					if('' !== $pmm_icon){ $item_output .= '<i class="' . $pmm_icon . '"></i>'; }
					$item_output .= $element_title;
					$item_output .= '<span>'. (! empty( $item->attr_title ) ? $item->attr_title : '') . '</span>';
					$item_output .= '</h2>';
					$item_output .= '<span class="pmm_sep_holder"><span class="pmm_sep_line"></span></span>';
					$item_output .= '</a>';
				}else{
					$item_output .= '<h2>';
					/** This filter is documented in wp-includes/post-template.php */
			if('' !== $pmm_icon){ $item_output .= '<i class="' . $pmm_icon . '"></i>'; }
					$item_output .= $element_title;
					$item_output .= '<span>'. (! empty( $item->attr_title ) ? $item->attr_title : '') . '</span>';
					$item_output .= '</h2>';
					$item_output .= '<span class="pmm_sep_holder"><span class="pmm_sep_line"></span></span>';
				}
			$item_output .= '</div>';
		}elseif('true' !== $pmm_mega && strlen($pmm_image)>10){
			$imgargs = array(
				'source' => $pmm_image,
				'width' => 600,
		 		'height' => 300,
		 		'quality' => 90,
		 		'echo' => 0,
		 		'title' => apply_filters( 'the_title', $item->title, $item->ID ),
		 		'alt' => apply_filters( 'the_title', $item->title, $item->ID ),
			);
			$item_output .= '<a'. $attributes .'>';
			$item_output .= _Proton__Images::thumbnail($imgargs);//'<img src="'.get_post_meta($item->ID, 'pmm_image', 1).'"/>';//
			$item_output .= '<span>'.$element_title.'</span>';
			$item_output .= '</a>';
		}elseif('true' === $pmm_link){
			$item_output .= '<a class="link_disabled">';
			/** This filter is documented in wp-includes/post-template.php */
			if('' !== $pmm_icon){ $item_output .= '<i class="' . $pmm_icon . '"></i>'; }
			$item_output .= $element_title;
			$item_output .= '<span>'. (! empty( $item->attr_title ) ? $item->attr_title : '') . '</span>';
			$item_output .= '</a>';
		}else{
			$item_output .= '<a'. $attributes .'>';
			/** This filter is documented in wp-includes/post-template.php */
			if('' !== $pmm_icon){ $item_output .= '<i class="' . $pmm_icon . '"></i>'; }
			$item_output .= $element_title;
			$item_output .= '<span>'. (! empty( $item->attr_title ) ? $item->attr_title : '') . '</span>';
			$item_output .= '</a>';
		}
		if($depth == 2 && $args->has_children){
			//$item_output .= '<span class="pmm_parent">></span>';
		}
		$item_output .= $args->after;

		/**
		 * Filter a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string $item_output The menu item's starting HTML output.
		 * @param object $item        Menu item data object.
		 * @param int    $depth       Depth of menu item. Used for padding.
		 * @param array  $args        An array of {@see wp_nav_menu()} arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

    function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$this->menuItemOptions = $item->ID;
		$output .= "</li>\n";
		if($depth == 1 && 'true' === $args->mega_child){
			$output .= "</div>\n";
		}
	}
}

//change walker in primary menu
add_filter( 'wp_nav_menu_args' , 'proton_add_menu_walker' );
function proton_add_menu_walker( $args ) {
	if ( $args['container_class'] == 'proton-mega-menu' ) {
		$args['walker'] = new _Proton__Opt_Menu_Walker;
	}

	return $args;
}

?>