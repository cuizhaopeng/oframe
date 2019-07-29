<?php

namespace app\admin\model;

use think\Model;
use think\Session;
use think\Db;

class OrderPacketCtns extends Model
{
	
	public static function getOrderPacketCtnsTable(&$option){
		$field[]='gyl_order_packet_ctns.id';
		$field[]='gyl_order_packet_ctns.parent_id';
		$field[]='gyl_order_packet_ctns.class';
		$field[]='gyl_order_packet_ctns.ctns_no';
		$field[]='gyl_order_packet_ctns.channel_id';
		$field[]='gyl_channel.channel_name';
		$field[]='gyl_channel.channel_code';
		$field[]='gyl_order_packet_ctns.country_code2';
		$field[]='gyl_country.name_zh as country_name';
		$field[]='gyl_order_packet_ctns.user_id';
		$field[]='gyl_user.user_name';
		$field[]='gyl_order_packet_ctns.action_time';
		$field[]='gyl_order_packet_ctns.create_time';
		$field[]='gyl_order_packet_ctns.status';
		$field[]='gyl_order_packet_ctns.finish';
		$field[]='gyl_order_packet_ctns.description';
		$field[]='gyl_order_packet_ctns.ctnmailbag';
		$field[]='gyl_order_packet_ctns.check_wgt';
		$field[]='gyl_order_packet_ctns.nwgt';
		$field[]='COUNT(gyl_order_packet.id) as item_qty';
		$field[]='COALESCE(sum(gyl_order_packet.cwgt),0) as item_cwgt';
		$field[]='gyl_order_packet_ctns.company_id';
		$field[]='gyl_company.company_name';
		$join[]=array('gyl_country','gyl_country.country_code2=gyl_order_packet_ctns.country_code2','LEFT');
		$join[]=array('gyl_channel','gyl_channel.id=gyl_order_packet_ctns.channel_id','LEFT');
		$join[]=array('gyl_user','gyl_user.id=gyl_order_packet_ctns.user_id','LEFT');
		$join[]=array('gyl_company','gyl_company.id=gyl_order_packet_ctns.company_id','LEFT');

		if($option['flag']=="1"){
			$join[]=array('gyl_order_packet','gyl_order_packet.ctns_sub_id=gyl_order_packet_ctns.id','LEFT');
		}else{
			$join[]=array('gyl_order_packet','gyl_order_packet.ctns_id=gyl_order_packet_ctns.id','LEFT');
		}
		
		$condition=$option['condition'];

		foreach ($condition as $k => $v) {
			switch ($k) {
				case 'client_id':
					is_numeric($v) && $map['gyl_order_packet_ctns.client_id']=array('eq', $v);
					break;
				case 'ctns_no':
					!empty($v) && $map['gyl_order_packet_ctns.ctns_no']=array('eq', $v);
					break;
				case 'ctn_list':
					!empty($v) && $map['gyl_order_packet_ctns.ctns_no']=array('in', $v);
					break;
				case 'description_list':
					!empty($v) && $map['gyl_order_packet_ctns.ctnmailbag']=array('in', $v);
					break;
				case 'channel_id':
					!empty($v) && $map['gyl_order_packet_ctns.channel_id']=array('eq', $v);
					break;
				case 'country_code2':
					!empty($v) && $map['gyl_order_packet_ctns.country_code2']=array('eq', $v);
					break;
				case 'action_time':
				    is_array($v) && $map['gyl_order_packet_ctns.action_time']=['between',$v];
				    break;
				case 'create_time':
				    is_array($v) && $map['gyl_order_packet_ctns.create_time']=['between',$v];
				    break;	
				case 'finish':
					is_numeric($v) && $map['gyl_order_packet_ctns.finish']=array('eq', $v);
					break;
				case 'status':
					is_numeric($v) && $map['gyl_order_packet_ctns.status']=array('eq', $v);
					break;
				case 'ids':
					!empty($v) && $map['gyl_order_packet_ctns.del']=array('in', $v);
					break;
				case 'id_list':
					!empty($v) && $map['gyl_order_packet_ctns.id']=array('in', $v);
					break;
				case 'item_qty':
					is_numeric($v) && $having[]="item_qty=0";
					break;
				case 'class':

					is_numeric($v) && $map['gyl_order_packet_ctns.class']=array('eq',$v);
					break;
				case 'company_id':
					is_numeric($v) && $v>0 && $map['gyl_order_packet_ctns.company_id']=array('eq',$v);
					break;
				case 'parent_id':
					is_numeric($v) && $map['gyl_order_packet_ctns.parent_id']=array('eq',$v);
					break;
				default:
					# code...
					break;
			}
		}

		if(isset($map['gyl_order_packet_ctns.ctns_no']) ||isset($map['gyl_order_packet_ctns.ctnmailbag'])){
			unset($map['gyl_order_packet_ctns.create_time']);
			unset($map['gyl_order_packet_ctns.action_time']);
		}

        if(session('user_agent')){
            $map['gyl_order_packet_ctns.company_id']=10;
        }

		$map['gyl_order_packet_ctns.del']=0;
		$group="gyl_order_packet_ctns.id";
		$order="gyl_order_packet_ctns.create_time desc";
		if(1==count($condition)&&(isset($condition['id'])||isset($condition['ctns_no']))){


			isset($condition['id'])&&$map_one['gyl_order_packet_ctns.id']=$condition['id'];
			isset($condition['ctns_no'])&&$map_one['gyl_order_packet_ctns.ctns_no']=$condition['ctns_no'];


			$orderInfo=Db::name("order_packet_ctns")->find(function($query)use($field,$join,$map_one,$order,$group,$having){
				$query->field($field);
				$query->join($join);
				$query->where($map_one);
				$query->order($order);
				$query->group($group);
				isset($having)&&$query->having(implode(" and ", $having));
			});



        return $orderInfo;
    }

		if(isset($option['page'])){
			$page=$option['page'];
			$orderTable=Db::name("order_packet_ctns")->select(function($query)use($field,$join,$map,$page,$order,$group,$having){
				$query->field($field);
				$query->join($join);
				$query->where($map);
				$query->order($order);
				$query->group($group);
				isset($having)&&$query->having(implode(" and ", $having));
				$query->limit(($page['pageNum']-1)*$page['numPerPage'],$page['numPerPage']);
			});
			$option['sql']=db()->getLastSql();

			$totalCount=Db::name("order_packet_ctns")->where($map)->count();
			$option['page']['totalCount']=$totalCount;
		}else{
			$orderTable=Db::name("order_packet_ctns")->select(function($query)use($field,$join,$map,$order,$group,$having){
				$query->field($field);
				$query->join($join);
				$query->where($map);
				$query->order($order);
				$query->group($group);
				isset($having)&&$query->having(implode(" and ", $having));
			});
		}

		// dump(Db::getLastSql());
		return $orderTable;
	}


}