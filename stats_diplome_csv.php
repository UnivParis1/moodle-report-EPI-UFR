<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once('../../lib/accesslib.php');
require_once('locallib.php');
require_login();

ini_set('max_execution_time', 600);
ini_set('memory_limit', '2048M');
@ini_set('display_errors', '1'); // NOT FOR PRODUCTION SERVERS!
$CFG->debug = 38911;  // DEBUG_DEVELOPER // NOT FOR PRODUCTION SERVERS!
$CFG->debugdisplay = true;   // NOT FOR PRODUCTION SERVERS!

/**
 * vérification que l'utilisateur est un administrateur
 */

if (is_siteadmin() || is_userauthorized($USER->id)) {
	$annee = isset($_GET['annee']) ? $_GET['annee'] : 0;
	$typestat = isset($_GET['typestat']) ? $_GET['typestat'] : '';
	if (!empty($annee)) {
		$path = '/'.$annee;
		//recherche de l'établissement
		$selectetab = "SELECT id  from {course_categories} where parent=? ;";
		$etab = $DB->get_record_sql($selectetab, array($annee));
		if (!empty($etab->id)) {
			$selectufr = "SELECT id,name,path from {course_categories} where parent = :parent  order by name;";
			$ufrs  = $DB->get_records_sql($selectufr, array('parent'=>$etab->id));
			if (!empty($typestat)) {
				if (!empty($ufrs)) {
					$nb_licence = 0;
					$nb_master = 0;
					$nb_doctorat = 0;
					$nb_autre = 0;
					$nbtotal = 0;
					$count = false;
					$exist_sql_total = false;
					$requete_etab_dedoublonnee = false;
					include 'SQL/'.$typestat.'.php';
			        foreach( $ufrs as $i=>$ufr) {
						$data = array();
			            $id_licence = 0;
			            $id_master = 0;
			        	$id_doctorat = 0;
			        	$select_id_licences = "SELECT id  from {course_categories} where parent=? and upper(name) like '%LICENCE%'";
			        	$objdiplomes = $DB->get_record_sql($select_id_licences, array($ufr->id));
			        	if (!empty($objdiplomes->id)) $id_licence = $objdiplomes->id;
						$select_id_master = "SELECT id  from {course_categories} where parent=? and upper(name) like '%MASTER%'";
			        	$objdiplomes = $DB->get_record_sql($select_id_master, array($ufr->id));
			        	if (!empty($objdiplomes->id)) $id_master = $objdiplomes->id;
			      		$select_id_doctorat = "SELECT id  from {course_categories} where parent=? and upper(name) like '%DOCTORAT%'";
			       		$objdiplomes = $DB->get_record_sql($select_id_doctorat, array($ufr->id));
			        	if (!empty($objdiplomes->id)) $id_doctorat = $objdiplomes->id;
			        	
			        	$data=  get_stats($SELECT,$id_licence,$id_master,$id_doctorat,$ufr->id,$ufr->name,$count,true,$calculautre);
						$nb_licence += $data[1];
						$nb_master  += $data[2];
						$nb_doctorat += $data[3];
						$nb_autre += $data[4];
					}
					//Calcul du nombre total d'éléments
				
					if ($requete_etab_dedoublonnee) {
						if ($count) {
							$listeEtabboucle= $DB->get_records_sql($SELECT, array('%/'.$etab->id.'/%','%/'.$etab->id.'/%'));
							foreach($listeEtabboucle as $cle=>$val) {if (!empty($val->id)) $listeEtab[$val->id]= $val->id;}
							$nbtotal = count($listeEtab);
						} else {
							$listeEtab= $DB->get_record_sql($SELECT, array('%/'.$etab->id.'/%','%/'.$etab->id.'/%'));
       						 if (empty($listeEtab->nb)) $listeEtab->nb = 0;
       						 $nbtotal =  $listeEtab->nb;
						}
					} else {
						$nbtotal = $nb_licence + $nb_master + $nb_doctorat + $nb_autre;
					}
					$array_csv[]= array(get_string($typestat,'local_up1reportepiufr'),$nbtotal);
					$array_csv[]= array(get_string('licence','local_up1reportepiufr'),$nb_licence);		
					$array_csv[]= array(get_string('master','local_up1reportepiufr'),$nb_master);		
					$array_csv[]= array(get_string('doctorat','local_up1reportepiufr'),$nb_doctorat);		
					$array_csv[]= array(get_string('autre','local_up1reportepiufr'),$nb_autre);
				}
				download_send_headers("data_export_".$typestat.'_'. date("Y-m-d") . ".csv");
				echo array2csv($array_csv);
				exit();
			}
		}
	}
}
