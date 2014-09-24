<?php
include('top.php');
?>	
     
        <div id="page">
			
				<div id="indhold">
					<div id="indholdText2">
						<div id="indholdDiv2">
	
	<!-- Her ses HTML'en til LOG-IN -->
	
        <h1>Admin login</h1>
        <table>
            <tr>
            <form name="form1" method="post" action="checklogin.php">
                <td>
                    <table>
                        <tr>
                            <td>Username</td>
                            <td></td>
                            <td><input name="myusername" type="text" id="myusername"></td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td></td>
                            <td><input name="mypassword" type="password" id="mypassword"></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td><input type="submit" name="Submit" value="Login"></td>
                        </tr>
                    </table>
                </td>
            </form>
        </tr>
    </table>
	
	</div>
	</div>
	</div>
	</div>

<?php
include('bottom.html');
?>