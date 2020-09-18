<?php
/**
 * script d'interrogation de l'api de hal, afin de récupérer :
 * * les articles de revues publiés en 2020 qui sont des références avec fichier ou qui sont des références sans fichiers mais qui ont un accès externe au texte
 * * les articles de revues publiés en 2020 qui sont des références sans fichiers et qui n'ont pas d'accès externe au texte
 */


require ('functions.php');

$url = 'https://'.$_SERVER[HTTP_HOST]. dirname($_SERVER['PHP_SELF']).'/getArticles.php';
$results = getApi($url);
$jsonResults = json_decode($results);
$nbWithFiles = $jsonResults->nbWithFiles;
$nbWithoutFiles = $jsonResults->nbWithoutFiles;
var_dump($results);
?>
<style>
	    #hal-uca-counters{
			margin: 0 auto;
			width: 600px;
			height: 500px;
		}

		canvas{
			box-shadow: 5px 5px 5px #ccc;
		}
</style>


<script type="text/javascript">
window.onload = function()
{
	var container = document.getElementById('hal-uca-counters');
	var canvas = document.getElementById("hal-uca-canvas");
	
	var ctx = canvas.getContext("2d");
	
	//récupération des dimensions css du conteneur du canvas
	var cssWidth = window.getComputedStyle(container).getPropertyValue('width');
	var cssHeight = window.getComputedStyle(container).getPropertyValue('height');
	var W = cssWidth.split('px')[0];
	var H = cssHeight.split('px')[0];
	
	canvas.width  = W;
	canvas.height = H; 
		
	var animation_loop, redraw_loop;


	var nbWithFiles = <?= $nbWithFiles ?>;
	var nbWithoutFiles = <?= $nbWithoutFiles?>;
    var nbArticles = nbWithoutFiles + nbWithFiles;
    var rootText = 'articles de revue de 2020';
    var textWithoutFiles = "attendent leur texte intégral dans HAL";
    var textWithFiles = "sont en libre accès depuis HAL";

    var xpos = 0;
    var ypos = 0;

    var fillColor = "#dd7226";
    var darkColor = "#333";
	var shadowColor = "#777";

	//pourcentage de remplissage de la jauge
	var jaugePercent = 0;
	var finalJaugePercent = Math.round(nbWithFiles * 100 / nbArticles);
    
	
	/**
     *  fonction d'affichage du texte
     */
	function drawText(xpos, ypos, rootText, text, nbArts, textColor){
        ctx.fillStyle = textColor;
        ctx.font = "bold 35px Arial ";
        ctx.fillText(nbArts, xpos, ypos + 15);

        //ajout du texte vers la droite 
        ctx.font = " bold 15px Arial";
        ctx.fillText(rootText, xpos + 80, ypos);
        
        //ajout du texte vers le bas
        ctx.fillText(text, xpos + 80, ypos + 15);
     }

	 /**
     *  fonction de traçage des graduations et des pourcentages
     */
	function drawGraduating(xpos, ypos, jaugeHeight){
		ctx.strokeStyle = darkColor;

		//calcul du pas entre les graduations		
		var yStep = jaugeHeight/16;
		
		for (i=1 ; i<16 ; i++){
			//placement au bon echelon
			ctx.moveTo(xpos, ypos - (yStep*i));

			//si on est à 25, 50 ou 75% on trace un trait plus grand
			if (i%4==0){
				//traçage de la ligne
				ctx.lineTo(xpos + 20 , ypos - (yStep*i));

				//Affichage des pourcentages à gauche des graduations
				ctx.fillStyle = darkColor;
        		ctx.font = "bold 15px Arial ";
        		ctx.fillText(25*i/4+" %", xpos-40, ypos - (yStep*i) + 5);
			}
			else{
				//traçage de la ligne
				ctx.lineTo(xpos + 10 , ypos - (yStep*i));
			}
		}

		ctx.stroke();
     }

	/**
	*	fonction de traçage de la jauge
	*/
	 function drawJauge(xpos,ypos, jaugeHeight, jaugeWidth, radius){
			//coin inférieur gauche
			ctx.moveTo(xpos, ypos);

			ctx.shadowColor = shadowColor;
			ctx.shadowBlur = 6;
			ctx.shadowOffsetX = 2;
			ctx.shadowOffsetY = 2;

			//coin inférieur droit
			ctx.lineTo(xpos + jaugeWidth , ypos);

			//coin supérieur droit
			ctx.lineTo(xpos + jaugeWidth , ypos - jaugeHeight + radius);
			
			//coin arrondi
			ctx.lineWidth = 2;
			ctx.arcTo(xpos + jaugeWidth , ypos - jaugeHeight,   xpos + jaugeWidth - radius , ypos - jaugeHeight, radius);
			ctx.arcTo(xpos, ypos - jaugeHeight, xpos, ypos - jaugeHeight + radius, radius);

			//coint supérieur gauche
			ctx.lineTo(xpos , ypos - jaugeHeight + radius);

			//fermeture du rectangle
			ctx.lineTo(xpos, ypos);
			
			//traçage de la ligne
			ctx.stroke();

			//reset de l'ombre pour ne pas en avoir sur le prochain traçage
			ctx.shadowOffsetX = 0;
			ctx.shadowOffsetY = 0;
			ctx.shadowBlur = 0;

			//traçage du remplissage
			ctx.strokeStyle = fillColor;
			ctx.fillRect(xpos, ypos, jaugeWidth, -jaugeHeight/100*jaugePercent);
			
			//affichage du poucentage en cours de remplissage
			ctx.font = "bold 15px Arial ";

        	ctx.fillText(jaugePercent + ' %', xpos - 5 + (jaugeWidth/2), ypos -(jaugeHeight/100*jaugePercent) - 10);

		}



	/*
	 * Initialise le canvas en créant le texte avec l'affichage des compteurs et le rectangle gradué avec la jauge
	 */
	function init()
	{
		//effacement du canvas
        ctx.clearRect(0, 0, W, H);

        //traçage de la bordure
		ctx.strokeStyle = fillColor;
        ctx.strokeRect(0, 0, W, H); 

        /**
        *   affichage du nombre d'articles sans fichiers
         */
        //calcul des coordonnées
        xpos = W/100*38;
        ypos = H/100*25;
        //traçage du texte
        drawText(xpos, ypos, rootText, textWithoutFiles, nbWithoutFiles, darkColor);

        /**
        *   affichage du nombre d'articles avec fichiers
         */
        //calcul des coordonnées
        xpos = W/100*38;
        ypos = H/100*75;
        //traçage du texte
        drawText(xpos, ypos, rootText, textWithFiles, nbWithFiles, fillColor);

		/**
        *   traçage de la jauge
         */
        ctx.beginPath();
		ctx.strokeStyle = darkColor;
        xpos = W/100*10;
        ypos = H-(H/100*9);
        var jaugeHeight = H/100*80;
        var jaugeWidth = W/100*18;
        var radius = 10;

		//traçage de la jauge
		drawJauge(xpos,ypos, jaugeHeight, jaugeWidth, radius);
		
		//tracer des graduations et des pourcentages
		drawGraduating(xpos,ypos, jaugeHeight);
	}

	/*
	 * Calcul du nouveau taux à afficher, rafraîchit les valeurs avec une requête AJAX
	 */
	function draw()
	{
		if (typeof animation_loop != undefined) clearInterval(animation_loop);


		var request = new XMLHttpRequest();
		request.onreadystatechange = function() {
			if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
				var response = JSON.parse(this.responseText);
				
				//récupération des résultats et affectation aux variables de l'animation
				nbWithFiles = response.nbWithFiles;
				nbWithoutFiles = response.nbWithoutFiles;
    			nbArticles = nbWithoutFiles + nbWithFiles;
				
				//on réinitialise la jauge
				jaugePercent = 0;

				//déclenchement de l'animation toutes les 30 millisecondes
				animation_loop = setInterval(animate_to, 30 );
			}
		};
		request.open("GET", "<?= $url ?>");
		request.send();
	}

	/*
	 * Crée l'animation du cercle
	 */
	function animate_to()
	{
		if (jaugePercent > finalJaugePercent) clearInterval(animation_loop);

		if (jaugePercent <= finalJaugePercent)
		 	++jaugePercent;
		else 	
			--jaugePercent;

		init();
	}

	draw();


	/* Crée une boucle sur la fonction draw() avec un intervalle de 30 secondes */
	redraw_loop = setInterval(draw, 30000);
}
</script>

<?php	if (empty($nbWithFiles) && empty($nbWithoutFiles)): ?>
			<p> Aucune donnée disponible pour le moment</p>
<?php 	else : ?>
			<div id="hal-uca-counters">
			    <canvas id="hal-uca-canvas">Graphique HAL non disponible</canvas>
            </div>
<?php   endif ?>

	

