<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once('../../lib/accesslib.php');
require_once('locallib.php');
require_login();

ini_set('max_execution_time', 600);
ini_set('memory_limit', '2048M');

/**
 * vérification que l'utilisateur est un administrateur
 */
if (is_siteadmin()) {
	$libelle_annee = '';
	$annee = isset($_GET['annee']) ? $_GET['annee'] : 0;
	$typestat = isset($_GET['typestat']) ? $_GET['typestat'] : '';
	if ($annee!=0) {
		$select_annee = "SELECT name from {course_categories} where id=? ;";
		$obj = $DB->get_record_sql($select_annee, array($annee));
		if (!empty($obj->name)) $libelle_annee = $obj->name;
	}
	if (!empty($annee)) {
		$path = '/'.$annee;
		//recherche de l'établissement
		$selectetab = "SELECT id  from {course_categories} where parent=? ;";
		$etab = $DB->get_record_sql($selectetab, array($annee));
		if (!empty($etab->id)) {
			$selectufr = "SELECT id,name,path from {course_categories} where parent = :parent  order by name;";
			$ufrs  = $DB->get_records_sql($selectufr, array('parent'=>$etab->id));
			if (!empty($typestat)) {
				$array_csv[] = array(	get_string('diplome','local_up1reportepiufr'),
									get_string('licence','local_up1reportepiufr'),
									get_string('master','local_up1reportepiufr'),
									get_string('doctorat','local_up1reportepiufr'),
									get_string('autre','local_up1reportepiufr'),
									get_string('global','local_up1reportepiufr'));
				if (!empty($ufrs)) {
					$nb_licence = 0;
					$nb_master = 0;
					$nb_doctorat = 0;
					$nb_autre = 0;
					$count = false;
					$exist_sql_total = false;
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
			        	$array_csv[]=  get_stats($SELECT,$id_licence,$id_master,$id_doctorat,$ufr->id,$ufr->name,$count,true,$calculautre);
					}
				}
				download_send_headers(str_replace(' ','_',$libelle_annee)."_".$typestat.'_'. date("Y-m-d") . ".csv");
				echo array2csv($array_csv);
				exit();
			}
		}
	}
}
