<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class days extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('days_model');
    }

    function index()
    {
        $day = $this->input->get('day', true);

        if ($day === false) {
            $week = $this->f->get_time_range_of_week(date('Y-m-d', time()));
        } else {
            $week = $this->f->get_time_range_of_week($day);
        }

        $data = $this->days_model->get_days(array(
            'time' => $week->start
        ));

        echo json_encode($data);
    }

    function update()
    {
        $this->days_model->update($_POST);

        echo json_encode(array(
            'success' => true,
            'op' => 'update'
        ));
    }

    function insert()
    {

        $data = $this->days_model->get(array(
            'sortBy' => 'time',
            'sortDirection' => 'DESC',
            'limit' => 1
        ));

        $time = strtotime(date('Y-m-d', $data['data'][0]->time));

        for ($i = 1; $i < 30; $i++) {

            $time += 3600 * 24;

            echo date('Y-m-d', $time);

            $this->days_model->insert(array(
                'time' => $time
            ));
        }
    }
}

/* End of file */
