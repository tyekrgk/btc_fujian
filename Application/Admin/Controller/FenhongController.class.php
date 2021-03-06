<?php
//dezend by http://www.yunlu99.com/ QQ:270656184
namespace Admin\Controller;

class FenhongController extends AdminController
{
	private $Model;

	public function index($p = 1, $r = 15, $str_addtime = '', $end_addtime = '', $order = '', $status = '', $type = '', $field = '', $name = '')
	{
		$this->checkUpdata();
		$myczType = M('MyczType')->select();
		$myczTypeList = array();
		$myczTypeListArr[0] = '全部方式';

		foreach ($myczType as $k => $v) {
			$myczTypeList[$v['name']] = $v['title'];
			$myczTypeListArr[$v['name']] = $v['title'];
		}

		$map = array();
		if ($str_addtime && $end_addtime) {
			$str_addtime = strtotime($str_addtime);
			$end_addtime = strtotime($end_addtime);
			if ((addtime($str_addtime) != '---') && (addtime($end_addtime) != '---')) {
				$map['addtime'] = array(
	array('egt', $str_addtime),
	array('elt', $end_addtime)
	);
			}
		}

		if (empty($order)) {
			$order = 'id_desc';
		}

		$order_arr = explode('_', $order);

		if (count($order_arr) != 2) {
			$order = 'id_desc';
			$order_arr = explode('_', $order);
		}

		$order_set = $order_arr[0] . ' ' . $order_arr[1];

		if (empty($status)) {
			$map['status'] = array('egt', 0);
		}

		if (($status == 1) || ($status == 2) || ($status == 3)) {
			$map['status'] = $status - 1;
		}

		if ($myczTypeList[$type]) {
			$map['type'] = $type;
		}

		if ($field && $name) {
			if ($field == 'username') {
				$map['userid'] = userid($name);
			}
			else {
				$map[$field] = $name;
			}
		}

		$data = M('Fenhong')->where($map)->order($order_set)->page($p, $r)->select();
		$count = M('Fenhong')->where($map)->count();
		$parameter['p'] = $p;
		$parameter['status'] = $status;
		$parameter['order'] = $order;
		$parameter['type'] = $type;
		$parameter['name'] = $name;
		$builder = new BuilderList();
		$builder->title('分红管理');
		$builder->titleList('分红列表', U('Fenhong/index'));
		$builder->button('add', '添 加', U('Fenhong/edit'));
		$builder->keyId();
		$builder->keyText('coinname', '分红币种');
		$builder->keyText('coinjian', '奖励币种');
		$builder->keyText('name', '分红名称');
		$builder->keyText('num', '分红数量');
		$builder->keyTime('addtime', '添加时间');
		$builder->keyTime('endtime', '操作时间');
		//$builder->keyDoAction('Fenhong/kaishi?id=###', '开始分红|---|kaishi|1', '操作');
                $builder->keyDoAction('Fenhong/kaishi?id=###', '开始分红|---|kaishi|1', '操作');
		$builder->keyDoAction('Fenhong/edit?id=###', '编辑', '操作');
		$builder->data($data);
		$builder->pagination($count, $r, $parameter);
		$builder->display();
	}

	public function edit($id = NULL)
	{
                //添加到数据库
		if (!empty($_POST)) {
			if (!check($_POST['name'], 'a')) {
				$this->error('分红名称格式错误');
                        }
                        
                        if(!check($_POST['type'],'d')){
                               $this->error('所属矿机选择错误！');
                        }

			if (!check($_POST['coinname'], 'w')) {
				$this->error('分红币种格式错误');
			}

			if (!check($_POST['num'], 'd')) {
				$this->error('分红数量格式错误');
			}

			if (!check($_POST['sort'], 'd')) {
				$this->error('类型排序格式错误');
			}

			if ($_POST['addtime']) {
				if (addtime(strtotime($_POST['addtime'])) == '---') {
					$this->error('添加时间格式错误');
				}
				else {
					$_POST['addtime'] = strtotime($_POST['addtime']);
				}
			}
			else {
				$_POST['addtime'] = time();
			}

			if ($_POST['endtime']) {
				if (addtime(strtotime($_POST['endtime'])) == '---') {
					$this->error('编辑时间格式错误');
				}
				else {
					$_POST['endtime'] = strtotime($_POST['endtime']);
				}
			}
			else {
				$_POST['endtime'] = time();
			}

			if (check($_POST['id'], 'd')) {
                            
                                $fenhong_name = M('fenhong') ->field(array('name')) ->where(array('id'=>$_POST['id'])) ->find();
                                if(trim($_POST['name']) != $fenhong_name['name']){
                                    $this ->error('分红名称不能修改!');
                                }else{
                                    $rs = M('Fenhong')->save($_POST);
                                }
                                
			}
			else {
                            
                                 //名称重复提示(为后面的做提示用)
                                 $name = trim($_POST['name']);
                                 $fenhong_names = M('fenhong') ->field(array('name')) -> select();
                                 $names = array();
                                 foreach($fenhong_names as $k=>$v){
                                     if($name==$v['name']){
                                         $this ->error('分红名称已经存在!');
                                     }
                                 }
                            
				$rs = M('Fenhong')->add($_POST);
			}

			if ($rs) {
				$this->success('操作成功');
			}
			else {
				$this->error('操作失败');
			}
		}
		else { //显示编辑页面
                    
			$coin_list = D('Coin')->get_all_name_list();

			foreach ($coin_list as $k => $v) {
				$coin_list[$k] = $v . '-全站持有' . D('Coin')->get_sum_coin($k);
				$coin_lista[$k] = $v;
			}
                        
                        //读取对应的矿机
                        $issue_list = M('Issue') ->field(array('id','name')) ->where(array('status'=>1)) ->select();
                        $issue_list_combin = array();
                        foreach($issue_list as $k => $v){
                            $issue_list_combin[$v['id']] = $v['name'];
                        }
                        

			$builder = new BuilderEdit();
			$builder->title('分红管理');
			$builder->titleList('分红列表', U('Fenhong/index'));

			if ($id) {
				$builder->keyReadOnly('id', '类型id');
				$builder->keyHidden('id', '类型id');
				$data = M('Fenhong')->where(array('id' => $id))->find();
				$data['addtime'] = addtime($data['addtime']);
				$data['endtime'] = addtime($data['endtime']);
				$builder->data($data);
			}

			$builder->keyText('name', '分红名称', '可以中中文');
                        $builder->keySelect('type', '所属矿机', '仅能选择', $issue_list_combin);
			$builder->keySelect('coinname', '分红币种', '分红币种', $coin_list);
			$builder->keySelect('coinjian', '奖励币种', '奖励币种', $coin_lista);
			$builder->keyText('num', '分红数量', '整数');
			$builder->keyTextarea('content', '分红介绍');
			$builder->keyText('sort', '排序', '整数');
			$builder->keyAddTime();
			$builder->keyEndTime();
			$builder->savePostUrl(U('Fenhong/edit'));
			$builder->display();
		}
	}

	public function status($id, $status, $model)
	{
		$builder = new BuilderList();
		$builder->doSetStatus($model, $id, $status);
	}
        

	public function kaishi()
	{
		$id = $_GET['id'];

		if (empty($id)) {
			$this->error('请选择要操作的数据!');
		}

		$data = M('Fenhong')->where(array('id' => $id))->find();
                //var_dump($data);
                //echo '<br/>';
                
                $issue =  M('issue') ->where(array('id'=>$data['type'])) ->find();//对应的矿机信息
                //var_dump($issue);
                //echo '<br/>';
                
		if ($data['status'] != 0) {
			$this->error('已经处理，禁止再次操作！');
		}
                
               /*
		$a = M('UserCoin')->sum($data['coinname']);
		$b = M('UserCoin')->sum($data['coinname'] . 'd');
		$data['quanbu'] = $a + $b;//全部币的个数
               
		$data['meige'] = round($data['num'] / $data['quanbu'], 8);
		$data['user'] = M('UserCoin')->where(array(
                                $data['coinname']       => array('gt', 0),
                                $data['coinname'] . 'd' => array('gt', 0),
                                '_logic'                => 'OR'
                            ))->count();
              */
                

                //按照个人所占总价的比例来计算，不是个数（万一中途改变价格，导致总的价值发生变化）
                //$data['quanbu'] = M('issue_log') -> where(array('name'=>$issue['name'])) ->sum('mum');//全部已经购买的总价值
                
                //$data['quanbu'] = M('issue_log') -> where(array('name'=>$issue['name'])) ->sum('mum');//全部已经购买的总价值
                //echo 'quanbu='.$data['quanbu'];
                
                $data['quanbu'] = M('issue_log') ->where(array('name'=>$issue['name'])) ->sum('num');//全部已经购买的机器的总条数
                
                //$data['num'] = M('issue_log') ->where(array('name'=>$data['name'],'userid'=>)) ->sum('mum'); //个人购买的总价值
                $data['meige'] = round($data['num']/$data['quanbu'],8);//分红总数量/全部的总金额
                //echo 'meige='.$data['meige'];
                $data['user'] = M('issue_log') ->where(array('name'=>$issue['name'],'status'=>1)) ->count('distinct(userid)');
                //echo 'user='.$data['user'];
 
		$this->assign('data', $data);
		$this->display();
	}
        
       
        //每次都执行一个人的分红
	public function fenfa($id = NULL, $fid = NULL, $dange = NULL)
	{
		if ($id === null) {
			echo json_encode(array('status' => -2, 'info' => '参数错误'));
			exit();
		}

		if ($fid === null) {
			echo json_encode(array('status' => -2, 'info' => '参数错误2'));
			exit();
		}

		if ($dange === null) {
			echo json_encode(array('status' => -2, 'info' => '参数错误3'));
			exit();
		}

		if ($id == -1) {
			S('fenhong_fenfa_j', null);
			S('fenhong_fenfa_c', null);
			S('fenhong_fenfa', null);
			$fenhong = M('Fenhong')->where(array('id' => $fid))->find();
                        //echo json_encode(array('status' => -2, 'info' => $fenhong));

			if (!$fenhong) {
				echo json_encode(array('status' => -2, 'info' => '分红初始化失败!'));
				exit();
			}

			S('fenhong_fenfa_j', $fenhong);
                      
                        /*
			$usercoin = M('UserCoin')->where(array(
                                    $fenhong['coinname']       => array('gt', 0),
                                    $fenhong['coinname'] . 'd' => array('gt', 0),
                                    '_logic'  => 'OR'
	                        ))->select();

			if (!$usercoin) {
				echo json_encode(array('status' => -2, 'info' => '没有用户持有'));
				exit();
			}

			$a = 1;
			foreach ($usercoin as $k => $v) {
				$shiji[$a]['userid'] = $v['userid'];
				$shiji[$a]['chiyou'] = $v[$fenhong['coinname']] + $v[$fenhong['coinname'] . 'd'];
				$a++;
			}
                        
                        if (!$shiji) {
				echo json_encode(array('status' => -2, 'info' => '计算错误'));
				exit();
			}
                        */
                        
                        //原来的usercoin的数量为一个三维数组，每个子的二位数组中包含了用户币的持有了（chiyou）,用户id(userid)
                        
                        //自己改的开始
                        $issue = M('issue') ->where(array('id'=>$fenhong['type'])) ->find();//对应的矿机信息

                        //每天认购的矿机信息
                        $usercoin11 = M('issue_log') ->where(array(
                                    'name' => $issue['name'],
                                    'mum' => array('gt',0),
                                    'status' => 1,
                                    '_logic' => 'AND'
                                 )) ->select();
                        //echo json_encode(array('status' => '121200', 'info' => $usercoin11));
                        
                        if(!$usercoin11){
                            echo json_encode(array('status' => -2, 'info' => '没有用户持有'));
			    exit();
                        }
                        
                        $a = 1;
                        foreach($usercoin11 as $k=>$v){
                            $userid_sum[$a]['userid'] = $v['userid'];
                            $userid_sum[$a]['chiyou'] = M('issue_log') ->where(array(
                                                   'name' =>  $issue['name'],
                                                   'userid' => $v['userid'],
                                                   'status' => 1
                                              )) ->group('userid') ->sum('num');
                            $a++;
                        }
                        
			if (!$userid_sum) {
				echo json_encode(array('status' => -2, 'info' => '计算错误'));
				exit();
			}

                        $res = array_unique_fb($userid_sum);//数组去重 

                        
                        $a=1;
                        foreach($res as $k=>$v){
                            $shiji[$a]['userid'] = $v[0];
                            $shiji[$a]['chiyou'] = $v[1];
                            $a++;
                        }
                        
                        //自己改的结束
			S('fenhong_fenfa_c', count($res));//需要分币的用户的个数
			S('fenhong_fenfa', $shiji); //需要分币的用户
                        
			echo json_encode(array('status' => 1, 'info' => '分红初始化成功'));
                        //echo json_encode(array('status' => 1000, 'shiji' => $shiji));
                        //{"status":1000,"shiji":{"1":{"userid":"97","chiyou":"500"},"2":{"userid":"96","chiyou":"1000"},"3":{"userid":"95","chiyou":"1000"},"4":{"userid":"94","chiyou":"1500"}}
                        //echo json_decode(array('status' => 1001, 'shijishu' => S('fenhong_fenfa_c')));
			exit();
                      
		}

		if ($id == 0) {
			echo json_encode(array('status' => 1, 'info' => ''));
			exit();
		}

		if (S('fenhong_fenfa_c') < $id) {
			echo json_encode(array('status' => 100, 'info' => '分红全部完成'));
			exit();
		}

		if ((0 < $id) && ($id <= S('fenhong_fenfa_c'))) { 
                    
			$fenhong = S('fenhong_fenfa_j');
			$fenfa = S('fenhong_fenfa');
    
			$cha = M('FenhongLog')->where(array('name' => $fenhong['name'], 'coinname' => $fenhong['coinname'], 'userid' => $fenfa[$id]['userid']))->find();
                        
			if ($cha) {
				echo json_encode(array('status' => -2, 'info' => '用户id' . $fenfa[$id]['userid'] . '本次分红已经发过'));
                                exit();
			}
                        
                        
                        $fenhong = M('Fenhong')->where(array('id' => $fid))->find();
                        $issue = M('issue') ->where(array('id'=>$fenhong['type'])) ->find();//对应的矿机信息
                        
                        //静态分红(原来的)
			//$faduoshao = round($fenfa[$id]['chiyou'] * $dange, 8);//
                        
                        
                        //额外配置项
                        $add_conf = M('add_conf') ->where(array('id'=>1)) ->find();

                        //静态+动态收入达到本金的静态倍率倍数后，静态停止不在享受奖励提成(本金：所有购买机器的钱):
                        
                        //用户的总收益
                        $userEarn = M('user_coin') ->field(array($fenhong['coinjian'],$fenhong['coinjian'].'d')) -> where(array('userid' => $fenfa[$id]['userid'])) ->find();
                        $totalEarn = $userEarn[$fenhong['coinjian']] + $userEarn[$fenhong['coinjian'.'d']];
                        
                        //用户购买机器的成本
                        $userspand = M('issue_log') ->where(array(
                                        'userid' => $fenfa[$id]['userid'],
                                        'name' => $issue['name'],
                                        '_logic' => 'AND'
                                     )) ->group('userid') -> sum('mum');
 
                        if($totalEarn > $add_conf['jtbl'] * $userspand){
                            $faduoshao_jt = 0.00;
                        }else{
                            $faduoshao_jt = round($fenfa[$id]['chiyou'] * $dange,8);
                        }
     
                        
                        //动态分红

                        //思路：分别获取了我的下一级的左右两个区域的所有人头，再依次计算出每个区域中每个人的的所有的下级，组成一维数组就是所有的下级人员；然后再分别计算业绩。
                        
                        $model = M('user');
                        $model_issue_log = M('issue_log');
                        
                        //左区业绩
                        $sub_users_left = M('user') ->field(array('id','area')) ->where(array('invit_1'=>$fenfa[$id]['userid'],'area'=>0)) ->select();
                        if($sub_users_left){
                            foreach($sub_users_left as $kk2=>$vv2){
                                $left_yeji_str = $model_issue_log ->field(array('mum')) ->where(array('userid'=>$vv2['id'])) ->find();
                                $left_yeji_decimal = $left_yeji_str ? $left_yeji_str['mum'] : 0.00;
                                $left_yeji += round($left_yeji_decimal,8);
                            }
                        }else{
                            $left_yeji = 0.00;
                        }
                        
                        //右区业绩
                        $sub_users_right = $model ->field(array('id','area')) ->where(array('invit_1'=>$fenfa[$id]['userid'],'area'=>1)) ->select();
                        if($sub_users_right){
                            foreach($sub_users_right as $kk3=>$vv3){
                                $right_yeji_str = $model_issue_log ->field(array('mum')) ->where(array('userid'=>$vv3['id'])) ->find();
                                $right_yeji_decimal = $right_yeji_str ? $right_yeji_str['mum'] : 0.00;
                                $right_yeji += round($right_yeji_decimal,8);
                            }
                        }else{
                            $right_yeji = 0.00;
                        }
                        
                        //确定基线市场
                        $base_market = $left_yeji <= $right_yeji ? $left_yeji : $right_yeji;
                        //echo json_encode(array('status'=>789,'base_market'=>$base_market));
     
                        
                        //动态提成
                        $faduoshao_dt11 = round($base_market * $add_conf['chanbilv'] * $issue['ticheng'] / 10000 ,8);
                        $faduoshao_dt = $faduoshao_dt11 >= $issue['tichengmax'] ? $issue['tichengmax'] : $faduoshao_dt11;
                        //echo json_encode(array('status'=>790,'faduoshao_dt'=>$faduoshao_dt));
                        
                        //总收益
                        $faduoshao = $faduoshao_jt + $faduoshao_dt;
                       // echo json_encode(array('status'=>790,'faduoshao'=>$faduoshao));
                    }
                    
                        
                        //开始分红
			$mo = M();
			$mo->execute('set autocommit=0');
			$mo->execute('lock tables movesay_user_coin write,movesay_fenhong_log write,movesay_user_coin_sep write');
			$rs = array();
			//$rs[] = $mo->table('movesay_user_coin')->where(array('userid' => $fenfa[$id]['userid']))->setInc($fenhong['coinjian'], $faduoshao);
                        $rs[] = $mo->table('movesay_user_coin')->where(array('userid' => $fenfa[$id]['userid']))->setInc($fenhong['coinjian'], $faduoshao);
			//$rs[] = $mo->table('movesay_fenhong_log')->add(array('name' => $fenhong['name'], 'userid' => $fenfa[$id]['userid'], 'coinname' => $fenhong['coinname'], 'coinjian' => $fenhong['coinjian'], 'fenzong' => $fenhong['num'], 'price' => $dange, 'num' => $fenfa[$id]['chiyou'], 'mum' => $faduoshao, 'addtime' => time(), 'status' => 1));
                        $rs[] = $mo->table('movesay_fenhong_log')->add(array('name' => $fenhong['name'], 'userid' => $fenfa[$id]['userid'], 'coinname' => $fenhong['coinname'], 'coinjian' => $fenhong['coinjian'], 'fenzong' => $fenhong['num'], 'price' => $dange, 'num' => $fenfa[$id]['chiyou'], 'jt' => $faduoshao_jt, 'dt' => $faduoshao_dt, 'mum' => $faduoshao, 'addtime' => time(), 'status' => 1)); 
                       
                        
                        //没有记录就添加记录，有记录就更新记录
                        $where = array('userid' => $fenfa[$id]['userid'], 'coiname' => $fenhong['coinname'], 'coinjian' => $fenhong['coinjian']);
                        $res = $mo->table('movesay_user_coin_sep') ->where($where) ->find();
                        if($res){
                            $rs[] = $mo->table('movesay_user_coin_sep') ->where($where) ->setInc('trade_mum',round($faduoshao * $add_conf['rate_trade'] /100,8));
                            $rs[] = $mo->table('movesay_user_coin_sep') ->where($where) ->setInc('maket_mum',round($faduoshao * $add_conf['rate_market'] /100,8));
                            $rs[] = $mo->table('movesay_user_coin_sep') ->where($where) ->setInc('fund_mum',round($faduoshao * $add_conf['rate_found'] /100,8));
                        }else{
                            $rs[] = $mo->table('movesay_user_coin_sep')->add(array('userid' => $fenfa[$id]['userid'], 'coiname' => $fenhong['coinname'], 'coinjian' => $fenhong['coinjian'], 'trade_mum' => round($faduoshao * $add_conf['rate_trade'] / 100,8) , 'maket_mum' => round($faduoshao * $add_conf['rate_market'] / 100,8) ,'fund_mum' => round($faduoshao * $add_conf['rate_found'] / 100,8)));
                        }
                        
                        if (check_arr($rs)) {
				$mo->execute('commit');
				$mo->execute('unlock tables');
				echo json_encode(array('status' => 1, 'info' => '用户id' . $fenfa[$id]['userid'] . ',持有数量' . $fenfa[$id]['chiyou'] . ',分红总数:' . $faduoshao.';其中,静态:'.$faduoshao_jt."个,动态:".$faduoshao_dt.'个'));
                                exit();
			}
			else {
				$mo->execute('rollback');
				echo json_encode(array('status' => -2, 'info' => '用户id' . $fenfa[$id]['userid'] . '，持有数量' . $fenfa[$id]['chiyou'] . '分红失败'));
				exit();
			}
		
	}
        
        
	public function log($p = 1, $r = 15, $str_addtime = '', $end_addtime = '', $order = '', $status = '', $type = '', $field = '', $name = '', $coinname = '', $coinjian = '')
	{
		$map = array();
		if ($str_addtime && $end_addtime) {
			$str_addtime = strtotime($str_addtime);
			$end_addtime = strtotime($end_addtime);
			if ((addtime($str_addtime) != '---') && (addtime($end_addtime) != '---')) {
				$map['addtime'] = array(
                                    array('egt', $str_addtime),
                                    array('elt', $end_addtime)
                                );
			}
		}

		if (empty($order)) {
			$order = 'id_desc';
		}

		$order_arr = explode('_', $order);

		if (count($order_arr) != 2) {
			$order = 'id_desc';
			$order_arr = explode('_', $order);
		}

		$order_set = $order_arr[0] . ' ' . $order_arr[1];

		if (empty($status)) {
			$map['status'] = array('egt', 0);
		}

		if (($status == 1) || ($status == 2) || ($status == 3)) {
			$map['status'] = $status - 1;
		}

		if ($field && $name) {
			if ($field == 'userid') {
				$map['userid'] = D('User')->get_userid($name);
			}else {
				$map[$field] = $name;
			}
		}

		if ($coinname) {
			$map['coinname'] = $coinname;
		}

		if ($coinjian) {
			$map['coinjian'] = $coinjian;
		}

		$data = M('FenhongLog')->where($map)->order($order_set)->page($p, $r)->select();
		$count = M('FenhongLog')->where($map)->count();
		$parameter['p'] = $p;
		$parameter['status'] = $status;
		$parameter['order'] = $order;
		$parameter['type'] = $type;
		$parameter['name'] = $name;
		$parameter['coinname'] = $coinname;
		$parameter['coinjian'] = $coinjian;
		$builder = new BuilderList();
		$builder->title('分红记录');
		$builder->titleList('记录列表', U('Fenhong/log'));
		$builder->setSearchPostUrl(U('Fenhong/log'));
		$builder->search('order', 'select', array('id_desc' => 'ID降序', 'id_asc' => 'ID升序'));
		$coinname_arr = array('' => '分红币种');
		$coinname_arr = array_merge($coinname_arr, D('Coin')->get_all_name_list());
		$builder->search('coinname', 'select', $coinname_arr);
		$coinjian_arr = array('' => '奖励币种');
		$coinjian_arr = array_merge($coinjian_arr, D('Coin')->get_all_name_list());
		$builder->search('coinjian', 'select', $coinjian_arr);
		$builder->search('field', 'select', array('name' => '分红名称', 'userid' => '用户名'));
		$builder->search('name', 'text', '请输入查询内容');
		$builder->keyId();
		$builder->keyText('name', '分红名称');
		$builder->keyUserid();
		$builder->keyText('coinname', '分红币种');
		$builder->keyText('coinjian', '奖励币种');
		$builder->keyText('fenzong', '分红总数');
		$builder->keyText('price', '每个奖励');
		$builder->keyText('num', '持有数量');
		$builder->keyText('mum', '分红数量');
		$builder->keyTime('addtime', '分红时间');
		$builder->data($data);
		$builder->pagination($count, $r, $parameter);
		$builder->display();
	}

	public function checkAuth()
	{
		if ((S('CLOUDTIME') + (60 * 60)) < time()) {
			S('CLOUD', null);
			S('CLOUD_IP', null);
			S('CLOUD_HOME', null);
			S('CLOUD_DAOQI', null);
			S('CLOUD_GAME', null);
			S('CLOUDTIME', time());
		}

		$CLOUD = S('CLOUD');
		$CLOUD_IP = S('CLOUD_IP');
		$CLOUD_HOME = S('CLOUD_HOME');
		$CLOUD_DAOQI = S('CLOUD_DAOQI');
		$CLOUD_GAME = S('CLOUD_GAME');

		if (!$CLOUD) {
			foreach (C('__CLOUD__') as $k => $v) {
				if (getUrl($v . '/Auth/text') == 1) {
					$CLOUD = $v;
					break;
				}
			}

			if (!$CLOUD) {
				S('CLOUDTIME', time() - (60 * 60 * 24));
				echo '<a title="授权服务器连失败"></a>';
				exit();
			}
			else {
				S('CLOUD', $CLOUD);
			}
		}

		if (!$CLOUD_DAOQI) {
			$CLOUD_DAOQI = getUrl($CLOUD . '/Auth/daoqi?mscode=' . MSCODE);

			if ($CLOUD_DAOQI) {
				S('CLOUD_DAOQI', $CLOUD_DAOQI);
			}
			else {
				S('CLOUDTIME', time() - (60 * 60 * 24));
				echo '<a title="获取授权到期时间失败"></a>';
				exit();
			}
		}

		if (strtotime($CLOUD_DAOQI) < time()) {
			S('CLOUDTIME', time() - (60 * 60 * 24));
			echo '<a title="授权已到期"></a>';
			exit();
		}

		if (!$CLOUD_IP) {
			$CLOUD_IP = getUrl($CLOUD . '/Auth/ip?mscode=' . MSCODE);

			if (!$CLOUD_IP) {
				S('CLOUD_IP', 1);
			}
			else {
				S('CLOUD_IP', $CLOUD_IP);
			}
		}

		if ($CLOUD_IP && ($CLOUD_IP != 1)) {
			$ip_arr = explode('|', $CLOUD_IP);

			if ('/' == DIRECTORY_SEPARATOR) {
				$ip_a = $_SERVER['SERVER_ADDR'];
			}
			else {
				$ip_a = @gethostbyname($_SERVER['SERVER_NAME']);
			}

			if (!$ip_a) {
				S('CLOUDTIME', time() - (60 * 60 * 24));
				echo '<a title="获取本地ip失败"></a>';
				exit();
			}

			if (!in_array($ip_a, $ip_arr)) {
				S('CLOUDTIME', time() - (60 * 60 * 24));
				echo '<a title="匹配授权ip失败"></a>';
				exit();
			}
		}

		if (!$CLOUD_HOME) {
			$CLOUD_HOME = getUrl($CLOUD . '/Auth/home?mscode=' . MSCODE);

			if (!$CLOUD_HOME) {
				S('CLOUD_HOME', 1);
			}
			else {
				S('CLOUD_HOME', $CLOUD_HOME);
			}
		}

		if ($CLOUD_HOME && ($CLOUD_HOME != 1)) {
			$home_arr = explode('|', $CLOUD_HOME);
			$home_a = $_SERVER['SERVER_NAME'];

			if (!$home_a) {
				$home_a = $_SERVER['HTTP_HOST'];
			}

			if (!$home_a) {
				S('CLOUDTIME', time() - (60 * 60 * 24));
				echo '<a title="获取本地域名失败"></a>';
				exit();
			}

			if (!in_array($home_a, $home_arr)) {
				S('CLOUDTIME', time() - (60 * 60 * 24));
				echo '<a title="匹配授权域名失败"></a>';
				exit();
			}
		}

		if (!$CLOUD_GAME) {
			$CLOUD_GAME = getUrl($CLOUD . '/Auth/game?mscode=' . MSCODE);

			if (!$CLOUD_GAME) {
				S('CLOUDTIME', time() - (60 * 60 * 24));
				echo '<a title="授权应用不存在"></a>';
				exit();
			}
			else {
				S('CLOUD_GAME', $CLOUD_GAME);
			}
		}

		$game_arr = explode('|', $CLOUD_GAME);

		if (!in_array('fenhong', $game_arr)) {
			S('CLOUDTIME', time() - (60 * 60 * 24));
			echo '<a title="分红没有授权"></a>';
			exit();
		}
	}

	public function checkUpdata()
	{
	}
        
        //获取对应的无限下级
        
        public function mytest($id){ //id=81
            $users = M('user') ->field(array('id','invit_1')) ->where(array('id' => array('gt',$id))) ->select();
            //mydump($users);die;
            $res = $this -> getUnlimitedLayers($users,$id);
            mydump($res);
        }
        
        private function getUnlimitedLayers($cate,$id){
            $arr=array();
            foreach($cate as $value){
                if($value['invit_1']==$id){
                    $arr[]=$value['id'];
                    $arr=array_merge($this->getUnlimitedLayers($cate,$value['id']),$arr);
                }
            }
            return $arr;
        }
        
        public function testxxx(){
            $allUsers = M('user') ->field(array('id','invit_1')) -> where(array(
                                      'id' => array('gt',$fenfa[$id]['userid'])
                         )) ->select();
                        
            $subs = $this -> getUnlimitedLayers($allUsers,$fenfa[$id]['userid']);
        }
        
}

?>
