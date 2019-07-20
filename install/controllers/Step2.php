<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Step2 extends CI_Controller 
{
    public $errors = array();
    public $writable_dirs = array(
	  'storage/cache' => FALSE,
	  'storage/uploads' => FALSE,
    );
    public $writable_subdirs = array(
		'storage/uploads/images' => FALSE,
		'storage/uploads/image-cache' => FALSE,
		'storage/uploads/.thumbs' => FALSE,
		'storage/uploads/files' => FALSE,
    );

    function index()
    {
        $data = array();
        clearstatcache();

        foreach ($this->writable_dirs as $path => $is_writable)
        {
            $this->writable_dirs[$path] = is_writable(CMS_ROOT . $path);
        }

        foreach ($this->writable_subdirs as $path => $is_writable)
        {
            if ( ! file_exists(CMS_ROOT . $path) || (file_exists(CMS_ROOT . $path) && is_writable(CMS_ROOT . $path)))
            {
                unset($this->writable_subdirs[$path]);
            }
        }

        if ($this->input->post())
        {
            if ($this->validate())
            {
                redirect('step3');
            }
        }

        $data['writable_dirs'] = array_merge($this->writable_dirs, $this->writable_subdirs);
        $data['errors'] = $this->errors;
        $data['content'] = $this->load->view('step_2', $data, TRUE);
        $this->load->view('global', $data);
    }

    private function validate()
    {
        if ( ! is_writable(CMS_ROOT . 'app.php'))
        {
            $this->errors[] =  CMS_ROOT . 'app.php is not writable.';
        }

        /*if ( ! is_writable(CMS_ROOT . 'app/config/database.php'))
        {
            $this->errors[] =  CMS_ROOT . 'app/config/database.php is not writable.';
        }*/

        $writable_dirs = array_merge($this->writable_dirs, $this->writable_subdirs);
        foreach ($writable_dirs as $path => $is_writable)
        {
            if ( ! $is_writable)
            {
                $this->errors[] = CMS_ROOT . $path . ' is not writable.';
            }
        }

        if (phpversion() < '5.1.6')
        {
            $this->errors[] = 'You need to use PHP 5.1.6 or greater.';
        }

        if ( ! ini_get('file_uploads'))
        {
            $this->errors[] = 'File uploads need to be enabled in your PHP configuration.';
        }

        if ( ! extension_loaded('mysqli'))
        {
            $this->errors[] = 'The PHP MySQLi extension is required.';
        }

        if ( ! extension_loaded('gd'))
        {
            $this->errors[] = 'The PHP GD extension is required.';
        }

        if ( ! extension_loaded('curl'))
        {
            $this->errors[] = 'The PHP cURL extension is required.';
        }

        if (empty($this->errors))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
}