<?php require_once('Connections/link.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["totel_price"]))) {
  $updateSQL = sprintf("UPDATE `order` SET totel_price=%s, statues=%s WHERE order_id=%s",
                       GetSQLValueString($_POST['totel_price'], "double"),
                       GetSQLValueString($_POST['statues'], "text"),
                       GetSQLValueString($_POST['order_id'], "int"));

  mysql_select_db($database_link, $link);
  $Result1 = mysql_query($updateSQL, $link) or die(mysql_error());

  $updateGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
 // header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_link, $link);

$repid="1";
$customer_id="5";

	$result2 = mysql_query("SELECT * FROM  `order` WHERE rep_id='$repid' AND statues='Pending'  AND `customer_id`='$customer_id' ORDER BY  `order`.`order_id` DESC ") or die(mysql_error()); 
	$row2 = mysql_fetch_array( $result2 );

	 $orderid=$row2["order_id"];


  $colname_orderItems = $orderid;


mysql_select_db($database_link, $link);
$query_orderItems = sprintf("SELECT * FROM order_details WHERE order_id = %s ORDER BY detail_id DESC", GetSQLValueString($colname_orderItems, "int"));
$orderItems = mysql_query($query_orderItems, $link) or die(mysql_error());
$row_orderItems = mysql_fetch_assoc($orderItems);
$totalRows_orderItems = mysql_num_rows($orderItems);
$updateid=$row_orderItems['order_id'];
?>

<table border="1">
  <tr>
    <td>detail_id</td>
    <td>item_name</td>
    <td>quentity</td>
    <td>unit_price</td>
    <td>total_price</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_orderItems['detail_id']; ?></td>
      <td><?php echo $row_orderItems['item_name']; ?></td>
      <td><?php echo $row_orderItems['quentity']; ?></td>
      <td><?php echo $row_orderItems['unit_price']; ?></td>
      <td><?php echo $row_orderItems['total_price']; 
	 
	  $newtotal=$newtotal+$row_orderItems['total_price'];
	  
	  ?></td>
    </tr>
    
    <?php } while ($row_orderItems = mysql_fetch_assoc($orderItems)); ?>
    <tr>
    <td></td>
    <td></td>
    <td></td>
    <td>TO</td>
    <td><?php echo $newtotal; ?></td>
    </tr>
</table>
<form action="changestatus.php" method="get" enctype="text/plain">
  <input type="hidden" name="order_id" value="<?php echo $updateid; ?>" />

  <input name="" type="submit" />
</form>
<p>&nbsp;</p>
<?php
mysql_free_result($orderItems);
?>
