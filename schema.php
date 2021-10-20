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
		text-align:center;
	}
	#bu-hal-deposer img{
		
	}
	#bu-hal-deposer a{
		position: absolute;
		display:inline-block;
		/* border: 1px solid blue; */
	}

	#bu-hal-deposer a#hal{
		top: 12%; 
		left: 38%; 
		width: 16%; 
		height: 8%;
	}

	#bu-hal-deposer a#hal-create{
		top: 28%; 
		left: 56%;
		width: 15%; 
		height: 7%;
	}

	#bu-hal-deposer a#hal-connect{
		top: 28%; 
		left: 75%;
		width: 20%; 
		height: 7%;
	}

	#bu-hal-deposer a#hal2{
		top: 89%; 
		left: 77%; 
		width: 17%; 
		height: 5%;
	}

	#bu-hal-deposer a#hal-loi{
		top: 48%; 
		left: 2%; 
		width: 25%; 
		height: 4%;
	}

	#bu-hal-deposer a#hal-inscription{
		top: 27%; 
		left: 10%; 
		width: 15%; 
		height: 4%;
	} 

	@media screen and (max-width: 500px){
		#bu-hal-deposer{
			margin: 0 auto;
			position: relative;
			width: 350px;
		}
		#bu-hal-deposer img{
			width: 350px;	
		}
	}

	@media screen and (min-width: 501px){
		#bu-hal-deposer{
			margin: 0 auto;
			position: relative;
			width: 450px;
		}
		#bu-hal-deposer img{
			width: 450px;	
		}
	}

	@media screen and (min-width: 768px){
	    #bu-hal-deposer{
			margin: 0 auto;
			position: relative;
			width: 550px;
		}
		#bu-hal-deposer img{
			width: 550px;	
		}
	}

	

	@media screen and (min-width: 1200px){
		#bu-hal-deposer{
			margin: 0 auto;
			position: relative;
			width: 700px;
		}
		#bu-hal-deposer img{
			width: 700px;	
		}
	}

</style>

<div id="bu-hal-deposer">
	<img src="<?= $baseUrl ?>/images/comment-deposer-hal.jpg" alt="Schéma de dépôt dans hal"/>
	<a href="https://hal.uca.fr" id="hal" aria-label="Lien vers hal"></a>
	<a href="https://hal.uca.fr/user/create" id="hal-create" aria-label="Lien vers la création d'un compte hal"></a>
	<a href="https://bu.uca.fr/ressources/hal-clermont/open-access-weeks-2021#inscription" id="hal-inscription" aria-label="s'inscrire à hal"></a>
  	<a aria-label="Lien pour se connecter à hal" id="hal-connect" href="https://cas.ccsd.cnrs.fr/cas/login?service=https%3A%2F%2Fhal.uca.fr%2Fuser%2Flogin%3Furl%3Dhttps%253A%252F%252Fhal.uca.fr%252F%252Fuser%252Fcreate" ></a>
	<a aria-label="Lien vers hal" id="hal2" href="https://hal.uca.fr/" ></a>
	<a aria-label="Loi pour une république numérique" href="https://www.legifrance.gouv.fr/jorf/article_jo/JORFARTI000033202841"  id="hal-loi"></a>
</div>

