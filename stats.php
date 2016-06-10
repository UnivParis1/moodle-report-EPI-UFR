<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once('../../lib/accesslib.php');
require_once('locallib.php');
require_login();

ini_set('max_execution_time', 600);
ini_set('memory_limit', '2048M');
$idcategorie=0;

$annee = isset($_REQUEST['annee']) ? $_REQUEST['annee'] : 0;
$typestat = isset($_REQUEST['typestat']) ? $_REQUEST['typestat'] : '';


$url = new moodle_url('/local/up1reportepiufr/index.php');
$PAGE->set_url($url);
$context = context_user::instance($USER->id);
$PAGE->set_context($context);
//$PAGE->navbar->add(get_string('analysis', 'feedback'));
$PAGE->set_heading("Statistiques générales");
$PAGE->set_title("Statistiques générales"); 
$PAGE->set_pagelayout('report');
	if ($annee==0) {
		$PAGE->set_heading(get_string('heading', 'local_up1reportepiufr'));
		$PAGE->set_title(get_string('title_index', 'local_up1reportepiufr'));
	} else {
		$select_annee = "SELECT name from {course_categories} where id=? ;";
		$obj = $DB->get_record_sql($select_annee, array($annee));
		$libelle_annee = '';
		if (!empty($obj->name)) $libelle_annee = $obj->name;
		$PAGE->set_heading( str_replace('[annee]', $libelle_annee, get_string('heading_with_year', 'local_up1reportepiufr')));
		$PAGE->set_title( str_replace('[annee]', $libelle_annee, get_string('title_index_with_year', 'local_up1reportepiufr')));
	}
echo $OUTPUT->header();
echo $OUTPUT->box_start('generalbox boxaligncenter boxwidthwide');
echo '<script type="text/javascript" src="jsapi/jsapi.js"></script>';
/**
 * vérification que l'utilisateur est un administrateur
 */

if (is_siteadmin()) {
	if (!empty($annee)) {
		$path = '/'.$annee;
		//recherche de l'établissement
		$selectetab = "SELECT id  from {course_categories} where parent=? ;";
		$etab = $DB->get_record_sql($selectetab, array($annee));
		if (!empty($etab->id)) {
			$selectufr = "SELECT id,name,path from {course_categories} where parent = :parent  order by name;";
			$ufrs  = $DB->get_records_sql($selectufr, array('parent'=>$etab->id));
			$titrecolonnes = array(	get_string('diplome','local_up1reportepiufr'),
									get_string('licence','local_up1reportepiufr'),
									get_string('master','local_up1reportepiufr'),
									get_string('doctorat','local_up1reportepiufr'),
									get_string('autre','local_up1reportepiufr'),
									(empty(get_string('global_'.$typestat,'local_up1reportepiufr'))?get_string('global','local_up1reportepiufr'):get_string('global_'.$typestat,'local_up1reportepiufr'))
								);
			if (!empty($typestat)) {
				include 'SQL/'.$typestat.'.php';
				if (!empty($ufrs)) {
					$nb_licence = 0;
					$nb_master = 0;
					$nb_doctorat = 0;
					$nb_autre = 0;
					$count = false;
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
			        	$data=  get_stats($SELECT,$id_licence,$id_master,$id_doctorat,$ufr->id,$ufr->name,$count,false,$calculautre);
						$datas[] = $data;
						$nb_licence += $data[1];
						$nb_master  += $data[2];
						$nb_doctorat += $data[3];
						$nb_autre += $data[4];
					}
					echo '
			    <script type="text/javascript">
			      google.load("visualization", "1", {packages:["corechart"]});
			      google.setOnLoadCallback(drawChart);
			      function drawChart() {
			
			        var data = google.visualization.arrayToDataTable([
			          ["'.get_string('diplome','local_up1reportepiufr').'", "'.get_string('nombre','local_up1reportepiufr').'"],
			          ["'.get_string('licence','local_up1reportepiufr').' ('.$nb_licence.')",     	'.$nb_licence.'],
			          ["'.get_string('master','local_up1reportepiufr').' ('.$nb_master.')",      	'.$nb_master.'],
			          ["'.get_string('doctorat','local_up1reportepiufr').' ('.$nb_doctorat.')",  	'.$nb_doctorat.'],
			          ["'.get_string('autre','local_up1reportepiufr').' ('.$nb_autre.')", 		'.$nb_autre.']
			        ]);
			
			        var options = {
			          title: "'.get_string($typestat,'local_up1reportepiufr').'",
            		colors : ["#3a5fb3","#00326e","#f8bd67","#fa803b"]
			        };
			        var chart = new google.visualization.PieChart(document.getElementById("piechart"));
			        chart.draw(data, options);
			      }
			    </script>';
					$table_nb = new html_table();
				    $table_nb->head = $titrecolonnes;
				    $table_nb->data = $datas;
				    echo '<h3>'.get_string($typestat,'local_up1reportepiufr').'</h3>';
				    echo get_string('msg_'.$typestat,'local_up1reportepiufr');
				    echo '<p>&nbsp;</p>';
				    echo html_writer::table($table_nb);
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
					
				    $table = new html_table();
				    $table->head = array('Libellé','Valeur');
				    $array_diplome = array();
					$array_diplome[]= array(get_string($typestat.'_libcol','local_up1reportepiufr'),$nbtotal);
					$array_diplome[]= array(get_string('licence','local_up1reportepiufr'),$nb_licence);		
					$array_diplome[]= array(get_string('master','local_up1reportepiufr'),$nb_master);		
					$array_diplome[]= array(get_string('doctorat','local_up1reportepiufr'),$nb_doctorat);		
					$array_diplome[]= array(get_string('autre','local_up1reportepiufr'),$nb_autre);
				    $table->data  = $array_diplome;
				    echo '<h3>'.get_string($typestat,'local_up1reportepiufr').'</h3>';
				    echo html_writer::table($table);
					echo '<div id="piechart" style="width: 900px; height: 500px;"></div>';
				}
								
			}
		}
	}
}
echo $OUTPUT->box_end();
echo $OUTPUT->footer(); 