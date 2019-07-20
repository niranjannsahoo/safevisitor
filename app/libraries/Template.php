<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Template Class
 *
 * Build your CodeIgniter pages much easier with partials, breadcrumbs, layouts and themes
 *
 * @package			CodeIgniter
 * @subpackage		Libraries
 * @category		Libraries
 * @author			Philip Sturgeon
 * @license			http://philsturgeon.co.uk/code/dbad-license
 * @link			http://getsparks.org/packages/template/show
 */
class Template {
	
	private $_module = '';
	private $_controller = '';
	private $_method = '';

	private $_theme = NULL;
	private $_theme_path = NULL;
	public  $theme_path = 'themes';
	private $_layout = FALSE; // By default, dont wrap the view with anything
	private $_layout_subdir = ''; // Layouts and partials will exist in views/layouts
	// but can be set to views/foo/layouts with a subdirectory

	private $_title = '';
	private $_meta_title;
	private $_meta_description;
	private $_meta_keywords;
	private $_meta_icon;
	private $_metadata = array();

	private $_partials = array();

	private $_breadcrumbs = array();

	private $_title_separator = ' | ';

	private $_parser_enabled = TRUE;
	private $_parser_body_enabled = TRUE;

	private $_theme_locations = array();

	private $_is_mobile = FALSE;

	// Minutes that cache will be alive for
	private $_cache_lifetime = 0;

	private $_ci;

	private $_data = array();
	
	
	private $_headers_sent = FALSE; // Checks if HTML <head> data has been outputted
	private $_page_head = '';
	private $_template_data = array();
	private $_javascripts = array();
	private $_scripts = array();
	private $_stylesheets = array();
	private $_css = array();
	private $_header_js_order = array();  // Tracks the order in which javascripts and inline scripts were added for header includes
	private $_footer_js_order = array();  // Tracks the order in which javascripts and inline scripts were added for footer includes
	private $_css_order = array();  // Tracks the order in which stylesheets and inline css were added
	
	/**
	 * Constructor - Sets Preferences
	 *
	 * The constructor can be passed an array of config values
	 */
	function __construct($config = array()){
		$this->_ci =& get_instance();

		if ( ! empty($config)){
			$this->initialize($config);
		}

		log_message('debug', 'Template Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize preferences
	 *
	 * @access	public
	 * @param	array
	 * @return	void
	 */
	function initialize($config = array()){
		//print_r($config);
		foreach ($config as $key => $val){
			if ($key == 'theme' AND $val != ''){
				$this->set_theme($val);
				continue;
			}
			//echo $key;
			$this->{'_'.$key} = $val;
			
		}
		//echo $this->_parser_enabled;

		// No locations set in config?
		if ($this->_theme_locations === array()){
			// Let's use this obvious default
			$this->_theme_locations = array(APPPATH . 'themes/');
		}
		//echo $this->_theme;
		// Theme was set
		if ($this->_theme){
			$this->set_theme($this->_theme);
		}
		//echo $this->_parser_enabled;
		// If the parse is going to be used, best make sure it's loaded
		if ($this->_parser_enabled === TRUE){
			//echo "ok";
			$this->_ci->load->library('parser');
		}

		// Modular Separation / Modular Extensions has been detected
		if (method_exists( $this->_ci->router, 'fetch_module' )){
			$this->_module 	= $this->_ci->router->fetch_module();
		}

		// What controllers or methods are in use
		$this->_controller	= $this->_ci->router->fetch_class();
		$this->_method 		= $this->_ci->router->fetch_method();

		// Load user agent library if not loaded
		$this->_ci->load->library('user_agent');

		// We'll want to know this later
		$this->_is_mobile	= $this->_ci->agent->is_mobile();
	}

	// --------------------------------------------------------------------

	/**
	 * Magic Get function to get data
	 *
	 * @access	public
	 * @param	  string
	 * @return	mixed
	 */
	public function __get($name){
		return isset($this->_data[$name]) ? $this->_data[$name] : NULL;
	}

	// --------------------------------------------------------------------

	/**
	 * Magic Set function to set data
	 *
	 * @access	public
	 * @param	  string
	 * @return	mixed
	 */
	public function __set($name, $value){
		$this->_data[$name] = $value;
	}

	// --------------------------------------------------------------------

	/**
	 * Set data using a chainable metod. Provide two strings or an array of data.
	 *
	 * @access	public
	 * @param	  string
	 * @return	mixed
	 */
	public function set($name, $value = NULL){
		// Lots of things! Set them all
		if (is_array($name) OR is_object($name)){
			foreach ($name as $item => $value){
				$this->_data[$item] = $value;
			}
		}
		// Just one thing, set that
		else{
			$this->_data[$name] = $value;
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Build the entire HTML output combining partials, layouts and views.
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */
	public function view($view, $data = array(), $return = FALSE){
			
		// Set whatever values are given. These will be available to all view files
		is_array($data) OR $data = (array) $data;

		// Merge in what we already have with the specific data
		$this->_data = array_merge($this->_data, $data);
		
		// We don't need you any more buddy
		unset($data);

		if (empty($this->_title)){
			$this->_title = $this->_guess_title();
		}
		
		$this->_ci->benchmark->mark('template_parser_start');
		// Output template variables to the template
		$template['title']	= $this->_title;
		$template['breadcrumbs'] = $this->_breadcrumbs;
		$template['metadata']	= implode("\n\t\t", $this->_metadata);
		$template['partials']	= array();

		// Assign by reference, as all loaded views will need access to partials
		$this->_data['template'] =& $template;
		
		foreach ($this->_partials as $name => $partial){
			// We can only work with data arrays
			is_array($partial['data']) OR $partial['data'] = (array) $partial['data'];

			// If it uses a view, load it
			if (isset($partial['view'])){
				$template['partials'][$name] = $this->_find_view($partial['view'], $partial['data']);
			}
			// Otherwise the partial must be a string
			else{
				if ($this->_parser_enabled === TRUE){
					$partial['string'] = $this->_ci->parser->parse_string($partial['string'], $this->_data + $partial['data'], TRUE, TRUE);
				}
				$template['partials'][$name] = $partial['string'];
			}
		}

		// Disable sodding IE7's constant cacheing!!
		$this->_ci->output->set_header('Expires: Sat, 01 Jan 2000 00:00:01 GMT');
		$this->_ci->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->_ci->output->set_header('Cache-Control: post-check=0, pre-check=0, max-age=0');
		$this->_ci->output->set_header('Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
		$this->_ci->output->set_header('Pragma: no-cache');

		// Let CI do the caching instead of the browser
		$this->_ci->output->cache($this->cache_lifetime);
		
		// Test to see if this file
		$this->_body = $this->_find_view($view, array(), $this->_parser_body_enabled);
	
		// Want this file wrapped with a layout file?
		
		if ($this->_layout){
			//echo $this->_layout;
			// Added to $this->_data['template'] by refference
			$template['body'] = $this->_body;

			// Find the main body and 3rd param means parse if its a theme view (only if parser is enabled)
			$this->_body =  self::_load_view('layouts/'.$this->_layout, $this->_data, TRUE, self::_find_view_folder());
			
		}

		// Want it returned or output to browser?
		if ( ! $return){
			$this->_ci->output->set_output($this->_body);
		}
		$this->_ci->benchmark->mark('template_parser_end');
		return $this->_body;
	}

	/**
	 * Set the title of the page
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */
	public function title(){
		// If we have some segments passed
		if (func_num_args() >= 1){
			$title_segments = func_get_args();
			$this->_title = implode($this->_title_separator, $title_segments);
		}

		return $this;
	}
	
	// --------------------------------------------------------------------
    /*
     * Set Title
     *
     * Specifies the page title used in the metadata output
     *
     * @param string 
     * @return object
     */
   function set_meta_title($title){
      if ( ! empty($title))
      {
         $this->_meta_title = $title;
      }
      return $this;
   }
   // --------------------------------------------------------------------
   /*
     * Set Description
     *
     * Specifies the page description used in the metadata output
     *
     * @param string 
     * @return object
     */
   function set_meta_description($description){
      if ( ! empty($description))
      {
         $this->_meta_description = $description;
      }
      return $this;
   }
   // --------------------------------------------------------------------
    /*
     * Set Keywords
     *
     * Specifies the page keywords used in the metadata output
     *
     * @param string 
     * @return object
     */
   function set_meta_keywords($keywords){
      if ( ! empty($keywords))
      {
         $this->_meta_keywords = $keywords;
      }
		return $this;
   }
	
	// --------------------------------------------------------------------
    /*
     * Set icon
     *
     * Specifies the page keywords used in the metadata output
     *
     * @param string 
     * @return object
     */
   function set_meta_icon($icon){
      if ( ! empty($icon))
      {
         $this->_meta_icon = $icon;
      }
		return $this;
   }
	
	/*
     * Metadata
     *
     * Commonly used in the header.php template file
     * Outputs title, description, and keyword metadata
     *
     * @return string
     */
   function metadata(){
        $metadata = '';
        if ( ! empty($this->_meta_title)){
            $metadata .= '<title>' . $this->_meta_title . '</title>' . "\r\n";
        }
		  
		  if ( ! empty($this->_meta_icon)){
            $metadata .= '<link rel="icon" href="'.$this->_meta_icon .'">'. "\r\n";
		  }
		  
        if ( ! empty($this->_meta_description))
        {
            $metadata .= '<meta name="description" content="' . $this->_meta_description . '" />' . "\r\n";
        }
        if ( ! empty($this->_meta_keywords))
        {
            $metadata .= '<meta name="keywords" content="' . $this->_meta_keywords . '" />' . "\r\n";
        }
        $this->_headers_sent = TRUE;
        return $metadata;
    }


	/**
	 * Put extra javascipt, css, meta tags, etc before all other head data
	 *
	 * @access	public
	 * @param	 string	$line	The line being added to head
	 * @return	void
	 */
	public function prepend_metadata($line){
		array_unshift($this->_metadata, $line);
		return $this;
	}


	/**
	 * Put extra javascipt, css, meta tags, etc after other head data
	 *
	 * @access	public
	 * @param	 string	$line	The line being added to head
	 * @return	void
	 */
	public function append_metadata($line){
		$this->_metadata[] = $line;
		return $this;
	}


	/**
	 * Set metadata for output later
	 *
	 * @access	public
	 * @param	  string	$name		keywords, description, etc
	 * @param	  string	$content	The content of meta data
	 * @param	  string	$type		Meta-data comes in a few types, links for example
	 * @return	void
	 */
	public function set_metadata($name, $content, $type = 'meta')
	{
		$name = htmlspecialchars(strip_tags($name));
		$content = htmlspecialchars(strip_tags($content));

		// Keywords with no comments? ARG! comment them
		if ($name == 'keywords' AND ! strpos($content, ','))
		{
			$content = preg_replace('/[\s]+/', ', ', trim($content));
		}

		switch($type)
		{
			case 'meta':
				$this->_metadata[$name] = '<meta name="'.$name.'" content="'.$content.'" />';
			break;

			case 'link':
				$this->_metadata[$content] = '<link rel="'.$name.'" href="'.$content.'" />';
			break;
		}

		return $this;
	}


	/**
	 * Which theme are we using here?
	 *
	 * @access	public
	 * @param	string	$theme	Set a theme for the template library to use
	 * @return	void
	 */
	public function set_theme($theme = NULL)
	{
		$this->_theme = $theme;
		
		foreach ($this->_theme_locations as $location)
		{
			
			if ($this->_theme AND file_exists($location.$this->_theme))
			{
				$this->_theme_path = rtrim($location.$this->_theme.'/');
				$this->single("config");
				break;
			}
		}

		return $this;
	}

	/**
	 * Get the current theme
	 *
	 * @access public
	 * @return string	The current theme
	 */
	 public function get_theme()
	 {
	 	return $this->_theme;
	 }

	/**
	 * Get the current theme path
	 *
	 * @access	public
	 * @return	string The current theme path
	 */
	public function get_theme_path()
	{
		return $this->_theme_path;
	}


	/**
	 * Which theme layout should we using here?
	 *
	 * @access	public
	 * @param	string	$view
	 * @return	void
	 */
	public function set_layout($view, $_layout_subdir = '')
	{
		$this->_layout = $view;

		$_layout_subdir AND $this->_layout_subdir = $_layout_subdir;

		return $this;
	}

	/**
	 * Set a view partial
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	boolean
	 * @return	void
	 */
	public function set_partial($name, $view, $data = array())
	{
		$this->_partials[$name] = array('view' => $view, 'data' => $data);
		return $this;
	}

	/**
	 * Set a view partial
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	boolean
	 * @return	void
	 */
	public function inject_partial($name, $string, $data = array())
	{
		$this->_partials[$name] = array('string' => $string, 'data' => $data);
		return $this;
	}


	/**
	 * Helps build custom breadcrumb trails
	 *
	 * @access	public
	 * @param	string	$name		What will appear as the link text
	 * @param	string	$url_ref	The URL segment
	 * @return	void
	 */
	public function set_breadcrumb($name, $uri = '')
	{
		$this->_breadcrumbs[] = array('name' => $name, 'uri' => $uri );
		return $this;
	}

	/**
	 * Set a the cache lifetime
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	boolean
	 * @return	void
	 */
	public function set_cache($minutes = 0)
	{
		$this->_cache_lifetime = $minutes;
		return $this;
	}


	/**
	 * enable_parser
	 * Should be parser be used or the view files just loaded normally?
	 *
	 * @access	public
	 * @param	 string	$view
	 * @return	void
	 */
	public function enable_parser($bool)
	{
		$this->_parser_enabled = $bool;
		return $this;
	}

	/**
	 * enable_parser_body
	 * Should be parser be used or the body view files just loaded normally?
	 *
	 * @access	public
	 * @param	 string	$view
	 * @return	void
	 */
	public function enable_parser_body($bool)
	{
		$this->_parser_body_enabled = $bool;
		return $this;
	}

	/**
	 * theme_locations
	 * List the locations where themes may be stored
	 *
	 * @access	public
	 * @param	 string	$view
	 * @return	array
	 */
	public function theme_locations()
	{
		return $this->_theme_locations;
	}

	/**
	 * add_theme_location
	 * Set another location for themes to be looked in
	 *
	 * @access	public
	 * @param	 string	$view
	 * @return	array
	 */
	public function add_theme_location($location)
	{
		$this->_theme_locations[] = $location;
	}

	/**
	 * theme_exists
	 * Check if a theme exists
	 *
	 * @access	public
	 * @param	 string	$view
	 * @return	array
	 */
	public function theme_exists($theme = NULL)
	{
		$theme OR $theme = $this->_theme;

		foreach ($this->_theme_locations as $location)
		{
			if (is_dir($location.$theme))
			{
				return TRUE;
			}
		}

		return FALSE;
	}
	
	/**
	 * get_themes
	 * Get all themes 
	 *
	 * @access	public
	 * @param	 string	$view
	 * @return	array
	 */
	public function get_themes()
	{
		$themes = array();
		foreach ($this->_theme_locations as $location)
		{
			
			if(is_dir($location))
			{
				foreach(glob($location. '/*',GLOB_ONLYDIR) as $theme)
				{
					$theme_name = basename($theme);
					$themes[$theme_name] = pathinfo($theme, PATHINFO_BASENAME);
				}
				break;
			}
		}

		

		return $themes;
	}

	/**
	 * get_layouts
	 * Get all current layouts (if using a theme you'll get a list of theme layouts)
	 *
	 * @access	public
	 * @param	 string	$view
	 * @return	array
	 */
	public function get_layouts()
	{
		$layouts = array();

		foreach(glob(self::_find_view_folder().'layouts/*.*') as $layout)
		{
			$layouts[] = pathinfo($layout, PATHINFO_BASENAME);
		}

		return $layouts;
	}


	/**
	 * get_layouts
	 * Get all current layouts (if using a theme you'll get a list of theme layouts)
	 *
	 * @access	public
	 * @param	 string	$view
	 * @return	array
	 */
	public function get_theme_layouts($theme = NULL)
	{
		$theme OR $theme = $this->_theme;

		$layouts = array();

		foreach ($this->_theme_locations as $location)
		{
			// Get special web layouts
			if( is_dir($location.$theme.'/views/web/layouts/') )
			{
				foreach(glob($location.$theme . '/views/web/layouts/*.*') as $layout)
				{
					$layouts[] = pathinfo($layout, PATHINFO_FILENAME);
				}
				break;
			}

			// So there are no web layouts, assume all layouts are web layouts
			if(is_dir($location.$theme.'/views/layouts/'))
			{
				foreach(glob($location.$theme . '/views/layouts/*.*') as $layout)
				{
					$layout = pathinfo($layout, PATHINFO_FILENAME);
					$layouts[$layout] = $layout;
				}
				break;
			}
		}

		return $layouts;
	}

	/**
	 * layout_exists
	 * Check if a theme layout exists
	 *
	 * @access	public
	 * @param	 string	$view
	 * @return	array
	 */
	public function layout_exists($layout)
	{
		// If there is a theme, check it exists in there
		if ( ! empty($this->_theme) AND in_array($layout, self::get_theme_layouts()))
		{
			return TRUE;
		}

		// Otherwise look in the normal places
		return file_exists(self::_find_view_folder().'layouts/' . $layout . self::_ext($layout));
	}

	/**
	 * load_view
	 * Load views from theme paths if they exist.
	 *
	 * @access	public
	 * @param	string	$view
	 * @param	mixed	$data
	 * @return	array
	 */
	public function load_view($view, $data = array())
	{
		return $this->_find_view($view, (array)$data);
	}
	
	public function single($content, $data = array()){
	  	$this->load_view($content.'.php', $data, TRUE);
	}
	// find layout files, they could be mobile or web
	private function _find_view_folder()
	{
		
		if ($this->_ci->load->get_var('template_views'))
		{
			
			return $this->_ci->load->get_var('template_views');
		}

		// Base view folder
		$view_folder = APPPATH.'views/';

		// Using a theme? Put the theme path in before the view folder
		if ( ! empty($this->_theme))
		{
			$view_folder = $this->_theme_path.'views/';
		}

		// Would they like the mobile version?
		if ($this->_is_mobile === TRUE AND is_dir($view_folder.'mobile/'))
		{
			// Use mobile as the base location for views
			$view_folder .= 'mobile/';
		}

		// Use the web version
		else if (is_dir($view_folder.'web/'))
		{
			$view_folder .= 'web/';
		}

		// Things like views/admin/web/view admin = subdir
		if ($this->_layout_subdir)
		{
			$view_folder .= $this->_layout_subdir.'/';
		}

		// If using themes store this for later, available to all views
		$this->_ci->load->vars('template_views', $view_folder);
		
		return $view_folder;
	}

	// A module view file can be overriden in a theme
	private function _find_view($view, array $data, $parse_view = TRUE)
	{
		//print_r($this->_theme_locations);
		//print_r($this->_theme);
		// Only bother looking in themes if there is a theme
		if (!empty($this->_theme))
		{
			
			foreach ($this->_theme_locations as $location)
			{
				$theme_views = array(
					$this->_theme . '/views/modules/' . $this->_module . '/' . $view,
					$this->_theme . '/views/' . $view
				);
				
				foreach ($theme_views as $theme_view)
				{
					//print_r($location . $theme_view . self::_ext($theme_view));
			
					if (file_exists($location . $theme_view . self::_ext($theme_view)))
					{
						return self::_load_view($theme_view, $this->_data + $data, $parse_view, $location);
					}
				}
				
			}
		}

		// Not found it yet? Just load, its either in the module or root view
		return self::_load_view($view, $this->_data + $data, $parse_view);
	}

	private function _load_view($view, array $data, $parse_view = TRUE, $override_view_path = NULL)
	{
		
		//echo $override_view_path;
		// Sevear hackery to load views from custom places AND maintain compatibility with Modular Extensions
		if ($override_view_path !== NULL)
		{
			//echo "ok1";
			if ($this->_parser_enabled === TRUE AND $parse_view === TRUE)
			{
				//echo $override_view_path.$view.self::_ext($view);
				// Load content and pass through the parser
				$content = $this->_ci->parser->parse_string($this->_ci->load->file(
					$override_view_path.$view.self::_ext($view), 
					TRUE
				), $data, TRUE);
			}

			else
			{
				$this->_ci->load->vars($data);
				
				// Load it directly, bypassing $this->load->view() as ME resets _ci_view
				$content = $this->_ci->load->file(
					$override_view_path.$view.self::_ext($view),
					TRUE
				);
			}
		}

		// Can just run as usual
		else
		{
			//echo "ok12";
			// Grab the content of the view (parsed or loaded)
			$content = ($this->_parser_enabled === TRUE AND $parse_view === TRUE)

				// Parse that bad boy
				? $this->_ci->parser->parse($view, $data, TRUE)

				// None of that fancy stuff for me!
				: $this->_ci->load->view($view, $data, TRUE);
		}
		
		return $content;
	}

	private function _guess_title()
	{
		$this->_ci->load->helper('inflector');

		// Obviously no title, lets get making one
		$title_parts = array();

		// If the method is something other than index, use that
		if ($this->_method != 'index')
		{
			$title_parts[] = $this->_method;
		}

		// Make sure controller name is not the same as the method name
		if ( ! in_array($this->_controller, $title_parts))
		{
			$title_parts[] = $this->_controller;
		}

		// Is there a module? Make sure it is not named the same as the method or controller
		if ( ! empty($this->_module) AND ! in_array($this->_module, $title_parts))
		{
			$title_parts[] = $this->_module;
		}

		// Glue the title pieces together using the title separator setting
		$title = humanize(implode($this->_title_separator, $title_parts));

		return $title;
	}

	private function _ext($file)
	{
		
		return pathinfo($file, PATHINFO_EXTENSION) ? '' : '.php';
	}
	
	
	// other feature
	
	// --------------------------------------------------------------------
    /*
     * Add Stylesheet
     *
     * This function is used to build an array of external stylesheets to include
     *
     * @param string or array
     * @return object
     */
   function add_stylesheet($stylesheets){
		
		
		if ( ! is_array($stylesheets)){
			$stylesheets = (array) $stylesheets;
		}
		
		foreach ($stylesheets as $stylesheet){
			$stylesheet = (strpos($stylesheet, 'http') === 0 ? $stylesheet : base_url($stylesheet));
			if ( ! in_array($stylesheet, $this->_stylesheets)){
				$this->_stylesheets[] = $stylesheet;
				$keys=array_keys($this->_stylesheets);
				$index = end($keys);
				// Keep track of the order in which stylesheets and css are added
				$this->_css_order[] = array(
					'array' => 'stylesheets',
					'index' => $index,
				);
			}
		}
		
		return $this;
   }
    // --------------------------------------------------------------------
    /*
     * Add CSS
     *
     * Used to build an array of internal css to include
     *
     * @param string or array
     * @return object
     */
	function add_css($css){
      if ( ! is_array($css))
      {
         $css = (array) $css;
      }
      foreach ($css as $style)
      {
         $this->_css[] = $style;
			$keys=array_keys($this->_css);
         $index = end($keys);
            
         // Keep track of the order in which stylesheets and css are added
         $this->_css_order[] = array(
			  'array' => 'css',
			  'index' => $index,
			);
      }
      return $this;
   }
    // --------------------------------------------------------------------
    /*
     * Add Javascript
     *
     * Used to build an array of external javascripts to include
     *
     * @param string or array
     * @return object
     */
   function add_javascript($javascripts, $footer = FALSE){
		if ( ! is_array($javascripts))
      {
         $javascripts = (array) $javascripts;
      }
      foreach ($javascripts as $javascript)
      {
			// If HTTP not in javascript uri add prepend base_url
         $javascript = (strpos($javascript, 'http') === 0 ? $javascript : base_url($javascript));
         if ( ! in_array($javascript, $this->_javascripts))
         {
            $this->_javascripts[] = $javascript;
				//printr($this->_javascripts);
				$keys = array_keys($this->_javascripts);
            $index = end($keys);
            // Determine where this script needs to be included
				// and keep track of the order in which javascripts and scripts are added
            if ($footer || $this->_headers_sent) 
            {
					$this->_footer_js_order[] = array(
						'array' => 'javascripts',
						'index' => $index,
					);
				}
            else
				{
					$this->_header_js_order[] = array(
						'array' => 'javascripts',
						'index' => $index,
					);
				}
         }
      }
      return $this;
	}
    // --------------------------------------------------------------------
    /*
     * Add Script
     *
     * Used to include internal javascript in the template
     *
     * @param string or array
     * @return object
     */
   function add_script($scripts, $footer = FALSE){
      if ( ! is_array($scripts))
      {
            $scripts = (array) $scripts;
      }
      foreach ($scripts as $javascript)
      {
         $this->_scripts[] = $javascript;
			$keys=array_keys($this->_scripts);
         $index = end($keys);
         // Determine where this script needs to be included
         // and keep track of the order in which javascripts and scripts are added
         if ($footer || $this->_headers_sent) 
         {
				$this->_footer_js_order[] = array(
				  'array' => 'scripts',
				  'index' => $index,
				);
			}
			else
			{
				$this->_header_js_order[] = array(
				  'array' => 'scripts',
				  'index' => $index,
				);
			}
      }
      return $this;
   }
    // --------------------------------------------------------------------
    /*
     * Add Page Head
     *
     * Used to include custom JavaScript, CSS, meta information and/or PHP in the <head> block of the template
     *
     * @param string
     * @return object
     */
   function add_page_head($code){
        if (is_string($code))
        {
            $this->_page_head = $code;
        }
        return $this;
   }
    // --------------------------------------------------------------------
    /*
     * Add Package
     *
     * Used to add predefined sets of javascripts and stylesheets
     *
     * @param string or array
     * @return object
     */
   function add_package($packages,$footer = FALSE){
      $pkg_const = unserialize(PACKAGES);
		//printr($pkg_const);
      if ( ! is_array($packages))
      {
         $packages = (array) $packages;
      }
		foreach ($packages as $package)
		{
			if (isset($pkg_const[$package]))
			{
				$package = $pkg_const[$package];
				if (isset($package['javascript']))
				{
					$this->add_javascript($package['javascript'],$footer);
				}
				if (isset($package['stylesheet']))
				{
					$this->add_stylesheet($package['stylesheet']);
				}
			}
		}
      return $this;
    }
    // --------------------------------------------------------------------
    
    // --------------------------------------------------------------------
    /*
     * Javascripts
     *
     * Commonly used in the HTML <head> of template files
     * Outputs javascript includes from the javascript array
     *
     * @return string
     */
   function javascripts($footer = FALSE){
		if ($footer)
		{
			$js_order_array = '_footer_js_order';
		}
		else
		{
			$js_order_array = '_header_js_order';
		}
		$js_includes = "\n\t<script>var BASE_HREF=\"" . base_url() . "\"</script>";
		foreach ($this->$js_order_array as $js_order) 
		{
			if ($js_order['array'] == 'javascripts')
			{
				 $js_includes .=  "\n\t<script type=\"text/javascript\" src=\"" . $this->_javascripts[$js_order['index']] . "\"></script>";
			}
			else if ($js_order['array'] == 'scripts')
			{
				 $script = $this->_scripts[$js_order['index']];
				 // Check if script has the script tags included
				 if (stripos(trim($script), '<script') === 0)
				 {
					  $js_includes .=  "\n" . $script;
				 }
				 else
				 {
					  $js_includes .=  "\n\t<script type=\"text/javascript\">" . $script . "</script>";
				 }
			}
	  }
	  if ( ! $footer)
	  {
			$this->_headers_sent = TRUE;
	  }
	  return $js_includes;
	}
    // --------------------------------------------------------------------
    /*
     * Stylesheets
     *
     * Commonly used in the HTML <head> of template files
     * Outputs stylesheets includes from the stylesheet array
     *
     * @return string
     */
   function stylesheets(){
      $css_includes = '';
      foreach ($this->_css_order as $css_order) 
      {
         if ($css_order['array'] == 'stylesheets')
         {
            $css_includes .=  "\n\t<link href=\"" . $this->_stylesheets[$css_order['index']] . "\" rel=\"stylesheet\" type=\"text/css\" />";
         }
			else if ($css_order['array'] == 'css')
			{
				 $style = $this->_css[$css_order['index']];
				 // Check if css has the script tags included
				 if (stripos(trim($style), '<stle') === 0)
				 {
					  $css_includes .=  "\n" . $style;
				 }
				 else
				 {
					  $css_includes .=  "\n\t<style type=\"text/css\">" . $style . "</style>";
				 }
			}
      }
      $this->_headers_sent = TRUE;
      return $css_includes;
   }
	
	// --------------------------------------------------------------------
    /*
     * Analytics
     *
     * Commonly used in the header template file immediately before the closing </head> tag
     * Outputs javascript for google analytics
     * Google Analytic's Account ID is set in /application/config/custom_config.php
     *
     * @return string
     */
   function analytics(){
      if ($this->CI->settings->ga_account_id)
      {
			return "<script type=\"text/javascript\">
					  var _gaq = _gaq || [];
					  _gaq.push(['_setAccount', '" . $this->CI->settings->ga_account_id . "']);
					  _gaq.push(['_trackPageview']);
					  (function() {
						 var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
						 ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
						 var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
					  })();
					</script>";
      }
   }
    // --------------------------------------------------------------------
    /*
     * Page Head
     *
     * Commonly used in the HTML <head> of template files
     * Outputs additional page <head> code
     *
     * @return string
     */
   function page_head(){
      $this->_headers_sent = TRUE;
      return $this->_page_head;
   }
    // --------------------------------------------------------------------
    /*
     * Head
     *
     * Commonly used in the header template file immediately after the starting <head> tag
     * Combines the outputs of metadata, stylesheets, javascripts, and analytics in one function
     *
     * @return string
     */
   function head(){
	  $return = '';
	  $return .= $this->_metadata();
	  $return .= $this->_stylesheets();
	  $return .= $this->_javascripts();
	  $return .= $this->_page_head();
	  $return .= $this->analytics();
	  return $return;
   }
    // --------------------------------------------------------------------
    /*
     * Footer
     *
     * Commonly used in the footer template file immediately before the closing </body> tag
     * Outputs the footer javascripts
     *
     * @return string
     */
	function footer_javascript(){
	  $return = '';
	  $return .= $this->javascripts(TRUE);
	  return $return;
   }
	
	public function widget($w_view = '', $w_data = array(),$return = TRUE){
        //echo FRONT_ROOT.'admin/widgets/'.$w_view.EXT;
		if (file_exists($path = ADMIN_ROOT .'widgets/'.$w_view.EXT))
      {
         //echo "ok";
			//$this->CI->load->library('parser');
			//return $this->CI->parser->parse($w_view, $w_data);
			//echo $this->theme;
			//return $this->CI->parser->parse_string($this->CI->load->theme('widgets/' . trim($w_view, '/'), $w_data, $return, $this->CI->template->theme_path), $w_data, $return);
			//return $this->CI->load->theme($this->theme .'/template/'. $this->template, $this->template_data,$return, $this->theme_path);
      }
		//return $this->CI->load->view("widgets/".$w_view);
        // Maybe it's a module. Plugins used as a module would typically have views or an admin interface
        /*if (file_exists($path = FRONT_ROOT.'admin/modules/'.$class.'/plugin.php'))
        {
            $dirname = dirname($path).'/';
            // Set the module as a package so I can load stuff
            $this->_ci->load->add_package_path($dirname);
            $response = $this->_process($path, $class, $method, $attributes, $content, $data);
            $this->_ci->load->remove_package_path($dirname);
            return $response;
        }*/
		return theme_partial($w_view, $w_data);
   }
	
}

// END Template class