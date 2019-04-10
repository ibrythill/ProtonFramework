<?php

namespace Proton\Helpers;

use Proton\Helpers\Mobile_Detect as Mobile_Detect;

class Detect
{
    /**
	* Holds the instance of this class.
	*/
	private static $instance;

	private $detect;

	/**
	 * Template Constructor.
	*/
	public function __construct(){
		$this->detect = new Mobile_Detect;
		//add_filter( 'init', array( $this, 'sidebars_init' ));
	}

	/**
	 * Returns the instance.
	 *
	 * @since  1.1.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		if ( !self::$instance ){
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function isMobile(){
		$this->detect->isMobile();
	}

	public function isTablet(){
		$this->detect->isTablet();
	}

	public function isiOS(){
		$this->detect->isiOS();
	}

	public function isAndroidOS(){
		$this->detect->isAndroidOS();
	}
}
