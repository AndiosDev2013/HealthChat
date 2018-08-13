<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'RESTProcessor.php';

class Member extends RESTProcessor {

    public function __init() {
        parent::__init();
        $this->__load_model('UserModel');
    }

    public function __check_login() {
        return true;
    }

    public function login() {
        $email = $this->__params('email');
        $password = $this->__params('password');
        $user = $this->__model->get_by_name_or_email($email);
        if ($user && $password == $user->password) {
            $device_token = $this->__params('device_token');
            if ($device_token) {
                $this->__model->save(array('uid' => $user->uid, 'device_token' => $device_token));
                $user->device_token = $device_token;
            }
            if (empty($user->token)) {
                $user->token = md5($user->name . $user->email . $user->password);
                $this->__model->save(array('uid' => $user->uid, 'token' => $user->token));
            }

            $this->success(array('token' => $user->token, 'user' => $this->__model->entity($user->uid)));
        } else
            return $this->error('Invalid e-mail address or password.', ERROR_CODE_INVALID_EMAIL_OR_PASSWORD);
    }

    public function check_name_or_email() {
        if ($this->__model->get_by_name_or_email($this->__params('name'))) {
            $this->error('The name or email is already in use.', ERROR_CODE_NAME_OR_EMAIL_ALREADY_REGISTERED);
        } else {
            $this->success(null, 'This name or email is valid.');
        }
    }

    public function check_name() {
        if ($this->__model->get_by_name($this->__params('name'))) {
            $this->error('The name is already in use.', ERROR_CODE_NAME_ALREADY_REGISTERED);
        } else {
            $this->success(null, 'This name is valid.');
        }
    }

    public function check_email() {
        if ($this->__model->get_by_email($this->__params('email'))) {
            $this->error('The e-mail address is already in use.', ERROR_CODE_EMAIL_ALREADY_REGISTERED);
        } else {
            $this->success(null, 'This e-mail address is valid.');
        }
    }

    public function signup() {
        $name = $this->__params('name');
        $gender = $this->__params('gender');
        $birthday = $this->__params('birthday');
        $password = $this->__params('password');
        $phone = $this->__params('phone');
        $email = $this->__params('email');
        $full_name = $this->__params('full_name');
        $occupation = $this->__params('occupation');
        $education = $this->__params('education');
        $address = $this->__params('address');
        $device_token = $this->__params('device_token');
        $res_id = (int) $this->__params('res_id');
        $about = $this->__params('about');
        $social_picture_url = $this->__params('social_picture_url');

        if ($this->__model->get_by_name($name)) {
            if ($res_id > 0)
                $this->__remove_resource($res_id);
            $this->error('The name is already in use.', ERROR_CODE_NAME_ALREADY_REGISTERED);
            return;
        } else if (!empty($email) && $this->__model->get_by_email($email)) {
            if ($res_id > 0)
                $this->__remove_resource($res_id);
            $this->error('The e-mail address is already in use.', ERROR_CODE_EMAIL_ALREADY_REGISTERED);
            return;
        }

        $newid = $this->__model->save(array('name' => $name, 'gender' => $gender, 'birthday' => $birthday, 'email' => $email, 'password' => $password, 'phone' => $phone, 'full_name' => $full_name, 'occupation' => $occupation, 'education' => $education, 'address' => $address, 'about' => $about, 'social_picture_url' => $social_picture_url, 'device_token' => $device_token, 'level' => USER_TYPE_GUEST, 'reg_date' => date('Y-m-d H:i:s', now())));
        if ($newid > 0) {
            $user = $this->__model->entity($newid);
            $this->success(array('token' => $user->token), 'Signup Succeeded.');
        } else
            $this->error('Signup failed.');
    }

    public function locate() {
        if (parent::__check_login()) {
            $longitude = $this->__params('longitude');
            $latitude = $this->__params('latitude');
            if ($this->__model->save(array('uid' => $this->__user->uid, 'longitude' => $longitude, 'latitude' => $latitude))) {
                $this->success();
                return;
            }
        } else {
            $this->error('Authentication required.', ERROR_CODE_AUTHENTICATION_REQUIRED);
            return;
        }
        $this->error('Unknown Error.');
    }

    public function recover_password() {
        $email = $this->__params('email');
        $password = $this->__params('password');
        $user = $this->__model->get_by_name_or_email($email);
        if ($user) {
            if (empty($user->token)) {
                $user->token = md5($user->name . $user->email . $user->password);
                $this->__model->save(array('uid' => $user->uid, 'token' => $user->token));
            }
            $subject = "Verification for Password Reset";
            $name = !empty($user->name) ? $user->name : $user->email;
            $email = $user->email;
            $token = $user->token;
            $base_url = base_url();
            $msg = "<html>
                        <body>
                            <h4>Hello $name</h4>
                            <p>
                                You have entered a new password to access HealthChat.
                            </p>
                            <p>
                                <strong>To activate the new password, click here:</strong>
                                $base_url/member/recover_password/$token/$password
                            </p>
                            <p>
                                Oops, it was not you? No problem - just ignore this e-mail. Your old password will remain valid.
                            </p>
                            <p>
                                Regards,<br />
                                Your HealthChat Support Team
                            </p>
                        </body>
                    </html>";

            ini_set('display_errors', true);
            if ($this->__controller->__mail($email, $subject, $msg))
                $this->success(null, 'Please check your email.');
            else
                $this->error('Email has not been sent.');
        } else
            $this->error('Invalid user name or email address.', ERROR_CODE_INVALID_NAME_OR_EMAIL);
    }

    public function search() {
        if (parent::__check_login()) {
            $offset = $this->__params('offset');
            $limit = $this->__params('limit');
            $this->response(array('member_list' => $this->__model->search($limit, $offset, array('type' => $this->__params('type')))));
            return;
        } else {
            $this->error('Authentication required.', ERROR_CODE_AUTHENTICATION_REQUIRED);
            return;
        }
        $this->error('Unknown Error.');
    }

    public function get() {
        if (parent::__check_login()) {
            $uid = $this->__params('uid');
            $user = $this->__model->get($uid);
            if ($user) {
                $this->response(array('user' => $user));
            } else {
                $this->error('Invalid User Id');
            }
            return;
        } else {
            $this->error('Authentication required.', ERROR_CODE_AUTHENTICATION_REQUIRED);
            return;
        }
        $this->error('Unknown Error.');
    }

    public function profile() {
        if (parent::__check_login()) {
            $this->success($this->__model->entity($this->__user->uid));
            return;
        } else {
            $this->error('Authentication required.', ERROR_CODE_AUTHENTICATION_REQUIRED);
            return;
        }
        $this->error('Unknown Error.');
    }

    public function update_profile() {
        if (parent::__check_login()) {
            $name = $this->__params('name');
            $password = $this->__params('password');
            $gender = $this->__params('gender');
            $birthday = $this->__params('birthday');
            $email = $this->__params('email');
            $full_name = $this->__params('full_name');
            $address = $this->__params('address');
            $about = $this->__params('about');
            $health_topic_array = $this->__params('health_topic_array');
            $diagnosed_with_array = $this->__params('diagnosed_with_array');
            $diagnosed_with_privacy = (int) $this->__params('diagnosed_with_privacy');
            $medicated_array = $this->__params('medicated_array');
            $medicated_privacy = (int) $this->__params('medicated_privacy');


            if ($name != $this->__user->name && $this->__model->get_by_name($name)) {
                $this->error('The name is already in use.', ERROR_CODE_NAME_ALREADY_REGISTERED);
                return;
            } else if ($email != $this->__user->email && !empty($email) && $this->__model->get_by_email($email)) {
                $this->error('The e-mail address is already in use.', ERROR_CODE_EMAIL_ALREADY_REGISTERED);
                return;
            }

            if ($this->__model->save(array('uid' => $this->__user->uid, 'name' => $name, 'gender' => $gender, 'birthday' => $birthday, 'email' => $email, 'password' => $password, 'full_name' => $full_name, 'address' => $address, 'about' => $about,
                        'health_topic_array' => $health_topic_array,
                        'diagnosed_with_array' => $diagnosed_with_array,
                        'diagnosed_with_privacy' => $diagnosed_with_privacy,
                        'medicated_array' => $medicated_array,
                        'medicated_privacy' => $medicated_privacy))) {
                $this->success();
                return;
            }
        } else {
            $this->error('Authentication required.', ERROR_CODE_AUTHENTICATION_REQUIRED);
            return;
        }
        $this->error('Unknown Error.');
    }

    public function update_avatar() {
        if (parent::__check_login()) {
            $user = $this->__model->entity($this->__user->uid);
            $res_id = $user->res_id;
            if ($res_id = $this->__save_resource('picture', $res_id)) {
                $this->__model->save(array('uid' => $user->uid, 'res_id' => $res_id));
                $user = $this->__model->entity($this->__user->uid);
                $this->success(array('picture_url' => $user->picture_url));
                return;
            }
            return;
        } else {
            $this->error('Authentication required.', ERROR_CODE_AUTHENTICATION_REQUIRED);
            return;
        }
        $this->error('Unknown Error.');
    }

    public function online() {
        if (parent::__check_login()) {
            $this->__model->online($this->__user->uid);
            $this->success();
            return;
        } else {
            $this->error('Authentication required.', ERROR_CODE_AUTHENTICATION_REQUIRED);
            return;
        }
        $this->error('Unknown Error.');
    }

    public function like() {
        if (parent::__check_login()) {
            $member_id = $this->__params('member_id');
            if ($member_id <= 0 || !($member = $this->__model->__entity($member_id))) {
                $this->error('Invalid member id');
                return;
            }

            $likeModel = $this->____load_model('MemberLikeModel');
            $like = $likeModel->get_by_member($member_id);
            if ($like) {
                $this->success(array('lid' => $like->lid, 'member_id' => $member_id, 'like_count' => $member->like_count));
                return;
            }

            $user_id = $this->__user->uid;
            if ($user_id == $member_id) {
                $this->error('You cannot like your self.');
                return;
            }
            $ret = $likeModel->save(array('member_id' => $member_id, 'user_id' => $user_id));
            if ($ret > 0) {
                $like_count = $likeModel->__total_count(array('member_id' => $member_id));
                $this->__model->__save(array('uid' => $member_id, 'like_count' => $like_count));
                $this->set_active_time();
                $this->success(array('lid' => $ret, 'member_id' => $member_id, 'like_count' => $like_count));
            } else
                $this->error('Unkown error');
        } else {
            $this->error('Authentication required.', ERROR_CODE_AUTHENTICATION_REQUIRED);
            return;
        }
        $this->error('Unknown Error.');
    }

    public function unlike() {
        $like_id = $this->__params('lid');
        $likeModel = $this->____load_model('MemberLikeModel');
        if ($like_id <= 0 || !($like = $likeModel->__entity($like_id))) {
            $this->error('Never liked.');
            return;
        }

        $ret = $likeModel->delete($like->lid);
        if ($ret) {
            $like_count = $likeModel->__total_count(array('member_id' => $like->member_id));
            $this->__model->__save(array('uid' => $like->member_id, 'like_count' => $like_count));
            $this->set_active_time();
            $this->success(array('lid' => $like->lid, 'member_id' => $like->member_id, 'like_count' => $like_count));
        } else
            $this->error('Unkown error');
    }

}

?>
