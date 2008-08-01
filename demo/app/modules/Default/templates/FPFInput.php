

<form action="<?php echo $ro->gen(null); ?>" method="post" id="foo">
  <dl>
    <dt><label for="fe-username"></label></dt>
    <dd><input type="text" name="username" id="fe-username" /></dd>
    <dt><label for="fe-password"></label></dt>
    <dd><input type="password" name="password" id="fe-password" /></dd>
    <dt>&#160;</dt>
    <dd><input type="checkbox" name="remember" id="fe-remember" /><label for="fe-remember"> </label></dd>
    <dt>&#160;</dt>
    <dd><input type="submit" value="" /></dd>
  </dl>
</form>


<?php #die("IN TEMPLATE"); ?>