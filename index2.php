<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once('../../lib/accesslib.php');
require_once('locallib.php');
require_login();

ini_set('max_execution_time', 600);
ini_set('memory_limit', '2048M');
$idcategorie=0;
$url = new moodle_url('/local/up1reportepiufr/index2.php');
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
	echo '<h2>Comparatif des usages par période</h2>';
	$form = '
	<form action="index2.php" method="POST">
	<filedset>
	<legend>Nombre de période à comparer
	<select name="nb_periode">
		<option value="0">--</option>
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>
		<option value="6">6</option>
		<option value="7">7</option>
		<option value="8">8</option>
		<option value="9">9</option>
		<option value="10">10</option>
	</select>
	<input type="hidden" value="nb_periode" name="formname">
	<input type="submit" value="valider">
	</legend>
	</fieldset>
	</form>
';
	 echo $form; // insertion du formulaire dans la page
	if (!empty($_POST['formname'])) {
		if ($_POST['formname']=='nb_periode') {
		$nb_periode = $_POST['nb_periode'];
		$form2 = '
				<form action="stat_by_periode.php" method="POST">
					<input type="hidden" name="nb_periode" value="'.$nb_periode.'">
			';
		for ($i=0;$i<$nb_periode;$i++) {
			$form2 .='
			<filedset>
         		<legend>Période '.$i.'</legend>
			Début&nbsp;de&nbsp;la&nbsp;période&nbsp;:&nbsp;
année&nbsp;<select name="annee_debut_'.$i.'">
	<option value="2020">2020</option>
	<option value="2019">2019</option>
	<option value="2018">2018</option>
	<option value="2017">2017</option>
	<option value="2016">2016</option>
</select>&nbsp;
mois&nbsp;&nbsp<select name="mois_debut_'.$i.'">
	<option value="01">01</option>
         <option value="02">02</option>
         <option value="03">03</option>
         <option value="04">04</option>
         <option value="05">05</option>
         <option value="06">06</option>
         <option value="07">07</option>
         <option value="08">08</option>
         <option value="09">09</option>
         <option value="10">10</option>
         <option value="11">11</option>
         <option value="12">12</option>
</select>&nbsp;
jour&nbsp;&nbsp<select name="jour_debut_'.$i.'">
        <option value="01">01</option>
         <option value="02">02</option>
         <option value="03">03</option>
         <option value="04">04</option>
         <option value="05">05</option>
         <option value="06">06</option>
         <option value="07">07</option>
         <option value="08">08</option>
         <option value="09">09</option>
         <option value="10">10</option>
         <option value="11">11</option>
         <option value="12">12</option>
	<option value="13"  >13</option> 
	<option value="14"  >14</option> 
	<option value="15" >15</option> 
	<option value="16"  >16</option> 
	<option value="17"  >17</option> 
	<option value="18"  >18</option> 
	<option value="19"  >19</option> 
	<option value="20"  >20</option> 
	<option value="21"  >21</option> 
	<option value="22"  >22</option> 
	<option value="23"  >23</option> 
	<option value="24"  >24</option> 
	<option value="25"  >25</option> 
	<option value="26"  >26</option> 
	<option value="27"  >27</option> 
	<option value="28"  >28</option> 
	<option value="29"  >29</option> 
	<option value="30"  >30</option> 
	<option value="31"  >31</option> 
</select>&nbsp;à&nbsp;00h00min00sec
<br />
<br />
 Fin&nbsp;de&nbsp;la&nbsp;période&nbsp;:&nbsp;
année&nbsp;<select name="annee_fin_'.$i.'">
		<option value="2020">2020</option>
        <option value="2019">2019</option>
        <option value="2018">2018</option>
        <option value="2017">2017</option>
        <option value="2016">2016</option>
</select>&nbsp;
mois&nbsp;&nbsp<select name="mois_fin_'.$i.'">
        <option value="01">01</option>
         <option value="02">02</option>
         <option value="03">03</option>
         <option value="04">04</option>
         <option value="05">05</option>
         <option value="06">06</option>
         <option value="07">07</option>
         <option value="08">08</option>
         <option value="09">09</option>
         <option value="10">10</option>
         <option value="11">11</option>
         <option value="12">12</option>
</select>&nbsp;
jour&nbsp;&nbsp<select name="jour_fin_'.$i.'">
        <option value="01">01</option>
         <option value="02">02</option>
         <option value="03">03</option>
         <option value="04">04</option>
         <option value="05">05</option>
         <option value="06">06</option>
         <option value="07">07</option>
         <option value="08">08</option>
         <option value="09">09</option>
         <option value="10">10</option>
         <option value="11">11</option>
         <option value="12">12</option>
        <option value="13"  >13</option> 
        <option value="14"  >14</option> 
        <option value="15" >15</option> 
        <option value="16"  >16</option> 
        <option value="17"  >17</option> 
        <option value="18"  >18</option> 
        <option value="19"  >19</option> 
        <option value="20"  >20</option> 
        <option value="21"  >21</option> 
        <option value="22"  >22</option> 
        <option value="23"  >23</option> 
        <option value="24"  >24</option> 
        <option value="25"  >25</option> 
        <option value="26"  >26</option> 
        <option value="27"  >27</option> 
        <option value="28"  >28</option> 
        <option value="29"  >29</option> 
        <option value="30"  >30</option> 
        <option value="31"  >31</option> 
</select>&nbsp;&nbsp;à&nbsp;23h59min59sec
<br />

			';
		}	
			
		$form2.='<input type="submit" value="valider"></form>';
		 echo $form2; // insertion du formulaire dans la page	
			
		}
	}
	
	if (!empty($annee)) {
		
		$table = new html_table();
		$table->head = array('UFR');
		$table->data = ObservatoireEPI($annee);
		echo '<h3>'.get_string('observatoire', 'local_up1reportepiufr').'</h3>';
		echo html_writer::table($table);	
		
	}
	
}
echo $OUTPUT->box_end();
echo $OUTPUT->footer(); 
