<?php

namespace app\admin\controller;

class Site extends Base
{
    // 基本设置
    public function index()
    {
        $site = db('site')->find();

        if(request()->isAjax()){
            $param = input('post.');
            try{
                db('site')->where('id', $param['id'])->update($param);
            }catch(\Exception $e){
                return json(['code' => -2, 'data' => '', 'msg' => $e->getMessage()]);
            }

            return json(['code' => 1, 'data' => '', 'msg' => '编辑管理员成功']);
        }
        $this->assign([
            'site' => $site,
            'msg'  => '修改成功'
        ]);
        return $this->fetch();
    }


}