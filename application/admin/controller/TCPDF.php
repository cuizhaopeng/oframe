<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/20
 * Time: 15:13
 */

namespace app\admin\controller;

use TCPDF as TCPDFS;
class TCPDF
{
    public function index()
    {
        //php使用TCPDF生成PDF文件教程
        /**
         * 初始化
         * [new \TCPDF(Orientation, Unit, Format, Unicode, Encoding, Diskcache)]
         * @param [Orientation] [设置文档打印格式是“Portrait”还是“Landscape”。 Landscape为横式打印，Portrait为纵向打印,可取首字母]
         * @param [Unit] [设置页面的单位。pt：点为单位，mm：毫米为单位，cm：厘米为单位，in：英尺为单位]
         * @param [Format] [设置打印格式，一般设置为A4]
         * @param [Unicode] [为true，输入的文本为Unicode字符文本]
         * @param [Encoding] [设置编码格式，默认为utf-8]
         * @param [Diskcache] [为true，通过使用文件系统的临时缓存数据减少RAM的内存使用]
         */
        $pdf = new \TCPDF("L", "mm", [100, 100], TRUE, "UTF-8", FALSE);

        /**
         * 设置页面边幅
         * [SetMargins(Left, Top, Right) [设置页面边幅]]
         * @param [Left] [左边幅]
         * @param [Top] [顶部边幅]
         * @param [Right] [右边幅]
         */
        $pdf->SetMargins(2, 2, 2);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        $pdf->SetAutoPageBreak(TRUE, 1); //设置自动分页符
        //删除预定义的打印和设置页眉、页尾
        $pdf->setPrintHeader(false);    //删除预定义的打印
        $pdf->setPrintFooter(false);    //关闭页眉、页尾
        $pdf->SetHeaderData('', 30, '测试', '报名表', array(0, 64, 255), array(0, 64, 128));    //设置页眉
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));    //设置页尾
        $pdf->setHeaderFont(Array('stsongstdlight', '', '10'));    //设置页眉字体
        $pdf->setFooterFont(Array('helvetica', '', '8'));    //设置页尾字体
        //设置文件信息
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor("jmcx");    //作者
        $pdf->SetTitle("pdf test");    //标题
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, example');    //关键词

        //坐标设置
        $pdf->GetX();        //获得当前的横坐标
        $pdf->GetY();        //获得当前的纵坐标
        $x = 13;
        $y = 15;    // 设置X坐标，Y坐标
        $pdf->SetX($x, $rtloff = false);    //移动横坐标
        $pdf->SetY($y, $resetx = true, $rtloff = false);    //移动纵坐标
        /**
         * 同时移动横、纵坐标
         * @param [X] [横坐标]
         * @param [Y] [纵坐标]
         * @param [Rtloff：true] [左上角会一直作为坐标轴的原点]
         * @param [Resetx：true] [重设横坐标]
         */
        $pdf->SetXY($x, $y, $rtloff = false);    //同时移动横、纵坐标

        /**
         * 设置字体
         * SetFont('times', 'I', 20)
         * @param [times] [字体类型，如helvetica(Helvetica)黑体，times (Times-Roman)罗马字体，stsongstdlight [cid0cs]中文字体，DroidSansFallback，freesans，freeserifi]
         * @param [I] [风格（B粗体，I斜体，underline下划线等）]
         * @param [20] [字体大小]
         */
        $pdf->SetFont('', '');    //注意：后面再次调用此方法会覆盖前面的字体
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);    //设置默认等宽字体

        /**
         * 定义指定类型使用的颜色 ('draw', 'fill', 'text').
         * @param $type (string) 受此颜色影响的对象类型: ('draw', 'fill', 'text').
         * @param $col1 (float) GRAY level for single color, or Red color for RGB (0-255), or CYAN color for CMYK (0-100).
         * @param $col2 (float) GREEN color for RGB (0-255), or MAGENTA color for CMYK (0-100).
         * @param $col3 (float) BLUE color for RGB (0-255), or YELLOW color for CMYK (0-100).
         * @param $col4 (float) KEY (BLACK) color for CMYK (0-100).
         * @param $ret (boolean) 如果为true则不发送命令。
         * @param $name (string) spot color name (if any)
         * @return (string) The PDF command or empty string.
         * @public
         * @since 5.9.125 (2011-10-03)
         */
        $pdf->setColor($type='text', $col1=0, $col2=-1, $col3=-1, $col4=-1, $ret=false, $name='');
        /**
         * 设置单行单元格
         * [$pdf->Cell(W, H, Text, Border, Ln, Align,Fill,Link) [设置单行单元格]]
         * @param [W]  [ 设置单元格的宽 ]
         * @param [H] [ 设置单元格的单行的高]
         * @param [Text] [单元格文本]
         * @param [Border] [设置单元格的边框。0，无边框，1，一个框，L，左边框，R，右边框，B， 底边框，T，顶边框，LTRB指四个边都显示]
         * @param [Ln=0] [单元格后的内容插到表格右边或左边，1，单元格的下一行，2，在单元格下面]
         * @param [Align] [文本位置。L，左对齐，R，右对齐，C，居中，J，自动对齐]
         * @param [Fill] [填充。false，单元格的背景为透明，true，单元格必需被填充]
         * @param [Link] [设置单元格文本的链接]
         */
        $pdf->Cell(0, 0, 'Hello World', 0, 1, 'C');

        /**
         * 设置单元格的边距
         * [setCellPaddings(Left, Top, Right, Bottom) [设置单元格的边距]]
         * @param [Left] [左边距]
         * @param [Top] [顶部边距]
         * @param [Right] [右边距]
         * @param [Bottom] [底部边距]
         */
        $pdf->setCellPaddings(0, 0, 0, 0);

        /**
         * 画一条线
         * [Line(x1, y1, x2, y2, style) [画一条线]]
         * @param [x1] [线条起点x坐标]
         * @param [y1] [线条起点y坐标]
         * @param [x2] [线条终点x坐标]
         * @param [y2] [线条终点y坐标]
         * @param [style] [array] [SetLineStyle的效果一样]
         */
        $pdf->Line($x1 = 10, $y1 = 10, $x2 = 15, $y2 = 15, $style = array());

        /**
         * 设置线条的风格
         * @param [Width] [设置线条粗细]
         * @param [Cap] [设置线条的两端形状]
         * @param [Join] [设置线条连接的形状]
         * @param [Dash] [设置虚线模式]
         * @param [Color] [设置线条颜色，一般设置为黑色，如：array(0, 0, 0)]
         */
        $pdf->SetLineStyle(array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => '0', 'color' => array(0, 0, 0)));

        /**
         * 执行一个换行符，横坐标自动移动到左边距的距离，纵坐标换到下一行
         * [Ln(H, cell) [执行一个换行符，横坐标自动移动到左边距的距离，纵坐标换到下一行]]
         * @param [H] [设置下行跟上一行的距离，默认的话，高度为最后一个单元格的高度]
         * @param [cell] [true，添加左或右或上的间距到横坐标]
         */
        $pdf->Ln($h = '', $cell = false);
        $pdf->AddPage();  //增加一个页面
        $pdf->startPage('P',[150,100]); //重新初始化页面参数，比如页面的大小。
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);    //设置图像比例因子
        $pdf->setLanguageArray("xx");    //设置一些语言相关的字符串

        /**
         * 设置一个text文本块[单元格]
         *   W：设置多行单元格的宽
         *   H：设置多行单元格的单行的高
         *   Text：文本
         *   Border：设置单元格的边框。0，无边框，1，一个框，L，左边框，R，右边框，B， 底边框，T，顶边框，LTRB指四个边都显示
         *   Align：文本位置；L，左对齐，R，右对齐，C，居中，J，自动对齐
         *   Fill：填充。false，单元格的背景为透明，true，单元格必需被填充
         *   Ln：0，单元格后的内容插到表格右边或左边，1，单元格的下一行，2，在单元格下面
         *   X：设置多行单元格的行坐标
         *   Y：设置多行单元格的纵坐标
         *   Reseth：true，重新设置最后一行的高度
         *   Stretch：调整文本宽度适应单元格的宽度
         *   Ishtml：true，可以输出html文本，有时很有用的
         *   Autopadding：true，自动调整文本与单元格之间的距离
         *   Maxh：设置单元格最大的高度
         *   Valign：设置文本在纵坐标中的位置，T，偏上，M，居中，B，偏下
         *   Fillcell：自动调整文本字体大小来适应单元格大小]
         */
        $pdf->MultiCell($w = 96, $h = 96, $txt = '', $border = 0, $align = 'J', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 0, $valign = 'T', $fitcell = false);

        /**
         * 插入图片
         * [Image(file, x, y, w, h, type, link) [插入图片]]
         * @param  [file] [图片路径]
         * @param  [x] [左上角或右上角的横坐标]
         * @param  [y] [左上角或右上角的纵坐标]
         * @param  [w] [设置图片的宽度，为空或为0，则自动计算]
         * @param  [h] [设置图片的高度，为空或为0，则自动计算]
         * @param  [type] [图片的格式，支持JPGE，PNG，BMP，GIF等，如果没有值，则从文件的扩展名中自动找到文件的格式]
         * @param  [link] [图片链接]
         */
        $pdf->Image('Public/img/zhongguoyouzhen.jpg', $imgX = 5, $imgY = 5, $imgW = 9, $imgH = 9);

        /**
         * 生成条形码
         * [write1DBarcode(param, code, x, y, w, h, size, style, 'N') [生成条形码]]
         * @param  [param] [生成条形码的参数]
         * @param  [code] [条形码类型 C39,C39+,C39E,C39E+,C93,S25,S25+,I25,I25+,C128,C128A,C128B,C128C,EAN8,EAN13,UPCA,UPCE,EAN5,EAN2,MSI,MSI+,,CODABAR,CODE11,PHARMA,PHARMA2T,IMB,,POSTNET,PLANET,RMS4CC,KIX]
         * @param  [x] [左上角或右上角的横坐标]
         * @param  [y] [左上角或右上角的纵坐标]
         * @param  [w] [设置条形码的宽度]
         * @param  [h] [设置条形码的高度]
         * @param  [size] [条形码的长度]
         * @param  [style] [array] [条形码的样式]
         * @param  [N] []
         */
        $pdf->write1DBarcode('12354sdfs1f2as1', 'C128A', $x = 5, $y = 29, $w = '', $h = 18, $size = 0.4, $style, 'N');


        //条形码样式
        $style = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,//边框
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,//是否显示条码下方文字
            'font' => 'DroidSansFallback',//字体(helvetica)
            'fontsize' => 10,//字体大小
            'stretchtext' => 0
        );

        /**
         * 输出HTML文本
         * writeHTML(HTML， Ln, Fill, Reseth, Cell, Align)
         * @param [html] [html文本]
         * @param [Ln：true] [在文本的下一行插入新行]
         * @param [Fill] [false，单元格的背景为透明，true，单元格必需被填充]
         * @param [Reseth：true] [重新设置最后一行的高度]
         * @param [Cell：true] [就调整间距为当前的间距]
         * @param [Align] [调整文本位置]
         */
        $pdf->writeHTML('html代码');

        // 内容旋转
        $pdf->StartTransform();//启动旋转效果
        $pdf->Rotate(-30, 0, 60);//表示整个坐标系以原来的坐标0，60坐标（感觉又不是。。）顺时针旋转 ,在$pdf->StartTransform()与$pdf->StopTransform()之间就使用旋转后的坐标
        $pdf->StopTransform();//停止旋转
        //PDF输出的方式
        $showType = 'I';  //I，在浏览器中打开；D，以文件形式下载；F，保存到服务器中；S，以字符串形式输出；E：以邮件的附件输出
        $pdf->Output("print.pdf", $showType);	//输出

		}
}