<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/8
 * Time: 16:01
 */

namespace app\admin\controller;

use app\admin\model\Menu as MenuModel;
use app\lib\exception\ErrorMessage;
use app\lib\exception\SuccessMessage;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Html;
use think\Db;
use think\facade\Request;
use app\admin\validate\Menu as MenuValidate;
class Menu extends Base
{
    private $menuModel;
    public function __construct()
    {
        parent::__construct();
        $this->menuModel = new MenuModel();
    }

    public function initAuth()
    {
        $this->setMenuAuth("菜单权限");
        $this->setApiAuth("添加菜单","add");
        $this->setApiAuth("编辑菜单","edit");
        $this->setApiAuth("删除菜单","del");
        $this->setApiAuth("查询菜单","select");
    }

    // 菜单页显示
    public function index()
    {

//        halt($res);
        $menuList = [[ //菜单数据，key名可以通过 config.js 去重新配置
                    "name"=>"component" //一级菜单名称（与视图的文件夹名称和路由路径对应）
                    ,"title"=>"组件" //一级菜单标题
                    ,"id"=>12 // 菜单id
                    ,"icon"=>"layui-icon-component" //一级菜单图标样式
                    ,"jump"=>'' //自定义一级菜单路由地址，默认按照 name 解析。一旦设置，将优先按照 jump 设定的路由跳转
                    ,"spread"=>false //是否默认展子菜单（1.0.0-beta9 新增）
                    ,"list"=> [
                        [ //二级菜单
                            "name"=> "grid" //二级菜单名称（与视图的文件夹名称和路由路径对应）
                            ,"title"=> "栅格" //二级菜单标题
                            ,"jump"=> ''  //自定义二级菜单路由地址
                            ,"spread"=> false //是否默认展子菜单（1.0.0-beta9 新增）
                            ,"list"=> [
                                [ //三级菜单
                                    "name"=> "list" //三级菜单名（与视图中最终的文件名和路由对应），如：component/grid/list
                                    ,"title"=> "等比例列表排列" //三级菜单标题
                                ],[
                                "name"=> "mobile"
                                ,"title"=> "按移动端排列"
                                ]
                            ]
                        ]
                    ]
                ]];
        // 获取菜单结构
//        $menuList = [];
        $this->assign('menuList',$menuList);
        return $this->fetch();
    }
    public function selectMenu()
    {
        $res = (new MenuModel())->select();
        if ($res) return new SuccessMessage(['data'=>$res]);
        return new ErrorMessage();
        $data = [
            "code"=>0
            ,"msg"=>"" //提示信息
            ,"data"=>[[ //菜单数据，key名可以通过 config.js 去重新配置
                "name"=>"component" //一级菜单名称（与视图的文件夹名称和路由路径对应）
                ,"id"=>"ID" // 菜单id
                ,"pid"=>0 // 菜单id
                ,"title"=>"组件" //一级菜单标题
                ,"icon"=>"layui-icon-component" //一级菜单图标样式
                ,"jump"=>'' //自定义一级菜单路由地址，默认按照 name 解析。一旦设置，将优先按照 jump 设定的路由跳转
                ,"spread"=>false //是否默认展子菜单（1.0.0-beta9 新增）
                ,"list"=> [
                    [ //二级菜单
                        "name"=> "grid" //二级菜单名称（与视图的文件夹名称和路由路径对应）
                        ,"title"=> "栅格" //二级菜单标题
                        ,"jump"=> ''  //自定义二级菜单路由地址
                        ,"spread"=> false //是否默认展子菜单（1.0.0-beta9 新增）
                        ,"list"=> [
                            [ //三级菜单
                                "name"=> "list" //三级菜单名（与视图中最终的文件名和路由对应），如：component/grid/list
                                ,"title"=> "等比例列表排列" //三级菜单标题
                            ],[
                                "name"=> "mobile"
                                ,"title"=> "按移动端排列"
                            ]
                        ]
                    ]
                ]
            ],[ //菜单数据，key名可以通过 config.js 去重新配置
                "name"=>"component" //一级菜单名称（与视图的文件夹名称和路由路径对应）
                ,"id"=>"I5D" // 菜单id
                ,"pid"=>0 // 菜单id
                ,"title"=>"组t件" //一级菜单标题
                ,"icon"=>"layui-icon-component" //一级菜单图标样式
                ,"jump"=>'' //自定义一级菜单路由地址，默认按照 name 解析。一旦设置，将优先按照 jump 设定的路由跳转
                ,"spread"=>false //是否默认展子菜单（1.0.0-beta9 新增）
                ,"list"=> [
                    [ //二级菜单
                        "name"=> "grid" //二级菜单名称（与视图的文件夹名称和路由路径对应）
                        ,"title"=> "栅格" //二级菜单标题
                        ,"jump"=> ''  //自定义二级菜单路由地址
                        ,"spread"=> false //是否默认展子菜单（1.0.0-beta9 新增）
                        ,"list"=> [
                        [ //三级菜单
                            "name"=> "list" //三级菜单名（与视图中最终的文件名和路由对应），如：component/grid/list
                            ,"title"=> "等比例列表排列" //三级菜单标题
                        ],[
                            "name"=> "mobile"
                            ,"title"=> "按移动端排列"
                        ]
                    ]
                    ]
                ]
            ]]
        ];


        return json($data);
    }


    // 上传
    public function update()
    {

    }
    // 添加菜单项
    public function add()
    {

        // 初始化验证类
        $menuValidate = new MenuValidate();

        // 验证请求类型  get:访问添加菜单页面，post:添加一个菜单项
        if ($menuValidate->method('','GET')) return $this->fetch();
//        halt($_POST['title']);
        // 接收验证参数
        $params = $menuValidate->goCheck();

        // 组合数据

        // 写入数据库
        $res = (new MenuModel())->save($params);
        if ($res) return new SuccessMessage();
        return new ErrorMessage();
    }

    // 创建excel表  Spreadsheet
    public function phpExcel()
    {
        // 校验数据
        //if (empty($sTime) || empty($eTime)) return '起始时间和结束时间不能为空';
        //if (empty($clientID)) return '客户名称不能为空';

        $spreadsheet = $this->bill();
        if ($spreadsheet['code']===1) return json($spreadsheet);
        if ($spreadsheet['code']) return json($spreadsheet);
        $filename = '对账单.xls';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        $writers = IOFactory::createWriter($spreadsheet['data'], 'Xls');
        //注意createWriter($spreadsheet, 'Xls') 第二个参数首字母必须大写
        ob_start();
        $writers->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();
        //if (empty($getData['time_start']) || empty($getData['time_end'])) return

        $data = [
            "code"=>0
            ,"msg"=>"成功！!!!!!" //提示信息
            ,"data"=>[
                'url'=>'http://admin.zero.com/05featuredemo.htm',
                'data'=> ['filename' => $filename, 'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)]
            ]
        ];
        return json($data);
    }
    public function phpExcelHtml()
    {
        // 校验数据
        //if (empty($sTime) || empty($eTime)) return '起始时间和结束时间不能为空';
        //if (empty($clientID)) return '客户名称不能为空';

        $spreadsheet = $this->bill();
        if ($spreadsheet['code']===1) return json($spreadsheet);
        $writeHtml = new Html($spreadsheet['data']);
        $writeHtml->save("05featuredemo.htm");
        //if (empty($getData['time_start']) || empty($getData['time_end'])) return

        $data = [
            "code"=>0
            ,"msg"=>"成功！!!!!!" //提示信息
            ,"data"=>[
                'url'=>'http://oms.gyang.net/05featuredemo.htm'
            ]
        ];
        return json($data);

    }

    public function bill()
    {
        $clientID = Request::param('client_id');
        $searchTime =[
            'time_start' => Request::param('time_start'),
            'time_end' => Request::param('time_end'),
        ];
        $sTime =$searchTime['time_start'].' 00:00:00';
        $eTime =$searchTime['time_end'].' 23:59:59';
        $sql = "select from_unixtime(gyl_client_total_offer.action_time, '%Y-%m-%d') as 'time',gyl_client.client_name,".
            "gyl_client.client_code,gyl_order_packet.order_no,gyl_user.user_name,gyl_order_packet.refer_no,gyl_order_packet.tracking_no,gyl_channel.channel_code,".
            "gyl_channel.channel_name,gyl_order_packet.country_code2,gyl_country.name_zh,gyl_order_packet.cwgt,gyl_client_offer.price_r from gyl_order_packet ".
            "left join gyl_client_offer on gyl_client_offer.order_id=gyl_order_packet.id ".
            "left join gyl_client on  gyl_client.id=gyl_order_packet.client_id ".
            "left join gyl_channel  on gyl_channel.id=gyl_order_packet.channel_id ".
            "left join gyl_user on gyl_user.id=gyl_client.user_id ".
            "left join gyl_country on gyl_country.country_code2=gyl_order_packet.country_code2 ".
            "left join gyl_client_total_offer on gyl_client_total_offer.id=gyl_client_offer.total_id ".
            "where gyl_order_packet.client_id={$clientID} and gyl_client_total_offer.action_time between unix_timestamp('{$sTime}') and unix_timestamp('{$eTime}')";

        $resDate = Db::query($sql);

        if (empty($resDate)){
            $data = [
                "code"=>1
                ,"msg"=>"没有找到该用户信息！" //提示信息
                ,"data"=>[
                    'url'=>'http://admin.zero.com/05featuredemo.htm'
                ]
            ];
            return $data;
        }

        $arrValueName = array_group_by($resDate,'channel_name','time');

        if (!empty($arrValueName)) {
            foreach ($arrValueName as $ke => $arrValueTime) {
                $number = 0;$weight = 0;$freight = 0;
                foreach ($arrValueTime as $k=>$value) {
                    $number = 0;$weight = 0;$freight = 0;
                    foreach($value as $item){
                        $number += 1;
                        $weight += (double)$item['cwgt'];
                        $freight += (double)$item['price_r'];
                    }
                    $arrRes[$ke][$k]['time'] = $k;
                    $arrRes[$ke][$k]['numbers'] = $number;
                    $arrRes[$ke][$k]['weight'] = $weight;
                    $arrRes[$ke][$k]['freight'] = $freight;
                }
            }
        }
        // 实例化
        $spreadsheet = new Spreadsheet();

        $worksheet = $spreadsheet->getActiveSheet();
        // 工作表标题
        $spreadsheet->getActiveSheet()->setTitle('汇总');
        $spreadsheet->createSheet()->setTitle('明细');
        // 设置列宽
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(22);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(22);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(22);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(22);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(22);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(22);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(22);

        // 设置行高
        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(28.45);
        $spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(28.45);
        $spreadsheet->getActiveSheet()->getRowDimension('3')->setRowHeight(28.45);
        $spreadsheet->getActiveSheet()->getRowDimension('4')->setRowHeight(28.45);
        $spreadsheet->getActiveSheet()->getRowDimension('5')->setRowHeight(28.45);
        $spreadsheet->getActiveSheet()->getRowDimension('6')->setRowHeight(28.45);
        $spreadsheet->getActiveSheet()->getRowDimension('7')->setRowHeight(28.45);
        $spreadsheet->getActiveSheet()->getRowDimension('8')->setRowHeight(28.45);
        $spreadsheet->getActiveSheet()->getRowDimension('9')->setRowHeight(28.45);
        // 合并
        $spreadsheet->getActiveSheet()->mergeCells('A1:G1');
        $spreadsheet->getActiveSheet()->mergeCells('A2:G2');
        $spreadsheet->getActiveSheet()->mergeCells('A3:G3');
        $spreadsheet->getActiveSheet()->mergeCells('A4:G4');
        $spreadsheet->getActiveSheet()->mergeCells('A5:G5');
        $spreadsheet->getActiveSheet()->mergeCells('B6:C6');
        $spreadsheet->getActiveSheet()->mergeCells('E6:G6');
        // 设置固定内容
        $worksheet->getCell('A1')->setValue('深圳市国洋运通国际物流有限公司');
        $worksheet->getCell('A2')->setValue('http://www.gyang.net');
        $worksheet->getCell('A3')->setValue('公司地址：深圳市龙岗区平湖街道新木村占米岭工业园B区3栋');
        $worksheet->getCell('A4')->setValue('电话：0755-29189969                               传真：0755-29406116');
        // 设置带有变量的内容

        $sTime=$searchTime['time_start'];
        $eTime=$searchTime['time_end'];
        $valueTime = "{$sTime}至{$eTime}小包对账单";
        $worksheet->getCell('A5')->setValue($valueTime);
        $worksheet->getCell('A6')->setValue('客户代码：');

        $userCode = $resDate[0]['client_code'];
        $worksheet->getCell('B6')->setValue($userCode);
        $worksheet->getCell('D6')->setValue('客户名称：');
        $userName = $resDate[0]['client_name'];
        $worksheet->getCell('E6')->setValue($userName);

        $worksheet->getCell('A7')->setValue('渠道名称');
        $worksheet->getCell('B7')->setValue('日期');
        $worksheet->getCell('C7')->setValue('件数');
        $worksheet->getCell('D7')->setValue('重量');
        $worksheet->getCell('E7')->setValue('运费');
        $worksheet->getCell('F7')->setValue('已收');
        $worksheet->getCell('G7')->setValue('未收');
        $worksheet->getCell('A8')->setValue('前期结余');
        $worksheet->getCell('G8')->setValue('￥0.00');
        // 居中对齐
        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '#000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ]
        ];
        $styleArrayLine = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ]
        ];
        $worksheet->getStyle('A1')->applyFromArray($styleArrayLine);
        $worksheet->getStyle('A2')->applyFromArray($styleArrayLine);
        $worksheet->getStyle('A3')->applyFromArray($styleArrayLine);
        $worksheet->getStyle('A4')->applyFromArray($styleArrayLine);
        $worksheet->getStyle('A5')->applyFromArray($styleArrayLine);
        //$worksheet->getStyle( 'A1:E1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('00CCFFCC');
        //$worksheet->getStyle( 'A1:E1')->getFill(
        for ($i=6;$i<10;$i++){
            $worksheet->getStyle("A{$i}")->applyFromArray($styleArray);
            $worksheet->getStyle("B{$i}")->applyFromArray($styleArray);
            $worksheet->getStyle("C{$i}")->applyFromArray($styleArray);
            $worksheet->getStyle("D{$i}")->applyFromArray($styleArray);
            $worksheet->getStyle("E{$i}")->applyFromArray($styleArray);
            $worksheet->getStyle("F{$i}")->applyFromArray($styleArray);
            $worksheet->getStyle("G{$i}")->applyFromArray($styleArray);
        }
        // 设置字体
        $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setName('SimSun')->setSize(17);
        $spreadsheet->getActiveSheet()->getStyle('A2')->getFont()->setBold(true)->setName('SimSun')->setSize(14);
        $spreadsheet->getActiveSheet()->getStyle('A3')->getFont()->setBold(true)->setName('SimSun')->setSize(14);
        $spreadsheet->getActiveSheet()->getStyle('A4')->getFont()->setBold(true)->setName('SimSun')->setSize(14);
        $spreadsheet->getActiveSheet()->getStyle('A5')->getFont()->setBold(true)->setName('SimSun')->setSize(14);
        $spreadsheet->getActiveSheet()->getStyle('A6')->getFont()->setBold(true)->setName('SimSun')->setSize(11);
        $spreadsheet->getActiveSheet()->getStyle('B6')->getFont()->setBold(true)->setName('SimSun')->setSize(11);
        $spreadsheet->getActiveSheet()->getStyle('D6')->getFont()->setBold(true)->setName('SimSun')->setSize(11);
        $spreadsheet->getActiveSheet()->getStyle('E6')->getFont()->setBold(true)->setName('SimSun')->setSize(11);
        // 第七行
        $spreadsheet->getActiveSheet()->getStyle('A7')->getFont()->setBold(true)->setName('SimSun')->setSize(10);
        $spreadsheet->getActiveSheet()->getStyle('B7')->getFont()->setBold(true)->setName('SimSun')->setSize(10);
        $spreadsheet->getActiveSheet()->getStyle('C7')->getFont()->setBold(true)->setName('SimSun')->setSize(10);
        $spreadsheet->getActiveSheet()->getStyle('D7')->getFont()->setBold(true)->setName('SimSun')->setSize(10);
        $spreadsheet->getActiveSheet()->getStyle('E7')->getFont()->setBold(true)->setName('SimSun')->setSize(10);
        $spreadsheet->getActiveSheet()->getStyle('F7')->getFont()->setBold(true)->setName('SimSun')->setSize(10);
        $spreadsheet->getActiveSheet()->getStyle('G7')->getFont()->setBold(true)->setName('SimSun')->setSize(10);
        $spreadsheet->getActiveSheet()->getStyle('A8')->getFont()->setBold(true)->setName('SimSun')->setSize(10);
        $spreadsheet->getActiveSheet()->getStyle('G8')->getFont()->setBold(true)->setName('SimSun')->setSize(10);

        $num3=$num2=$num = 10;
        foreach ($arrRes as $keyy=>$res)
        {
            //halt($keyy);
            foreach ($res as $item) {
                // 设置字体
                $spreadsheet->getActiveSheet()->getStyle("B{$num}")->getFont()->setName('SimSun')->setSize(10);
                $spreadsheet->getActiveSheet()->getStyle("C{$num}")->getFont()->setName('SimSun')->setSize(10);
                $spreadsheet->getActiveSheet()->getStyle("D{$num}")->getFont()->setName('SimSun')->setSize(10);
                $spreadsheet->getActiveSheet()->getStyle("E{$num}")->getFont()->setName('SimSun')->setSize(10);
                // 设置内容
                $worksheet->getCell("B{$num}")->setValue($item['time']);
                $worksheet->getCell("C{$num}")->setValue($item['numbers']);
                $worksheet->getCell("D{$num}")->setValue($item['weight']);
                $worksheet->getCell("E{$num}")->setValue($item['freight']);
                // 设置行高
                $spreadsheet->getActiveSheet()->getRowDimension("{$num}")->setRowHeight(28.45);
                // 设置居中对齐
                $worksheet->getStyle("A{$num}")->applyFromArray($styleArray);
                $worksheet->getStyle("B{$num}")->applyFromArray($styleArray);
                $worksheet->getStyle("C{$num}")->applyFromArray($styleArray);
                $worksheet->getStyle("D{$num}")->applyFromArray($styleArray);
                $worksheet->getStyle("E{$num}")->applyFromArray($styleArray);
                $worksheet->getStyle("F{$num}")->applyFromArray($styleArray);
                $worksheet->getStyle("G{$num}")->applyFromArray($styleArray);
                $num++;
            }
            $sNum = $num-1;
            // 小计
            $spreadsheet->getActiveSheet()->getStyle("B{$num}")->getFont()->setBold(true)->setName('SimSun')->setSize(10);
            $spreadsheet->getActiveSheet()->getStyle("B{$num}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('00CCFFCC');
            $spreadsheet->getActiveSheet()->getStyle("C{$num}")->getFont()->setBold(true)->setName('SimSun')->setSize(10);
            $spreadsheet->getActiveSheet()->getStyle("C{$num}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('00CCFFCC');
            $spreadsheet->getActiveSheet()->getStyle("D{$num}")->getFont()->setBold(true)->setName('SimSun')->setSize(10);
            $spreadsheet->getActiveSheet()->getStyle("D{$num}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('00CCFFCC');
            $spreadsheet->getActiveSheet()->getStyle("E{$num}")->getFont()->setBold(true)->setName('SimSun')->setSize(10);
            $spreadsheet->getActiveSheet()->getStyle("E{$num}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('00CCFFCC');
            $spreadsheet->getActiveSheet()->getStyle("F{$num}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('00CCFFCC');
            $spreadsheet->getActiveSheet()->getStyle("G{$num}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('00CCFFCC');

            // 设置内容
            $worksheet->getCell("B{$num}")->setValue('小计');
            $worksheet->getCell("C{$num}")->setValue("=SUM(C{$num2}:C{$sNum})");
            $worksheet->getCell("D{$num}")->setValue("=SUM(D{$num2}:D{$sNum})");
            $worksheet->getCell("E{$num}")->setValue("=SUM(E{$num2}:E{$sNum})");
            // 设置行高
            $spreadsheet->getActiveSheet()->getRowDimension("{$num}")->setRowHeight(28.45);
            // 设置居中对齐
            $worksheet->getStyle("A{$num}")->applyFromArray($styleArray);
            $worksheet->getStyle("B{$num}")->applyFromArray($styleArray);
            $worksheet->getStyle("C{$num}")->applyFromArray($styleArray);
            $worksheet->getStyle("D{$num}")->applyFromArray($styleArray);
            $worksheet->getStyle("E{$num}")->applyFromArray($styleArray);
            $worksheet->getStyle("F{$num}")->applyFromArray($styleArray);
            $worksheet->getStyle("G{$num}")->applyFromArray($styleArray);
            // 合并

            $spreadsheet->getActiveSheet()->mergeCells("A{$num2}:A{$num}");
            // 设置内容
            $worksheet->getCell("A{$num2}")->setValue("{$keyy}");
            // 设置字体
            $spreadsheet->getActiveSheet()->getStyle("A{$num2}")->getFont()->setBold(true)->setName('SimSun')->setSize(10);
            // 设置居中对齐
            $worksheet->getStyle("A{$num2}")->applyFromArray($styleArray);
            $num2 = ++$num;
        }
        $sNum2 = $num-1;
        $spreadsheet->getActiveSheet()->getStyle("B{$num}")->getFont()->setBold(true)->setName('SimSun')->setSize(10);
        $spreadsheet->getActiveSheet()->getStyle("C{$num}")->getFont()->setBold(true)->setName('SimSun')->setSize(10);
        $spreadsheet->getActiveSheet()->getStyle("D{$num}")->getFont()->setBold(true)->setName('SimSun')->setSize(10);
        $spreadsheet->getActiveSheet()->getStyle("E{$num}")->getFont()->setBold(true)->setName('SimSun')->setSize(10);

        // 设置内容
        $worksheet->getCell("A{$num}")->setValue("本期合计");
        $worksheet->getCell("C{$num}")->setValue("=SUM(C{$num3}:C{$sNum2})/2");
        $worksheet->getCell("D{$num}")->setValue("=SUM(D{$num3}:D{$sNum2})/2");

        // 设置居中对齐
        $worksheet->getCell("E{$num}")->setValue("=SUM(E{$num3}:E{$sNum2})/2");
        // 设置行高
        $spreadsheet->getActiveSheet()->getRowDimension("{$num}")->setRowHeight(28.45);

        $worksheet->getStyle("A{$num}")->applyFromArray($styleArray);

        $worksheet->getStyle("B{$num}")->applyFromArray($styleArray);
        $worksheet->getStyle("C{$num}")->applyFromArray($styleArray);
        $worksheet->getStyle("D{$num}")->applyFromArray($styleArray);
        $worksheet->getStyle("E{$num}")->applyFromArray($styleArray);
        $worksheet->getStyle("F{$num}")->applyFromArray($styleArray);
        $worksheet->getStyle("G{$num}")->applyFromArray($styleArray);
        // 合并
        $spreadsheet->getActiveSheet()->mergeCells("A{$num}:B{$num}");

        // 设置字体
        $spreadsheet->getActiveSheet()->getStyle("A{$num}")->getFont()->setBold(true)->setName('SimSun')->setSize(10);

        $spreadsheet->getActiveSheet()->getStyle("A{$num}")->getFont()->setBold(true)->setName('SimSun')->setSize(10);
        $spreadsheet->getActiveSheet()->getStyle("C{$num}")->getFont()->setBold(true)->setName('SimSun')->setSize(10);
        $spreadsheet->getActiveSheet()->getStyle("D{$num}")->getFont()->setBold(true)->setName('SimSun')->setSize(10);
        $spreadsheet->getActiveSheet()->getStyle("E{$num}")->getFont()->setBold(true)->setName('SimSun')->setSize(10);

        $spreadsheet->getActiveSheet()->getStyle("A{$num}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('00C0C0C0');
        $spreadsheet->getActiveSheet()->getStyle("B{$num}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('00C0C0C0');
        $spreadsheet->getActiveSheet()->getStyle("C{$num}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('00C0C0C0');
        $spreadsheet->getActiveSheet()->getStyle("D{$num}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('00C0C0C0');
        $spreadsheet->getActiveSheet()->getStyle("E{$num}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('00C0C0C0');
        $spreadsheet->getActiveSheet()->getStyle("F{$num}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('00C0C0C0');
        $spreadsheet->getActiveSheet()->getStyle("G{$num}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('00C0C0C0');

        // 设置内容
        $worksheet->getCell("A{$num}")->setValue("本期合计");
        $worksheet->getCell("C{$num}")->setValue("=SUM(C{$num3}:C{$sNum2})/2");
        $worksheet->getCell("D{$num}")->setValue("=SUM(D{$num3}:D{$sNum2})/2");
        $worksheet->getCell("E{$num}")->setValue("=SUM(E{$num3}:E{$sNum2})/2");
        // 设置居中对齐

        // 设置行高
        $spreadsheet->getActiveSheet()->getRowDimension("{$num}")->setRowHeight(28.45);

        $worksheet->getStyle("A{$num}")->applyFromArray($styleArray);
        $worksheet->getStyle("B{$num}")->applyFromArray($styleArray);
        $worksheet->getStyle("C{$num}")->applyFromArray($styleArray);
        $worksheet->getStyle("D{$num}")->applyFromArray($styleArray);
        $worksheet->getStyle("E{$num}")->applyFromArray($styleArray);
        $worksheet->getStyle("F{$num}")->applyFromArray($styleArray);
        $worksheet->getStyle("G{$num}")->applyFromArray($styleArray);
        $num++;
        // 设置行高
        $spreadsheet->getActiveSheet()->getRowDimension("{$num}")->setRowHeight(28.45);
        // 设置居中对齐
        $worksheet->getStyle("A{$num}")->applyFromArray($styleArray);
        $worksheet->getStyle("B{$num}")->applyFromArray($styleArray);
        $worksheet->getStyle("C{$num}")->applyFromArray($styleArray);
        $worksheet->getStyle("D{$num}")->applyFromArray($styleArray);
        $worksheet->getStyle("E{$num}")->applyFromArray($styleArray);
        $worksheet->getStyle("F{$num}")->applyFromArray($styleArray);
        $worksheet->getStyle("G{$num}")->applyFromArray($styleArray);

        // 设置字体
        $spreadsheet->getActiveSheet()->getStyle("A{$num}")->getFont()->setBold(true)->setName('SimSun')->setSize(10);
        $spreadsheet->getActiveSheet()->getStyle("G{$num}")->getFont()->setBold(true)->setName('SimSun')->setSize(10);
        // 设置内容
        $worksheet->getCell("A{$num}")->setValue("未收合计");
        $worksheet->getCell("G{$num}")->setValue("=ROUND(SUM(E{$num3}:E{$sNum2})/2,2)");
        $num=$num+1;
        for ($i=$num;$i<$num+21;$i++)
        {
            // 设置行高
            $spreadsheet->getActiveSheet()->getRowDimension("{$i}")->setRowHeight(28.45);
            // 设置内容
            switch ($i) {
                case $num+1:
                    $worksheet->getCell("A{$i}")->setValue("我司收款银行信息如下：");
                    $spreadsheet->getActiveSheet()->mergeCells("A{$i}:C{$i}");
                    break;
                case $num+2:
                    $worksheet->getCell("A{$i}")->setValue("国洋运通对私账户：");
                    $worksheet->getCell("E{$i}")->setValue("国洋运通对公帐号(农业银行)：");
                    // 合并
                    $spreadsheet->getActiveSheet()->mergeCells("E{$i}:G{$i}");
                    break;
                case $num+3:
                    $worksheet->getCell("A{$i}")->setValue("户名：国辉：");
                    $worksheet->getCell("E{$i}")->setValue("开户名:深圳市国洋运通国际物流有限公司");
                    $spreadsheet->getActiveSheet()->mergeCells("E{$i}:G{$i}");
                    break;
                case $num+4:
                    $worksheet->getCell("A{$i}")->setValue("卡号：6226096555512618");
                    $worksheet->getCell("E{$i}")->setValue("英文名:Shenzhen China GYL international logistics Co.,LTD");
                    $spreadsheet->getActiveSheet()->mergeCells("A{$i}:B{$i}");
                    $spreadsheet->getActiveSheet()->mergeCells("E{$i}:G{$i}");
                    break;
                case $num+5:
                    $worksheet->getCell("A{$i}")->setValue("开户行：招商银行深圳分行龙华支行");
                    $worksheet->getCell("E{$i}")->setValue("公司地址:深圳市龙岗区平湖街道新木村占米岭工业区B区3号");
                    $spreadsheet->getActiveSheet()->mergeCells("A{$i}:B{$i}");
                    $spreadsheet->getActiveSheet()->mergeCells("E{$i}:G{$i}");
                    break;
                case $num+6:
                    $worksheet->getCell("E{$i}")->setValue("开户行地址:中国农业银行股份有限公司深圳八卦岭支行");
                    $spreadsheet->getActiveSheet()->mergeCells("E{$i}:G{$i}");
                    break;
                case $num+7:
                    $worksheet->getCell("A{$i}")->setValue("国洋运通对公帐号(建设银行)");
                    $spreadsheet->getActiveSheet()->mergeCells("A{$i}:C{$i}");
                    $worksheet->getCell("E{$i}")->setValue("RMB账户:   4100    4800    0400    28671");
                    $spreadsheet->getActiveSheet()->mergeCells("E{$i}:G{$i}");
                    break;
                case $num+8:
                    $worksheet->getCell("A{$i}")->setValue("公司名称：深圳市国洋运通国际物流有限公司");
                    $worksheet->getCell("E{$i}")->setValue("GBP账户:   4100    4800    0400    28721");
                    $spreadsheet->getActiveSheet()->mergeCells("A{$i}:C{$i}");
                    $spreadsheet->getActiveSheet()->mergeCells("E{$i}:G{$i}");
                    break;
                case $num+9:
                    $worksheet->getCell("A{$i}")->setValue("开户行地址：中国建设银行股份有限公司深圳市水榭春天支行");
                    $worksheet->getCell("E{$i}")->setValue("HKD账户:   4100    4800    0400    28713");
                    $spreadsheet->getActiveSheet()->mergeCells("A{$i}:C{$i}");
                    $spreadsheet->getActiveSheet()->mergeCells("E{$i}:G{$i}");
                    break;
                case $num+10:
                    $worksheet->getCell("A{$i}")->setValue("账号（RMB)：44201016900052502825");
                    $worksheet->getCell("E{$i}")->setValue("EUR账户:   4100    4800    0400    28689");
                    $spreadsheet->getActiveSheet()->mergeCells("A{$i}:C{$i}");
                    $spreadsheet->getActiveSheet()->mergeCells("E{$i}:G{$i}");
                    break;
                case $num+11:
                    $worksheet->getCell("E{$i}")->setValue("USD账户:   4100    4800    0400    28705");
                    $spreadsheet->getActiveSheet()->mergeCells("E{$i}:G{$i}");
                    break;
                case $num+12:
                    $worksheet->getCell("A{$i}")->setValue("对公支付宝账户：");
                    $worksheet->getCell("E{$i}")->setValue("JPY账户：  4100    4800    0400    28697");
                    $spreadsheet->getActiveSheet()->mergeCells("E{$i}:G{$i}");
                    break;
                case $num+13:
                    $worksheet->getCell("A{$i}")->setValue("开户名：深圳市国洋运通国际物流有限公司");
                    $worksheet->getCell("E{$i}")->setValue("SWIFT CODE:      ABOCCNBJ410");
                    $spreadsheet->getActiveSheet()->mergeCells("A{$i}:B{$i}");
                    $spreadsheet->getActiveSheet()->mergeCells("E{$i}:G{$i}");
                    break;
                case $num+14:
                    $worksheet->getCell("A{$i}")->setValue("帐号：finance@gyang.net	");
                    $worksheet->getCell("E{$i}")->setValue("如付外币,请填写服务费的种类名称为service fee");
                    $spreadsheet->getActiveSheet()->mergeCells("A{$i}:B{$i}");
                    $spreadsheet->getActiveSheet()->mergeCells("E{$i}:G{$i}");
                    break;
                case $num+15:

                    break;
                case $num+16:
                    $worksheet->getCell("A{$i}")->setValue("国洋运通香港外币账号(恒生银行)：");
                    $spreadsheet->getActiveSheet()->mergeCells("A{$i}:C{$i}");
                    break;
                case $num+17:
                    $worksheet->getCell("A{$i}")->setValue("Bank Information：	");
                    $spreadsheet->getActiveSheet()->mergeCells("A{$i}:C{$i}");
                    break;
                case $num+18:
                    $worksheet->getCell("A{$i}")->setValue("Account Name:LONG WIN INTERNATIONAL LOGISTICS CO., LIMITED");
                    $spreadsheet->getActiveSheet()->mergeCells("A{$i}:C{$i}");
                    break;
                case $num+19:
                    $worksheet->getCell("A{$i}")->setValue("Account Number:788729614883");
                    $spreadsheet->getActiveSheet()->mergeCells("A{$i}:C{$i}");
                    break;
                case $num+20:
                    $worksheet->getCell("A{$i}")->setValue("Bank Address:83 DES VOEUX ROAD CENTRAL,HONG KONG");
                    $spreadsheet->getActiveSheet()->mergeCells("A{$i}:C{$i}");
                    break;
                case $num+21:
                    $worksheet->getCell("A{$i}")->setValue("Swift Number: HASEHKHH");
                    $spreadsheet->getActiveSheet()->mergeCells("A{$i}:C{$i}");
                    break;
            }
        }

        $worksheet2 = $spreadsheet->getSheet(1);
        // 居中对齐
        $styleArraySheet2 = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ]
        ];
        // 设置列宽
        $spreadsheet->getSheet(1)->getColumnDimension('A')->setWidth(18);
        $spreadsheet->getSheet(1)->getColumnDimension('B')->setWidth(18);
        $spreadsheet->getSheet(1)->getColumnDimension('C')->setWidth(18);
        $spreadsheet->getSheet(1)->getColumnDimension('D')->setWidth(18);
        $spreadsheet->getSheet(1)->getColumnDimension('E')->setWidth(18);
        $spreadsheet->getSheet(1)->getColumnDimension('F')->setWidth(18);
        $spreadsheet->getSheet(1)->getColumnDimension('G')->setWidth(18);
        $spreadsheet->getSheet(1)->getColumnDimension('H')->setWidth(18);
        $spreadsheet->getSheet(1)->getColumnDimension('I')->setWidth(18);
        $spreadsheet->getSheet(1)->getColumnDimension('J')->setWidth(18);
        // 设置字体
        $spreadsheet->getSheet(1)->getStyle("A1")->getFont()->setBold(true)->setName('SimSun')->setSize(9);
        $spreadsheet->getSheet(1)->getStyle("B1")->getFont()->setBold(true)->setName('SimSun')->setSize(9);
        $spreadsheet->getSheet(1)->getStyle("C1")->getFont()->setBold(true)->setName('SimSun')->setSize(9);
        $spreadsheet->getSheet(1)->getStyle("D1")->getFont()->setBold(true)->setName('SimSun')->setSize(9);
        $spreadsheet->getSheet(1)->getStyle("E1")->getFont()->setBold(true)->setName('SimSun')->setSize(9);
        $spreadsheet->getSheet(1)->getStyle("F1")->getFont()->setBold(true)->setName('SimSun')->setSize(9);
        $spreadsheet->getSheet(1)->getStyle("G1")->getFont()->setBold(true)->setName('SimSun')->setSize(9);
        $spreadsheet->getSheet(1)->getStyle("H1")->getFont()->setBold(true)->setName('SimSun')->setSize(9);
        $spreadsheet->getSheet(1)->getStyle("I1")->getFont()->setBold(true)->setName('SimSun')->setSize(9);
        $spreadsheet->getSheet(1)->getStyle("J1")->getFont()->setBold(true)->setName('SimSun')->setSize(9);
        // 设置居中对齐
        $worksheet2->getStyle("A1")->applyFromArray($styleArraySheet2);
        $worksheet2->getStyle("B1")->applyFromArray($styleArraySheet2);
        $worksheet2->getStyle("C1")->applyFromArray($styleArraySheet2);
        $worksheet2->getStyle("D1")->applyFromArray($styleArraySheet2);
        $worksheet2->getStyle("E1")->applyFromArray($styleArraySheet2);
        $worksheet2->getStyle("F1")->applyFromArray($styleArraySheet2);
        $worksheet2->getStyle("G1")->applyFromArray($styleArraySheet2);
        $worksheet2->getStyle("H1")->applyFromArray($styleArraySheet2);
        $worksheet2->getStyle("I1")->applyFromArray($styleArraySheet2);
        $worksheet2->getStyle("J1")->applyFromArray($styleArraySheet2);

        // 设置行高
        $spreadsheet->getSheet(1)->getRowDimension('1')->setRowHeight(23.35);

        // 设置固定内容
        $worksheet2->getCell('A1')->setValue('账单日期');
        $worksheet2->getCell('B1')->setValue('订单号');
        $worksheet2->getCell('C1')->setValue('渠道');
        $worksheet2->getCell('D1')->setValue('参考单号');
        $worksheet2->getCell('E1')->setValue('追踪单号');
        $worksheet2->getCell('F1')->setValue('WISH单号');
        $worksheet2->getCell('G1')->setValue('国家编码');
        $worksheet2->getCell('H1')->setValue('国家名称');
        $worksheet2->getCell('I1')->setValue('重量');
        $worksheet2->getCell('J1')->setValue('应收金额');

        $newNumber = 2;
        foreach ($resDate as $item) {
            // 设置字体
            $spreadsheet->getSheet(1)->getStyle("A{$newNumber}")->getFont()->setName('SimSun')->setSize(10);
            $spreadsheet->getSheet(1)->getStyle("B{$newNumber}")->getFont()->setName('SimSun')->setSize(10);
            $spreadsheet->getSheet(1)->getStyle("C{$newNumber}")->getFont()->setName('SimSun')->setSize(10);
            $spreadsheet->getSheet(1)->getStyle("D{$newNumber}")->getFont()->setName('SimSun')->setSize(10);
            $spreadsheet->getSheet(1)->getStyle("E{$newNumber}")->getFont()->setName('SimSun')->setSize(10);
            $spreadsheet->getSheet(1)->getStyle("F{$newNumber}")->getFont()->setName('SimSun')->setSize(10);
            $spreadsheet->getSheet(1)->getStyle("G{$newNumber}")->getFont()->setName('SimSun')->setSize(10);
            $spreadsheet->getSheet(1)->getStyle("H{$newNumber}")->getFont()->setName('SimSun')->setSize(10);
            $spreadsheet->getSheet(1)->getStyle("I{$newNumber}")->getFont()->setName('SimSun')->setSize(10);
            $spreadsheet->getSheet(1)->getStyle("J{$newNumber}")->getFont()->setName('SimSun')->setSize(10);
            // 设置居中对齐
            $worksheet2->getStyle("A{$newNumber}")->applyFromArray($styleArraySheet2);
            $worksheet2->getStyle("B{$newNumber}")->applyFromArray($styleArraySheet2);
            $worksheet2->getStyle("C{$newNumber}")->applyFromArray($styleArraySheet2);
            $worksheet2->getStyle("D{$newNumber}")->applyFromArray($styleArraySheet2);
            $worksheet2->getStyle("E{$newNumber}")->applyFromArray($styleArraySheet2);
            $worksheet2->getStyle("F{$newNumber}")->applyFromArray($styleArraySheet2);
            $worksheet2->getStyle("G{$newNumber}")->applyFromArray($styleArraySheet2);
            $worksheet2->getStyle("H{$newNumber}")->applyFromArray($styleArraySheet2);
            $worksheet2->getStyle("I{$newNumber}")->applyFromArray($styleArraySheet2);
            $worksheet2->getStyle("J{$newNumber}")->applyFromArray($styleArraySheet2);

            // 设置行高
            $spreadsheet->getSheet(1)->getRowDimension("{$newNumber}")->setRowHeight(23.35);

            // 设置固定内容
            $worksheet2->getCell("A{$newNumber}")->setValue("{$item['time']}");
            $worksheet2->getCell("B{$newNumber}")->setValue("{$item['order_no']}");
            $worksheet2->getCell("C{$newNumber}")->setValue("{$item['channel_name']}");

            $worksheet2->getCell("D{$newNumber}")->setValueExplicit("{$item['refer_no']}",DataType::TYPE_STRING);
            $worksheet2->getCell("E{$newNumber}")->setValue("{$item['tracking_no']}");

            $worksheet2->getCell("F{$newNumber}")->setValue("{$item['tracking_no']}");
            $worksheet2->getCell("G{$newNumber}")->setValue("{$item['country_code2']}");
            $worksheet2->getCell("H{$newNumber}")->setValue("{$item['name_zh']}");
            $worksheet2->getCell("I{$newNumber}")->setValue("{$item['cwgt']}");
            $worksheet2->getCell("J{$newNumber}")->setValue("{$item['price_r']}");

            $newNumber++;
        }
        $snewNumber=$newNumber-1;
        // 设置字体
        $spreadsheet->getSheet(1)->getStyle("A{$newNumber}")->getFont()->setBold(true)->setName('SimSun')->setSize(10);
        $spreadsheet->getSheet(1)->getStyle("I{$newNumber}")->getFont()->setBold(true)->setName('SimSun')->setSize(10);
        $spreadsheet->getSheet(1)->getStyle("J{$newNumber}")->getFont()->setBold(true)->setName('SimSun')->setSize(10);
        // 设置居中对齐
        $worksheet2->getStyle("A{$newNumber}")->applyFromArray($styleArraySheet2);
        $worksheet2->getStyle("I{$newNumber}")->applyFromArray($styleArraySheet2);
        $worksheet2->getStyle("J{$newNumber}")->applyFromArray($styleArraySheet2);

        // 设置行高
        $spreadsheet->getSheet(1)->getRowDimension("{$newNumber}")->setRowHeight(23.35);

        // 设置固定内容
        $worksheet2->getCell("A{$newNumber}")->setValue("合计");

        $worksheet2->getCell("I{$newNumber}")->setValue("=SUM(I2:I{$snewNumber})");
        $worksheet2->getCell("J{$newNumber}")->setValue("=ROUND(SUM(J2:J{$snewNumber}),2)");
        // 设置文档属性
        $spreadsheet->getProperties()
            ->setCreator("Helloweba")    //作者
            ->setLastModifiedBy("Yuegg") //最后修改者
            ->setTitle("Office 2007 XLSX Test Document")  //标题
            ->setSubject("Office 2007 XLSX Test Document") //副标题
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")  //描述
            ->setKeywords("office 2007 openxml php") //关键字
            ->setCategory("Test result file"); //分类
        return ['code'=>0,'data'=>$spreadsheet];
    }

    public function updated()
    {
        $sql = "select gyl_order_packet.tracking_no,gyl_order_packet_tracking.code,gyl_order_packet_tracking.action_time,gyl_order_packet_tracking.id from gyl_order_packet right JOIN gyl_order_packet_tracking ON gyl_order_packet.tracking_no=gyl_order_packet_tracking.tracking_no where  gyl_order_packet.create_time>'1555862400' and gyl_order_packet_tracking.code=302 and gyl_order_packet.refer_no REGEXP '^VO'";
        $sql1 = " select tracking_no,code,action_time,id from gyl_order_packet_tracking where code=302";
        //$count = count(Db::query($sql));
        $resData = Db::query($sql1);
        foreach ($resData as $resDatum) {
            /*if ($resDatum['code']=301)
            {
                $sql2 = "update gyl_order_packet_tracking set code = 'WAYBILL_GENERATED' where id={$resDatum['id']} and code={$resDatum['code']}";
            }*/
            if ($resDatum['code']=302)
            {
                $sql2 = "update gyl_order_packet_tracking set code = 'PICK_UP' where id={$resDatum['id']} and code={$resDatum['code']}";
            }

            $res = Db::execute($sql2);
            //dump($res);
            $arr[] = $res;
        }

        halt(count($arr));
    }
    function exportExcel(array $datas, $fileName = '', array $options = [])
    {
        try {
            if (empty($datas)) {
                return false;
            }

            set_time_limit(0);
            /** @var Spreadsheet $objSpreadsheet */
            $objSpreadsheet = app(Spreadsheet::class);
            /* 设置默认文字居左，上下居中 */
            $styleArray = [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
            ];
            $objSpreadsheet->getDefaultStyle()->applyFromArray($styleArray);
            /* 设置Excel Sheet */
            $activeSheet = $objSpreadsheet->setActiveSheetIndex(0);

            /* 打印设置 */
            //if (isset($options['print']) && $options['print']) {
                /* 设置打印为A4效果 */
                //$activeSheet->getPageSetup()->setPaperSize(PageSetup:: PAPERSIZE_A4);
                /* 设置打印时边距 */
                /*$pValue = 1 / 2.54;
                $activeSheet->getPageMargins()->setTop($pValue / 2);
                $activeSheet->getPageMargins()->setBottom($pValue * 2);
                $activeSheet->getPageMargins()->setLeft($pValue / 2);
                $activeSheet->getPageMargins()->setRight($pValue / 2);
            }*/

            /* 行数据处理 */
            foreach ($datas as $sKey => $sItem) {
                /* 默认文本格式 */
                $pDataType = DataType::TYPE_STRING;

                /* 设置单元格格式 */
                if (isset($options['format']) && !empty($options['format'])) {
                    $colRow = Coordinate::coordinateFromString($sKey);

                    /* 存在该列格式并且有特殊格式 */
                    if (isset($options['format'][$colRow[0]]) &&
                        NumberFormat::FORMAT_GENERAL != $options['format'][$colRow[0]]) {
                        $activeSheet->getStyle($sKey)->getNumberFormat()
                            ->setFormatCode($options['format'][$colRow[0]]);

                        if (false !== strpos($options['format'][$colRow[0]], '0.00') &&
                            is_numeric(str_replace(['￥', ','], '', $sItem))) {
                            /* 数字格式转换为数字单元格 */
                            $pDataType = DataType::TYPE_NUMERIC;
                            $sItem     = str_replace(['￥', ','], '', $sItem);
                        }
                    } elseif (is_int($sItem)) {
                        $pDataType = DataType::TYPE_NUMERIC;
                    }
                }

                $activeSheet->setCellValueExplicit($sKey, $sItem, $pDataType);

                /* 存在:形式的合并行列，列入A1:B2，则对应合并 */
                if (false !== strstr($sKey, ":")) {
                    $options['mergeCells'][$sKey] = $sKey;
                }
            }

            unset($datas);

            /* 设置锁定行 */
            if (isset($options['freezePane']) && !empty($options['freezePane'])) {
                $activeSheet->freezePane($options['freezePane']);
                unset($options['freezePane']);
            }

            /* 设置宽度 */
            if (isset($options['setWidth']) && !empty($options['setWidth'])) {
                foreach ($options['setWidth'] as $swKey => $swItem) {
                    $activeSheet->getColumnDimension($swKey)->setWidth($swItem);
                }

                unset($options['setWidth']);
            }

            /* 设置背景色 */
            if (isset($options['setARGB']) && !empty($options['setARGB'])) {
                foreach ($options['setARGB'] as $sItem) {
                    $activeSheet->getStyle($sItem)
                        ->getFill()->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB(Color::COLOR_YELLOW);
                }

                unset($options['setARGB']);
            }

            /* 设置公式 */
            if (isset($options['formula']) && !empty($options['formula'])) {
                foreach ($options['formula'] as $fKey => $fItem) {
                    $activeSheet->setCellValue($fKey, $fItem);
                }

                unset($options['formula']);
            }

            /* 合并行列处理 */
            if (isset($options['mergeCells']) && !empty($options['mergeCells'])) {
                $activeSheet->setMergeCells($options['mergeCells']);
                unset($options['mergeCells']);
            }

            /* 设置居中 */
            if (isset($options['alignCenter']) && !empty($options['alignCenter'])) {
                $styleArray = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ];

                foreach ($options['alignCenter'] as $acItem) {
                    $activeSheet->getStyle($acItem)->applyFromArray($styleArray);
                }

                unset($options['alignCenter']);
            }

            /* 设置加粗 */
            if (isset($options['bold']) && !empty($options['bold'])) {
                foreach ($options['bold'] as $bItem) {
                    $activeSheet->getStyle($bItem)->getFont()->setBold(true);
                }

                unset($options['bold']);
            }

            /* 设置单元格边框，整个表格设置即可，必须在数据填充后才可以获取到最大行列 */
            if (isset($options['setBorder']) && $options['setBorder']) {
                $border    = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN, // 设置border样式
                            'color'       => ['argb' => 'FF000000'], // 设置border颜色
                        ],
                    ],
                ];
                $setBorder = 'A1:' . $activeSheet->getHighestColumn() . $activeSheet->getHighestRow();
                $activeSheet->getStyle($setBorder)->applyFromArray($border);
                unset($options['setBorder']);
            }

            $fileName = !empty($fileName) ? $fileName : (date('YmdHis') . '.xlsx');

            if (!isset($options['savePath'])) {
                /* 直接导出Excel，无需保存到本地，输出07Excel文件 */
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header(
                    "Content-Disposition:attachment;filename=" . iconv(
                        "utf-8", "GB2312//TRANSLIT", $fileName
                    )
                );
                header('Cache-Control: max-age=0');//禁止缓存
                $savePath = 'php://output';
            } else {
                $savePath = $options['savePath'];
            }

            ob_clean();
            ob_start();
            $objWriter = IOFactory::createWriter($objSpreadsheet, 'Xlsx');
            $objWriter->save($savePath);
            /* 释放内存 */
            $objSpreadsheet->disconnectWorksheets();
            unset($objSpreadsheet);
            ob_end_flush();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Excel导出，TODO 可继续优化
     *
     * @param array  $datas      导出数据，格式['A1' => 'XXXX公司报表', 'B1' => '序号']
     * @param string $fileName   导出文件名称
     * @param array  $options    操作选项，例如：
     *                           bool   print       设置打印格式
     *                           string freezePane  锁定行数，例如表头为第一行，则锁定表头输入A2
     *                           array  setARGB     设置背景色，例如['A1', 'C1']
     *                           array  setWidth    设置宽度，例如['A' => 30, 'C' => 20]
     *                           bool   setBorder   设置单元格边框
     *                           array  mergeCells  设置合并单元格，例如['A1:J1' => 'A1:J1']
     *                           array  formula     设置公式，例如['F2' => '=IF(D2>0,E42/D2,0)']
     *                           array  format      设置格式，整列设置，例如['A' => 'General']
     *                           array  alignCenter 设置居中样式，例如['A1', 'A2']
     *                           array  bold        设置加粗样式，例如['A1', 'A2']
     *                           string savePath    保存路径，设置后则文件保存到服务器，不通过浏览器下载
     */

}