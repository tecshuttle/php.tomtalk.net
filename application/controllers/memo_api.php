<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class memo_api extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('item_type_model');
    }

    public function index()
    {
        echo 'this is memo api controller';
    }

    public function get_item_type()
    {

        $request_body = file_get_contents('php://input', true);
        $post = json_decode($request_body, true);

        if (isset($post['id'])) {
            $sql = 'SELECT * FROM memorize.item_type WHERE id = ' . $post['id'];
            $query = $this->db->query($sql);
            echo json_encode($query->row(0));
        } else {
            $sql = 'SELECT * FROM memorize.item_type WHERE uid = 1 AND priority !=0 ORDER BY priority DESC';
            $query = $this->db->query($sql);
            echo json_encode($query->result());
        }
    }

    public function save_item_type()
    {
        $request_body = file_get_contents('php://input', true);
        $type = json_decode($request_body, true);

        $result = $this->item_type_model->update($type);

        echo json_encode(array(
            'success' => $result
        ));
    }
}

/* End of file */