<?php
namespace app\admin\controller;

class Index extends Base
{
    // 后台总体框架
    public function index()
    {
        return $this->fetch('/index');
    }

    // 后台默认首页
    public function indexPage()
    {
        //查找总注册会员数
        $data['user_count'] = db('user')->count('id');

        //查找今日注册会员数
        $today_time = date('Y-m-d 00:00:00',time());

        $data['user_today'] = db('user')
            ->where('created_at','>=',$today_time)
            ->count('id');

        //总收入状况
        $data['money_count'] = db('order')->sum('price');

        //售出产品总数
        $data['goods_count'] = db('order')->where('goods_id','<>','')->count('id');

        $this->assign([
            'data' => $data
        ]);

        return $this->fetch('index');
    }

    // 清除缓存
    public function clear()
    {
        if (false === removeDir(RUNTIME_PATH)) {
            return json(['code' => -1, 'data' => '', 'msg' => '清除缓存失败']);
        }
        return json(['code' => 1, 'data' => '', 'msg' => '清除缓存成功']);
    }

    // 修改管理员密码
    public function changePassword()
    {
        if(request()->isPost()){

            $param = input('post.');
            $reLogin = false;

            if(empty($param['old_pwd']) && !empty($param['password'])){
                return json(['code' => -2, 'data' => '', 'msg' => '请输入旧密码']);
            }

            if(!empty($param['old_pwd']) && empty($param['password'])){
                return json(['code' => -3, 'data' => '', 'msg' => '请输入新密码']);
            }

            if(!empty($param['old_pwd']) && !empty($param['password'])){

                $userPwd = db('admins')->where('id', cookie('user_id'))->find();
                if(empty($userPwd)){
                    return json(['code' => -4, 'data' => '', 'msg' => '管理员不存在']);
                }

                if(md5($param['old_pwd'] . config('salt')) != $userPwd['password']){
                    return json(['code' => -1, 'data' => '', 'msg' => '旧密码错误']);
                }

                $info['password'] = md5($param['password'] . config('salt'));
                $reLogin = true;
            }

            db('admins')->where('id', cookie('user_id'))->setField('password', $info['password']);

            return json(['code' => 1, 'data' => $reLogin, 'msg' => '修改信息成功']);
        }
    }
}
