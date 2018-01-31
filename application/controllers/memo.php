<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class memo extends MY_Controller
{
    function __construct()
    {
        parent::__construct();

    }

    public function index()
    {
        $data = array(
            'title' => '别忘了',
            'css_js_version' => $this->css_js_version,
            'css' => array(
                '/css/bootstrap-3.1.1\css\bootstrap.min.css',
                '/css/memo.css'
            ),
            'js' => array(
                '/js/jquery-1.11.1.min.js',
                '/js/jquery.autosize.js',
                '/js/underscore.js',
                '/js/backbone-min.js',
                '/js/isotope.pkgd.min.js',
                '/js/ext.string.js',
                '/js/question_item.js?v=1',
                '/js/type_view.js?v=1',
                '/js/memorize.js?v=1'
            )
        );

        $this->load->view('header', $data);
        $this->load->view('memo/index', $data);
        $this->load->view('footer', $data);
    }


}

/* End of file */