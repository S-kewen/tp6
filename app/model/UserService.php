<?php
namespace app\model;
use think\Model;
use think\model\concern\SoftDelete;
use think\facade\Db;
class UserService extends Model{
    use SoftDelete;
    protected $name = 'UserService';
    protected $table = 't_user';
    protected $deleteTime = 'deleted'; //软删除标记字段
    protected $defaultSoftDelete = 0; // 软删除字段的默认值
    public function list($where,$order='id DESC',$page=1,$total=20){
        $count = UserService::where($where)->count();
        $list = UserService::where($where)
        			->order($order)
        			->page($page,$total)
        			->select();
        if($list->isEmpty()){
        	return null;
        }
        $data = $list->toArray();
        $arr = [
        	'count' => $count,
        	'data' => $data
        ];
        return $arr;
    }
    public function insertOne($params){
        return Db::table('t_user')->insert($params);;
    }
    public function getCount($where){
        return UserService::where($where)
                    ->limit(1)
                    ->count();
    }
    public function updateOne($where,$set){
        return Db::table('t_user')->where($where)->limit(1)->update($set);
    }
    public function deleteOne($where){
        return Db::table('t_user')->where($where)->limit(1)->delete();
    }
    public function selectOne($where){
        return UserService::where($where)
                    ->limit(1)
                    ->select();
    }
}