<?php
/**
 * script d'interrogation de l'api de hal, afin de récupérer :
 * * les articles de revues publiés en 2020 qui sont des références avec fichier ou qui sont des références sans fichiers mais qui ont un accès externe au texte
 * * les articles de revues publiés en 2020 qui sont des références sans fichiers et qui n'ont pas d'accès externe au texte
 */


require ('functions.php');
$baseUrl = 'https://'.$_SERVER[HTTP_HOST]. dirname($_SERVER['PHP_SELF']);
$url = $baseUrl.'/getArticles.php';
$results = getApi($url);
$jsonResults = json_decode($results);
$nbWithFiles = $jsonResults->nbWithFiles;
$nbWithoutFiles = $jsonResults->nbWithoutFiles;
?>

<style>
	    #hal-uca-counters{
			margin: 0 auto;
			width: 600px;
			height: 500px;
		}
	
</style>


<script type="text/javascript">
window.onload = function()
{
	
	
	
	/**
     *  fonction d'affichage du texte
     */
	function drawText(rootText, text, nbArts, textColor){
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
	function drawGraduating(){
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
	 function drawJauge(){
			ctx.beginPath();
			ctx.strokeStyle = darkColor;

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
			resetShadow();

			//traçage du remplissage
			ctx.strokeStyle = fillColor;
			ctx.fillRect(xpos, ypos, jaugeWidth, -jaugeHeight/100*jaugePercent);
			
			//affichage du poucentage en cours de remplissage
			ctx.font = "bold 15px Arial ";

        	ctx.fillText(jaugePercent + ' %', xpos - 5 + (jaugeWidth/2), ypos -(jaugeHeight/100*jaugePercent) - 10);

		}

	function resetShadow(){
		//reset de l'ombre pour ne pas en avoir sur le prochain traçage
		ctx.shadowOffsetX = 0;
		ctx.shadowOffsetY = 0;
		ctx.shadowBlur = 0;
	}

	function drawCanvasBorder(){
		//init des coordonnées pour le traçage du cadre avec coins arrondis
		ctx.shadowColor = shadowColor;
		ctx.shadowBlur = 4;
		ctx.shadowOffsetX = 2;
		ctx.shadowOffsetY = 2;
		ctx.strokeStyle = fillColor;
		

		//début du tracé en bas à gauche
        xpos = 0 + offset;
        ypos = H - offset;
        
		//largeur et hauteur du cadre
		var borderWidth =  W - (2*offset);
		var borderHeight = H - (2*offset);

        //traçage de la bordure
		ctx.beginPath();
		
		//coin inférieur gauche
		ctx.moveTo(xpos + radius, ypos);
		
		//ligne basse
		ctx.lineTo( borderWidth - radius , ypos);

		//arrondi en bas à droite
		ctx.arcTo( xpos + borderWidth  , ypos,  xpos + borderWidth  , ypos - radius , radius);

		//ligne côté droit
		ctx.lineTo(xpos + borderWidth , ypos - borderHeight + radius);

		//arrondi en haut à droite
		ctx.arcTo(xpos + borderWidth, ypos - borderHeight, xpos + borderWidth - radius, ypos - borderHeight, radius);

		//ligne haute
		ctx.lineTo(xpos +radius  , ypos - borderHeight);

		//coin arrondi en haut à gauche
		ctx.arcTo(xpos, ypos - borderHeight, xpos, ypos - borderHeight + radius, radius);

		//ligne gauche
		ctx.lineTo(xpos, borderHeight - radius);
		
		//coin arrondi en bas à gauche
		ctx.arcTo(xpos, ypos, xpos + radius, ypos, radius);

		//traçage de la ligne
		ctx.stroke();

		resetShadow();

	}


	/*
	 * Initialise le canvas en créant le texte avec l'affichage des compteurs et le rectangle gradué avec la jauge
	 */
	function init()
	{
		//effacement du canvas
        ctx.clearRect(0, 0, W, H);

		drawCanvasBorder();
				
        /**
        *   affichage du nombre d'articles sans fichiers
         */
        //calcul des coordonnées
        xpos = W/100*38;
        ypos = H/100*25;
        //traçage du texte
        drawText(rootText, textWithoutFiles, nbWithoutFiles, darkColor);

        /**
        *   affichage du nombre d'articles avec fichiers
         */
        //calcul des coordonnées
        xpos = W/100*38;
        ypos = H/100*75;

        //traçage du texte
        drawText(rootText, textWithFiles, nbWithFiles, fillColor);

		

		/**
        *   traçage de la jauge
         */
        xpos = W/100*10;
        ypos = H-(H/100*9);

		//traçage de la jauge
		drawJauge();
		
		//tracer des graduations et des pourcentages
		drawGraduating();

		/**
		* affichage des logos
		*/
		//calcul des coordonnées
        xpos = W/100*90;
        ypos = H/100*70;
		ctx.drawImage(cadenas, xpos, ypos);

		//calcul des coordonnées
        xpos = W/100*55;
        ypos = H/100*35;
		
		//rotation du macaron
		ctx.save();
		ctx.translate(xpos+(macaronWidth/2), ypos + (macaronHeight/2));
		ctx.rotate(25*Math.PI/180.0);
		ctx.translate(-(xpos+(macaronWidth/2)), -(ypos + (macaronHeight/2)));
		ctx.drawImage(macaron, xpos, ypos, macaronWidth, macaronHeight);
		ctx.restore();

		//ajout d'un lien cliquable sur le macaron
		//calcul des coordonnées
		mouseX = xpos;
		mouseY = ypos;

		//ajout des évenements
		canvas.addEventListener("mousemove", function(ev){
			var x,y;
			x = ev.pageX;
			y = ev.pageY;
			x-=canvas.offsetLeft;
			y-=canvas.offsetTop;

			//is the mouse over the link?
			if((x>=mouseX && x <= (mouseX + macaronWidth)) && (y>=mouseY && y<=(mouseY+macaronHeight))){
				document.body.style.cursor = "pointer";
				onLink=true;

				// alert(onLink);
			}
			else{
				document.body.style.cursor = "";
				onLink=false;
			}
		});

		canvas.addEventListener("click", function(){
			if (onLink)  {
				window.location = macaronLink;
			}
		});
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

	var container = document.getElementById('hal-uca-counters');
	var canvas = document.getElementById("hal-uca-canvas");
	
	if (canvas.getContext){
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
		var rootText = 'articles de revues de 2020';
		var textWithoutFiles = "attendent leur texte intégral dans HAL";
		var textWithFiles = "sont en libre accès depuis HAL";

		//déclage de la bordure par rapport au cadre pour l'ombre et les coins arrondis
		var offset = 8;
		var radius = 10;

		//cordonnées d'origine d'un tracé
		var xpos = 0;
		var ypos = 0;

		var fillColor = "#dd7226";
		var darkColor = "#515151";
		var shadowColor = "#777";

		//pourcentage de remplissage de la jauge
		var jaugePercent = 0;
		var finalJaugePercent = Math.round(nbWithFiles * 100 / nbArticles);
		
		//taille de la jauge
		var jaugeHeight = H/100*80;
        var jaugeWidth = W/100*18;


		
		var cadenas = new Image();
		cadenas.src = "<?= $baseUrl ?>" + '/images/logoCadenas_orange.png';
		cadenas.onload = function () {
        };

		var macaronWidth = 150;
		var macaronHeight = 150;
		var macaronLink = 'https://uca.fr';
		var macaron = new Image();
		var onLink = false;
		var mouseX = 0;
		var mouseY = 0;

		macaron.src = "<?= $baseUrl ?>" + '/images/HAL_UCA_macaron.png';
		macaron.onload = function () {
        };
		
		//traçage du dessin
		draw();
		
    
		/* Crée une boucle sur la fonction draw() avec un intervalle de 30 secondes */
		redraw_loop = setInterval(draw, 30000);
	}


}
</script>

<?php	if (empty($nbWithFiles) && empty($nbWithoutFiles)): ?>
			<p> Aucune donnée disponible pour le moment</p>
<?php 	else : ?>
			<div id="hal-uca-counters">
			    <canvas id="hal-uca-canvas">Graphique HAL non disponible</canvas>
            </div>
<?php   endif ?>

	

