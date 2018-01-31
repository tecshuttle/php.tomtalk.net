<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    var $css_js_version = '';

    public function __construct($type = NULL)
    {
        parent::__construct();

        session_start();

        if (ENVIRONMENT !== 'production') {

            //$this->output->enable_profiler(TRUE);
        }

        $this->css_js_version = $this->config->config['css_js_version'];
    }
}


/* End of file Controller.php */