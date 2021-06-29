<?php
namespace app\controller;
use app\BaseController;
use think\facade\View;
use think\facade\Db;
use think\facade\Request;
use app\model\UserService;
class User extends BaseController{
	public function index(){
		echo json_encode([
			'code' =>200,
			'msg' => 'success'
		],JSON_UNESCAPED_UNICODE);
		exit;
	}
	public function list(){
		$param = Request::param();
		if(isset($param['id'])){
			$where['id'] = $param['id'];
		}
		if(isset($param['type'])){
			$where['type'] = $param['type'];
		}
		if(isset($param['state'])){
			$where['state'] = $param['state'];
		}
		$pageNumber = isset($param['pageNumber']) ? $param['pageNumber'] : 1;
		$pageSize = isset($param['pageSize']) ? $param['pageSize'] : 20;
		$userService = new UserService();
		$maps  = $userService->list(isset($where)?$where:true,isset($order)?$order:['id DESC'],$pageNumber,$pageSize);
		echo json_encode([
			'code' =>200,
			'msg' => 'success',
			'data'=>[
				'list'=>$maps['data'],
				'total'=>$maps['count']
			]
		],JSON_UNESCAPED_UNICODE);
		exit;
	}
	public function create(){
		$param = Request::param();
		if(!isset($param['username']) || !isset($param['password'])){
			echo json_encode(['code' =>-1000,'msg' => '参数错误'],JSON_UNESCAPED_UNICODE);
			exit;
		}
		$userService = new UserService();
		$where['username'] = $param['username'];
		if($userService->findOne($where)>0){
			echo json_encode(['code' =>-1001,'msg' => '该用户已存在'],JSON_UNESCAPED_UNICODE);
			exit;
		}
		$where['password'] = $param['password'];
		$where['type'] = isset($param['type']) ? $param['type'] : 1;
		$where['state'] = isset($param['state']) ? $param['state'] : 1;
		if($userService->insertOne($where)){
			echo json_encode(['code' =>200,'msg' => 'success'],JSON_UNESCAPED_UNICODE);
			exit;
		}else{
			echo json_encode(['code' =>-1002,'msg' => '插入数据失败'],JSON_UNESCAPED_UNICODE);
			exit;
		}

	}
	public function changeInfo(){
		$param = Request::param();
		if(!isset($param['id']) || !isset($param['state'])){
			echo json_encode(['code' =>-1000,'msg' => '参数错误'],JSON_UNESCAPED_UNICODE);
			exit;
		}
		$userService = new UserService();
		$where['id'] = $param['id'];
		if($userService->findOne($where)==0){
			echo json_encode(['code' =>-1001,'msg' => '该用户不存在'],JSON_UNESCAPED_UNICODE);
			exit;
		}
		$set['state'] = $param['state'];
		if($userService->updateOne($where,$set)){
			echo json_encode(['code' =>200,'msg' => 'success'],JSON_UNESCAPED_UNICODE);
			exit;
		}else{
			echo json_encode(['code' =>-1002,'msg' => '修改信息失败'],JSON_UNESCAPED_UNICODE);
			exit;
		}

	}
	public function delete(){
		$param = Request::param();
		if(!isset($param['id'])){
			echo json_encode(['code' =>-1000,'msg' => '参数错误'],JSON_UNESCAPED_UNICODE);
			exit;
		}
		$userService = new UserService();
		$where['id'] = $param['id'];
		if($userService->findOne($where)==0){
			echo json_encode(['code' =>-1001,'msg' => '该用户不存在'],JSON_UNESCAPED_UNICODE);
			exit;
		}
		if($userService->deleteOne($where)){
			echo json_encode(['code' =>200,'msg' => 'success'],JSON_UNESCAPED_UNICODE);
			exit;
		}else{
			echo json_encode(['code' =>-1002,'msg' => '删除失败'],JSON_UNESCAPED_UNICODE);
			exit;
		}

	}
}