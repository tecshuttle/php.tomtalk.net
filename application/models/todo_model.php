<?php

class todo_model extends CI_Model
{
    var $table = 'todo_lists';

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    function get_project_total_hour($name)
    {
        $sql = "SELECT sum(time_long) / 3600 AS total_hour FROM $this->table WHERE job_name LIKE '$name%'";

        $query = $this->db->query($sql);

        $data = $query->result();

        return $data[0]->total_hour;
    }

    function get_project_day_hours($project_id)
    {
        $sql = "SELECT FROM_UNIXTIME( start_time, '%Y-%m-%d' ) AS DATE, SUM( time_long ) /3600 AS HOUR "
            . "FROM todo_lists WHERE user_id =1 AND project_id = $project_id GROUP BY DATE ORDER BY DATE";

        $query = $this->db->query($sql);
        $rows = $query->result();

        $data = array();

        if (count($rows) != 0) { //=========补全每天的时间点========
            //持续多少天
            $interval_days = $this->timespan(strtotime($rows[0]->DATE), strtotime($rows[count($rows) - 1]->DATE), 'days');

            //把rows的数据，整理成以日期为值的数组
            $work_day = array();
            foreach ($rows as $row) {
                $work_day[$row->DATE] = $row->HOUR;
            }

            //补全工作日数据
            $data = array();
            $day_time = strtotime($rows[0]->DATE);
            for ($i = 0; $i <= $interval_days; $i++) {
                $day = date('Y-m-d', $day_time);
                if (isset($work_day[$day])) {
                    $data[$day] = $work_day[$day] * 1; //格式转换
                } else {
                    $data[$day] = 0.00;
                }

                $day_time += 3600 * 24;
            }
        }

        return $data;
    }


    private function timespan($time1, $time2 = NULL, $output = 'years,months,weeks,days,hours,minutes,seconds')
    {
        // Array with the output formats
        $output = preg_split('/[^a-z]+/', strtolower((string)$output));
        // Invalid output
        if (empty($output))
            return FALSE;
        // Make the output values into keys
        extract(array_flip($output), EXTR_SKIP);
        // Default values
        $time1 = max(0, (int)$time1);
        $time2 = empty($time2) ? time() : max(0, (int)$time2);
        // Calculate timespan (seconds)
        $timespan = abs($time1 - $time2);
        // All values found using Google Calculator.
        // Years and months do not match the formula exactly, due to leap years.
        // Years ago, 60 * 60 * 24 * 365
        isset($years) and $timespan -= 31556926 * ($years = (int)floor($timespan / 31556926));
        // Months ago, 60 * 60 * 24 * 30
        isset($months) and $timespan -= 2629744 * ($months = (int)floor($timespan / 2629743.83));
        // Weeks ago, 60 * 60 * 24 * 7
        isset($weeks) and $timespan -= 604800 * ($weeks = (int)floor($timespan / 604800));
        // Days ago, 60 * 60 * 24
        isset($days) and $timespan -= 86400 * ($days = (int)floor($timespan / 86400));
        // Hours ago, 60 * 60
        isset($hours) and $timespan -= 3600 * ($hours = (int)floor($timespan / 3600));
        // Minutes ago, 60
        isset($minutes) and $timespan -= 60 * ($minutes = (int)floor($timespan / 60));
        // Seconds ago, 1
        isset($seconds) and $seconds = $timespan;
        // Remove the variables that cannot be accessed
        unset($timespan, $time1, $time2);
        // Deny access to these variables
        $deny = array_flip(array('deny', 'key', 'difference', 'output'));
        // Return the difference
        $difference = array();
        foreach ($output as $key) {
            if (isset($$key) AND !isset($deny[$key])) {
                // Add requested key to the output
                $difference[$key] = $$key;
            }
        }
        // Invalid output formats string
        if (empty($difference))
            return FALSE;
        // If only one output format was asked, don't put it in an array
        if (count($difference) === 1)
            return current($difference);
        // Return array
        return $difference;
    }


    function get_month_chart_data($job_type_id, $start_date, $end_date)
    {
        $start_time = strtotime($start_date);
        $end_time = strtotime($end_date);

        $sql = "SELECT FROM_UNIXTIME( start_time,  '%Y-%m-%d' ) AS DATE, SUM( time_long ) /3600 AS HOUR "
            . "FROM $this->table WHERE user_id=1 AND job_type_id = $job_type_id AND start_time >= $start_time AND start_time <= $end_time "
            . "GROUP BY DATE ORDER BY DATE ASC";

        //echo $sql . '<br/>';

        $query = $this->db->query($sql);
        $rows = $query->result();

        //=========补全每天的时间点========

        //把rows的数据，整理成以日期为值的数组
        $work_day = array();
        foreach ($rows as $row) {
            $work_day[$row->DATE] = $row->HOUR;
        }

        //补全工作日数据
        $data = array();
        $day_time = strtotime($start_date);

        //这个月有多少天
        for ($i = 0; $i < 100; $i++) {
            $day = date('Y-m-d', $day_time);
            if (isset($work_day[$day])) {
                $data[$day] = $work_day[$day] * 1; //格式转换
            } else {
                $data[$day] = 0.00;
            }

            $day_time += 3600 * 24;
            //如果月份不同了，则退出
            if (date('M', $start_time) != date('M', $day_time)) {
                break;
            }
        }

        return $data;
    }


}

//end file
