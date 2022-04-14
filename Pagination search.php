
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../css/base.css" />
</head>
<style>
</style>
<body>


<!-- Location bar1  -->
<div id="locationbar">
<!-- Input location -->
<form method="get">
Position: <input class='positionInput' type='text' name='positionInput' id='positionInput' size='50' >
<!-- Submit -->
<input type="submit" name="subPosition" value="Submit">
</form>

<!-- Paging function -->
<?php
//Database connection
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "XXX";
// Check the connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) 
  {
  echo "Error: Unable to connect to MySQL." . PHP_EOL;
  echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
  echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
  exit;
  }
//Define input
$positionInput=$_GET["positionInput"];
$IDInput=$_GET["IDInput"];
//Confirm total pages
$ID=$_GET["ID"];
$sql_totalID="SELECT count(*) from chart1";
$totalresult=mysqli_query($conn,$sql_totalID);
$total=mysqli_fetch_row($totalresult)[0];
//Each page 1 the information
$num=1;
$totalID=ceil($total/$num);
//ID range
if($ID>$totalID){
    $ID=$totalID;
}
if($ID<1){
    $ID=1;
}
$start=($ID-1)*1;
//Search criteria 
//Judge whether to submit
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if(!empty($_GET['positionInput'])){
        //Check if position is a number
            if (is_numeric($positionInput)) { 
                 $positionInput_correct = (floor($positionInput/1000)+1)*1000;
              //Determine whether divisible by 1000                                   
               if ($positionInput_correct > 1000){
                 //Actual input
                   $position = $positionInput_correct;
                  //Position query ID statement
                  $sql_position_find_ID="SELECT b.ID from chart2 a right join chart1 b on a.path_name = b.path_name where a.position = $position";
                  //Determine current ID*******
                    $result__position_find_ID = mysqli_query($conn,$sql_position_find_ID);
                    $row__position_find_ID = mysqli_fetch_assoc($result__position_find_ID);
                    if (!empty($row__position_find_ID)) {
                         $ID = $row__position_find_ID["ID"];}
                     // An error: no position query ID result
                      else{
                        echo '<script language="javascript">';
                        echo 'alert("Sorry, there is no peak map for your query.")';
                        echo '</script>'; 
                    } 
                   }
                  }
              // Error: no number entered
                else{
                  echo '<script language="javascript">';
                  echo 'alert("Sorry, only number allowed.")';
                  echo '</script>'; 
                 }
                }  
                 //Check ID input               
              elseif(!empty($_GET['IDInput'])){
            //Check if the ID is numeric
              if (is_numeric($IDInput)) { 
                  //Determine if the ID is in range
                   if ( $IDInput >0 and $IDInput < $totalID){
                     //Confirm input
                       $ID = $IDInput;
                    }
                       // Error reporting: ID range error
                        else {
                        echo '<script language="javascript">';
                        echo 'alert("Sorry, page range is error.")';
                        echo '</script>';
                       }
                     } 
                     // Error: no number entered
                 else{
                      echo '<script language="javascript">';
                      echo 'alert("Sorry, only number allowed.")';
                      echo '</script>'; 
                    }
                }
            }       
//Final result output
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

<!-- Location bar2  -->
<div id="locationbar">
<form method="get">
<!-- Input ID -->
Page: <input class='IDInput' type='text' name='IDInput' id='IDInput' size='5' >
<!-- Submit -->
<input type="submit" name="subID" value="Jump">
</form>

</body>
</html>
