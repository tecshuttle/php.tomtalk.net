<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class review extends CI_Controller
{
    var $uid = 0;

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('blog_model');

        session_start();
        if (isset($_SESSION['uid'])) {
            $this->uid = $_SESSION['uid'];
        }

        header("Access-Control-Allow-Origin: *");
    }

    public function index()
    {
        $data = array(
            'title' => '简历',
            'css' => array(
                '/css/bootstrap-3.1.1/css/bootstrap.min.css',
                '/css/review.css'
            ),
            'js' => array(
                '/js/jquery-1.11.1.min.js',
                '/js/vue.min.js',
                '/js/marked.min.js',
                '/js/blog/review.js'
            )
        );

        $this->load->view('header', $data);
        $this->load->view('blog/review', $data);
        $this->load->view('footer', $data);
    }

    public function getReview()
    {
        $option = array(
            'cid' => 328
        );

        $data = $this->blog_model->get($option);

        echo json_encode($data);

    }
}

/* End of file */
