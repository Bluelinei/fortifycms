  </div><!--END Main Content Area Wrapper-->
  <div class="clear"></div><!--CLEAR the main content wrapper areas-->
<!--START footer-->
  <footer style="position: fixed;">

    <div class="logout-button point-cursor"><p class="vertical-middle">Log Out</p></div>
    <p style="left: 10px; top: 50%; transform: translateY(-50%); color: #fff; pointer-events: none;"><?php echo 'Logged in as '.$_SESSION['name']; ?></p>
    <div class="twenty-per-wide notification-button point-cursor">

    </div>
    <div class="clear"></div>
  </footer><!--END Footer-->
  <script>
  	$('.logout-button').on('click', logout);
  </script>


  </body>
</html>
