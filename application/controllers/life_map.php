<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class life_map extends CI_Controller
{
    function __construct()
    {
        parent::__construct(); // Call the Model constructor
        $this->load->model('gallery_model');
    }

    public function index()
    {
        $data = array(
            'css' => array(),
            'js' => array()
        );

        $this->load->view('life_map/index', $data);
    }
}

/* End of file */