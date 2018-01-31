<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class todo extends CI_Controller
{
    var $todo_lists = 'todo_lists';
    var $todo_projects = 'todo_projects';
    var $uid = 0;

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('todo_model');

        session_start();
        if (isset($_SESSION['uid'])) {
            $this->uid = $_SESSION['uid'];
        }

        header("Access-Control-Allow-Origin: *");
    }

    public function index()
    {
        if (!$this->getUser()) {
            header('Location: /user/login');
        }

        $data = array(
            'menu'       => 'about_us',
            'work_type'  => $this->get_work_type(),
            'user_name'  => $_SESSION['user_name'],
            'projects'   => $this->get_projects(),
            'project_id' => $this->input->get('project_id', true),
            'css'        => array(),
            'js'         => array(
                '/assets/js/scroll.js'
            )
        );

        $this->load->view('todo/index', $data);
    }

    private function get_projects()
    {
        $sql = "SELECT * FROM $this->todo_projects WHERE user_id= {$this->uid} ORDER BY id ASC";

        return $this->db->query($sql)->result();
    }

    public function test()
    {
        $request_body = file_get_contents('php://input', true);
        $post         = json_decode($request_body, true);

        $result = array(
            'success' => true
        );

        echo json_encode(array_merge($post, $result));
    }


    public function get_work_type()
    {
        $work_type = array(
            array('id' => '0', 'name' => '其它', 'color' => '#EEEEEE'),
            array('id' => '1', 'name' => '计划', 'color' => '#D6E685'),
            array('id' => '2', 'name' => '开会', 'color' => '#1E6823'),
            array('id' => '3', 'name' => '需求分析', 'color' => '#EF14B4'),
            array('id' => '4', 'name' => '编码', 'color' => '#BA0BE0'),
            array('id' => '5', 'name' => '测试', 'color' => '#186DED'),
            array('id' => '6', 'name' => 'fixbug', 'color' => '#ff0000'),
            array('id' => '7', 'name' => '部署', 'color' => '#DB4733'),
            array('id' => '8', 'name' => '文档', 'color' => '#FFBA04'),
            array('id' => '9', 'name' => '重构', 'color' => '#009D59'),
            array('id' => '10', 'name' => '学习', 'color' => '#8CC665'),
            array('id' => '11', 'name' => '工作沟通', 'color' => '#3CA665')
        );

        $dataType = $this->input->post('dataType', true);

        if ($dataType === 'json') {
            echo json_encode($work_type);
        } else {
            return $work_type;
        }
    }


    public function day()
    {
        if (!$this->getUser()) {
            header('Location: /user/login');
        }

        $data = array(
            'user_name' => $_SESSION['user_name'],
            'css'       => array(),
            'js'        => array()
        );

        $this->load->view('todo/day', $data);
    }

    public function getUser()
    {
        if (isset($_SESSION['uid'])) {
            $this->load->model('users_model');
            $user                  = $this->users_model->getByID($_SESSION['uid']);
            $_SESSION['user_name'] = $user->email;

            return $user;
        } else if (isset($_COOKIE['uid'])) {
            $this->load->model('users_model');
            $user                  = $this->users_model->getByID($_COOKIE['uid']);
            $_SESSION['uid']       = $user->uid;
            $_SESSION['user_name'] = $user->email;

            return $user;
        } else {
            return false;
        }
    }

    public function get_jobs_of_week()
    {
        $day         = $this->input->post('day', true);
        $job_type_id = $this->input->post('job_type_id', true);
        $project_id  = $this->input->post('project_id', true);

        $all_jobs_of_week = $this->todo_lib->get_all_jobs_of_week($day, $job_type_id, $project_id, $this->uid);

        //把任务按日期分组
        $list = $this->todo_lib->gather_jobs_by_day($all_jobs_of_week);

        echo json_encode($list);
    }


    public function get_jobs_of_day()
    {
        $day = $this->input->post('day', true);

        $start = strtotime($day);
        $end   = $start + 3600 * 24 - 1;

        $sql = "SELECT * FROM $this->todo_lists WHERE start_time >= $start AND start_time <= $end"
            . " ORDER BY start_time ASC";

        $query = $this->db->query($sql);

        echo json_encode($query->result());
    }

    /**计算job的开始时间
     *1、如果是当天第一个任务，start_time则从0时开始
     *2、如果已有其它任务，start_time为最后一个任务开始时间+任务时长+1
     */
    public function add_job()
    {
        //取得任务当天的开始时间
        $week       = $this->f->get_time_range_of_week($this->input->post('week_date', true));
        $i_day      = $this->input->post('i_day', true);
        $project_id = $this->input->post('project_id', true);

        if ($i_day == 0) {
            $start_time = $week->start + 6 * (3600 * 24);
        } else {
            $start_time = $week->start + ($i_day - 1) * (3600 * 24);
        }

        //如果当天已存在任务，则新任务添加在原有任务后面
        $job = $this->is_had_job_day($start_time);
        if ($job) {
            $start_time = $job->start_time + $job->time_long + 1;
        }

        $data = array(
            'user_id'    => $this->uid,
            'job_name'   => '#',
            'project_id' => $project_id,
            'start_time' => $start_time,
            'time_long'  => 60 * 60 //任务耗时默认1小时
        );

        $this->db->insert($this->todo_lists, $data);

        echo json_encode(array(
            'success'    => true,
            'start_time' => date('Y-m-d H:i:s', $start_time),
            'id'         => $this->db->insert_id()
        ));
    }

    public function get_job()
    {
        $id = $this->input->post('id', true);

        echo json_encode(array(
            'success' => true,
            'job'     => $this->get_job_by_id($id)
        ));
    }

    public function job_edit()
    {
        $data = array(
            'project_id'  => $this->input->post('project_id', true),
            'id'          => $this->input->post('id', true),
            'job_name'    => $this->input->post('job_name', true),
            'job_type_id' => $this->input->post('job_type_id', true),
            'time_long'   => $this->input->post('time_long', true),
            'job_desc'    => $this->input->post('job_desc', true)
        );

        $data['task_type_id'] = ($data['job_type_id'] == 3 ? $this->input->post('task_type_id', true) : 0);

        //是否要切换日期
        $start_time = $this->input->post('start_time', true);
        if ($start_time) {
            $data['start_time'] = $start_time;
        }

        //标记任务是否完成
        $status = $this->input->post('status', true);
        if ($status) {
            $data['status'] = $status;

            //如果是当日任务，列在完成队列尾部
            if (!$start_time) {
                //取最后一个完成的任务
                $cul_job  = $this->get_job_by_id($data['id']);
                $job_date = date('Y-m-d', $cul_job->start_time);

                $last_done_job = $this->get_last_done_job($job_date);

                if ($last_done_job) {
                    $data['start_time'] = $last_done_job->start_time + 1;
                }
            }
        }

        $this->db->update($this->todo_lists, $data, array('id' => $data['id']));

        echo json_encode(array(
            'success' => true
        ));
    }

    /**
     * 调整任务的时间
     * 1、如果没有后续任务，则本任务时间为前一任务start_time + 1
     * 2、如果有后续任务，则本任务时间为前一任务start_time + 1，后续任务时间顺延
     * 3、如果没有前置任务，则本任务时间为当日0时，后续任务顺延
     */
    public function move_job()
    {
        $id          = $this->input->post('id', true); //取job ID
        $prev_job_id = $this->input->post('prev_job_id', true);
        $next_job_id = $this->input->post('next_job_id', true);

        //没有前置任务
        if ($prev_job_id == 0) {
            //当前任务，全部移到当天结束前最后时刻
            $day      = $this->get_time_range_of_day();
            $all_jobs = $this->all_jobs_of_day();

            foreach ($all_jobs as $key => $row) {
                $data = array(
                    'id'         => $row->id,
                    'start_time' => $day->end - count($all_jobs) + $key
                );

                $this->db->update($this->todo_lists, $data, array('id' => $data['id']));
            }

            //把当前任务，放到本日最前面
            $data = array(
                'id'         => $id,
                'start_time' => $day->start
            );

            $this->db->update($this->todo_lists, $data, array('id' => $data['id']));

            $this->reorder_rest_job_start_time($id); //后续任务顺延
        }

        //没有后续任务
        if ($next_job_id == 0) {
            //取目的日期，最后一个任务
            $all_jobs = $this->all_jobs_of_day();
            $last_job = $all_jobs[count($all_jobs) - 1];

            //当前任务，放在它后面
            $data = array(
                'id'         => $id,
                'start_time' => $last_job->start_time + 1
            );

            $this->db->update($this->todo_lists, $data, array('id' => $data['id']));

            $this->reorder_rest_job_start_time($all_jobs[0]->id); //后续任务顺延
        }

        //接前置任务，后续任务顺延
        if ($prev_job_id > 0 AND $next_job_id > 0) {
            //取任务当天的结束时间
            $job        = $this->get_job_by_id($id);
            $day_str    = date('Y-m-d', $job->start_time);
            $end_of_day = strtotime($day_str) + 3600 * 24 - 1;

            //当前任务以下，全部移到当天结束前最后时刻
            $all_jobs  = $this->siblings_of_job($id);
            $do_marker = false;
            foreach ($all_jobs as $key => $row) {

                if ($do_marker) {
                    $data = array(
                        'id'         => $row->id,
                        'start_time' => $end_of_day - count($all_jobs) + $key
                    );

                    $this->db->update($this->todo_lists, $data, array('id' => $data['id']));
                }

                if ($row->id == $prev_job_id) {
                    $do_marker = true;
                }
            }

            //当前任务，移到前置任务后
            $prev_job = $this->get_job_by_id($prev_job_id);
            $data     = array(
                'id'         => $id,
                'start_time' => $prev_job->start_time + 1
            );

            $this->db->update($this->todo_lists, $data, array('id' => $data['id']));

            $this->reorder_rest_job_start_time($id); //后续任务顺延*/
        }

        echo json_encode(array(
            'success' => true
        ));
    }


    public function export_csv()
    {
        $week = $this->f->get_time_range_of_week($this->input->get('day', true));

        $sql = "SELECT * FROM $this->todo_lists "
            . " WHERE user_id = $this->uid AND start_time >= $week->start AND start_time <= $week->end"
            . " ORDER BY start_time ASC";

        $query = $this->db->query($sql);
        $data  = $query->result();

        $str = "日期,任务,耗时（分钟）\n";
        $str = iconv('utf-8', 'gb2312', $str);

        foreach ($data as $row) {
            $name = iconv('utf-8', 'gb2312', $row->job_name); //中文转码
            $date = date('Y-m-d', $row->start_time);
            $str .= $date . "," . $name . "," . $row->time_long / 60 . "\n"; //用引文逗号分开
        }

        $filename = date('Ymd') . '.csv'; //设置文件名

        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=" . $filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');

        echo $str;
    }

    /**
     * 传了week_data参数，是周视图，需要根据日期数计算当天日期
     * 只传了day参数的，是日视图
     */
    public function init_day()
    {
        $week_date = $this->input->post('week_date', true);
        $day       = $this->input->post('day', true);

        //取初始化当天的时间
        if ($week_date) {
            $time = strtotime($week_date);
            $day  = ($day == 0 ? 7 : $day);

            $start_time = $time + (($day - 1) * 3600 * 24);
        } else {
            $start_time = strtotime($day);
        }

        //取初始化当天是星期数，不同的日期，日程可单独配置
        $w    = date('w', $start_time);
        $jobs = $this->getInitDayJobs($w);

        foreach ($jobs as $i => $job) {
            $data = array(
                'user_id'     => $this->uid,
                'job_name'    => $job['job_name'],
                'job_type_id' => $job['job_type_id'],
                'start_time'  => $start_time + $i,
                'time_long'   => $job['time_long']
            );

            $this->db->insert($this->todo_lists, $data);
        }

        echo json_encode(array(
            'success' => true
        ));
    }

    private function getInitDayJobs($w)
    {
        $work_day = array(
            array('job_name' => '睡觉-早', 'time_long' => 3600 * 4, 'job_type_id' => 4),
            array('job_name' => '扇贝', 'time_long' => 3600, 'job_type_id' => 6),
            array('job_name' => '洗漱、穿衣', 'time_long' => 1800, 'job_type_id' => 1),
            array('job_name' => '通勤', 'time_long' => 3600 + (12 * 60), 'job_type_id' => 7),

            array('job_name' => '早餐', 'time_long' => (12 * 60), 'job_type_id' => 1),
            array('job_name' => '午餐、午休', 'time_long' => 3600 + (18 * 60), 'job_type_id' => 1),

            array('job_name' => '晚餐', 'time_long' => 1800, 'job_type_id' => 1),
            array('job_name' => '下班回家', 'time_long' => 3600 + (12 * 60), 'job_type_id' => 7),
            array('job_name' => '晚上休息', 'time_long' => 3600, 'job_type_id' => 0),
            array('job_name' => '睡觉-晚', 'time_long' => 3600 * 2.5, 'job_type_id' => 4)
        );

        $weekend = array(
            array('job_name' => '睡觉-早', 'time_long' => 3600 * 4, 'job_type_id' => 4),
            array('job_name' => '扇贝', 'time_long' => 3600, 'job_type_id' => 6),
            array('job_name' => '晚上休息', 'time_long' => 3600, 'job_type_id' => 0),
            array('job_name' => '睡觉-晚', 'time_long' => 3600 * 2.5, 'job_type_id' => 4)
        );

        return (($w == 6 or $w == 0) ? $weekend : $work_day);
    }


    public function ________pub_func________()
    {
        //let this blank.
    }

    public function get_job_by_id($id)
    {
        $sql = "SELECT * FROM $this->todo_lists WHERE id= $id";

        $query = $this->db->query($sql);
        $data  = $query->result();

        return $data[0];
    }

    private function get_last_done_job($date)
    {
        $day_start = strtotime($date . ' 00:00:00');
        $day_end   = strtotime($date . ' 23:59:59');

        $sql   = "SELECT * FROM $this->todo_lists WHERE start_time >= $day_start AND start_time <= $day_end AND status = 1 "
            . "ORDER BY start_time ASC";
        $query = $this->db->query($sql);
        $rows  = $query->result();

        if (count($rows) > 0) {
            return $rows[count($rows) - 1];
        } else {
            return false;
        }
    }

    public function reorder_rest_job_start_time($job_id)
    {
        $job        = $this->get_job_by_id($job_id);
        $rest_start = $job->start_time + 1;

        $all_jobs  = $this->siblings_of_job($job_id);
        $do_marker = false;
        foreach ($all_jobs as $row) {
            if ($do_marker) {
                $option = array(
                    'id'         => $row->id,
                    'start_time' => $rest_start
                );

                $this->db->update($this->todo_lists, $option, array('id' => $option['id']));
                $rest_start += 1;
            }

            if ($row->id == $job_id) {
                $do_marker = true;
            }
        }
    }

    public function siblings_of_job($job_id)
    {
        $job = $this->get_job_by_id($job_id);

        //取任务当天的结束时间
        $day_str    = date('Y-m-d', $job->start_time);
        $start      = strtotime($day_str);
        $end_of_day = $start + 3600 * 24 - 1;

        $sql = "SELECT * FROM $this->todo_lists WHERE start_time >= $start AND start_time <= $end_of_day"
            . " ORDER BY start_time ASC";

        $query = $this->db->query($sql);

        return $query->result();
    }

    //取任务当天，全部任务
    public function all_jobs_of_day()
    {
        $day = $this->get_time_range_of_day();

        $sql = "SELECT * FROM $this->todo_lists WHERE start_time >= $day->start AND start_time <= $day->end"
            . " ORDER BY start_time ASC";

        $query = $this->db->query($sql);

        return $query->result();
    }


    public function is_had_job_day($start)
    {
        $end = $start + 3600 * 24 - 1;

        $sql = "SELECT * FROM $this->todo_lists WHERE start_time >=$start AND start_time <= $end "
            . "ORDER BY start_time DESC";

        $query = $this->db->query($sql);
        $data  = $query->result();

        if (count($data) === 0) {
            return false;
        } else {
            return $data[0];
        }
    }

    //周视图传to_day参数
    //日视图传date参数
    public function get_time_range_of_day()
    {
        $date = $this->input->post('date', true);

        if ($date) {
            $start = strtotime($date);
            $day   = (object)array(
                'start' => $start,
                'end'   => $start + 3600 * 24 - 1
            );

            return $day;
        }

        $to_day = substr($this->input->post('to_day', true), 3);
        $week   = $this->f->get_time_range_of_week($this->input->post('week_date', true));

        if ($to_day == 0) {
            $start = $week->start + 6 * (3600 * 24);
        } else {
            $start = $week->start + ($to_day - 1) * (3600 * 24);
        }

        return (object)array(
            'start' => $start,
            'end'   => $start + 3600 * 24 - 1
        );
    }
}

/* End of file */
