<?php
namespace app\admin\controller;

/**
 * @todo 管理员、角色、权限管理
 */
class Admin extends Common {
	// 管理员列表
	public function index() {
		// if (is_post()) {
		// 	$rst = model('Admin')->editAdmin([input('post.key') => input('post.value')], ['id' => input('post.id')]);
		// 	return $rst;
		// }
		// if (input('get.keywords')) {
		// 	$this->map[] = ['ad_tel|ad_account|ad_work_number', '=', input('get.keywords')];
		// }
		// $this->map['delete_status'] = 1;
		// $list = model('Admin')->getList($this->map);
		$map=[];
		if (input('get.keywords')) {
			$map=[['ad_account|ad_tel','=',input('get.keywords')]];
			// halt($post);
		}
		$list=db('admin')->where($map)->select();
		// halt($list);
		$this->assign(array(
			'ro_list' => db('role')->select(),
			'list' => $list,
		));
		return $this->fetch();
	}
	//页面直接修改，启用，角色
	public function change(){
		if (is_post()) {
			$post=input('post.');
			// halt($post);
			if ($post['key']=='ro_id') {
				// halt('ro_id');
				$list=db('admin')->where('id',$post['id'])->update(['ro_id'=>$post['value']]);
				if ($list) {
					$this->success('修改成功');
				}
				else{
					$this->error('未修改');
				}
			}
			if ($post['key']=='status') {
				$info=db('admin')->where('id',$post['id'])->update(['status'=>$post['value']]);
				if ($info) {
					$this->success('修改完成');
				}
				else{
					$this->error('请稍后重试');
				}
			}
			// $rst = model('Admin')->editAdmin([input('post.key') => input('post.value')], ['id' => input('post.id')]);
			return $rst;
		}
	}
	//添加
	public function add() {
		if (is_post()) {
			$data = input('post.');
			// halt($data);
			$sec=db('admin')->where('ad_account',$data['ad_account'])->find();
			if ($sec) {
				$this->error('该账号已存在');
			}
			if ($data['ro_id']==null) {
				$this->error('请选择角色权限');
			}
			if ($data['ad_account']==null||$data['ad_tel']==null||$data['ad_name']==null||$data['ad_pwd']==null) {
				$this->error('请完善信息');
			}
			$data['add_time']=date('y-m-d H:i:s');
			$list=db('admin')->insert($data);
			if ($list) {
				$this->success('添加成功');
			}
			else{
				$this->error('请稍后重试');
			}
			//var_dump($data);exit();
			// $validate = validate('Verify');
			// $res = $validate->scene('addAdmin')->check($data);
			// if (!$res) {
			// 	$this->error($validate->getError());
			// }
			// $rel = model('Admin')->addAdmin($data);
			// return $rel;
		}
		$this->assign('ro_list', db('role')->select());
		return $this->fetch();
	}
	public function edit() {

		if (is_post()) {
			$validate = validate('Verify');
			$res = $validate->scene('editAdmin')->check(input('post.'));
			if (!$res) {
				$this->error($validate->getError());
			}

			$rel = model('admin')->editAdmin(input('post.'), ['id' => input('post.id')]);
			return $rel;
		}
		$this->assign(array(
			'info' => model('Admin')->get(input('get.id')),
			'ro_list' => db('role')->select(),
		));
		return $this->fetch();
	}

	//角色
	public function roleIndex() {
		if (is_post()) {
			$data = input('post.');
			if(empty($data['ro_name'])){
				$this->error('请填写角色名');
			}
			if(empty($data['rules'])){
				$this->error('请选择权限');
			}
			sort($data['rules']);
			$data['ro_rules'] = implode(',', array_unique($data['rules']));
			unset($data['rules']);
			if ($data['id']) {
				$rst = db('Role')->update($data);
			} else {
				$rst = db('Role')->insert($data);
			}
			if ($rst) {
				$this->success('操作完成');
			}
			$this->error('您并没有作出修改');
		} else {
			$this->assign('list', db('role')->select());
			return $this->fetch();
		}
	}
	// 添加角色
	public function roleAdd() {
		$list = db('rule')->where('pid', 0)->select();
		foreach ($list as $k => $v) {
			$list[$k]['child'] = db('rule')->where('pid', $v['id'])->select();
		}
		$this->assign('ru_list', $list);
		if (input('get.id')) {
			$this->assign('info', db('role')->where('id', input('get.id'))->find());
		} 
		/*else {
			$this->assign('info', ['ro_rules' => '1,2,3,4']);
		}*/
		return $this->fetch();
	}
	//逻辑删除管理员
	public function deleteAdmin(){
		$post=input('post.');
		// halt($post);
		if ($post['id']==1) {
			$this->error('初始管理员不能删除');
		}
		if ($post['id']==session('admin')['id']) {
			$this->error('不能删除自身');
		}
		// halt(123);
		$list=db('admin')->delete($post['id']);
		if ($list) {
			$this->success('删除成功');
		}
		else{
			$this->error('请稍后重试');
		}
		// if (input('post.id')) {
  //           $id = input('post.id');
  //       } else {
  //           $this->error('id不存在');
  //       }
  //       if (input('post.key')) {
  //           $key = input('post.key');
  //       } else {
  //           $this->error('数据表不存在');
  //       }
  //       $info = model($key)->get($id);
  //       $data['delete_status'] = 0;
  //       $where['id'] = $id;
  //       if ($info) {
  //           $rel = model($key)->where($where)->update($data);
  //           if ($rel) {
  //               $this->success('删除成功');
  //           } else {
  //               $this->error('请联系网站管理员');
  //           }
  //       } else {
  //           $this->error('数据不存在');
  //       }
	}

}
