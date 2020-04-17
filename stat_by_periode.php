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
echo $OUTPUT->header();
echo $OUTPUT->box_start('generalbox boxaligncenter boxwidthwide');
if (is_userauthorized($USER->id) || is_siteadmin()) {
	if (!empty($_POST['nb_periode'])) {
		echo '<h3></h3>';
		$nb_periode=$_POST['nb_periode'];
		$entete = array(' ');
		$data = array();
		$data[0][0] = 'Nombre d\'ajout de fichier ressource';
		$data[1][0] = 'Nombre de vues de fichiers ressource';
		$data[2][0] = 'Nombre de cours créés';
		$data[3][0] = 'Nombre de forum de discussions créés';
		$data[4][0] = 'Nombre d\'autre forum de discussions créés';
		$data[5][0] = 'Nombre de devoir créés';
		$data[6][0] = 'Nombre de devoir rendus';
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
          ['Nbe forum de discussions créés', ".$data[3][1].",".$data[3][2].",".$data[3][3]."],
          ['Nbe forum de discussions créés', ".$data[4][1].",".$data[4][2].",".$data[4][3]."],
          ['Nbe devoir créés', ".$data[5][1].",".$data[5][2].",".$data[5][3]."],
          ['Nbe devoir rendu', ".$data[6][1].",".$data[6][2].",".$data[6][3]."]
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
