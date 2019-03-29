<?php

namespace controllers;

use system\Base;
use system\Model;

class Index extends Base {
    public function index() {
        return 'Welcome to use MyClassPHP';
    }

    public function test() {
        $model = new Model();
        $list  = $model->from('weixin_media')->limit(1)->select();

        return $list;
    }
}

?>