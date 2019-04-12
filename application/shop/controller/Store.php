<?php
namespace app\index\controller;

/**
 * 门店控制器
 */
class Store extends Common {

	public function __construct() {
		parent::__construct();
	}
	//标签列表
	public function label() {
		$label = cache('setting')['label'];
		$list = unserialize(cache('setting')['label']);
		$this->s_msg(null, $list);
	}
	//轮播列表
	public function shuffling() {
		$shuffling = cache('setting')['shuffling_figure'];
		$list = explode(',', $shuffling);
		$this->s_msg(null, $list);
	}
	//门店列表
	public function index() {
		if (is_post()) {
			$rst = model('Store')->xiugai([input('post.key') => input('post.value')], ['id' => input('post.id')]);
			return $rst;
		}
		if (input('get.keywords')) {
			$this->map[] = ['us_tel', 'like', '%' . input('get.keywords') . '%'];
		}
		if (is_numeric(input('get.ad_status'))) {
			$this->map[] = ['us_status', '=', input('get.us_status')];
		}
		$list = model('Store')->chaxun($this->map, $this->order, $this->size);
		$this->s_msg(null, $list);
	}
	//门店首页
	public function product() {
		$store = model("Store")->get(input('get.id'));
		$cate = model("Cate")->where('st_id', $store['id'])->select();
		foreach ($cate as $k => $v) {
			$cate[$k]['list'] = model('product')->where('ca_id', $v['id'])->select();
		}
		$data = [
			0 => $store,
			1 => $cate,
		];
		$this->s_msg(null, $data);
	}
	// 产品详细
	public function detail() {
		$product = model('product')->get(input('get.id'));
		$this->s_msg(null, $product);
	}

}
