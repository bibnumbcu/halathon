<?php
/**
 * script d'interrogation de l'api de hal, afin de récupérer :
 * * les articles de revues publiés en 2020 qui sont des références avec fichier ou qui sont des références sans fichiers mais qui ont un accès externe au texte
 * * les articles de revues publiés en 2020 qui sont des références sans fichiers et qui n'ont pas d'accès externe au texte
 * réponse en json
 */


require ('functions.php');


$apiUrl = 'https://api.archives-ouvertes.fr/search/clermont-univ/';

// 1ère requête avec tous les articles de type file en 2020 et tous les articles de type notice avec accès externe au fichier en 2020
$firstQuery = $apiUrl.'?q=*:*&fq=docType_s:ART&fq=producedDateY_i:2021&fq='.urlencode('submitType_s:file OR (submitType_s:notice AND linkExtId_s:(openaccess OR arxiv OR pubmedcentral))');

//2ème requête pour les articles de type notice de 2020 n'ayant pas d'accès extérieur au texte
$secondQuery = $apiUrl.'?q=*:*&fq=docType_s:ART&fq=producedDateY_i:2021&fq=submitType_s:notice&fq=NOT(linkExtId_s:openaccess%20OR%20arxiv%20OR%20pubmedcentral)&wt=json';

$results = array('nbWithFiles' => 0, 'nbWithoutFiles' => 0);

//récupération des résultats
$firstResults = getApi($firstQuery);
$firstResultsArray = json_decode($firstResults);
$results['nbWithFiles'] = $firstResultsArray->response->numFound;

$secondResults = getApi($secondQuery);
$secondResultsArray = json_decode($secondResults);
$results['nbWithoutFiles'] = $secondResultsArray->response->numFound;


header('Content-type: application/json');
echo json_encode( $results );

?>
