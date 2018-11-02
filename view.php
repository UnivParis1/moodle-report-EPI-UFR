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
$idcategorie=0;
$url = new moodle_url('/local/up1reportepiufr/index.php');
$PAGE->set_url($url);
$context = context_user::instance($USER->id);
$PAGE->set_context($context);
$PAGE->requires->js(new moodle_url('/local/jquery/jquery.js'), true);
$PAGE->requires->css(new moodle_url('/local/up1reportepiufr/css/up1reportepiufr.css'));
//$PAGE->requires->js(new moodle_url('/local/up1reportepiufr/jsapi/jsapi.js'));
//echo '<script type="text/javascript" src="jsapi/jsapi.js"></script>';
//$PAGE->navbar->add(get_string('analysis', 'feedback'));


/**
 * vérification que l'utilisateur est un administrateur
 */

if (is_siteadmin() || is_userauthorized($USER->id)) {
	$annee = 0;
	$only_one_editing_teacher = 0;
	$more_than_one_editing_teacher = 0;
	$one_editing_teacher_and_other_teachers = 0;
	$other_case = 0;
	$total_ufr = 0;
	$path = '';
	if (!empty($_GET['id']))  {
		$id= $_GET['id'];
		$selectufr = "SELECT id,name,path  from {course_categories} where id=? ;";
		$objufr = $DB->get_record_sql($selectufr, array($id));
		if (!empty($objufr->name)) $ufrname = $objufr->name;
		if (!empty($objufr->path)) $path = $objufr->path;
		$heading_page_libelle = "Observatoire des EPI de la composante &laquo; $ufrname &raquo;";
		$title_page_libelle = "Observatoire des EPI de la composante &laquo; $ufrname &raquo;";
		$heading_output_libelle = "Observatoire des EPI de la composante &laquo; $ufrname &raquo;";
		
		$PAGE->set_heading($heading_page_libelle);
		$PAGE->set_title($title_page_libelle);
		$PAGE->set_pagelayout('report');
		echo $OUTPUT->header();
		echo $OUTPUT->heading($heading_output_libelle);
		echo $OUTPUT->box_start('generalbox boxaligncenter boxwidthwide');
		echo '<script type="text/javascript" src="jsapi/jsapi.js"></script>';
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
		
		$table_resume_ufr = new html_table();
		$table_infos_generales = new html_table();
		$table_infos_activites = new html_table();
		$table_infos_ressources = new html_table();
		$table_resume_ufr->align = array('left','center');
   	 	$table_infos_generales->align = array(
				'left',
				'center',
				'center', 
				'center',  
 				'center',  
				'center', 
				'center', 
				'center', 
				'center', 
				'center', 
				'center', 
  				'center', 
  				'left'
   	 	);

        $table_infos_generales->size = array(
                                '15%',
                                '5%',
                                '15%',
                                '5%',
                                '5%',
                                '5%',
                                '5%',
                                '5%',
                                '5%',
                                '5%',
                                '5%',
                                '5%',
                                '15%'
                );
   	 	$table_infos_activites->align = array(
				'left',
  				'center', 
 				'center', 
  				'center', 
 				'center', 
 				'center', 
 				'center', 
 				'center', 
  				'center', 
  				'center', 
  				'center', 
   	 	);
		$table_infos_activites->size = array(
				'25%',
                                '5%',
                                '5%',
                                '5%',
                                '5%',
                                '5%',
                                '5%',
                                '5%',
				'5%',
				'5%',
				'25%'
		);
   	 	$table_infos_ressources->align = array(
				'left',
	 			'center', 
	 			'center', 
	 			'center', 
 				'left',  
   	 	);
/*   	 	$table_infos_generales->wrap = array(
				null,
				'nowrap',
				'nowrap', 
				'nowrap',  
 				'nowrap',  
				'nowrap', 
				'nowrap', 
				'nowrap', 
				'nowrap', 
				'nowrap', 
				'nowrap', 
  				'nowrap', 
  				'nowrap'
   	 	);
 */  	 	$table_infos_activites->wrap = array(
   	 			null,
	 			'nowrap', 
 				'nowrap', 
  				'nowrap', 
 				'nowrap', 
  				'nowrap', 
 				'nowrap', 
 				'nowrap', 
 				'nowrap', 
 				'nowrap', 
  				'nowrap', 
  				'nowrap', 
  				'nowrap', 
   	 	);
   	 	$table_infos_ressources->wrap = array(
   	 			null,
	 			'nowrap', 
 				'nowrap'
   	 	);
		$table_resume_ufr->head = array('Indicateurs','valeur');
   	 	$table_infos_generales->head = array(
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
 				get_string('col26','local_up1reportepiufr'),
 				get_string('col27','local_up1reportepiufr'),
				'Ouvert'
   	 	);
   	 	$table_infos_activites->head = array(
				get_string('col01','local_up1reportepiufr'),
 				get_string('col16','local_up1reportepiufr'),
 				get_string('col17','local_up1reportepiufr'),
 				get_string('col18','local_up1reportepiufr'),
  				get_string('col19','local_up1reportepiufr'),
  				get_string('col20','local_up1reportepiufr'),
  				get_string('col21','local_up1reportepiufr'),
  				get_string('col22','local_up1reportepiufr'),
  				get_string('col23','local_up1reportepiufr'),
  				get_string('col24','local_up1reportepiufr'),
   	 	);
   	 	$table_infos_ressources->head = array(
				get_string('col01','local_up1reportepiufr'),
  				get_string('col14','local_up1reportepiufr'),
  				get_string('col15','local_up1reportepiufr'),
 	 			get_string('col12','local_up1reportepiufr'),
 				get_string('col13','local_up1reportepiufr'),
   	 	);
   	 	$nb_responsable_epi = 0;
   	 	$nb_responsable_epi_total = 0;
   	 	$nb_enseignant_editeur = 0;
   	 	$nb_enseignant_editeur_total = 0;
   	 	$nb_enseignant_non_editeur = 0;		
   	 	$nb_enseignant_non_editeur_total = 0;		
   	 	$nb_epis = 0;
   	 	$nb_etudiants = 0;
   	 	$nb_etudiants_total = 0;
   	 	$nb_etudiant_acces_anonyme = 0;
   	 	$nb_activités_totals;
   	 	$nb_ppt_total = 0;
   	 	$nb_doc_total = 0;
   	 	$nb_pdf_total = 0;
   	 	$nb_autre_total = 0;
   	 	$nb_url_total = 0;
   	 	$nb_page_total = 0;
   	 	$nb_activite_forum = 0;
   	 	$nb_activite_forum_total = 0;
   	 	$nb_contribution_forum = 0;
   	 	$nb_contribution_forum_total = 0;
   	 	$nb_activite_sondage = 0;
   	 	$nb_activite_feedback = 0;
   	 	$nb_activite_bdd = 0;
   	 	$nb_activite_glossaire = 0;
   	 	$nb_activite_test= 0;
   	 	$nb_activite_sondage_total = 0;
   	 	$nb_activite_feedback_total = 0;
   	 	$nb_activite_bdd_total = 0;
   	 	$nb_activite_glossaire_total = 0;
   	 	$nb_activite_test_total = 0;
   	 	$nb_activite_externe = 0;
   	 	$nb_activite_externe_total = 0;
   	 	$nb_devoir_remis = 0;
   	 	$nb_devoir_remis_total = 0;
   	 	$nb_vues = 0;
   	 	$nb_epi_acces_anonyme = 0;
   	 	$epi_cumules_ressources = array();
   	 	$epi_cumules_activites = array();
		$min_nb_epi = 100000000;
		$max_nb_epi = 1;
		$nb_oui_07 = 0;
		$nb_oui_08 = 0;
		$nb_oui_09 = 0;
		$percent_default_section_global= 0;
		$nb_vues = 0;
		$nb_vues_total = 0;
	   	$epi_activites_ressources = array();
	   	$nb_activite_totales = 0;
   	 	echo '
    <script type="text/javascript">
      google.load("visualization", "1.1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart2);
      google.setOnLoadCallback(drawChart3);
      google.setOnLoadCallback(drawChart4);';
   	 	foreach($epis as $i=>$epi) {
   	 		$nb_epis++;
   	 		// Recherche du context
			$context = context_course::instance($epi->id);
			list($contextlist, $cparams) = $DB->get_in_or_equal($context->get_parent_context_ids(true));
			$nb_responsable_epi = getNbResponsableEpi($contextlist, $cparams);
			$nb_responsable_epi_total += $nb_responsable_epi ;
			$nb_enseignant_editeur = getNbEnseignantsEditeurs($contextlist, $cparams);
			$nb_enseignant_editeur_total += $nb_enseignant_editeur;
   	 		$nb_enseignant_non_editeur = getNbEnseignantsNonEditeurs($contextlist, $cparams);
   	 		$nb_enseignant_non_editeur_total += $nb_enseignant_non_editeur;
   	 		$nb_etudiants = getNbEtudiantsInscrits($contextlist, $cparams);
   	 		$nb_etudiants_total += $nb_etudiants;
   	 		list($nb_ppt,$nb_doc,$nb_pdf,$nb_autre_format) = FileDetails($epi->id);
   	 		$nb_file = $nb_ppt + $nb_doc + $nb_pdf + $nb_autre_format;
   	 		$details="PPT ($nb_ppt), DOC ($nb_doc), PDF ($nb_pdf), Autre ($nb_autre_format)";
   	 		$nb_page = intval(getNbPages($epi->id));
   	 		$nb_ppt_total += intval($nb_ppt);
   	 		$nb_doc_total += intval($nb_doc);
   	 		$nb_pdf_total += intval($nb_pdf);
   	 		$nb_autre_total += intval($nb_autre_format);
   	 		$nb_url = intval(getNbURL($epi->id));
   	 		$nb_url_total += intval($nb_url);
   	 		$nb_page_total += intval($nb_page);
			$nb_ressources = $nb_url + $nb_page + $nb_ppt + $nb_doc + $nb_pdf + $nb_autre_format;
			if (isset($epi_cumules_ressources[$nb_ressources])) $epi_cumules_ressources[$nb_ressources] = $epi_cumules_ressources[$nb_ressources] + 1; else  $epi_cumules_ressources[$nb_ressources] = 1;
			$nb_activite_forum = getNbForums($epi->id);
			$nb_activite_forum_total += $nb_activite_forum;
			$nb_contribution_forum = getNbContributionsForums($epi->id);
			$nb_contribution_forum_total += $nb_contribution_forum;
   	 		$nb_activite_sondage = getNbSurvey($epi->id);
   	 		$nb_activite_sondage_total += $nb_activite_sondage;
	   	 	$nb_activite_feedback = getNbFeedbacks($epi->id);
	   	 	$nb_activite_feedback_total += $nb_activite_feedback;
	   	 	$nb_activite_bdd =getNbDatabases($epi->id);
	   	 	$nb_activite_bdd_total += $nb_activite_bdd;
	   	 	$nb_activite_glossaire = getNbGlossary($epi->id);
	   	 	$nb_activite_glossaire_total += $nb_activite_glossaire;
	   	 	$nb_activite_test= getNbTests($epi->id);
	   	 	$nb_activite_test_total += $nb_activite_test;
	   	 	$nb_activite_externe = getNbExternalActivities($epi->id);
	   	 	$nb_activite_externe_total = $nb_activite_externe;
	   	 	$nb_devoir_remis = getNbDevoirsRemis($epi->id);
	   	 	$nb_devoir_remis_total += $nb_devoir_remis;
    		$nb_activite_totales += $nb_activite_forum + $nb_activite_sondage + $nb_activite_feedback + $nb_activite_bdd + $nb_activite_glossaire + $nb_activite_test + $nb_activite_externe;
	   	 	$nb_activites = $nb_activite_forum + $nb_activite_sondage + $nb_activite_sondage + $nb_activite_bdd + $nb_activite_glossaire + $nb_activite_test + $nb_activite_externe;
	   	 	if (isset($epi_cumules_activites[$nb_activites]))  $epi_cumules_activites[$nb_activites] = $epi_cumules_activites[$nb_activites] + 1; else $epi_cumules_activites[$nb_activites] = 1;
	   	 	if (isset($epi_cumules_ressources[$nb_ressources]))  {
	   	 		if ($epi_cumules_ressources[$nb_ressources] < $min_nb_epi) $min_nb_epi = $epi_cumules_ressources[$nb_ressources] ;
	   	 		if ($epi_cumules_ressources[$nb_ressources] > $max_nb_epi) $max_nb_epi = $epi_cumules_ressources[$nb_ressources] ;
	   	 	}
	   	 	if (isset($epi_cumules_activites[$nb_activites]))  {
	   	 		if ($epi_cumules_activites[$nb_activites] < $min_nb_epi) $min_nb_epi = $epi_cumules_activites[$nb_activites] ;
	   	 		if ($epi_cumules_activites[$nb_activites] > $max_nb_epi) $min_nb_epi = $epi_cumules_activites[$nb_activites] ;
	   	 	}	
	   	 	$nb_vues = getNbVues($epi->id);
	   	 	$nb_vues_total += $nb_vues;
			$have_student_key = HaveStudentKey($epi->id);
			if ($have_student_key == 'Oui') $nb_oui_07++;
			$have_guest_key = HaveGuestKey($epi->id);
			if ($have_guest_key == 'Oui') $nb_oui_08++;
			$have_free_access = HaveFreeAccess($epi->id);
			if ($have_free_access == 'Oui') $nb_oui_09++;
			$percent_default_section = getPercentDefaultSection($epi->id);
			$percent_default_section_global+=$percent_default_section;
			if ($have_guest_key=='Oui') $nb_epi_acces_anonyme++;
			
			echo '';
			
   	 		$data[] = array(		
   	 				'<a href='.$CFG->wwwroot.'/course/view.php?id='.$epi->id.'" target="_BLANK">'.$epi->fullname.'</a>',	
   	 				tooltipthis(getNiveauRattachement($epi->id),'col02'),										
   	 				getMailResponsableEpiByIdEpi($contextlist, $cparams),	
   	 				$nb_responsable_epi,
   	 				$nb_enseignant_editeur,	
   	 				$nb_enseignant_non_editeur,	
   	 				$nb_etudiants,	
   	 				tooltipthis($have_student_key,'col08'),	
   	 				tooltipthis($have_guest_key,'col09'),	
   	 				tooltipthis($have_free_access,'col10'),	
   					tooltipthis($percent_default_section.'%','col11'),	
   					$nb_file,	
   					tooltipthis($details,'col13'),	
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
   	 		 
   	 		$data1[] = array(	
   	 				'<a href='.$CFG->wwwroot.'/course/view.php?id='.$epi->id.'" target="_BLANK">'.$epi->fullname.'</a>',	
   	 				tooltipthis(getNiveauRattachement($epi->id),'col02'),										
   	 				getMailResponsableEpiByIdEpi($contextlist, $cparams),	
   	 				tooltipthis($nb_responsable_epi,'col04'),	
   	 				tooltipthis($nb_enseignant_editeur,'col05'),	
   	 				tooltipthis($nb_enseignant_non_editeur,'col06'),	
   	 				tooltipthis($nb_etudiants,'col07'),	
   	 				tooltipthis($have_student_key,'col08'),	
   	 				tooltipthis($have_guest_key,'col09'),	
   	 				tooltipthis($have_free_access,'col10'),	
   					tooltipthis($percent_default_section.'%','col11'),	
   					tooltipthis($nb_vues,'col26'),	
   					tooltipthis(date('d/m/Y', $epi->timemodified).' à '.date('H:i:s', $epi->timemodified),'col27'),
					tooltipthis(isOpen($epi->id),'ouvert')	
   	 		 );
   	 		$data2[] = array(		
   	 				'<a href='.$CFG->wwwroot.'/course/view.php?id='.$epi->id.'" target="_BLANK">'.$epi->fullname.'</a>',	
   					tooltipthis($nb_activite_forum,'col16'),	
   					tooltipthis($nb_contribution_forum,'col17'),	
   					tooltipthis($nb_activite_sondage,'col18'),	
   					tooltipthis($nb_activite_feedback,'col19'),	
   					tooltipthis($nb_activite_bdd,'col20'),	
   					tooltipthis($nb_activite_glossaire,'col21'),	
   					tooltipthis($nb_activite_test,'col22'),	
   					tooltipthis($nb_activite_externe,'col23'),	
   					tooltipthis($nb_devoir_remis,'col24'),	
   			);
   	 		$data3[] = array(		
   	 				'<a href='.$CFG->wwwroot.'/course/view.php?id='.$epi->id.'" target="_BLANK">'.$epi->fullname.'</a>',	
   					tooltipthis($nb_url,'col14'),	
   					tooltipthis($nb_page,'col15'),	
   					tooltipthis($nb_file,'col12'),	
   					tooltipthis($details,'col13')
   			);
   	 		$nb_ppt = 0;
   	 		$nb_doc = 0;
   	 		$nb_pdf = 0;
   	 		$nb_autre = 0;
   	 		$i = $nb_activite_forum + $nb_activite_sondage + $nb_activite_feedback + $nb_activite_bdd + $nb_activite_glossaire + $nb_activite_test + $nb_activite_externe;
   	 		$j = $nb_autre_format + $nb_doc + $nb_page + $nb_url + $nb_ppt + $nb_pdf;
   	 		$epi_activites_ressources[] = array($i,$j);
   	 		 
   	 		if ( ($nb_enseignant_editeur == 1) && ($nb_enseignant_non_editeur >= 1) ) {
   	 			$one_editing_teacher_and_other_teachers++;
   	 		} elseif ( ($nb_enseignant_editeur == 1) && ($nb_enseignant_non_editeur == 0) ) {
   	 			$only_one_editing_teacher++;
   	 		} elseif ( ($nb_enseignant_editeur > 1) ) {
   	 			$more_than_one_editing_teacher++;
   	 		}
   	 	}
		$nb_ressources_totales = $nb_autre_total + $nb_doc_total + $nb_page_total + $nb_url_total + $nb_ppt_total + $nb_pdf_total; 
		

		$data_resume_ufr[] = array('<strong>'.get_string('col04','local_up1reportepiufr').'</strong>',$nb_responsable_epi_total);
		$data_resume_ufr[] = array('<strong>'.get_string('col05','local_up1reportepiufr').'</strong>',$nb_enseignant_editeur_total);
		$data_resume_ufr[] = array('<strong>'.get_string('col06','local_up1reportepiufr').'</strong>',$nb_enseignant_non_editeur_total);
		$data_resume_ufr[] = array('<strong>'.get_string('col07','local_up1reportepiufr').'</strong>',$nb_etudiants_total);
		$data_resume_ufr[] = array('<strong>'.get_string('col08','local_up1reportepiufr').'</strong>',round($nb_oui_07/$nb_epis * 100,2).'%');
		$data_resume_ufr[] = array('<strong>'.get_string('col09','local_up1reportepiufr').'</strong>',round($nb_oui_08/$nb_epis * 100,2).'%');
		$data_resume_ufr[] = array('<strong>'.get_string('col10','local_up1reportepiufr').'</strong>',round($nb_oui_09/$nb_epis * 100,2).'%');
		$data_resume_ufr[] = array('<strong>'.get_string('col11','local_up1reportepiufr').'</strong>',round($percent_default_section_global/$nb_epis,2).'%');
		$data_resume_ufr[] = array('<strong>'.get_string('col12','local_up1reportepiufr').'</strong>',$nb_autre_total + $nb_doc_total + $nb_ppt_total + $nb_pdf_total);
		$data_resume_ufr[] = array('<strong>'.get_string('col13','local_up1reportepiufr').'</strong>','PPT('.$nb_ppt_total.'), DOC('.$nb_doc_total.'), PDF('.$nb_pdf_total.'), Autre('.$nb_autre_total.')');
		$data_resume_ufr[] = array('<strong>'.get_string('col14','local_up1reportepiufr').'</strong>',$nb_url_total);
		$data_resume_ufr[] = array('<strong>'.get_string('col15','local_up1reportepiufr').'</strong>',$nb_page_total);
		$data_resume_ufr[] = array('<strong>'.get_string('col16','local_up1reportepiufr').'</strong>',$nb_activite_forum_total);
		$data_resume_ufr[] = array('<strong>'.get_string('col17','local_up1reportepiufr').'</strong>',$nb_contribution_forum_total);
		$data_resume_ufr[] = array('<strong>'.get_string('col18','local_up1reportepiufr').'</strong>',$nb_activite_sondage_total);
		$data_resume_ufr[] = array('<strong>'.get_string('col19','local_up1reportepiufr').'</strong>',$nb_activite_feedback_total);
		$data_resume_ufr[] = array('<strong>'.get_string('col20','local_up1reportepiufr').'</strong>',$nb_activite_bdd_total);
		$data_resume_ufr[] = array('<strong>'.get_string('col21','local_up1reportepiufr').'</strong>',$nb_activite_glossaire_total);
		$data_resume_ufr[] = array('<strong>'.get_string('col22','local_up1reportepiufr').'</strong>',$nb_activite_test_total);
		$data_resume_ufr[] = array('<strong>'.get_string('col23','local_up1reportepiufr').'</strong>',$nb_activite_externe_total);
		$data_resume_ufr[] = array('<strong>'.get_string('col24','local_up1reportepiufr').'</strong>',$nb_devoir_remis_total);
		$data_resume_ufr[] = array('<strong>'.get_string('col26','local_up1reportepiufr').'</strong>',$nb_vues_total);

  		$table_resume_ufr->data = $data_resume_ufr;
		$table_infos_generales->data = $data1;
		$table_infos_activites->data = $data2;
		$table_infos_ressources->data = $data3;
} else {
	$heading_page_libelle = 'Observatoire des EPI';
	$title_page_libelle = 'Observatoire des EPI - Choix de la catégorie';
	$heading_output_libelle = 'Observatoire des EPI';
	$PAGE->set_heading($heading_page_libelle);
	$PAGE->set_title($title_page_libelle);
	$PAGE->set_pagelayout('report');
	echo $OUTPUT->header();
	echo $OUTPUT->heading($heading_output_libelle);
	echo $OUTPUT->box_start('generalbox boxaligncenter boxwidthwide');
	echo '<script type="text/javascript" src="jsapi/jsapi.js"></script>';
}

echo '
      function drawChart2() {

        var data = google.visualization.arrayToDataTable([
          ["Indicateur", "Valeur", { role: \'style\' }, { role: \'annotation\' } ],
          ["EPI ",     					'.$nb_epis.',"#3a5fb3","'.$nb_epis.'"],
         /* ["Etudiants ",     			'.$nb_etudiants_total.',"#e5e4e2","'.$nb_etudiants_total.'"],*/
          ["EPI en accès anonymes",		'.$nb_epi_acces_anonyme.',"#00326e","'.$nb_epi_acces_anonyme.'"],
          ["Ressources",     			'.$nb_ressources_totales.',"#f8bd67","'.$nb_ressources_totales.'"],
          ["Activités",     			'.$nb_activite_totales.',"#fa803b","'.$nb_activite_totales.'"]
        ]);

        var options = {
          	title: "Résumé de l\'UFR",
           	width: 900
        };
                
        var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_resume"));
        chart.draw(data, options);
      }
      
      function drawChart3() {

        var data = google.visualization.arrayToDataTable([
          ["TENDANCE", "RÉSULTAT"],
          ["Seulement un enseignant éditeur ('.$only_one_editing_teacher.')",     	'.$only_one_editing_teacher.'],
          ["Plusieurs enseignants éditeurs ('.$more_than_one_editing_teacher.')",      	'.$more_than_one_editing_teacher.'],
          ["Un enseignant éditeur et plusieurs enseignants non éditeurs ('.$one_editing_teacher_and_other_teachers.')",  	'.$one_editing_teacher_and_other_teachers.'],
          ["Autre cas ('.$other_case.')", 		'.$other_case.']
        ]);

        var options = {
         	title: "Tendance de la collaboration entre enseignants",
           	width: 900,
           	colors : ["#3a5fb3","#00326e","#f8bd67","#fa803b"]
        };
                
        var chart = new google.visualization.PieChart(document.getElementById("piechart_collaboration_teachers"));
        chart.draw(data, options);
      }
      
      function drawChart4() {
        var data = google.visualization.arrayToDataTable([
          ["TYPE DE RESSOURCE", "TOTAL"],
          ["Nombre de fichiers PPT ('.$nb_ppt_total.')",     	'.$nb_ppt_total.'],
          ["Nombre de fichiers DOC ('.$nb_doc_total.')",     	'.$nb_doc_total.'],
          ["Nombre de fichiers PDF ('.$nb_pdf_total.')",     	'.$nb_pdf_total.'],
          ["Nombre de fichiers d\'autres formats ('.$nb_autre_total.')",     	'.$nb_autre_total.'],
          ["Nombre d\'URL ('.$nb_url_total.')",     			'.$nb_url_total.'],
          ["Nombre de pages ('.$nb_page_total.')",     		'.$nb_page_total.']
        ]);

        var options = {
          	title: "Examen des ressources utilisés",
          	pieHole: 0.4,
           	width: 900, 
           	height:600,
           	colors : ["#3a5fb3","#00326e","#f8bd67","#fa803b","#e5e4e2","#c0c0c0"]
        };
		var chart = new google.visualization.PieChart(document.getElementById("piechart_ressources_utilisees"));
        chart.draw(data, options);
      }
      function afficher(box) {
      $(".box-a-afficher").hide();
      $("#"+box).show();
	  }
    </script>';
	} else {
		$heading_page_libelle = 'Observatoire des EPI';
		$title_page_libelle = 'Observatoire des EPI - Choix de la catégorie';
		$heading_output_libelle = 'Observatoire des EPI';
	}
echo '
	<ul>
		<li><a href="javascript:afficher(\'div-resume-ufr\')">Résumé de l\'UFR</a></li>
		<li><a href="javascript:afficher(\'div-info-gene\')">Informations générales</a></li>
		<li><a href="javascript:afficher(\'div-activites\')">Activités</a></li>
		<li><a href="javascript:afficher(\'div-ressources\')">Ressources</a></li>
		<li><a href="javascript:afficher(\'div-graphes\')">Graphes</a></li>
		<li><a href="observatoire_csv.php?id='.$_GET['id'].'" target="_BLANK">Télécharger le CSV</a></li>
	</ul>
';

if (!empty($table_resume_ufr)) echo '<div id="div-resume-ufr" class="box-a-afficher"><h2>Résumé de l\'UFR</h2>'.html_writer::table($table_resume_ufr).'</div>';
if (!empty($table_infos_generales)) echo '<div id="div-info-gene" class="box-a-afficher" style="display:none;"><h2>Informations générales</h2>'.html_writer::table($table_infos_generales).'</div>';
if (!empty($table_infos_activites)) echo '<div id="div-activites" class="box-a-afficher" style="display:none;"><h2>Activités</h2>'.html_writer::table($table_infos_activites).'</div>';
if (!empty($table_infos_ressources)) echo '<div id="div-ressources" class="box-a-afficher" style="display:none;"><h2>Ressources</h2>'.html_writer::table($table_infos_ressources).'</div>';
echo '<div id="div-graphes" class="box-a-afficher" style="display:none;width:900px;"><h2>Graphes</h2>';
echo '<div id="columnchart_resume" style="width: 900px;"></div>';
echo '<div id="piechart_collaboration_teachers" style="width: 900px; "></div>';
echo '<div id="piechart_ressources_utilisees" style="width: 900px; "></div>';
echo '</div>';
echo $OUTPUT->box_end();
echo $OUTPUT->footer(); 
