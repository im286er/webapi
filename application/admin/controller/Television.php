<?php
/**
 * Created by PhpStorm.
 * User: chenly
 * Date: 2017/10/12
 * Time: 8:53
 */

namespace app\admin\controller;

use app\common\controller\BaseAdmin;
use think\Db;

class Television extends BaseAdmin{

    public $table = 'television';

    public function index(){

        $this->title = '系统操作日志';
        //$db = Db::name($this->table)->order('id desc');

        $db = Db::field('a.*,b.name as countryName,c.name as provinceName')
            ->table("t_television")
            ->alias('a')
            ->join('t_region b','a.country = b.code')
            ->join('t_region c','a.province = c.code')
            ->order('a.id desc');


        return parent::_list($db);
    }

    public function add()
    {
        $get = $this->request->get();


        $country = "100000";
        $countrys = Db::name("region")->where("code",$country)->order('code asc')->select();
        $this->assign('countrys', $countrys);
        $this->assign('country', $country);

        if(isset($get['province']) && $get['province'] !== ''){
            $province = $get['province'];
            $provinces = Db::name("region")->where("code",$province)->order('code asc')->select();
            $this->assign('provinces', $provinces);
            $this->assign('province', $province);
        }else{
            $this->assign('provinces', "");
        }
        if(isset($get['city']) && $get['city'] !== ''){
            $city = $get['city'];
            $citys = Db::name("region")->where("code",$city)->order('code asc')->select();
            $this->assign('citys', $citys);
            $this->assign('city', $city);
        }else{
            $this->assign('citys', "");
        }

        return $this->_form($this->table, 'form');
    }

    public function getchildregion($parentCode){
        $db = Db::name("region")->where("parentCode",$parentCode)->order('code asc');
        $data = $db->select();
        return json($data);
    }

    public function edit()
    {
        return $this->_form($this->table, 'form');
    }

    protected function _form_filter(&$vo)
    {
        if ($this->request->isGet()) {

            //$get = $this->request->get();

            $country = '100000';
            $countrys = Db::name("region")->where("code",$country)->order('code asc')->select();
            $this->assign('countrys', $countrys);
            $this->assign('country', $country);

            if(isset($vo['province']) && $vo['province'] !== ''){
                $province = $vo['province'];
                $provinces = Db::name("region")->where("code",$province)->order('code asc')->select();
                $this->assign('provinces', $provinces);
                $this->assign('province', $province);
            }else{
                $this->assign('provinces', "");
            }
            if(isset($vo['city']) && $vo['city'] !== ''){
                $city = $vo['city'];
                $citys = Db::name("region")->where("code",$city)->order('code asc')->select();
                $this->assign('citys', $citys);
                $this->assign('city', $city);
            }else{
                $this->assign('citys', "");
            }

        }
    }

    public function del()
    {
        if (DataService::update($this->table)) {
            $this->success("删除成功!", '');
        }
        $this->error("删除失败, 请稍候再试!");
    }

}