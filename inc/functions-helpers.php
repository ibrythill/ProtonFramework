<?php

	 /* Include */
	function pro_find_include($file,$d=""){
		while(!is_file(PROTON_DIR.$d.$file) && $d != "../../../"){
				$d.="../";
		}
		$includePath = (PROTON_DIR.$d.$file);
		return $includePath;
	}

	function pro_include($files){
		if(is_array($files)){
			foreach ($files as $key => $file) {
				include $file;
			}
		}else{
			include $files;
		}
	}


	//Get the theme's settings option
	function pro_get_option($id){
		global $protonthemepanel;
		//$options = get_option($protonthemepanel);
		return $protonthemepanel[$id];
	}


		/* Add admin notices */
	function proton_admin_notice($msg, $type = 'update'){
		if ( false === ( $proton_notices = get_transient( 'proton_notices' ) ) || !is_array($proton_notices)) {
			$proton_notices = array();
		}
		$proton_notices[] = array($type, $msg);
		set_transient( 'proton_notices', $proton_notices, 10 * MINUTE_IN_SECONDS );
	}

	function proton_show_notice() {
		if ( false === ( $proton_notices = get_transient( 'proton_notices' ) ) ) {
			return;
		}

		foreach($proton_notices as $proton_notice){
			?>
		    <div class="<?php echo $proton_notice[0] ?> notice notice-success is-dismissible">
		        <p><?php echo $proton_notice[1] ?></p>
		    </div>
		    <?php
		}

		delete_transient( 'proton_notices' );
	}
	add_action('admin_notices', 'proton_show_notice');


	/**
	 * Function for validating booleans before saving them as metadata. If the value is
	 * `true`, we'll return a `1` to be stored as the meta value.  Else, we return `false`.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  mixed
	 * @return bool|int
	 */
	function proton_validate_boolean( $value ) {

		return wp_validate_boolean( $value ) ? 1 : false;
	}

	/**
	 * Pre-WP 4.6 function for sanitizing hex colors.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $color
	 * @return string
	 */
	function proton_sanitize_hex_color( $color ) {

		if ( function_exists( 'sanitize_hex_color' ) )
			return sanitize_hex_color( $color );

		return $color && preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ? $color : '';
	}

	/**
	 * Pre-WP 4.6 function for sanitizing hex colors without a hash.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $color
	 * @return string
	 */
	function proton_sanitize_hex_color_no_hash( $color ) {

		if ( function_exists( 'sanitize_hex_color_no_hash' ) )
			return sanitize_hex_color_no_hash( $color );

		$color = ltrim( $color, '#' );

		if ( '' === $color )
			return '';

		return proton_sanitize_hex_color( '#' . $color ) ? $color : null;
	}

	/**
	 * Pre-WP 4.6 function for sanitizing a color and adding a hash.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $color
	 * @return string
	 */
	function proton_maybe_hash_hex_color( $color ) {

		if ( function_exists( 'maybe_hash_hex_color' ) )
			return maybe_hash_hex_color( $color );

		if ( $unhashed = proton_sanitize_hex_color_no_hash( $color ) )
			return '#' . $unhashed;

		return $color;
	}


	/**
	 * proton_register_script function.
	 *
	 * @access public
	 * @param mixed $name
	 * @param mixed $path
	 * @param int $priority (default: 1)
	 * @param bool $fallback (default: false)
	 * @param array $dep (default: array())
	 * @return void
	 */
	function proton_register_script($name, $path, $priority = 1, $fallback = false, $dep = array()){

		if($fallback){
			wp_enqueue_script($name, $path.'.js', $dep, false);
		}else{
			//$Proton->scripts[$name] = array($path, $ver, $handle, $order);
			Proton\Loaders\Scripts::get_instance()->register($name, $path, $dep, $priority);
		}
		return;
	}

	/**
	 * proton_enqueue_script function.
	 *
	 * @access public
	 * @param mixed $name
	 * @param mixed $path
	 * @param int $priority (default: 1)
	 * @param bool $fallback (default: false)
	 * @param array $dep (default: array())
	 * @return void
	 */
	function proton_enqueue_script($name, $path, $priority = 1, $fallback = false, $dep = array()){

		if($fallback){
			wp_enqueue_script($name, $path.'.js', $dep, false);
		}else{
			//$Proton->scripts[$name] = array($path, $ver, $handle, $order);
			Proton\Loaders\Scripts::get_instance()->queue($name, $path, $dep, $priority);
		}
		return;
	}


	/**
	 * proton_register_style function.
	 *
	 * @access public
	 * @param mixed $name
	 * @param mixed $path
	 * @param int $priority (default: 1)
	 * @param bool $fallback (default: false)
	 * @param array $dep (default: array())
	 * @return void
	 */
	function proton_register_style($name, $path, $priority = 1, $fallback = false, $dep = array()){

		if($fallback){
			wp_enqueue_style($name, $path.'.js', $dep);
		}else{
			//$Proton->scripts[$name] = array($path, $ver, $handle, $order);
			Proton\Loaders\Styles::get_instance()->register($name, $path, $dep, $priority);
		}
		return;
	}

	/**
	 * proton_enqueue_style function.
	 *
	 * @access public
	 * @param mixed $name
	 * @param mixed $path
	 * @param int $priority (default: 1)
	 * @param bool $fallback (default: false)
	 * @param array $dep (default: array())
	 * @return void
	 */
	function proton_enqueue_style($name, $path, $priority = 1, $fallback = false, $dep = array()){

		if($fallback){
			wp_enqueue_style($name, $path.'.js', $dep);
		}else{
			//$Proton->scripts[$name] = array($path, $ver, $handle, $order);
			Proton\Loaders\Styles::get_instance()->queue($name, $path, $dep, $priority);
		}
		return;
	}


	/**
	 * isMobile function.
	 *
	 * @access public
	 * @return void
	 */
	function isMobile(){
		return Proton\Helpers\Detect::get_instance()->isMobile();
	}
	/**
	 * isTablet function.
	 *
	 * @access public
	 * @return void
	 */
	/**
	 * isTablet function.
	 *
	 * @access public
	 * @return void
	 */
	/**
	 * isTablet function.
	 *
	 * @access public
	 * @return void
	 */
	function isTablet(){
		return Proton\Helpers\Detect::get_instance()->isTablet();
	}
	/**
	 * isiOS function.
	 *
	 * @access public
	 * @return void
	 */
	function isiOS(){
		return Proton\Helpers\Detect::get_instance()->isiOS();
	}
	/**
	 * isAndroidOS function.
	 *
	 * @access public
	 * @return void
	 */
	function isAndroidOS(){
		return Proton\Helpers\Detect::get_instance()->isAndroidOS();
	}
