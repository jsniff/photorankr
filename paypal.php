<?php

$itemname = $_POST['item_name'];
echo '<br />';
echo $itemname;

?>
<html>
<body>



<form target="_self" action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="item_name" value="<?php echo $label; ?>">
<input type="hidden" name="amount" value="170" />
<input type="hidden" name="business" value="photorankr@photorankr.com">
<input type="hidden" name="add" value="1">
<input type="hidden" name="cmd" value="_cart">
<input type="hidden" name="item_number" value="<?php echo $imageID; ?>">
<input type="hidden" name="amount" value="<?php echo $price; ?>">
<input type="hidden" name="no_shipping" value="0">
<input type="hidden" name="return" value="http://photorankr.com/ordersuccess.html">
<input type="hidden" name="cancel_return" value="http://photorankr.com/ordercancel.html">
<input type="hidden" name="logo_custom" value="http://photorankr.com/logo.html">
<input type="hidden" name="no_note" value="1">

<input type="submit" name="submit" value="BUY PHOTO NOW"></input>
</form>
</body>
</html>