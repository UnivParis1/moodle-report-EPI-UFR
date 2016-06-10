<?php
require_once("../../config.php");
require_once('../../lib/accesslib.php');
require_once('locallib.php');
require_login();

ini_set('max_execution_time', 600);
ini_set('memory_limit', '2048M');

/**
 * vérification que l'utilisateur est un administrateur
 */


if (is_siteadmin()) {
	$annee = 0;
	$path = '';
	if (!empty($_GET['id']))  {
		$id= $_GET['id'];
		$selectufr = "SELECT id,name,path  from {course_categories} where id=? ;";
		$objufr = $DB->get_record_sql($selectufr, array($id));
		if (!empty($objufr->name)) $ufrname = $objufr->name;
		if (!empty($objufr->path)) $path = $objufr->path;
		$select_epis= "	SELECT id, fullname, timemodified 
						FROM {course} 
						WHERE category IN (
						 	SELECT id from {course_categories} where path like ? ) 
						ORDER BY fullname";
		$select_epis="SELECT  C.id, C.fullname, C.timemodified 
        	FROM {course} C 
        	INNER JOIN {course_categories} CC on ( C.category =  CC.id) 
        	WHERE (CC.path LIKE ? OR CC.path LIKE ? )
        	ORDER BY C.fullname";
		$epis = $DB->get_records_sql($select_epis,array($path,$path.'/%'));
   	 	$data[] = array(
				'ID',
				get_string('col01','local_up1reportepiufr'),
				get_string('col02','local_up1reportepiufr'),
 				get_string('col03','local_up1reportepiufr'),
 				get_string('col04','local_up1reportepiufr'), 
 				get_string('col05','local_up1reportepiufr'),
				get_string('col06','local_up1reportepiufr'),
				get_string('col07','local_up1reportepiufr'),
				get_string('col08','local_up1reportepiufr'),
				get_string('col09','local_up1reportepiufr'),
				get_string('col10','local_up1reportepiufr'),
				get_string('col11','local_up1reportepiufr'),
 	 			get_string('col12','local_up1reportepiufr'),
 				get_string('col13','local_up1reportepiufr'),
  				get_string('col14','local_up1reportepiufr'),
  				get_string('col15','local_up1reportepiufr'),
 				get_string('col16','local_up1reportepiufr'),
 				get_string('col17','local_up1reportepiufr'),
 				get_string('col18','local_up1reportepiufr'),
  				get_string('col19','local_up1reportepiufr'),
  				get_string('col20','local_up1reportepiufr'),
  				get_string('col21','local_up1reportepiufr'),
  				get_string('col22','local_up1reportepiufr'),
  				get_string('col23','local_up1reportepiufr'),
  				get_string('col24','local_up1reportepiufr'),
				
 				get_string('col26','local_up1reportepiufr'),
 				get_string('col27','local_up1reportepiufr'),
   	 	);
   	 	foreach($epis as $i=>$epi) {
   	 		// Recherche du context
			$context = context_course::instance($epi->id);
			list($contextlist, $cparams) = $DB->get_in_or_equal($context->get_parent_context_ids(true));
			$nb_responsable_epi = getNbResponsableEpi($contextlist, $cparams);
			$nb_enseignant_editeur = getNbEnseignantsEditeurs($contextlist, $cparams);
   	 		$nb_enseignant_non_editeur = getNbEnseignantsNonEditeurs($contextlist, $cparams);
   	 		$nb_etudiants = getNbEtudiantsInscrits($contextlist, $cparams);
   	 		list($nb_ppt,$nb_doc,$nb_pdf,$nb_autre_format) = FileDetails($epi->id);
   	 		$nb_file = $nb_ppt + $nb_doc + $nb_pdf + $nb_autre_format;
   	 		$details="PPT ($nb_ppt), DOC ($nb_doc), PDF ($nb_pdf), Autre ($nb_autre_format)";
   	 		$nb_page = intval(getNbPages($epi->id));
   	 		$nb_url = intval(getNbURL($epi->id));
			$nb_ressources = $nb_url + $nb_page + $nb_ppt + $nb_doc + $nb_pdf + $nb_autre_format;
			$nb_activite_forum = getNbForums($epi->id);
			$nb_contribution_forum = getNbContributionsForums($epi->id);
   	 		$nb_activite_sondage = getNbSurvey($epi->id);
	   	 	$nb_activite_feedback = getNbFeedbacks($epi->id);
	   	 	$nb_activite_bdd =getNbDatabases($epi->id);
	   	 	$nb_activite_glossaire = getNbGlossary($epi->id);
	   	 	$nb_activite_test= getNbTests($epi->id);
	   	 	$nb_activite_externe = getNbExternalActivities($epi->id);
	   	 	$nb_devoir_remis = getNbDevoirsRemis($epi->id);
	   	 	$nb_activites = $nb_activite_forum + $nb_activite_sondage + $nb_activite_sondage + $nb_activite_bdd + $nb_activite_glossaire + $nb_activite_test + $nb_activite_externe;
	   	 	$nb_vues = getNbVues($epi->id);
			$have_student_key = HaveStudentKey($epi->id);
			$have_guest_key = HaveGuestKey($epi->id);
			$have_free_access = HaveFreeAccess($epi->id);
			$percent_default_section = getPercentDefaultSection($epi->id);
   	 		$data[] = array(	
					$epi->id,	
   	 				$epi->fullname,	
   	 				getNiveauRattachement($epi->id),										
   	 				getMailResponsableEpiByIdEpiForCSV($contextlist, $cparams),	
   	 				$nb_responsable_epi,
   	 				$nb_enseignant_editeur,	
   	 				$nb_enseignant_non_editeur,	
   	 				$nb_etudiants,	
   	 				$have_student_key,
   	 				$have_guest_key,
   	 				$have_free_access,
   					$percent_default_section.'%',
   					$nb_file,	
   					$details,	
   					$nb_url,	
   					$nb_page,
   					$nb_activite_forum,	
   					$nb_contribution_forum,
   					$nb_activite_sondage,
   					$nb_activite_feedback,	
   					$nb_activite_bdd,
   					$nb_activite_glossaire,	
   					$nb_activite_test,
   					$nb_activite_externe,
   					$nb_devoir_remis,
   					$nb_vues,	
   					date('d/m/Y', $epi->timemodified).'<br /> à '.date('H:i:s', $epi->timemodified),	
   	 		 );
   	 	}
}
		download_send_headers("observatoire".$_GET['id'].'_'. date("Y-m-d") . ".csv");
		echo array2csv($data);
		exit();
}
