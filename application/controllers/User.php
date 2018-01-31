<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class user extends CI_Controller
{
    public function index()
    {
        echo 'user controller';
    }


    public function getUser()
    {
        session_start();

        if (isset($_SESSION['uid'])) {
            $this->load->model('users_model');
            $user = $this->users_model->getByID($_SESSION['uid']);
            return $user;
        } else if (isset($_COOKIE['uid'])) {
            $this->load->model('users_model');
            $user = $this->users_model->getByID($_COOKIE['uid']);
            return $user;
        } else {
            return false;
        }
    }

    public function register()
    {
        $data = array(
            'title' => 'Company profile',
            'menu' => 'about_us',
            'css' => array(),
            'js' => array(
                '/assets/js/scroll.js'
            )
        );

        $this->load->view('todo/register', $data);
    }

    public function register_submit()
    {
        $this->load->model('users_model');

        $this->users_model->insert(array(
            'email' => $this->input->post('email', true),
            'password' => md5($this->input->post('pwd', true))
        ));

        header('Location: /user/login');
    }

    public function login()
    {
        $data = array(
            'title' => 'Company profile',
            'menu' => 'about_us',
            'css' => array(),
            'js' => array(
                '/assets/js/scroll.js'
            )
        );

        $this->load->view('todo/login', $data);
    }


    public function login_submit()
    {
        $email = $_POST['email'];
        $password = $_POST['pwd'];

        $this->load->model('users_model');
        $user = $this->users_model->login($email, $password);

        if ($user) {
            session_start();
            $_SESSION['uid'] = $user->uid;
            $_SESSION['user_name'] = $user->email;

            $expire = time() + 30 * 86400; // 30 day
            setcookie('uid', $user->uid, $expire, '/');
        }

        header('Location: /');
    }

    public function logout()
    {
        session_start();

        unset($_SESSION['uid']);
        unset($_SESSION['user_name']);

        $expire = -1;
        setcookie('uid', '', $expire, '/');

        header('Location: /todo');
    }

    public function forget_password()
    {
        //取密码
        $this->load->model('users_model');
        $user = $this->users_model->forgetPassword($_POST['email']);
        $password = ($user ? $user->password : '');

        $this->load->library('email'); //加载CI的email类

        //以下设置Email参数
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'smtp.163.com';
        $config['smtp_user'] = 'sky007_tom';
        $config['smtp_pass'] = 'tecshuttle';
        $config['smtp_port'] = '25';
        $config['charset'] = 'utf-8';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';
        $this->email->initialize($config);

        //以下设置Email内容
        $this->email->from('sky007_tom@163.com', 'tom');
        $this->email->to($_POST['email']);
        $this->email->subject('xs forget password');
        $this->email->message("<h2>Your password is: $password </h2>");

        $this->email->send();

        echo json_encode(array(
            'success' => true
        ));
    }
}

/* End of file */