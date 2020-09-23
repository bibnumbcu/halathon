<?php
/**
 * affichage du schéma de la page du halathon
 * image avec zones cliquables
 */


$baseUrl = 'https://'.$_SERVER[HTTP_HOST]. dirname($_SERVER['PHP_SELF']);

?>

<style>
	#bu-hal-deposer{
		position: relative;
		width: 500px;
	}
	#bu-hal-deposer img{
		width: 500px;
	}
	#bu-hal-deposer a{
		position: absolute;
		display:inline-block;
		border: 1px solid blue;
	}

</style>

<div id="bu-hal-deposer">
	<img src="<?= $baseUrl ?>/images/comment-deposer-hal.jpg" />
  	<a href="https://www.google.fr" style="top: 10%; left: 10%; width: 15%; height: 15%;"></a>
  	<a href="https://bu.uca.fr" style="top: 20%; left: 50%; width: 15%; height: 15%;"></a>
  	<a href="https://bibliotheque-virtuelle.bu.uca.fr" style="top: 50%; left: 80%; width: 15%; height: 15%;"></a>
</div>
