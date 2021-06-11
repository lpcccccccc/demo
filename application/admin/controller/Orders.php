<?php

namespace app\admin\controller;


class Orders extends Base
{
    // 订单列表
    public function index()
    {

        if(request()->isAjax()){
            $request =request()->param();
            echo "<pre>";
            print_r($request);
            echo "<pre>";
            exit();
            $result = db('order')->order('id', 'desc')->select();
            foreach($result as $key=>$vo){

                // 优化显示状态
                if(empty($vo['goods_id'])){
                    $result[$key]['goods_id'] = '充值业务';
                }else{
                    $result[$key]['goods_id'] = '商品表查的name';
                }
                // 生成操作按钮
                $result[$key]['operate'] = $this->makeBtn($vo['id']);
                $user_name = db('user')->field('user_name')->where('id',$vo['user_id'])->find();
                $result[$key]['user_name'] = $user_name['user_name'];
            }
            $return['total'] = db('order')->count();  //总数据
            $return['rows'] = $result;
            return json($return);
        }

        return $this->fetch();
    }

    // 生成按钮
    private function makeBtn($id)
    {
        $operate = '<a href="' . url('orders/editorder', ['id' => $id]) . '">';
        $operate .= '<button type="button" class="btn btn-primary btn-sm"><i class="fa fa-paste"></i> 编辑</button></a> ';

        $operate .= '<a href="javascript:delOrder(' . $id . ')"><button type="button" class="btn btn-danger btn-sm">';
        $operate .= '<i class="fa fa-trash-o"></i> 删除</button></a> ';
        return $operate;
    }

}