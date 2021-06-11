<?php

namespace app\admin\controller;

class Version extends Base
{
    // 产品列表
    public function show()
    {
        $request =request()->param('version');
        if($request == 'bussiness')
        {
            //商业版
            $version = db('goods')->where('id','2')->find();

        }else if($request == 'free')
        {
            //免费版
            $version = db('goods')->where('id','1')->find();
        }else
        {
            //无限版
            $version = db('goods')->where('id','3')->find();
        }
//        $order_num = $request['searchText'];
//        $result = db('order')->order('id', 'desc')->select();
        if(request()->isAjax()){

            $param = input('post.');
            try{
                db('goods')->where('id', $param['id'])->update($param);
            }catch(\Exception $e){
                return json(['code' => -2, 'data' => '', 'msg' => $e->getMessage()]);
            }

            return json(['code' => 1, 'data' => '', 'msg' => '修改成功']);
        }
        $this->assign([
            'version' => $version,
            'msg'  => '加载成功'
        ]);
        return $this->fetch();
    }


}