       <?php
        if (isset($_GET["status"]) && $_GET["status"] == 1) {
            print "账号或者密码错误 ";
        }
        else if(isset($_GET["status"]) && $_GET["status"] == 2){
            print "验证码错误 ";
        }
      ?> 
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>

        
    </head>
    <body>


        <div style="margin:0 auto;width:200px;text-align: center;margin-top: 300px;">
            <div>
               
 
                
        </div>
        <form action="tabPagesBehind/adminLoginB.php?action=1" method="post">
            <div style="width:310px">账号: <input name="username" type="text" size="30" /> </div> <br/>
            <div style="width:310px">密码: <input name="password" type="password" size="31" /></div><br/>
     
          <input type="submit" name="OK" value="OK"/> <input type="reset" name="Cancel" value="Cancel"/>
        </form>
        </div>



    </body>
</html>
