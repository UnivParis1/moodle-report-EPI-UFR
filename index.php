<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once('../../lib/accesslib.php');
require_once('locallib.php');
require_login();

ini_set('max_execution_time', 600);
ini_set('memory_limit', '2048M');
$idcategorie=0;
$url = new moodle_url('/local/up1reportepiufr/index.php');
$PAGE->set_url($url);
$context = context_user::instance($USER->id);
$PAGE->set_context($context);
$PAGE->requires->css(new moodle_url('/local/up1reportepiufr/css/up1reportepiufr.css'));
//$PAGE->navbar->add(get_string('analysis', 'feedback'));
/**
 * vérification que l'utilisateur est un administrateur
 */
if (is_userauthorized($USER->id) || is_siteadmin()) {
	$annee = 0;
	if (isset($_REQUEST['annee'])) $annee = $_REQUEST['annee'];
	
	$PAGE->set_pagelayout('report');
	if (is_siteadmin()) admin_externalpage_setup('local_up1reportepiufr', '', null, '', array('pagelayout'=>'report'));
	
	if ($annee==0) {
		$PAGE->set_heading(get_string('heading', 'local_up1reportepiufr'));
		$PAGE->set_heading(get_string('heading', 'local_up1reportepiufr'));
		$PAGE->set_title(get_string('title_index', 'local_up1reportepiufr'));
	} else {
		$select_annee = "SELECT name from {course_categories} where id=? ;";
		$obj = $DB->get_record_sql($select_annee, array($annee));
		$libelle_annee = '';
		if (!empty($obj->name)) $libelle_annee = $obj->name;
		$PAGE->set_heading( str_replace('[annee]', $libelle_annee, get_string('heading_with_year', 'local_up1reportepiufr')));
		$PAGE->set_heading( str_replace('[annee]', $libelle_annee, get_string('heading_with_year', 'local_up1reportepiufr')));
		$PAGE->set_title( str_replace('[annee]', $libelle_annee, get_string('title_index_with_year', 'local_up1reportepiufr')));
	}
	echo $OUTPUT->header();
	echo $OUTPUT->box_start('generalbox boxaligncenter boxwidthwide');
	echo '<script type="text/javascript" src="jsapi/jsapi.js"></script>';
	
	$sql= "SELECT id, name from mdl_course_categories where parent=0 and name like'%20%';";
	$cats = $DB->get_records_sql($sql);
	$select = '<select name="annee" id="annee">';
	if ($annee == 0) $select .= '<option value="0" selected>--</option>'; else $select .= '<option value="0">--</option>';
	foreach($cats as $i=>$row) {
		if ($annee == $row->id) $select .= '<option value="'.$row->id.'" selected>'.$row->name.'</option>'; else $select .= '<option value="'.$row->id.'">'.$row->name.'</option>';
	}
	$libelle_choose_cat = get_string('choose_cat', 'local_up1reportepiufr');
	$libelle_valider = get_string('ok', 'local_up1reportepiufr');
	$select .= '</select>';
$form = <<< EOF
<form action="index.php" method="GET" >
	<h3> $libelle_choose_cat $select<input type="submit" value="$libelle_valider"></h3>
</form>
<span style="color:red"><a href="index2.php" style="color:red">Evolution des usages par périodes</a></span>
EOF;
	echo $form; // insertion du formulaire dans la page
	
	if (!empty($annee)) {
		$url_stats_epis_users = array();
		$url_stats_activity = array();
		$url_stats_forum = array();
		
		$url_stats_epis_users[]  = array(
				'<a href="stats.php?annee='.$annee.'&typestat=nb_epis" target="_BLANK">'.  get_string('nb_epis', 'local_up1reportepiufr').'</a>',
				'<a href="stats_ufr_csv.php?annee='.$annee.'&typestat=nb_epis" TARGET="_BLANK" target="_BLANK"><img src="img/csv.png" width="32"></a>',
				'<a href="stats_diplome_csv.php?annee='.$annee.'&typestat=nb_epis" TARGET="_BLANK" target="_BLANK"><img src="img/csv.png" width="32"></a>'
			); 
		$url_stats_epis_users[]  = array(
				'<a href="stats.php?annee='.$annee.'&typestat=nb_enseignants" target="_BLANK">'.  get_string('nb_enseignants', 'local_up1reportepiufr').'</a>',
				'<a href="stats_ufr_csv.php?annee='.$annee.'&typestat=nb_enseignants" target="_BLANK"><img src="img/csv.png" width="32"></a>',
				'<a href="stats_diplome_csv.php?annee='.$annee.'&typestat=nb_enseignants" target="_BLANK"><img src="img/csv.png" width="32"></a>'
			); 
		$url_stats_epis_users[]  = array(
				'<a href="stats.php?annee='.$annee.'&typestat=nb_etudiants" target="_BLANK" target="_BLANK">'.  get_string('nb_etudiants', 'local_up1reportepiufr').'</a>',
				'<a href="stats_ufr_csv.php?annee='.$annee.'&typestat=nb_etudiants" target="_BLANK"><img src="img/csv.png" width="32"></a>',
				'<a href="stats_diplome_csv.php?annee='.$annee.'&typestat=nb_etudiants" target="_BLANK"><img src="img/csv.png" width="32"></a>'
			); 
		$url_stats_epis_users[]  = array(
				'<a href="stats.php?annee='.$annee.'&typestat=nb_etudiants_jamais_connectes" target="_BLANK">'.  get_string('nb_etudiants_jamais_connectes', 'local_up1reportepiufr').'</a>',
				'<a href="stats_ufr_csv.php?annee='.$annee.'&typestat=nb_etudiants_jamais_connectes" target="_BLANK"><img src="img/csv.png" width="32"></a>',
				'<a href="stats_diplome_csv.php?annee='.$annee.'&typestat=nb_etudiants_jamais_connectes" target="_BLANK"><img src="img/csv.png" width="32"></a>'
			); 
		$url_stats_activity[]  = array(
				'<a href="stats.php?annee='.$annee.'&typestat=nb_activites_devoir" target="_BLANK">'.  get_string('nb_activites_devoir', 'local_up1reportepiufr').'</a>',
				'<a href="stats_ufr_csv.php?annee='.$annee.'&typestat=nb_activites_devoir" TARGET="_BLANK"><img src="img/csv.png" width="32"></a>',
				'<a href="stats_diplome_csv.php?annee='.$annee.'&typestat=nb_activites_devoir" TARGET="_BLANK"><img src="img/csv.png" width="32"></a>'
			); 
		$url_stats_activity[]  = array(
				'<a href="stats.php?annee='.$annee.'&typestat=nb_devoirs_rendus" target="_BLANK">'.  get_string('nb_devoirs_rendus', 'local_up1reportepiufr').'</a>',
				'<a href="stats_ufr_csv.php?annee='.$annee.'&typestat=nb_devoirs_rendus" TARGET="_BLANK"><img src="img/csv.png" width="32"></a>',
				'<a href="stats_diplome_csv.php?annee='.$annee.'&typestat=nb_devoirs_rendus" TARGET="_BLANK"><img src="img/csv.png" width="32"></a>'
			); 
		$url_stats_forum[]  = array(
				'<a href="stats.php?annee='.$annee.'&typestat=nb_forum_annonces" target="_BLANK">'.  get_string('nb_forum_annonces', 'local_up1reportepiufr').'</a>',
				'<a href="stats_ufr_csv.php?annee='.$annee.'&typestat=nb_forum_annonces" TARGET="_BLANK"><img src="img/csv.png" width="32"></a>',
				'<a href="stats_diplome_csv.php?annee='.$annee.'&typestat=nb_forum_annonces" TARGET="_BLANK"><img src="img/csv.png" width="32"></a>'
			); 
		$url_stats_forum[]  = array(
				'<a href="stats.php?annee='.$annee.'&typestat=nb_contribution_forum_annonce" target="_BLANK">'.  get_string('nb_contribution_forum_annonce', 'local_up1reportepiufr').'</a>',
				'<a href="stats_ufr_csv.php?annee='.$annee.'&typestat=nb_contribution_forum_annonce" TARGET="_BLANK"><img src="img/csv.png" width="32"></a>',
				'<a href="stats_diplome_csv.php?annee='.$annee.'&typestat=nb_contribution_forum_annonce" TARGET="_BLANK"><img src="img/csv.png" width="32"></a>'
			); 
		$url_stats_forum[]  = array(
				'<a href="stats.php?annee='.$annee.'&typestat=nb_forum_autres" target="_BLANK"">'.  get_string('nb_forum_autres', 'local_up1reportepiufr').'</a>',
				'<a href="stats_ufr_csv.php?annee='.$annee.'&typestat=nb_forum_autres" TARGET="_BLANK"><img src="img/csv.png" width="32"></a>',
				'<a href="stats_diplome_csv.php?annee='.$annee.'&typestat=nb_forum_autres" TARGET="_BLANK"><img src="img/csv.png" width="32"></a>'
			); 
		$url_stats_forum[]  = array(
				'<a href="stats.php?annee='.$annee.'&typestat=nb_consultation_forum_autre" target="_BLANK">'.  get_string('nb_consultation_forum_autre', 'local_up1reportepiufr').'</a>',
				'<a href="stats_ufr_csv.php?annee='.$annee.'&typestat=nb_consultation_forum_autre" TARGET="_BLANK"><img src="img/csv.png" width="32"></a>',
				'<a href="stats_diplome_csv.php?annee='.$annee.'&typestat=nb_consultation_forum_autre" TARGET="_BLANK"><img src="img/csv.png" width="32"></a>'
			); 
		$url_stats_forum[]  = array(
				'<a href="stats.php?annee='.$annee.'&typestat=nb_contribution_forum_autre" target="_BLANK">'.  get_string('nb_contribution_forum_autre', 'local_up1reportepiufr').'</a>',
				'<a href="stats_ufr_csv.php?annee='.$annee.'&typestat=nb_contribution_forum_autre" TARGET="_BLANK"><img src="img/csv.png" width="32"></a>',
				'<a href="stats_diplome_csv.php?annee='.$annee.'&typestat=nb_contribution_forum_autre" TARGET="_BLANK"><img src="img/csv.png" width="32"></a>'
			); 
		$url_stats_activity[]  = array(
				'<a href="stats.php?annee='.$annee.'&typestat=nb_ressource_page" target="_BLANK">'.  get_string('nb_ressource_page', 'local_up1reportepiufr').'</a>',
				'<a href="stats_ufr_csv.php?annee='.$annee.'&typestat=nb_ressource_page" TARGET="_BLANK"><img src="img/csv.png" width="32"></a>',
				'<a href="stats_diplome_csv.php?annee='.$annee.'&typestat=nb_ressource_page" TARGET="_BLANK"><img src="img/csv.png" width="32"></a>'
			);  
		$url_stats_activity[]  = array(
				'<a href="stats.php?annee='.$annee.'&typestat=nb_ressource_url" target="_BLANK">'.  get_string('nb_ressource_url', 'local_up1reportepiufr').'</a>',
				'<a href="stats_ufr_csv.php?annee='.$annee.'&typestat=nb_ressource_url" TARGET="_BLANK"><img src="img/csv.png" width="32"></a>',
				'<a href="stats_diplome_csv.php?annee='.$annee.'&typestat=nb_ressource_url" TARGET="_BLANK"><img src="img/csv.png" width="32"></a>'
			);  
		$url_stats_activity[]  = array(
				'<a href="stats.php?annee='.$annee.'&typestat=nb_ressource_fichier" target="_BLANK">'.  get_string('nb_ressource_fichier', 'local_up1reportepiufr').'</a>',
				'<a href="stats_ufr_csv.php?annee='.$annee.'&typestat=nb_ressource_fichier" TARGET="_BLANK"><img src="img/csv.png" width="32"></a>',
				'<a href="stats_diplome_csv.php?annee='.$annee.'&typestat=nb_ressource_fichier" TARGET="_BLANK"><img src="img/csv.png" width="32"></a>'
			); 
			/*
		$url_stats_epis_users = array();
		$url_stats_activity = array();
		$url_stats_forum = array();
			 */
		$tablel_stats_epis_users = new html_table();
		$tablel_stats_epis_users->head = array(	get_string('lien_epis_users', 'local_up1reportepiufr'),
								get_string('dw_stat_by_cmp', 'local_up1reportepiufr'),
								get_string('dw_stat_by_dip', 'local_up1reportepiufr')
							);
		$tablel_stats_epis_users->align = array('left','center','center');
		$tablel_stats_epis_users->data = $url_stats_epis_users;
		
		$tablel_stats_activity = new html_table();
		$tablel_stats_activity->head = array(	get_string('lien_activity', 'local_up1reportepiufr'),
								get_string('dw_stat_by_cmp', 'local_up1reportepiufr'),
								get_string('dw_stat_by_dip', 'local_up1reportepiufr')
							);
		$tablel_stats_activity->align = array('left','center','center');
		$tablel_stats_activity->data = $url_stats_activity;
		
		$tablel_stats_forum = new html_table();
		$tablel_stats_forum->head = array(	get_string('lien_forum', 'local_up1reportepiufr'),
								get_string('dw_stat_by_cmp', 'local_up1reportepiufr'),
								get_string('dw_stat_by_dip', 'local_up1reportepiufr')
							);
		$tablel_stats_forum->align = array('left','center','center');
		$tablel_stats_forum->data = $url_stats_forum;
		
		echo '<h3>'.get_string('stat_gene', 'local_up1reportepiufr').'</h3>';
		echo html_writer::table($tablel_stats_epis_users);		
		echo html_writer::table($tablel_stats_activity);		
		echo html_writer::table($tablel_stats_forum);			
		
		$table2 = new html_table();
		$table2->head = array('UFR');
		$table2->data = ObservatoireEPI($annee);
		echo '<h3>'.get_string('observatoire', 'local_up1reportepiufr').'</h3>';
		echo html_writer::table($table2);	
		
	}
	
}
echo $OUTPUT->box_end();
echo $OUTPUT->footer(); 
