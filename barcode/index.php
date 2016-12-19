<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Qr Code Test</title>
  </head>
  <body>
    <form action="index.php" method="post">
      <input type="text" name="msg" placeholder="Barcode Value" />
      <input type="text" name="size" placeholder="Barcode Size" value="300" />
      <input type="text" name="padding" placeholder="Barcode Padding" value="10" />
      <input type="submit" name="submit" />
    </form>
    <?php
      uniqid('',true);
     ?>
    <?php if(isset($_POST['submit'])): ?>

      <img src="qrcode.php?text=<?php echo $_POST['msg']; ?>&size=<?php echo $_POST['size']; ?>&padding=<?php echo $_POST['padding']; ?>" alt="QR Code" />
    <?php endif; ?>
  </body>
</html>
