
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../css/base.css" />
</head>
<style>
</style>
<body>


<!-- 定位栏1  -->
<div id="locationbar">
<!-- 输入位置 -->
<form method="get">
Position: <input class='positionInput' type='text' name='positionInput' id='positionInput' size='50' >
<!-- 提交 -->
<input type="submit" name="subPosition" value="Submit">
</form>

<!-- 分页 -->
<?php
//数据库连接
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "XXX";
// 检查连接
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) 
  {
  echo "Error: Unable to connect to MySQL." . PHP_EOL;
  echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
  echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
  exit;
  }
//定义输入
$positionInput=$_GET["positionInput"];
$IDInput=$_GET["IDInput"];
//确认总页数
$ID=$_GET["ID"];
$sql_totalID="SELECT count(*) from chart1";
$totalresult=mysqli_query($conn,$sql_totalID);
$total=mysqli_fetch_row($totalresult)[0];
//每页1条
$num=1;
$totalID=ceil($total/$num);
//ID范围
if($ID>$totalID){
    $ID=$totalID;
}
if($ID<1){
    $ID=1;
}
$start=($ID-1)*1;
//搜索条件 
//判断是否提交
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if(!empty($_GET['positionInput'])){
        //检查position是否为数字
            if (is_numeric($positionInput)) { 
                 $positionInput_correct = (floor($positionInput/1000)+1)*1000;
              //判断是否被1000整除                                   
               if ($positionInput_correct > 1000){
                 //确实输入
                   $position = $positionInput_correct;
                  //position查询ID语句
                  $sql_position_find_ID="SELECT b.ID from chart2 a right join chart1 b on a.path_name = b.path_name where a.position = $position";
                  //确定当前ID*******
                    $result__position_find_ID = mysqli_query($conn,$sql_position_find_ID);
                    $row__position_find_ID = mysqli_fetch_assoc($result__position_find_ID);
                    if (!empty($row__position_find_ID)) {
                         $ID = $row__position_find_ID["ID"];}
                     // 报错：没有position查询ID结果
                      else{
                        echo '<script language="javascript">';
                        echo 'alert("Sorry, there is no peak map for your query.")';
                        echo '</script>'; 
                    } 
                   }
                  }
              // 报错：没有输入数字
                else{
                  echo '<script language="javascript">';
                  echo 'alert("Sorry, only number allowed.")';
                  echo '</script>'; 
                 }
                }  
                 //检查ID输入               
              elseif(!empty($_GET['IDInput'])){
            //检查ID是否为数字
              if (is_numeric($IDInput)) { 
                  //判断ID是否在范围内
                   if ( $IDInput >0 and $IDInput < $totalID){
                     //确认输入
                       $ID = $IDInput;
                    }
                       // 报错：ID范围错误
                        else {
                        echo '<script language="javascript">';
                        echo 'alert("Sorry, page range is error.")';
                        echo '</script>';
                       }
                     } 
                     // 报错：没有输入数字
                 else{
                      echo '<script language="javascript">';
                      echo 'alert("Sorry, only number allowed.")';
                      echo '</script>'; 
                    }
                }
            }       
//最终结果输出
$sql_img_output="SELECT * from chart1 where ID = $ID";
$result=mysqli_query($conn,$sql_img_output);
while($res=mysqli_fetch_assoc($result)){
    $rows[]=$res;}
mysqli_close($conn);    
?>

<!-- img  -->
<div id="peakimg">
<TABLE BORDER=0 CELLPADDING=0 align="center">
<TR><TD HEIGHT=5></TD></TR>
<TR><TD>
<?php foreach($rows as $k=>$v):?>
<TR>
<TD> <img src ="<?php echo $v["path"];?>" BORDER=1 WIDTH=1300 HEIGHT=850 USEMAP=#ideoMap id='chrom' style='display: inline;'></TD>
</TR> 
<?php endforeach;?>
</TABLE>
</div>

<!-- move -->
<div id="move" align="center"> 
<a href ="?ID=1"><input  type='submit' name='First'   value=' First ' title='move to the first'></a></a>
<a href ="?ID=<?php echo $ID-1;?>"><input  type='submit' name='left_click'   value=' < ' title='move to left'></a>
<a href ="?ID=<?php echo $ID+1;?>"><input  type='submit' name='right_click'  value=' > ' title='move to right'></a>
<a href ="?ID=<?php echo $totalID;?>"><input  type='submit' name='Last'   value=' Last ' title='move to the last'></a>
</div> 

<!-- page -->
<div id ="page">
<TABLE BORDER=0 CELLPADDING=0 align="center">
<TR><TD HEIGHT=5></TD></TR>
<TR><TD><?php echo "{$ID}/{$totalID}"?></TD></TR>
</TABLE>

<!-- 定位栏2  -->
<div id="locationbar">
<form method="get">
<!-- 输入ID -->
Page: <input class='IDInput' type='text' name='IDInput' id='IDInput' size='5' >
<!-- 提交 -->
<input type="submit" name="subID" value="Jump">
</form>

</body>
</html>