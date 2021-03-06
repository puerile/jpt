<?php
$app->get("/login", function() use ($app) {
	$app->render('login.php', array());
});
$app->post("/login", function() use ($app, $pdo) {
	/* CHECK NNTP CONNECTION */
	$nntp = new SSP\NNTP\NNTP();
	$nntp->connect("news.junge-piraten.de"); //Return true or false
	$res = $pdo->query("SELECT `username` FROM `access` WHERE `username` = ?", array($app->request->params('user')));
	if(count($res)==0)
	{
		$app->render('login.php', array("login"=>false));
	}
	else
	{
		$login = $nntp->autentifizierung($app->request->params('user'), $app->request->params('pass')); //Return true or false
		if($login)
		{
			$_SESSION["login"]=true;
			$_SESSION["username"]=$app->request->params('user');
			$_SESSION["pw"]=$app->request->params('pass');
			$app->redirect('/');
		}
		$app->render('login.php', array("login"=>$login));
	}
});
$app->get("/logout", function() use ($app) {
	$_SESSION["login"]=false;
	$_SESSION["username"]=null;
	$app->render('login.php', array());
});