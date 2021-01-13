<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once('../../lib/accesslib.php');
require_once('locallib.php');
require_login();

ini_set('max_execution_time', 600);
ini_set('memory_limit', -1);
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
echo $OUTPUT->header();
echo $OUTPUT->box_start('generalbox boxaligncenter boxwidthwide');
if (is_userauthorized($USER->id) || is_siteadmin()) {
	if (!empty($_POST['nb_periode'])) {
		echo '<h3></h3>';
		$nb_periode=$_POST['nb_periode'];
		$entete = array(' ');
		$data = array();
		$data[0][0] = 'Nombre d\'ajouts de fichiers ressources';
		$data[1][0] = 'Nombre de vues de fichiers ressources';
		$data[2][0] = 'Nombre de cours créés';
		$data[3][0] = 'Nombre de messages sur les forums des annonces';
		$data[4][0] = 'Nombre de messages sur les forums';
		$data[5][0] = 'Nombre de devoirs créés';
		$data[6][0] = 'Nombre de devoirs rendus';
		$data[7][0] = 'Nombre de quiz créés';
		$data[8][0] = 'Nombre de réponses aux quiz';
		$data[9][0] = 'Nombre de sessions BBB';
		$data[10][0] = 'Nombre de sessions BBB enregistrés';
		$data[11][0] = 'Nombre de participants aux sessions BBB';
		$data[12][0] = 'Nombre de réunions Zoom créées';
		$data[13][0] = 'Nombre de participants aux réunions Zoom';
		for ($i=0;$i< $nb_periode;$i++) {
			$periode_debut = $_POST['annee_debut_'.$i] . '-'.
				$_POST['mois_debut_'.$i] . '-'.
					$_POST['jour_debut_'.$i] . ' 00:00:00 ';
                        $periode_fin = $_POST['annee_fin_'.$i] . '-'.
                                $_POST['mois_fin_'.$i] . '-'.
                                        $_POST['jour_fin_'.$i] . ' 23:59:59 ';
			$entete[$i+1] = 'De '.$periode_debut. ' à '.$periode_fin;
			$data[0][$i+1]= nb_ajout_fichier_ressource($periode_debut,$periode_fin);	
			$data[1][$i+1]= nb_vue_fichier_ressource($periode_debut,$periode_fin);
			$data[2][$i+1] = nb_course_created($periode_debut,$periode_fin);
			$data[3][$i+1] = nb_forum_announce_created($periode_debut,$periode_fin);
			$data[4][$i+1] = nb_other_forum_created($periode_debut,$periode_fin);
			$data[5][$i+1] = nb_assign_created($periode_debut,$periode_fin);
			$data[6][$i+1] = nb_assign_submitted($periode_debut,$periode_fin);
			$data[7][$i+1] = nb_quiz_created($periode_debut,$periode_fin);
			$data[8][$i+1] = nb_quiz_submitted($periode_debut,$periode_fin);
			$data[9][$i+1] = nb_real_bbb($periode_debut,$periode_fin);
			$data[10][$i+1] = nb_bbb_sessions($periode_debut,$periode_fin);
			$data[11][$i+1] = nb_bbb_participants($periode_debut,$periode_fin);
			$data[12][$i+1] = nb_zoom_reunions($periode_debut,$periode_fin);
			$data[13][$i+1] = nb_zoom_participants($periode_debut,$periode_fin);

		}
		$table2 = new html_table();
		$table2->head = $entete;
		$table2->data = $data;
		echo html_writer::table($table2);	
echo '   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load(\'current\', {\'packages\':[\'bar\']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([';
echo "
          ['$entete[0]', '$entete[1]', '$entete[2]', '$entete[3]'],
          ['Ajout de fichier ressource', ".$data[0][1].",".$data[0][2].",".$data[0][3]."],
          ['Nb vu de fichier ressource', ".$data[1][1].",".$data[1][2].",".$data[1][3]."],
          ['Nb cours créés', ".$data[2][1].",".$data[2][2].",".$data[2][3]."],
          ['Nbe forums de discussions créés', ".$data[3][1].",".$data[3][2].",".$data[3][3]."],
          ['Nbe forums de discussions créés', ".$data[4][1].",".$data[4][2].",".$data[4][3]."],
          ['Nbe devoirs créés', ".$data[5][1].",".$data[5][2].",".$data[5][3]."],
          ['Nbe devoirs rendu', ".$data[6][1].",".$data[6][2].",".$data[6][3]."],
          ['Nbe quiz créés', ".$data[7][1].",".$data[7][2].",".$data[7][3]."],
          ['Nbe réponse quiz', ".$data[8][1].",".$data[8][2].",".$data[8][3]."],
          ['Nombre de sessions BBB (+5 particpants)', ".$data[9][1].",".$data[9][2].",".$data[9][3]."],
          ['Nombre de sessions BBB enregistrés', ".$data[10][1].",".$data[10][2].",".$data[10][3]."],
          ['Nombre de participants aux sessions BBB', ".$data[11][1].",".$data[11][2].",".$data[11][3]."]
          ['Nombre de réunions Zoom créées', ".$data[12][1].",".$data[12][2].",".$data[12][3]."]
          ['Nombre de participants aux réunions Zoom', ".$data[13][1].",".$data[13][2].",".$data[13][3]."]
        ]);


        var options = {
          chart: {
            title: 'Evolution des usages par période'
          }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>";
echo '
<div id="columnchart_material" style="width: 800px; height: 500px;"></div>
';		
	}
	
}
echo $OUTPUT->box_end();
echo $OUTPUT->footer(); 
