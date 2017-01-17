<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo C('web_title');?></title>
    <meta name="Keywords" content="<?php echo C('web_keywords');?>">
    <meta name="Description" content="<?php echo C('web_description');?>">
    <meta name="robots" content="index,follow"/>
    <meta name="author" content="qijianke.com">
    <meta name="coprright" content="qijianke.com">
    <link rel="shortcut icon" href=" /favicon.ico"/>
    <link rel="stylesheet" href="/Public/Home/css/movesay.css"/>
    <link rel="stylesheet" href="/Public/Home/css/style.css"/>
    <link rel="stylesheet" href="/Public/Home/css/font-awesome.min.css"/>
    <script type="text/javascript" src="/Public/Home/js/script.js"></script>
    <script type="text/javascript" src="/Public/Home/js/jquery.flot.js"></script>
    <script type="text/javascript" src="/Public/Home/js/jquery.cookies.2.2.0.js"></script>
    <script type="text/javascript" src="/Public/layer/layer.js"></script>
</head>
<body>
<div class="header bg_w" style="position: fixed; z-index: 99;">
    <div class="hearder_top">
        <div class="autobox po_re zin100" id="header">
            <div class="logo-small" style="max-height: 25px;">
                <a href="/"><img src="/Upload/public/<?php echo ($C['web_llogo_small']); ?>" alt=""/></a>
            </div>
            <div class="nav fz_12">
                <ul>
                    <li style="text-align: right; padding-right: 20px;">
                        <a href="/" id="index_box"><?php echo L('trade.shouye');?></a>
                    </li>
                    <li>
                        <a id="trade_box" class="active"><span class="active"><?php echo L('trade.jiaoyizhongxin');?></span>
                            <img src="/Public/Home/images/down.png"></a>

                        <div class="deal_list " style="display: none;   top: 36px;">
                            <dl id="menu_list_json"></dl>
                            <div class="sj"></div>
                            <div class="nocontent"></div>
                        </div>
                    </li>
                    <?php if(is_array($daohang)): $i = 0; $__LIST__ = $daohang;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                            <a id="<?php echo ($vo['name']); ?>_box" href="/<?php echo ($vo['url']); ?>"><?php echo (get_lang_text($vo['title'],$vo['title_en'])); ?></a>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
            <div class="right orange" id="login">
                <?php if(($_SESSION['userId']) > "0"): ?><dl class="mywallet">
                        <dt id="user-finance">
                        <div class="mywallet_name clear">
                            <a href="/finance/"><?php echo (session('userName')); ?></a><i></i>
                        </div>
                        <div class="mywallet_list" style="display: none;">
                            <div class="clear">
                                <ul class="balance_list">
                                    <h4><?php echo L('trade.keyongyue');?></h4>
                                    <li>
                                        <a href="javascript:void(0)"><em style="margin-top: 5px;" class="deal_list_pic_cny"></em><strong><?php echo L('trade.renminbi');?>：</strong><span><?php echo ($userCoin_top['cny']); ?></span></a>
                                    </li>
                                </ul>
                                <ul class="freeze_list">
                                    <h4><?php echo L('trade.weituodongjie');?></h4>
                                    <li>
                                        <a href="javascript:void(0)"><em style="margin-top: 5px;" class="deal_list_pic_cny"></em><strong><?php echo L('trade.renminbi');?>：</strong><span><?php echo ($userCoin_top['cnyd']); ?></span></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="mywallet_btn_box">
                                <a href="/finance/mycz.html"><?php echo L('trade.chongzhi');?></a><a href="/finance/mytx.html"><?php echo L('trade.tixian');?></a><a href="/finance/myzr.html"><?php echo L('trade.zhuanru');?></a><a href="/finance/myzc.html"><?php echo L('trade.zhuanchu');?></a><a href="/finance/mywt.html">委托管理</a><a href="/finance/mycj.html">成交查询</a>
                            </div>
                        </div>
                        </dt>
                        <dd>
                            ID：<span><?php echo (session('userId')); ?></span>
                        </dd>
                        <dd>
                            <a href="<?php echo U('Login/loginout');?>"><?php echo L('trade.tuichu');?></a>
                        </dd>
                    </dl>
                    <?php else: ?> <!-- 登陆前 -->
                    <div class="orange">
                        <span class="zhuce"><a class="orange" href="<?php echo U('Login/register');?>"><?php echo L('trade.zhuce');?></a></span> |
                        <a href="javascript:;" class="orange" onclick="loginpop();"><?php echo L('trade.denglu');?></a>
                    </div><?php endif; ?>
            </div>
            <div class="right">
				<select id="select_lang" style="background-color: #F6F6F6;">
					<option <?php if((LANG_SET) == "zh-cn"): ?>selected<?php endif; ?> value="zh-cn">中文</option>
					<option <?php if((LANG_SET) == "en-us"): ?>selected<?php endif; ?> value="en-us">English</option>
				</select>
			</div>
        </div>
    </div>
    <div class="autobox">
        <div class="all_coin_price">
            <div class="all_coin_show">
                <a href=""><img src="/Upload/coin/<?php echo ($C['market'][$market]['xnbimg']); ?>" style="float: left; width: 40px; height: 40px; margin-right: 5px;"><span><?php echo ($C['market'][$market]['title']); ?></span><em></em></a>
            </div>
            <div class="all_coin_list" style="display: none;">
                <div class="all_coin_box">
                    <ul id="all_coin"></ul>
                </div>
            </div>
        </div>
        <dl class="all_coin_info">
            <dt id="market_new_price"></dt>
            <dd>
                <p class="orange" id="market_max_price"></p>
                <?php echo L('trade.zuigaojia');?>
            </dd>
            <dd>
                <p class="green" id="market_min_price"></p>
                <?php echo L('trade.zuidijia');?>
            </dd>
            <dd>
                <p id="market_buy_price"></p>
                <?php echo L('trade.maiyijia');?>
            </dd>
            <dd>
                <p id="market_sell_price"></p>
                <?php echo L('trade.mai4yijia');?>
            </dd>
            <dd class="w150">
                <p id="market_volume"></p>
                <?php echo L('trade.chengjiaoliang');?>
            </dd>
            <dd class="w150">
                <p id="market_change"></p>
                <?php echo L('trade.rizhangdie');?>
            </dd>
        </dl>
    </div>
</div>
<div class="list-tab-box">
    <ul class="list-tab">
        <li id="list-tab_index" style="width: 180px; margin: 0px;">
            <a href="<?php echo U('Trade/index','market='.$market);?>"><?php echo ($C['market'][$market]['title']); echo L('trade.jiaoyi');?></a>
        </li>
        <!--
        <li id="list-tab_chart" style="width: 180px;">
            <a href="<?php echo U('Trade/chart','market='.$market);?>"><?php echo ($C['market'][$market]['title']); echo L('trade.xingqing');?></a>
        </li>
        -->
        <li id="list-tab_info">
            <a href="<?php echo U('Trade/info','market='.$market);?>"><i class="arrow-tab"></i><?php echo L('trade.lejie'); echo C('coin')[$xnb]['title'];?></a>
        </li>
        <!-- 此处暂不开放评价
        <li id="list-tab_comment">
            <a href="<?php echo U('Trade/comment','market='.$market);?>"><i class="arrow-tab"></i>评价<?php echo C('coin')[$xnb]['title'];?></a>
        </li>
         -->
    </ul>
</div>
<!--头部结束-->

<script>
	var LANG_SET = '<?php echo (LANG_SET); ?>';
	
    $.getJSON("/Ajax/getJsonMenu?t=" + Math.random(), function (data) {
        if (data) {
            var list = '';
            for (var i in data) {
                list += '<dd><a href="/Trade/index/market/' + data[i]['name'] + '"><img src="/Upload/coin/' + data[i]['img'] + '" style="width: 18px; margin-right: 5px;">' + data[i]['title'] + '</a></dd>';
            }
            $("#menu_list_json").html(list);
        }
    });
    $('#trade_box').hover(function () {
        $('.deal_list').show()
    }, function () {
        $('.deal_list').hide()
    });
    $('.deal_list').hover(function () {
        $('.deal_list').show()
    }, function () {
        $('.deal_list').hide()
    });
    $('#user-finance').hover(function () {
        $('.mywallet_list').show();
    }, function () {
        $('.mywallet_list').hide()
    });
    $('#select_lang').change(function(){
		var self = $(this);
		if(self.val() == 'zh-cn'){
			window.location = '?l=zh-cn';
		}else{
			window.location = '?l=en-us';
		}
	});
</script>



<script>
    function getJsonTop() {
        $.getJSON("/Ajax/getJsonTop?market=<?php echo ($market); ?>&t=" + Math.random(), function (data) {
            if (data) {
                if (data['info']['new_price']) {
                    $('#market_new_price').removeClass('buy');
                    $('#market_new_price').removeClass('sell');
                    if ($("#market_new_price").html() > data['info']['new_price']) {
                        $('#market_new_price').addClass('sell');
                    }
                    if ($("#market_new_price").html() < data['info']['new_price']) {
                        $('#market_new_price').addClass('buy');
                    }
                    $("#market_new_price").html(data['info']['new_price']);
                }
                if (data['info']['buy_price']) {
                    $('#market_buy_price').removeClass('buy');
                    $('#market_buy_price').removeClass('sell');
                    if ($("#market_buy_price").html() > data['info']['buy_price']) {
                        $('#market_buy_price').addClass('sell');
                    }
                    if ($("#market_buy_price").html() < data['info']['buy_price']) {
                        $('#market_buy_price').addClass('buy');
                    }
                    $("#market_buy_price").html(data['info']['buy_price']);
                    $("#sell_best_price").html('￥' + data['info']['buy_price']);
                }
                if (data['info']['sell_price']) {
                    $('#market_sell_price').removeClass('buy');
                    $('#market_sell_price').removeClass('sell');
                    if ($("#market_sell_price").html() > data['info']['sell_price']) {
                        $('#market_sell_price').addClass('sell');
                    }
                    if ($("#market_sell_price").html() < data['info']['sell_price']) {
                        $('#market_sell_price').addClass('buy');
                    }
                    $("#market_sell_price").html(data['info']['sell_price']);
                    $("#buy_best_price").html('￥' + data['info']['sell_price']);
                }
                if (data['info']['max_price']) {
                    $("#market_max_price").html(data['info']['max_price']);
                }
                if (data['info']['min_price']) {
                    $("#market_min_price").html(data['info']['min_price']);
                }
                if (data['info']['volume']) {
                    if (data['info']['volume'] > 10000) {
                        data['info']['volume'] = (data['info']['volume'] / 10000).toFixed(2) + "万"
                    }
                    if (data['info']['volume'] > 100000000) {
                        data['info']['volume'] = (data['info']['volume'] / 100000000).toFixed(2) + "亿"
                    }
                    $("#market_volume").html(data['info']['volume']);
                }
                if (data['info']['change']) {
                    $('#market_change').removeClass('buy');
                    $('#market_change').removeClass('sell');
                    if (data['info']['change'] > 0) {
                        $('#market_change').addClass('buy');
                    } else {
                        $('#market_change').addClass('sell');
                    }
                    $("#market_change").html(data['info']['change'] + "%");
                }


                if (data['list']) {
                    var list = '';
                    for (var i in data['list']) {
                        list += '<li><a href="/Trade/index/market/' + data['list'][i]['name'] + '"> <img src="/Upload/coin/' + data['list'][i]['img'] + '" style="width: 40px; float: left; margin-right: 5px;"> <span class="all_coin_name"><p>' + data['list'][i]['title'] + '</p> <span id="all_coin_' + data['list'][i]['name'] + '">' + data['list'][i]['new_price'] + '</span></span></a></li>';
                    }
                    $("#all_coin").html(list);
                }


            }
        });
        setTimeout('getJsonTop()', 5000);
    }
    $(function () {
        getJsonTop();
        $('.all_coin_price').hover(function () {
            $('.all_coin_list').show()
        }, function () {
            $('.all_coin_list').hide()
        });
    });
</script>
<?php if(!empty($prompt_text)): ?><div class="mytips">
        <h6 style="color: #ff8000;"><?php echo L('trade.wenxintishi');?></h6>
        <?php echo ($prompt_text); ?>
    </div><?php endif; ?>

<div class="autobox mt20 clear" id="Kline-change" style="padding-top: 20px;">
 <div class="left w790" style="position: relative;">
  <!--<?php echo L('trade.xingqing');?>图-->
  <div id="kline">
   <div id="paint_chart" style="height: 400px">
    <iframe style="border-style: none;" border="0" width="100%" height="400" id="market_chart" src="/Trade/ordinary?market=<?php echo ($market); ?>"></iframe>
   </div>
  </div>
  <!--<?php echo L('trade.xingqing');?>图结束-->
  <div class="fast_tr clear">
   <a name="mark-trade"></a>
   <form class="ft_box" id="form-buy">
    <dl>
     <dt class="orange"><?php echo L('trade.mairu');?></dt>
     <dd>
      <p><?php echo L('trade.zuijiamaijia');?>：</p>
      <span class="orange" id="buy_best_price">-</span> <?php echo (strtoupper($rmb)); ?>/<?php echo (strtoupper($xnb)); ?>

      <?php if(($C['trade_hangqing']) == "1"): ?><a style="float: right;margin-right: 60px;color:#e55600;" id="market_hangqing" onclick="hangqing()"><?php echo L('trade.jinrixingqing');?></a><?php endif; ?>
     </dd>
     <dd>
      <p><?php echo L('trade.mairujiage');?>：</p>
      <input type="text" id="buy_price" name="price" placeholder="<?php echo L('trade.1gebijiage');?>">
     </dd>
     <dd>
      <p><?php echo L('trade.zuidakemai');?>：</p>
      <span class="col_333" id="buy_max" title="满仓(全买)，设置<?php echo L('trade.mairushuliang');?>为最大值">-</span> <?php echo (strtoupper($xnb)); ?>
     </dd>
     <dd>
      <p><?php echo L('trade.mairubili');?>：</p>
      <div class="slider_wrap">
       <div id="ratio_num_buy" class="ratio">0%</div>
       <div class="sliderbox">
        <div id="slider_buy" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
         <div class="ui-slider-range ui-widget-header ui-slider-range-min"></div>
         <a data_slide="buy" class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;"></a>
         <div class="ui-slider-range ui-widget-header ui-corner-all ui-slider-range-min" style="width: 0%;"></div>
        </div>
       </div>
      </div>
     </dd>
     <dd>
      <p><?php echo L('trade.mairushuliang');?>：</p>
      <input type="text" id="buy_num" name="num">
     </dd>
     <dd>
      <p><?php echo L('trade.zongjia');?>：</p>
      <span class="col_333" id="buy_mum">-</span> <?php echo (strtoupper($rmb)); ?>
     </dd>
     <dd>
      <p><?php echo L('trade.shouxufei');?>：</p>
      <?php echo C('market')[$market]['fee_buy'];?>%
     </dd>
     <dd class="pwdtrade">
      <p><?php echo L('trade.jiaoyimima');?>：</p>
      <input id="buy_paypassword" name="pwtrade" type="password"> <span onclick="layertpwd()" class="settings"></span>
     </dd>
    </dl>
    <div>
     <div class="trader_btn">
      <div class="tan_btn" id="tm-buy"></div>
      <input type="button" value="<?php echo L('trade.mairu');?>" onclick="tradeadd_buy();">
     </div>
    </div>
   </form>
   <form class="ft_box nobr" id="form-sell">
    <dl>
     <dt class="green"><?php echo L('trade.maichu');?></dt>
     <dd>
      <p><?php echo L('trade.zuijiamaijia4');?>：</p>
      <span class="orange" id="sell_best_price" style="    color: #690!important;">-</span> <?php echo (strtoupper($rmb)); ?>/<?php echo (strtoupper($xnb)); ?>

      <?php if(($C['trade_hangqing']) == "1"): ?><a style="float: right;margin-right: 60px;color:#e55600;" id="market_hangqing" onclick="hangqing()"><?php echo L('trade.jinrixingqing');?></a><?php endif; ?>
     </dd>
     <dd>
      <p><?php echo L('trade.maichujiage');?>：</p>
      <input type="text" id="sell_price" name="price" placeholder="<?php echo L('trade.1gebijiage');?>"/>
     </dd>
     <dd>
      <p><?php echo L('trade.zuidakemai');?>：</p>
      <span id="sell_max" class="col_333">-</span> <?php echo (strtoupper($xnb)); ?>
     </dd>
     <dd>
      <p><?php echo L('trade.maichubili');?>：</p>
      <div class="slider_wrap">
       <div id="ratio_num_sell" class="ratio">0%</div>
       <div class="sliderbox">
        <div id="slider_sell" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
         <div class="ui-slider-range ui-widget-header ui-slider-range-min"></div>
         <a data_slide="sell" class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;"></a>
         <div class="ui-slider-range ui-widget-header ui-corner-all ui-slider-range-min" style="width: 0%;"></div>
        </div>
       </div>
      </div>
     </dd>
     <dd>
      <p><?php echo L('trade.maichushuliang');?>：</p>
      <input type="text" id="sell_num" name="num">
     </dd>
     <dd>
      <p><?php echo L('trade.zongjia');?>：</p>
      <span class="col_333" id="sell_mum">-</span> <?php echo (strtoupper($rmb)); ?>
     </dd>
     <dd>
      <p><?php echo L('trade.shouxufei');?>：</p>
      <?php echo C('market')[$market]['fee_sell'];?>%
     </dd>
     <dd class="pwdtrade">
      <p><?php echo L('trade.jiaoyimima');?>：</p>
      <input id="sell_paypassword" name="pwtrade" type="password"> <span onclick="layertpwd()" class="settings"></span>
     </dd>
    </dl>
    <div>
     <div class="trader_btn">
      <div class="tan_btn" id="tm-sell"></div>
      <input class="bg_green" type="button" value="<?php echo L('trade.maichu');?>" onclick="tradeadd_sell();">
     </div>
    </div>
   </form>
  </div>
 </div>
 <div class="right w390">
  <div class="zcxx hide" style="display: block; margin-bottom: 20px;">
   <div class="right_table">
    <table style="width: 100%">
     <tbody>
      <tr>
       <th><?php echo L('trade.keyong'); echo ($C['coin'][$xnb]['title']); ?></th>
       <td><span id="my_xnb">0</span></td>
      </tr>
      <tr>
       <th><?php echo L('trade.dongjie'); echo ($C['coin'][$xnb]['title']); ?></th>
       <td><font id="my_xnbd">0</font></td>
      </tr>
      <tr>
       <th><?php echo L('trade.keyong'); echo ($C['coin'][$rmb]['title']); ?></th>
       <td><span id="my_rmb">0</span></td>
      </tr>
      <tr>
       <th><?php echo L('trade.dongjie'); echo ($C['coin'][$rmb]['title']); ?></th>
       <td><font id="my_rmbd">0</font></td>
      </tr>
      <tr>
       <th><?php echo L('trade.zhanghuzongzichan');?></th>
       <td><font id="user_finance">0</font></td>
      </tr>
     </tbody>
    </table>
   </div>
  </div>
  <div class="tradeBox">
   <div class="slideHd">
    <!-- 下面是前/后按钮代码，如果不需要删除即可 -->
    <ul class="active">
     <li id="trade_moshi_1" class="trade_moshi on"><a href="javascript:void(0);" onclick="moshi(1)"> <?php echo L('trade.morenmoshi');?> </a></li>
     <?php if(($C['trade_moshi']) == "1"): ?><li id="trade_moshi_2" class="trade_moshi"><a href="javascript:void(0);" onclick="moshi(2)"> <?php echo L('trade.liaotianmoshi');?> </a></li><?php endif; ?>
     <li id="trade_moshi_3" class="trade_moshi"><a href="javascript:void(0);" onclick="moshi(3)"> <?php echo L('trade.zhikanmairu');?> </a></li>
     <li id="trade_moshi_4" class="trade_moshi"><a href="javascript:void(0);" onclick="moshi(4)"> <?php echo L('trade.zhikanmaichu');?> </a></li>
    </ul>
   </div>
  </div>
  <div class="right" style="display: none;" id="trade_moshi_liaotian_1">
   <div class="coinBox buyonesellone">
    <div class="coinBoxBody buybtcbody2">
     <div id="marqueebox1" class="">
      <ul id="chat_content">
      </ul>
     </div>
     <div id="marqueebox2">
      <ul class="clearfix">
       <li id="face" class="left"><a href="javascript:void(0);" class="face faceBtn" id="face1"> <img src="/Public/Home/images/face.gif" width="20">
       </a></li>
       <li id="msg" class="left"><input type="text" name="msg" class="text" id="chat_text" style="width: 288px;"></li>
       <li id="send" class="right"><input class="tijiao" type="button" value="发送" onclick="upChat()"></li>
      </ul>
     </div>
    </div>
   </div>
  </div>
  <div class="entrust" style="max-height: 685px;" id="trade_moshi_liaotian_2">
   <div class="entrust_list">
    <ul>
     <li class="first" style="width: 45px"><?php echo L('trade.maimai');?></li>
     <li class="w85" style="width: 90px"><?php echo L('trade.jiage');?></li>
     <li class="w64" style="width: 110px"><?php echo L('trade.shuliang');?></li>
     <li class="w62" style="width: 100px"><?php echo L('trade.zonge');?></li>
    </ul>
    <div class="el_dl" id="selllist"></div>
    <div class="el_dl" id="buylist" style="border-bottom: 1px dotted #fff;"></div>
   </div>
  </div>
 </div>
</div>
<div class="clear over_auto  account_table autobox mt20" style="margin-top: 20px;">
 <div id="entrust_over" class=" over_auto" style="margin-bottom: 10px;">
  <div class="TitleBox" style="border_top: 1px solid #D5D5D5;">
   <h3 class="PlateTitle"><?php echo L('trade.wodeweituo');?></h3>
  </div>
  <div class="over_auto">
   <table class="Transaction no_border_right no_border_left_right">
    <thead>
     <tr>
      <th width="180"><?php echo L('trade.shijian');?></th>
      <th width="80"><?php echo L('trade.maimai');?></th>
      <th><?php echo L('trade.jiage');?></th>
      <th><?php echo L('trade.shuliang');?></th>
      <th><?php echo L('trade.yichengjiao');?></th>
      <th><?php echo L('trade.zonge');?></th>
      <th width="80"><?php echo L('trade.caozuo');?></th>
     </tr>
    </thead>
    <tbody id="entrustlist" class="no-border-left-right">
    </tbody>
   </table>
  </div>
 </div>
</div>
<div class=" autobox " style="border_bottom: 1px solid #D5D5D5; margin-top: 20px;">
 <div class="clear">
  <div class="TitleBox" style="border_top: 1px solid #D5D5D5;">
   <h3 class="PlateTitle"><?php echo L('trade.chengjiaojilu');?></h3>
  </div>
  <div class=" over_hidden">
   <div class="over_auto">
    <table class="Transaction  no_border_right no_border_left_right">
     <thead>
      <tr>
       <th width="180"><?php echo L('trade.shijian');?></th>
       <th width="80"><?php echo L('trade.maimai');?></th>
       <th width="250"><?php echo L('trade.chengjiaojia');?></th>
       <th width="250"><?php echo L('trade.chengjiaoliang');?></th>
       <th><span style="padding_right: 18px;"><?php echo L('trade.zonge');?></span></th>
      </tr>
     </thead>
    </table>
   </div>
   <div class="over_auto" style="_height: 508px; max-height: 508px; overflow-x: hidden; overflow-y: auto;">
    <table class="Transaction  no_border_right no_border_left_right">
     <tbody id="orderlist" class="no-border-left-right">
     </tbody>
    </table>
   </div>
  </div>
 </div>
</div>
<br>
<br>






<script type="text/javascript">
	var market="<?php echo ($market); ?>";
    var market_round="<?php echo ($C['market'][$market]['round']); ?>";
    var market_round_num=8-"<?php echo ($C['market'][$market]['round']); ?>";
	var userid="<?php echo (session('userId')); ?>";
	var trade_moshi=1;
	var getDepth_tlme=null;
	var trans_lock = 0;



    function hangqing() {
     layer.alert("<?php echo L('trade.jiaoyishichang');?>:<?php echo C('market')[$market]['title'];?><br><br>" +
             "<?php echo L('trade.zuorishoupanjia');?>:<?php echo (NumToStr($C['market'][$market]['hou_price'])); ?><br>" +
             "<?php echo L('trade.jinrizhangfuxianzhi');?>：<?php echo C('market')[$market]['zhang'];?>%<br>" +
             "<?php echo L('trade.jinridiefuxianzhi');?>：<?php echo C('market')[$market]['die'];?>%<br>" +
             "<?php echo L('trade.zuixiaojiaoyijia');?>:<?php echo (NumToStr($C['market'][$market]['buy_min'])); ?><br>" +
             "<?php echo L('trade.maichuzuida');?>:<?php echo (NumToStr($C['market'][$market]['sell_max'])); ?><br>",
             {title: '<?php echo L('trade.jinrixingqing');?>',});
    }


	function moshi(id){
		trade_moshi=id;
		$('.trade_moshi').removeClass('on');
		$('#trade_moshi_'+id).addClass('on');
		if(id==3){
			$('#selllist').hide();
		}else{
			$('#selllist').show();
		}
		
		if(id==4){
			$('#buylist').hide();
		}else{
			$('#buylist').show();
		}
		
		if(id==2){
			$('#trade_moshi_liaotian_2').hide();
			$('#trade_moshi_liaotian_1').show();
			getChat();
		}else{
			$('#trade_moshi_liaotian_2').show();
			$('#trade_moshi_liaotian_1').hide();
			getDepth();
		}
	}
	
    
	
	function layertpwd(){
        var html = '<div id="all_mask" class="all_mask"></div><div id="tpwd" class="all_mask_loginbox">' +
            '<div class="login_title pl20"><?php echo L('trade.yimimashezhi');?></div><form id="tpwdsetting" class="set_verify">' +
            '<ul class="tpwd"><li><label for="only"><input type="radio" id="only" value="1" name="aaatpwdsetting"> <?php echo L('trade.denglushurumima');?> </label></li><li>' +
            '<label for="every"><input type="radio" checked id="every" value="2" name="aaatpwdsetting"> <?php echo L('trade.jiaoyishurumima');?></label></li><li><label for="none">' +
            '<input type="radio" id="none" name="aaatpwdsetting" value="3"> <?php echo L('trade.jiaoyibuxumima');?></label></li><li><input type="password" id="aaapaypassword" name="paypassword" placeholder="<?php echo L('trade.shurujiaoyimima');?>" class="text"/>' +
            '</li></ul><div class="save_verify"><input type="button" value="<?php echo L('trade.baocun');?>" onclick="tpwdsettingaa()" /></div><div class="mask_wrap_close" id="pwd_close"></div></form></div>';
        $('body').append(html);
        $('#tpwd').css('top',($(window).height()/2)-(265/2)+$(document).scrollTop());
        $('.all_mask').css({'height': $(document).height()});
        $('#pwd_close').click(function(){
        	$('#tpwd').remove();
        	$('#all_mask').remove();
        	$('#all_mask').remove();
        })
        
        
        $.get('/user/tpwdsetting', function(d){
        	if(d==1){
        		$('#only').prop('checked', true);
        	}
       		 if(d==2){
    				$('#every').prop('checked', true);
    			}
			if(d==3){
				$('#none').prop('checked', true);
			}
            

        })
	}
	
	//保存交易密码设置
	function tpwdsettingaa(){
       var paypassword=$("#aaapaypassword").val();
       var tpwdsetting=$("input[name='aaatpwdsetting']:checked").val();
       if(paypassword==""||paypassword==null){
        layer.tips('<?php echo L('trade.shurujiaoyimima');?>','#paypassword',{tips : 3 });
        return false;
       }
       if(tpwdsetting==""||tpwdsetting==null){
        layer.tips('<?php echo L('trade.shuruxuanze');?>','#tpwdsetting',{tips : 3 });
        return false;
       }



	    $.post('/user/uptpwdsetting', {paypassword : paypassword,tpwdsetting : tpwdsetting}, function(d){
	        if(d.status) {
                  layer.msg('<?php echo L('trade.shezhichenggong');?>',{icon : 1 });
                  window.location.reload();
	             } else {
                  layer.msg(d.info,{icon : 2 });
                 }

	         },'json');
	    }
	
	
	function tradeadd_buy(){
		if(trans_lock){
			layer.msg('<?php echo L('trade.buyaozhongfutijiao');?>',{icon : 2 });
			return;
		}
		trans_lock = 1;
		
		var price=parseFloat($('#buy_price').val());
		var num=parseFloat($('#buy-num').val());
		var paypassword=$('#buy_paypassword').val();
		if(price==""||price==null){
			layer.tips('<?php echo L('trade.qingshuruneirong');?>','#buy_price',{tips : 3 });
			return false;
		}
		if(num==""||num==null){
			layer.tips('<?php echo L('trade.qingshuruneirong');?>','#buy_num',{tips : 3 });
			return false;
		}

		//加载层-风格3
		layer.load(2);
		
		
		//此处演示关闭
		setTimeout(function(){
		  layer.closeAll('loading');
		  trans_lock = 0;
		}, 10000);
		$.post("<?php echo U('Trade/upTrade');?>",{price : $('#buy_price').val(),num : $('#buy_num').val(),paypassword : $('#buy_paypassword').val(),market : market,type : 1 },function(data){
			layer.closeAll('loading');
			trans_lock = 0;
			if(data.status==1){
				
				$("#buy_price").val('');
				$("#buy_num").val('');
				$("#sell_price").val('');
				$("#sell_num").val('');
				layer.msg(data.info,{icon : 1 });
			}else{
				layer.msg(data.info,{icon : 2 });
			}
		},'json');
	}
	
	function tradeadd_sell(){
		if(trans_lock){
			layer.msg('<?php echo L('trade.buyaozhongfutijiao');?>',{icon : 2 });
			return;
		}
		trans_lock = 1;
		var price=parseFloat($('#sell_price').val());
		var num=parseFloat($('#sell_num').val());
		var paypassword=$('#sell_paypassword').val();
		if(price==""||price==null){
			layer.tips('<?php echo L('trade.qingshuruneirong');?>','#sell_price',{tips : 3 });
			return false;
		}
		if(num==""||num==null){
			layer.tips('<?php echo L('trade.qingshuruneirong');?>','#sell_num',{tips : 3 });
			return false;
		}
		layer.load(2);
		//此处演示关闭
		setTimeout(function(){
		  layer.closeAll('loading');
		  trans_lock = 0;
		}, 10000);
		
		$.post("<?php echo U('Trade/upTrade');?>",{price : $('#sell_price').val(),num : $('#sell_num').val(),paypassword : $('#sell_paypassword').val(),market : market,type : 2 },function(data){
			layer.closeAll('loading');
			trans_lock = 0;
			if(data.status==1){
				$("#buy_price").val('');
				$("#buy_num").val('');
				$("#sell_price").val('');
				$("#sell_num").val('');
				layer.msg(data.info,{icon : 1 });
			}else{
				layer.msg(data.info,{icon : 2 });
			}
		},'json');
	}
	
	
	
	

	//撤销
	function cancelaa(id){
		$.post("<?php echo U('Trade/chexiao');?>",{id : id },function(data){
			if(data.status==1){
				getEntrustAndUsercoin();
				layer.msg(data.info,{icon : 1 });
			}else{
				layer.msg(data.info,{icon : 2 });
			}
		});
	}

	function getTradelog(){
		$.getJSON("/Ajax/getTradelog?market="+market+"&t="+Math.random(),function(data){
			if(data){
				if(data['tradelog']){
					var list='';
					var type='';
					var typename='';
					for( var i in data['tradelog']){
						if(data['tradelog'][i]['type']==1){
							list+='<tr title="<?php echo L('trade.yizhegejiagemaichu');?>" onclick="autotrust(this,\'buy\',2)"><td class="buy"   width="180">'+data['tradelog'][i]['addtime']+'</td><td class="buy"   width="80">买</td><td class="buy"   width="250">'+data['tradelog'][i]['price']+'</td><td class="buy"  width="250">'+data['tradelog'][i]['num']+'</td><td class="buy">'+data['tradelog'][i]['mum']+'</td></tr>';
						}else{
							list+='<tr title="<?php echo L('trade.yizhegejiagemairu');?>" onclick="autotrust(this,\'sell\',2)"><td class="sell"   width="180">'+data['tradelog'][i]['addtime']+'</td><td class="sell"   width="80">卖</td><td class="sell"   width="250">'+data['tradelog'][i]['price']+'</td><td class="sell"  width="250">'+data['tradelog'][i]['num']+'</td><td class="sell">'+data['tradelog'][i]['mum']+'</td></tr>';
						}
					}
					$("#orderlist").html(list);
				}
			}
		});
		setTimeout('getTradelog()',5000);
	}

	function getDepth(){
		if(trade_moshi!=2){

			$.getJSON("/Ajax/getDepth?market="+market+"&trade_moshi="+trade_moshi+"&t="+Math.random(),function(data){
				if(data){

					if(data['depth']){
						var list='';
						var sellk=data['depth']['sell'].length;
						if(data['depth']['sell']){
							for(i=0; i<data['depth']['sell'].length; i++){
								list+='<dl title="<?php echo L('trade.yizhegejiagemairu');?>" style="cursor: pointer;" onclick="autotrust(this,\'sell\',1)"><dt class="sell"  style="width: 40px;padding-left: 5px;">卖'+(sellk-i)+'</dt><dd class="sell"  style="width: 90px">'+data['depth']['sell'][i][0]+'</dd><dd class="sell"  style="width: 110px">'+data['depth']['sell'][i][1]+'</dd><dd class="sell"  style="width: 100px">'+(data['depth']['sell'][i][0]*data['depth']['sell'][i][1]).toFixed(6)+'</dd></dl>';
							}

						}
						$("#selllist").html(list);
						list='';
						if(data['depth']['buy']){
							for(i=0; i<data['depth']['buy'].length; i++){
								list+='<dl title="<?php echo L('trade.yizhegejiagemaichu');?>" style="cursor: pointer;" onclick="autotrust(this,\'buy\',1)"><dt class="buy"  style="width: 40px;padding-left: 5px;">买'+(i+1)+'</dt><dd class="buy"  style="width: 90px">'+data['depth']['buy'][i][0]+'</dd><dd class="buy"  style="width: 110px">'+data['depth']['buy'][i][1]+'</dd><dd class="buy"  style="width: 100px">'+(data['depth']['buy'][i][0]*data['depth']['buy'][i][1]).toFixed(6)+'</dd></dl>';
							}

						}
						$("#buylist").html(list);
					}

				}
			});
			clearInterval(getDepth_tlme);
			
			var wait=second=5;
			getDepth_tlme=setInterval(function(){
				wait--;
				if(wait<0){
					clearInterval(getDepth_tlme);
					getDepth();
					wait=second;
				}
			},1000);
		}
	}

	function getEntrustAndUsercoin(){
		$.getJSON("/Ajax/getEntrustAndUsercoin?market="+market+"&t="+Math.random(),function(data){
			if(data){
				if(data['entrust']){
					$('#entrust_over').show();
					var list='';
					var cont=data['entrust'].length;
					for(i=0; i<data['entrust'].length; i++){
						if(data['entrust'][i]['type']==1){
							list+='<tr title="<?php echo L('trade.yizhegejiagemaichu');?>" onclick="autotrust(this,\'buy\',2)"><td class="buy">'+data['entrust'][i]['addtime']+'</td><td class="buy">买</td><td class="buy">'+data['entrust'][i]['price']+'</td><td class="buy">'+data['entrust'][i]['num']+'</td><td class="buy">'+data['entrust'][i]['deal']+'</td><td class="buy">'+(data['entrust'][i]['price']*data['entrust'][i]['num']).toFixed(6)+'</td><td><a style="color: #2674FF;" class="cancelaa" id="'+data['entrust'][i]['id']+'" onclick="cancelaa(\''+data['entrust'][i]['id']+'\')" href="javascript:void(0);"><?php echo L('trade.chexiao');?></a></td></tr>';
						}else{
							list+='<tr title="<?php echo L('trade.yizhegejiagemairu');?>" onclick="autotrust(this,\'sell\',2)"><td class="sell">'+data['entrust'][i]['addtime']+'</td><td class="sell">卖</td><td class="sell">'+data['entrust'][i]['price']+'</td><td class="sell">'+data['entrust'][i]['num']+'</td><td class="sell">'+data['entrust'][i]['deal']+'</td><td class="sell">'+(data['entrust'][i]['price']*data['entrust'][i]['num']).toFixed(6)+'</td><td><a style="color: #2674FF;" class="cancelaa" id="'+data['entrust'][i]['id']+'" onclick="cancelaa(\''+data['entrust'][i]['id']+'\')" href="javascript:void(0);"><?php echo L('trade.chexiao');?></a></td></tr>';
						}
					}
					if(cont==10){
						list+='<tr><td style="text_align:center;" colspan="7"><a href="/Finance/mywt" style="color: #2674FF;">更多委托信息</a>&nbsp;&nbsp;</td></tr>';
					}
					$('#entrustlist').html(list);
				}else{
					$('#entrust_over').hide();
				}

				if(data['usercoin']){
					if(data['usercoin']['cny']){
						$("#my_rmb").html(data['usercoin']['cny']);
					}else{
						$("#my_rmb").html('0.00');
					}

					if(data['usercoin']['cnyd']){
						$("#my_rmbd").html(data['usercoin']['cnyd']);
					}else{
						$("#my_rmbd").html('0.00');
					}

					if(data['usercoin']['xnb']){
						$("#my_xnb").html(data['usercoin']['xnb']);
					}else{
						$("#my_xnb").html('0.00');
					}

					if(data['usercoin']['xnbd']){
						$("#my_xnbd").html(data['usercoin']['xnbd']);
					}else{
						$("#my_xnbd").html('0.00');
					}
				}

			}
		});
		$.getJSON("/Ajax/allfinance?t="+Math.random(),function(data){
			
			$('#user_finance').html(data);
		});
		
		
		setTimeout('getEntrustAndUsercoin()',5000);
	}

	

	$(function(){
		getTradelog();
		getDepth();
		if(userid>0){
			getEntrustAndUsercoin();
		}else{
            $('#entrust_over').hide();
        }
		
		

	
	});


    function toNum(num,round){
     return Math.round(num*Math.pow(10,round)-1)/Math.pow(10,round);
    }


	// 自动填价格
	function autotrust(_this,type,cq){
		
		if(type=='sell'){
			$('#buy_price').val($(_this).children().eq(cq).html()).css({'color' : '#333','font_size' : '14px' });
			if($("#my_rmb").html()>0){
				$("#buy_max").html(toNum(($("#my_rmb").html()/$('#buy_price').val()),market_round_num));
			}
			if($('#buy_num').val()){
		    	 $("#buy_mum").html(($('#buy_num').val()*$('#buy_price').val()).toFixed(8)*1);
		     }
			
		}
		if(type=='buy'){
			$('#sell_price').val($(_this).children().eq(cq).html()).css({'color' : '#333','fontSize' : '14px' });
			if($("#my_xnb").html()>0){
				$("#sell_max").html($("#my_xnb").html());
			}
			if($('#sell_num').val()){
		    	 $("#sell_mum").html(($('#sell_num').val()*$('#sell_price').val()).toFixed(8)*1);
            }
		}
		 
	}

	
	
	
	


	$('#buy_price,#buy_num,#sell_price,#sell_num').css("ime-mode","disabled").bind('keyup change',function(){
		var buyprice=parseFloat($('#buy_price').val());
		var buynum=parseFloat($('#buy_num').val());
		var sellprice=parseFloat($('#sell_price').val());
		var sellnum=parseFloat($('#sell_num').val());
		var buymum=buyprice*buynum;
		var sellmum=sellprice*sellnum;
		var myrmb=$("#my_rmb").html();
		var myxnb=$("#my_xnb").html();
		var buykenum=0;
		var sellkenum=0;
		if(myrmb>0){
			buykenum=myrmb/buyprice;
		}
		if(myxnb>0){
			sellkenum=myxnb;
		}
		if(buyprice!=null&&buyprice.toString().split(".")!=null&&buyprice.toString().split(".")[1]!=null){
			if(buyprice.toString().split('.')[1].length>market_round){
				$('#buy_price').val(buyprice.toFixed(market_round));
			}
		}
		if(buynum!=null&&buynum.toString().split(".")!=null&&buynum.toString().split(".")[1]!=null){
			if(buynum.toString().split('.')[1].length>market_round_num){
				$('#buy_num').val(toNum(buynum,market_round_num));
			}
		}
		if(sellprice!=null&&sellprice.toString().split(".")!=null&&sellprice.toString().split(".")[1]!=null){
			if(sellprice.toString().split('.')[1].length>market_round){
				$('#sell_price').val(sellprice.toFixed(market_round));
			}
		}
		if(sellnum!=null&&sellnum.toString().split(".")!=null&&sellnum.toString().split(".")[1]!=null){
			if(sellnum.toString().split('.')[1].length>market_round_num){
				$('#sell_num').val(toNum(sellnum,market_round_num));
			}
		}
		if(buymum!=null&&buymum>0){
			$('#buy_mum').html(buymum.toFixed(8)*1);
		}
		if(sellmum!=null&&sellmum>0){
			$('#sell_mum').html(sellmum.toFixed(8)*1);
		}
		if(buykenum!=null&&buykenum>0&&buykenum!='Infinity'){
			$('#buy_max').html(toNum(buykenum,market_round_num));
		}
		if(sellkenum!=null&&sellkenum>0&&sellkenum!='Infinity'){
			$('#sell_max').html(sellkenum);
		}
	}).bind("paste",function(){
		return false;
	}).bind("blur",function(){
		if(this.value.slice(-1)=="."){
			this.value=this.value.slice(0,this.value.length-1);
		}
	}).bind("keypress",function(e){
		var code=(e.keyCode ? e.keyCode : e.which); //兼容火狐 IE 
		if(this.value.indexOf(".")==-1){
			return (code>=48&&code<=57)||(code==46);
		}else{
			return code>=48&&code<=57
		}
	});
	
	
</script>
<script type="text/javascript" src="/Public/Home/js/jquery.qqFace.js"></script>
<script type="text/javascript">
//@他
function atChat(_this){
	$("#chat_text").val('@'+$(_this).html()+' :');
}
// 回车提交聊天
$("#chat_text").keyup(function(e){
	if(e.keyCode===13){
		upChat();
		return false;
	}
});
// 提交聊天
function upChat(){
	var content=$("#chat_text").val();
	if(content==""||content==null){
		layer.tips('<?php echo L('trade.qingshuruneirong');?>','#chat_text',{tips : 3 });
		return false;
	}
	$.post("/Ajax/upChat",{content : content },function(data){
		if(data.status==1){
			$("#chat_text").val('');
			getChat();
		}else{
			layer.tips(data.info,'#chat_text',{tips : 3 });
			return false;
		}
	},'json');
}
//表情盒子的ID//给那个控件赋值//表情存放的路径
$('#face1').qqFace({id : 'facebox1',assign : 'chat_text',path : '/Upload/face/' });




function getChat(){
	if(trade_moshi==2){

		$.getJSON("/Ajax/getChat?t="+Math.random(),function(data){
			if(data){


				var list='';
				for(i=0; i<data.length; i++){
					list+='<li><a href="javascript:void(0);" onclick="atChat(this)">'+data[i][1]+'</a>：'+data[i][2]+'</li>';
				}
				list=list.replace(/\[\/#([0-9]*)\]/g,'<img src="/Upload/face/$1.gif"  width="18">');


              if($("#chat_content").html()!=list){
               $("#chat_content").html(list);
               $("#marqueebox1").scrollTop(40000);
              }



			}
		});
		setTimeout('getChat()',5000);
	}
}

</script>



<script type="text/javascript" src="/Public/Home/js/jquery-ui.js"></script>
<script type="text/javascript">
 $(function() {
  slider();
 });
 // 买入/卖出 比例
 function slider(){
  var type = ['sell','buy'];
  for(var i in type){
   $("#slider_"+type[i]).slider({
    value: 0,min: 0, max: 100,step: 10,range: "min",slide: function(a, t) {
     var type = $(t.handle).attr('data_slide');
     var e = parseFloat($("#"+type+'_max').text());
     if(isNaN(e)) e=0;
     $("#"+type+' .ui-slider-handle').addClass('ui-state-focus ui-state-active');
     $("#"+type+"_num").val((e / 100 * t.value).toFixed(market_round_num));
     $("#ratio_num_"+type).text(t.value + "%");
     if($('#'+type+'_price').val()){
    	 $("#"+type+"_mum").html(((e / 100 * t.value*$('#'+type+'_price').val())).toFixed(8)*1);
     }
    }
   })
  }
 }
</script>
<script>
	//菜单高亮
	$('#list-tab_index').addClass('on');
</script>
<style>
	.footer{
		clear:both;
	}

	.footer .main{
		height:240px;
	}

	#footer a{
		color:#FFF;
		margin:0px 0px;
	}

	.footer .bottom{
		height:80px;
		background:#2C2C2C;
	}

	.footer .main .list{
		float:left;
		margin-top:40px;
		width: 185px;
		padding: 0px 5px;
	}

	.footer .main .list label{
		margin-top:10px;
		display:block;
		font-weight:bold;
		color:#FFF;
		font-size:16px;
		text-align: left;
		padding-left: 45px;
	}

	.footer .main .list ul{
		margin:10px 0px 0px;
		padding:0px;
	}

	.footer .main .list li{
		display:block;
		height:30px;
		line-height:30px;
		color:#CCC;
		text-align:center;
		list-style:none;
		text-align: left;
		padding-left: 45px;
	}

	.footer .main .list li a{
		display:block;
		width:100%;
		height:100%;
		color:#CCC;
		font-size:14px;
	}

	.footer .about_me{
		float:left;
		margin-top:40px;
		width:280px;
		height:150px;
		border-right:1px #606060 solid;
		padding-right:50px;
	}

	.footer .wx{
		margin-top:50px;
		height:55px;
	}

	.footer .wx a{
		position:relative;
		margin:0 14px;
		cursor:pointer;
	}

	.footer .wx a img{

		left:-69px;

		transition:300ms;
		-webkit-transition:300ms;
		-ms-transition:300ms;
		-o-transition:300ms;
		-moz-transition:300ms;
	}

	.footer .wx a:hover img{
		display:block;
		top:-180px;
	}

	.footer .footer_wx_icon{
		float:left;

		border-radius:55px;
		-webkit-border-radius:55px;
		-ms-border-radius:55px;
		-o-border-radius:55px;
		-moz-border-radius:55px;

		transition:300ms;
		-webkit-transition:300ms;
		-ms-transition:300ms;
		-o-transition:300ms;
		-moz-transition:300ms;
	}

	.footer .footer_wx_icon:hover{

	}

	.footer .footer_sn_icon{
		float:left;
		width:55px;
		height:55px;

		background-color:#34353A;

		border-radius:55px;
		-webkit-border-radius:55px;
		-ms-border-radius:55px;
		-o-border-radius:55px;
		-moz-border-radius:55px;

		transition:300ms;
		-webkit-transition:300ms;
		-ms-transition:300ms;
		-o-transition:300ms;
		-moz-transition:300ms;
	}

	.footer .footer_sn_icon:hover{

		background-color:#F00;
	}

	.footer .footer_qq_icon{
		float:left;
		width:55px;
		height:55px;

		background-color:#34353A;

		border-radius:55px;
		-webkit-border-radius:55px;
		-ms-border-radius:55px;
		-o-border-radius:55px;
		-moz-border-radius:55px;

		transition:300ms;
		-webkit-transition:300ms;
		-ms-transition:300ms;
		-o-transition:300ms;
		-moz-transition:300ms;
	}

	.footer .footer_qq_icon:hover{

		background-color:#F00;
	}

	.footer .about_me h4{
		margin:10px 0px 0px 44px;
		color:#FFF;
		font-size:14px;
		font-weight:normal;
	}

	.footer .about_me .about_me_text{
		margin-top:20px;
		margin-left:44px;
		font-size:14px;
		color:#CCC;
	}

	.footer .contact_us{
		float:left;
		margin-top:50px;
		padding-left:57px;
		border-left:1px #606060 solid;
		height:150px;
		color:#CCC;
		font-size:14px;
	}

	.footer .contact_us_text1{
		margin-top:6px;
		font-size:28px;
		color:#FFF;
	}

	.footer .contact_us_text2{
		margin-top:5px;
		font-size:12px;
	}

	.footer .contact_us_text3 span{
		float:left;
		line-height:31px;
	}

	.footer .contact_us_text3{
		margin-top:18px;
		display:block;
		color:#CCC;
	}

	.footer .contact_us_text3 i{
		display:block;
		float:left;
		margin-left:10px;
		width:32px;
		height:30px;
		cursor:pointer;
		border:1px #CCC solid;

		border-radius:16px;
		-webkit-border-radius:16px;
		-ms-border-radius:16px;
		-o-border-radius:16px;
		-moz-border-radius:16px;

		transition:300ms;
		-webkit-transition:300ms;
		-ms-transition:300ms;
		-o-transition:300ms;
		-moz-transition:300ms;

	}

	.footer .contact_us_text3 i:hover{
		border:1px #DB0015 solid;
		background-color:#DB0015;
	}

	.footer .bottom .text{
		float:left;
		margin-top:34px;
		color:#999;
		font-size:14px;
	}

	.footer .bottom .g{
		float:right;
		margin-right:10px;
	}

	.footer .bottom .g a{
		float:left;
		margin-left:20px;
		margin-top:24px;
		width:100px;
		height:36px;
	}
</style>
<footer id="footer" class="footer" style="padding: 0px 0px 20px 0px;">
	<section class="main">
		<div class="about_me">
			<div class="wx">
				<a href="javascript:" class="footer_wx_icon"><img src="/Upload/public/<?php echo ($C['footer_logo']); ?>"></a>
			</div>
		</div>
		<div class="layout_center">
			<?php if(is_array($footerArticleType)): $i = 0; $__LIST__ = $footerArticleType;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="list"><label><?php echo (get_lang_text($vo['title'],$vo['title_en'])); ?></label>
					<ul>
						<?php if(is_array($footerArticle[$vo['name']])): $i = 0; $__LIST__ = $footerArticle[$vo['name']];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vvo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Article/index',array('id'=>$vvo['id']));?>" style="overflow: hidden;"><?php echo ($vvo['title']); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
						<li><a href="<?php echo U('Article/index',array('id'=>$vo['id']));?>" style="overflow: hidden;    text-align: left;"><?php echo L('public.gengduo');?></a></li>
					</ul>
				</div><?php endforeach; endif; else: echo "" ;endif; ?>





			<div class="contact_us">
				<div class="contact_us_text0" style="text-align: left;"><?php echo L('public.mianfeizixun');?> :</div>
				<div class="contact_us_text1" style="text-align: left;margin-top: 10px;margin-bottom: 12px;"><?php echo C('contact_moble');?></div>
				<div class="contact_us_text2" style="text-align: left;margin-bottom: 5px;"><?php echo L('public.gongzuoshijian');?></div>
				<?php $_result=C('contact_qqun');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><a href="#" class="contact_us_text3"><span><?php echo L('public.huiyuanqunhao');?> :<?php echo ($i); echo L('public.qun');?>：<?php echo ($v); ?></span></a><?php endforeach; endif; else: echo "" ;endif; ?>
			</div>
		</div>
	</section>
</footer>
<div class="footer_bottom">
	<div class="autobox" style="height: 40px;margin-top: 5px;">
		<span style="display: inline-block;color:#A6A9AB;">CopyRight© 2013-2016 <?php echo ($C['web_name']); echo L('public.jiaoyipingtai');?> All Rights Reserved &nbsp;&nbsp;|&nbsp;&nbsp;<a href="http://www.miitbeian.gov.cn/publish/query/indexFirst.action" target="_blank"><?php echo ($C['web_icp']); ?></a><span style="display: inline-block; color:#A6A9AB"></span></span>
		<span style="float: right;">
			<a href="http://www.gov.cn/" target="_blank" class="margin10" style="margin-left:5px;"> <img src="/Upload/footer/footer_1.png">
			</a>
			<a href="http://www.szfw.org/" target="_blank" class="margin10" style="margin-left:5px;"> <img src="/Upload/footer/footer_2.png">
			</a>
			<a href="http://www.miibeian.gov.cn/" target="_blank" class="margin10" style="margin-left:5px;"><img src="/Upload/footer/footer_3.png">
			</a>
			<a href="http://www.cyberpolice.cn/" target="_blank" class="margin10" style="margin-left:5px;"><img src="/Upload/footer/footer_4.png">
			</a>
		</span>
	</div>
	<!-- 原安全验证位置 -->
</div>

<!--客服qq-->

<div id="all_mask" class="all_mask" style="height: 0px; display: none;"></div>
<div class="all_mask_loginbox" style="top: 313px; display: none;">
	<div class="login_title pl20"><?php echo L('public.denglu');?></div>
	<form id="form-login" class="mask_wrap login-fb">
		<div class="login_text zin90">
			<div class="mask_wrap_title"><?php echo L('public.zhanghao');?>：</div>
			<input id="login_username" name="username" type="text" placeholder="<?php echo L('public.shuruhuiyuanming');?>">
		</div>
		<div class="login_text zin80">
			<div class="mask_wrap_title"><?php echo L('public.mima');?>：</div>
			<input id="login_password" name="password" type="password" placeholder="<?php echo L('public.shurudenglumima');?>">
		</div>
		<?php if(($C['login_verify']) == "1"): ?><div class="login_text zin70" id="ga-box-i">
				<img id="codeImg reloadverifyindex" src="<?php echo U('Verify/code');?>" width="120" height="38" onclick="this.src=this.src+'?t='+Math.random()" style="margin-top: 1px; cursor: pointer;" title="<?php echo L('public.huanyizhang');?>">
				<input type="text" class="code" id="login_verify" name="code" placeholder="<?php echo L('public.shuruyanzhengma');?>" style="width: 106px; float: left;">
			</div><?php endif; ?>
		<div class="login_button">
			<input type="button" value="<?php echo L('public.denglu');?>" onclick="upLogin();">
		</div>
		<div class="login-footer wwxwwx" style="border-bottom-left-radius: 3px; border-bottom-right-radius: 3px;">
			<!--<a target="_blank" href="/"><img src="/Public/Home/images/qq2.png" style="vertical-align: text-bottom; padding-right: 5px;">zzQQ<?php echo L('public.denglu');?></a>-->

			<span style="color: #CCC; float: right; margin-right: 25px;">
			<a style="font-size: 12px;" href="<?php echo U('Login/register');?>"><?php echo L('public.mianfeizhuce');?></a>｜<a href="<?php echo U('Login/findpwd');?>" style="font-size: 12px;"><?php echo L('public.wangjimima');?></a></span>
		</div>
	</form>
	<div class="mask_wrap_close" onclick="wrapClose()"></div>
</div>
<script type="text/javascript" src="/Public/Home/js/jquery.cookies.2.2.0.js"></script>
<script>
	$('input').focus(function () {
		var t = $(this);
		if (t.attr('type') == 'text' || t.attr('type') == 'password')
			t.css({'box-shadow': '0px 0px 3px #1583fb', 'border': '1px solid #1583fb', 'color': '#333'});
		if (t.val() == t.attr('placeholder'))
			t.val('');
	});
	$('input').blur(function () {
		var t = $(this);
		if (t.attr('type') == 'text' || t.attr('type') == 'password')
			t.css({'box-shadow': 'none', 'border': '1px solid #e1e1e1', 'color': '#333'});
		if (t.attr('type') != 'password' && !t.val())
			t.val(t.attr('placeholder'));
	});


	function NumToStr(num) {
		if (!num) return num;
		num = Math.round(num * 100000000) / 100000000;
		num = num.toFixed(8);
		var min = 0.0001;
		var times = 0;
		var arr;
		if (num <= min) {
			times = 0;
			while (num <= min) {
				num *= 10;
				times++;
				if (times > 100) break;
			}
			num = num + '';
			arr = num.split(".");
			for (var i = 0; i < times; i++) {
				arr['1'] = '0' + arr['1'];
			}
			return arr[0] + '.' + arr['1'] + '';
		}
		return num.toFixed(8) + ' ';
	}


	function loginpop() {
		$('.all_mask').css({'height': $(document).height()});
		$('.all_mask').show();
		$('.all_mask_loginbox').show();
		$(".reloadverify").click();
	}

	var is_login = <?php echo (session('userId')); ?>
	;

	if (window.location.hash == '#login') {
		if (!is_login) {
			loginpop();
		}
	}

	if (is_login) {
		$.getJSON("/Ajax/allfinance?t=" + Math.random(), function (data) {

			$('#user_finance').html(data);
		});
	}


	function wrapClose() {
		$('.all_mask').hide();
		$('.all_mask_loginbox').hide();
	}

	var cookieValue = $.cookies.get('cookie_username');
	if (cookieValue != '' && cookieValue != null) {
		$("#login_username").val(cookieValue);
	}

	function upLogin() {
		var username = $("#login_username").val();
		var password = $("#login_password").val();
		var verify = $("#login_verify").val();
		if (username == "" || username == null) {
			layer.tips('<?php echo L('public.shuyonghuming');?>', '#login_username', {tips: 3});
			return false;
		}
		if (password == "" || password == null) {
			layer.tips('<?php echo L('public.shurudenglumima');?>', '#login_password', {tips: 3});
			return false;
		}

		$.post("<?php echo U('Login/submit');?>", {
			username: username,
			password: password,
			verify: verify,
		}, function (data) {
			if (data.status == 1) {
				$.cookies.set('cookie_username', username);
				layer.msg(data.info, {icon: 1});
				window.location = '/Finance';
			} else {
				//刷新验证码
				$(".reloadverifyindex").click();
				layer.msg(data.info, {icon: 2});
				if (data.url) {
					window.location = data.url;
				}
			}
		}, "json");
	}
</script></body></html>