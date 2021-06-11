<?php

namespace app\admin\controller;


class Orders extends Base
{
    // 订单列表
    public function index()
    {
        if(request()->isAjax()){
            $request =request()->param();
            $order_num = $request['searchText'];
            $result = db('order')->order('id', 'desc')->select();
            $return['total'] = db('order')->count();  //总数据

            if(!empty($order_num))
            {
                $result = db('order')->whereLike('order_num','%'.$order_num.'%')->order('id', 'desc')->select();
                $return['total'] = db('order')->whereLike('order_num','%'.$order_num.'%')->count();  //总数据
            }

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
            $return['rows'] = $result;

            return json($return);
        }

        return $this->fetch();
    }

    // 删除客服
    public function delOrder()
    {
        if(request()->isAjax()){
            $id = input('param.id/d');

            try{
                db('order')->where('id', $id)->delete();
            }catch(\Exception $e){
                return json(['code' => -1, 'data' => '', 'msg' => $e->getMessage()]);
            }

            return json(['code' => 1, 'data' => '', 'msg' => '成功删除订单']);
        }
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