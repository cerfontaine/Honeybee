<?php
require('php/function.inc.php');
$message = '';
$title='Contact';
if(isset($_POST['contacter'])){
    $notempty = isNotEmpty($_POST, $message);
    $mailok = MailIsValid($_POST['reponsemail'], $message);
    if($notempty && $mailok){
        envoyerMailContact($_POST['reponsemail'], $_POST['sujet'], $_POST['message']);
    }
}
?>
<?php include('inc/head.er.inc.php')?>
<body>
    <?php include('inc/bee.inc.php');?>
	<header>		
		<h2><a href="index.php">You've got to <span class="bee">bee</span> kidding me</a></h2>
	</header>
	<main>
        <?php echo $message; ?>
		<fieldset class="login">
			<h1>Contact</h1>
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
				 <input id="sujet" name="sujet" type="text" placeholder="Sujet" required autofocus>
				 <textarea id="message" name="message" placeholder="Votre message" required rows="3" cols="20"></textarea>
				 <input id="mail" name="reponsemail" type="email" placeholder="E-mail" required>
				 <input type="submit" name="contacter" value="Contacter">
			</form>
		</fieldset>
	</main>
    <?php include('inc/footer.inc.php')?>
</body>
</html>