<?php
include('top.php');
?>

<div id="page">
    <div id="indhold">
        <div id="indholdText2">
            <div id="indholdDiv2">
                <?php
				/* Her bestemmes der hvad der skal ind på, se filerne for nærmere kommentarer */
                  if (isset($_GET['INFO'])){
                     include('Turinfo.php');
                  }
                  else {
                      include('Turtabel.php');
                  } 
                ?>
            </div>
        </div>
    </div>
</div>

<?php
include('bottom.html');
?>
