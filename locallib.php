<?php
function is_userauthorized($id) {
	global $CFG;
	if (in_array($id, $CFG->observatoireuserauthorized)) return true;
	return false;
}

function get_stats($sql, $id_licence,$id_master,$id_doctorat, $id_ufr,$name_ufr, $count = false, $csv = false, $calculautre=false) {
	global $DB;
	$listelicence =array();
	$listemaster =array();
	$listedoctorat =array();
	$listeautre =array();
	$listetotal =array();
	$array_ids_autres = array();
	$orsql = '';
	$array_orsql = array();
	if ($calculautre) {
		$ids_autres = '';
	    $select_ids_autres = "	SELECT id  
	    						FROM {course_categories} 
	    						WHERE parent=? 
	    						AND  upper(name) NOT LIKE '%LICENCE%'
	    						AND  upper(name) NOT LIKE '%MASTER%'
	    						AND  upper(name) NOT LIKE '%DOCTORAT%'";
	    $objautres = $DB->get_records_sql($select_ids_autres, array($id_ufr));
	    if (!empty($objautres)) {
	    	$j=0;
	        foreach($objautres as $i=>$row) {
		    	$array_orsql[] = '%/'.$id_ufr.'/'.$row->id.'/%';
		    	$array_orsql[] = '%/'.$id_ufr.'/'.$row->id;
		    	if ($j>0) {
		    		$orsql .= ' OR CC.path LIKE ?  OR CC.path LIKE ? ';
		    	}
		    	$j++;
		    }  
	    }		
	}

	if ($count) {
		$listelicenceboucle= $DB->get_records_sql($sql, array('%/'.$id_ufr.'/'.$id_licence.'/%','%/'.$id_ufr.'/'.$id_licence));
		foreach($listelicenceboucle as $cle=>$val) {if (!empty($val->id)) $listelicence[$val->id]= $val->id;}
		$listemasterboucle= $DB->get_records_sql($sql, array('%/'.$id_ufr.'/'.$id_master.'/%','%/'.$id_ufr.'/'.$id_master));
		foreach($listemasterboucle as $cle=>$val) {if (!empty($val->id)) $listemaster[$val->id]=$val->id;}
		$listedoctoratboucle= $DB->get_records_sql($sql, array('%/'.$id_ufr.'/'.$id_doctorat.'/%','%/'.$id_ufr.'/'.$id_doctorat));
		foreach($listedoctoratboucle as $cle=>$val) {if (!empty($val->id)) $listedoctorat[$val->id]=$val->id;}
		$listetotalboucle = $DB->get_records_sql($sql, array('%/'. $id_ufr .'/%','%/'. $id_ufr ));
	    foreach	($listetotalboucle as $cle=>$val) {
			if (!empty($val->id)) {
				$listetotal[$val->id]= $val->id;
				//if ( !isset($listelicence[$val->id]) && !isset($listemaster[$val->id]) && !isset($listedoctorat[$val->id])  ) $listeautre[]= $val->id;
			}
	    }
		$nblicence = count($listelicence);
		$nb_master = count($listemaster);
		$nb_doctorat = count($listedoctorat);
		$nb_total = count($listetotal);
		if ($calculautre) {
	    	if (!empty($array_orsql))  {
	    		if (strlen($orsql)) { if (substr($sql,-2)==='))') $sql = substr($sql,0, -2).$orsql.'))'; else $sql = substr($sql,0, -1).$orsql.')';}
				$listeautreboucle= $DB->get_records_sql($sql, $array_orsql);
				foreach($listeautreboucle as $cle=>$val) {if (!empty($val->id)) $listeautre[$val->id]=$val->id;}
				$nb_autre = count($listeautre);
		    } else {
		    	$nb_autre = 0;
	    	}	    	
	    } else {
	    	$nb_autre = $nb_total - $nblicence - $nb_master - $nb_doctorat;
	    }

	} else {
		$listelicence= $DB->get_record_sql($sql, array('%/'.$id_licence.'/%','%/'.$id_licence));
        if (empty($listelicence->nb)) $listelicence->nb = 0;
        $listemaster= $DB->get_record_sql($sql, array('%/'.$id_master.'/%','%/'.$id_master));
        if (empty($listemaster->nb)) $listemaster->nb = 0;
        $listedoctorat= $DB->get_record_sql($sql, array('%/'.$id_doctorat.'/%','%/'.$id_doctorat));
        if (empty($listedoctorat->nb)) $listedoctorat->nb = 0;
        $listetotal = $DB->get_record_sql($sql, array('%/'. $id_ufr .'/%','%/'. $id_ufr )); 
        if (empty($listetotal->nb)) $listetotal->nb = 0;
		$nblicence = $listelicence->nb;
		$nb_master = $listemaster->nb;
		$nb_doctorat = $listedoctorat->nb;
		$nb_total = $listetotal->nb;
	    if ($calculautre) {
	    	if (!empty($array_orsql))  {
		    	if (strlen($orsql)) if (substr($sql,-2)==='))') $sql = substr($sql,0, -2).$orsql.'))'; else $sql = substr($sql,0, -1).$orsql.')';
				$listeautre= $DB->get_record_sql($sql, $array_orsql);
				if (!empty($listeautre->nb))  {
					$nb_autre = $listeautre->nb;
				} else {
					$nb_autre = 0;
				}	
	    	} else {
	    		$nb_autre = 0;
	    	}    	
	    } else {
	    	$nb_autre = $nb_total - $nblicence - $nb_master - $nb_doctorat;
	    }

	}
	if ($csv )
    	return array($name_ufr,
    					$nblicence,$nb_master,$nb_doctorat,
    					$nb_autre,$nb_total);
    else
    	return array('<a href="view.php?id='.$id_ufr.'">'.$name_ufr.'</a>',
    					$nblicence,$nb_master,$nb_doctorat,
    					$nb_autre,$nb_total);
	
}
function download_send_headers($filename) {
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download  
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}
function array2csv(array &$array) {
   if (count($array) == 0) {
     return null;
   }
   ob_start();
   $df = fopen("php://output", 'w');
   fputcsv($df, array_keys(reset($array)),';' );
   foreach ($array as $row) {
      fputcsv($df, $row,';');
   }
   fclose($df);
   return ob_get_clean();
}
function Nettoyer_chaine($chaine) {
	$chaine = str_replace('#039;', "'", $chaine);
//	$chaine = str_replace(';', ',', $chaine);
	$chaine = html_entity_decode($chaine, ENT_QUOTES);
	return $chaine;
}

function getNiveauRattachement($id) {
	global $DB;
	$select_niveau_ROF= "	SELECT data 
   	 						FROM {custom_info_data}
   	 						WHERE objectid = ?
   	 						AND fieldid = 14";
   	 $obj =  $DB->get_record_sql($select_niveau_ROF,array($id));
   	 if (!empty($obj->data)) {
   	 	$niveaux = explode('/',$obj->data);
   	 	if (!empty($niveaux[0])) return $niveaux[0];
   	 }
   	 return '';
}

function getMailResponsableEpiByIdEpi($contextlist, $cparams) {
	global $DB;
	$select_responsable = "	SELECT GROUP_CONCAT(u.email SEPARATOR ', ') as mail_responsable
   	 						FROM {user} u
   	 						JOIN {role_assignments} ra on (ra.userid=u.id)
   	 						WHERE ra.contextid $contextlist
   	 						AND ra.roleid=22";
   	$obj_responsable = $DB->get_record_sql($select_responsable,$cparams);
   	$mail_responsable = '';
   	if (!empty($obj_responsable->mail_responsable)) $mail_responsable = '<a href="mailto:'.$obj_responsable->mail_responsable.'">'.$obj_responsable->mail_responsable.'</a>';
   	return $mail_responsable;
}

function getMailResponsableEpiByIdEpiForCSV($contextlist, $cparams) {
	global $DB;
	$select_responsable = "	SELECT GROUP_CONCAT(u.email SEPARATOR ', ') as mail_responsable
   	 						FROM {user} u
   	 						JOIN {role_assignments} ra on (ra.userid=u.id)
   	 						WHERE ra.contextid $contextlist
   	 						AND ra.roleid=22";
   	$obj_responsable = $DB->get_record_sql($select_responsable,$cparams);
   	$mail_responsable = '';
   	if (!empty($obj_responsable->mail_responsable)) $mail_responsable = $obj_responsable->mail_responsable;
   	return $mail_responsable;
}

function isOpen($id) {
	global $DB;
	$select_visible = " SELECT visible from {course} where id= ? ";
	$obj =  $DB->get_record_sql($select_visible,array($id));
        if (!empty($obj->visible)) return 'Oui';
	return 'Non';
}

function getNbResponsableEpi($contextlist, $cparams) {
	global $DB;
	$select_nb_responsable_epi = "	SELECT count(*) as nb_responsable_epi
		   	 						FROM {user} u
		   	 						JOIN {role_assignments} ra on (ra.userid=u.id)
		   	 						WHERE ra.contextid $contextlist
		   	 						AND ra.roleid =22";

   	$obj_nb_responsable_epi = $DB->get_record_sql($select_nb_responsable_epi,$cparams);
   	$nb_responsable_epi = '0';
	if (!empty($obj_nb_responsable_epi->nb_responsable_epi)) $nb_responsable_epi= $obj_nb_responsable_epi->nb_responsable_epi;
   	return $nb_responsable_epi;
}

function getNbEnseignantsEditeurs($contextlist, $cparams) {
	global $DB;
	$select_nb_responsable_epi = "	SELECT count(*) as nb_enseignant_editeur
		   	 						FROM {user} u
		   	 						JOIN {role_assignments} ra on (ra.userid=u.id)
		   	 						WHERE ra.contextid $contextlist
		   	 						AND ra.roleid =3";

   	$obj_nb_responsable_epi = $DB->get_record_sql($select_nb_responsable_epi,$cparams);
   	$nb_enseignant_editeur = '0';
	if (!empty($obj_nb_responsable_epi->nb_enseignant_editeur)) $nb_enseignant_editeur= $obj_nb_responsable_epi->nb_enseignant_editeur;
   	return $nb_enseignant_editeur;
}

function getNbEnseignantsNonEditeurs($contextlist, $cparams) {
	global $DB;
	$select_nb_responsable_epi = "	SELECT count(*) as nb_enseignant_non_editeur
		   	 						FROM {user} u
		   	 						JOIN {role_assignments} ra on (ra.userid=u.id)
		   	 						WHERE ra.contextid $contextlist
		   	 						AND ra.roleid =4";

   	$obj_nb_responsable_epi = $DB->get_record_sql($select_nb_responsable_epi,$cparams);
   	$nb_enseignant_non_editeur = '0';
	if (!empty($obj_nb_responsable_epi->nb_enseignant_non_editeur)) $nb_enseignant_non_editeur= $obj_nb_responsable_epi->nb_enseignant_non_editeur;
   	return $nb_enseignant_non_editeur;
}

function getNbEtudiantsInscrits($contextlist, $cparams) {
	global $DB;
	$select_nb_students = "	SELECT count(distinct userid) as nb_students
   	 						FROM {role_assignments} r
   	 						WHERE contextid $contextlist
   	 						AND roleid=5";

   	 $obj_nb_students= $DB->get_record_sql($select_nb_students,$cparams);
   	 $nb_sudents = 0; 
   	 if (!empty($obj_nb_students->nb_students)) $nb_sudents = $obj_nb_students->nb_students;
   	 if (!empty($obj_nb_responsable_epi->nb_enseignant_non_editeur)) $nb_responsable_epi= $obj_nb_responsable_epi->nb_enseignant_non_editeur;
   	return $nb_sudents;
}

function HaveStudentKey($id) {
	global $DB;
	$select_cle_etudiante = "	SELECT id 
   	 							FROM {enrol}
   	 							WHERE courseid = ?
   	 							AND enrol like 'self'
   	 							AND password is not null";
   	 $obj_cle_etudiante =  $DB->get_records_sql($select_cle_etudiante,array($id));
   	 $cle_etudiante= 'Non';
   	 if (!empty($obj_cle_etudiante)) $cle_etudiante = 'Oui';
   	 return $cle_etudiante;
}

function HaveGuestKey($id) {
	global $DB;
   	 $select_cle_visiteur = "	SELECT id 
   	 							FROM {enrol}
   	 							WHERE courseid = ?
   	 							AND enrol like 'guest'
   	 							AND password is not null";
   	 $obj_cle_visiteur =  $DB->get_records_sql($select_cle_visiteur,array($id));
   	 $cle_visiteur = 'Non';
   	 if (!empty($obj_cle_visiteur)) $cle_visiteur = 'Oui';
   	 return $cle_visiteur;
}

function HaveFreeAccess($id) {
	global $DB;
	$select_acces_libre = "	SELECT id 
   	 						FROM {enrol}
   	 						WHERE courseid = ?
   	 						AND enrol like 'guest'
   	 						AND (password is null or password='')";
   	 $obj_acces_libre =  $DB->get_records_sql($select_acces_libre,array($id));
   	 $acces_libre = 'Non';
   	 if (!empty($obj_acces_libre)) $acces_libre == 'Oui';
   	 return $acces_libre;
}
 
function getPercentDefaultSection($id) {
	global $DB;
	$select_nb_default_section = "	SELECT count(id) as nb
   	 									FROM {course_sections}
   	 									WHERE course = ?
   	 									AND (name like 'section 1' OR name like 'section 2' OR name like 'section 3' )";
   	 $obj=  $DB->get_record_sql($select_nb_default_section,array($id));

   	 if (!empty($obj->nb))  {
   	 	if ($obj->nb == 0) return 0;
   	 	elseif ($obj->nb == 1) return 33;
   	 	elseif ($obj->nb == 2) return 66;
   	 	else return 100;
   	 	
   	 }
   	 return 0;
}

function FileDetails($id) {
	global $DB;
	$nb_files = 0;
	$nb_ppt = 0;
	$nb_doc = 0;
	$nb_pdf = 0;
	$nb_autre_format = 0;
	$select = "	SELECT F.id, F.filename 
			FROM {files} F  
			INNER JOIN {context} AS CTX ON F.contextid = CTX.id  
			INNER JOIN {course_modules} AS CM ON CTX.instanceid=CM.id 
			WHERE CM.course = ?
			AND (F.component = 'mod_resource' OR F.component = 'mod_folder') 
			AND F.filename!='.'";
	$obj = $DB->get_records_sql($select,array($id));
	foreach ($obj as $i=>$row) {
		$info = new SplFileInfo($row->filename);
		if (strtoupper($info->getExtension()) == 'PPT') $nb_ppt++; 
		elseif (strtoupper($info->getExtension()) == 'DOC' || strtoupper($info->getExtension()) == 'DOCX') $nb_doc++; 
		elseif (strtoupper($info->getExtension()) == 'PDF') $nb_pdf++; 
		else $nb_autre_format++;
	}
	return array($nb_ppt,$nb_doc,$nb_pdf,$nb_autre_format);
}

 function getNbURL($id) {
	global $DB;
	$select_nb_url = "	SELECT count(id) as nb
   	 					FROM {url}
   	 					WHERE course = ?";
   	 $obj=  $DB->get_record_sql($select_nb_url,array($id));

   	 if (!empty($obj->nb))  return $obj->nb;
   	 return 0;
}

 function getNbPages($id) {
	global $DB;
	$select_nb_page = "	SELECT count(id) as nb
   	 					FROM {page}
   	 					WHERE course = ?";
   	 $obj=  $DB->get_record_sql($select_nb_page,array($id));

   	 if (!empty($obj->nb))  return $obj->nb;
   	 return 0;
}

 function getNbForums($id) {
	global $DB;
	$select_nb_forum = "	SELECT count(id) as nb
   	 						FROM {forum}
   	 						WHERE course = ?";
   	 $obj=  $DB->get_record_sql($select_nb_forum,array($id));
   	 if (!empty($obj->nb))  return $obj->nb;
   	 return 0;
}

function getNbContributionsForums($id) {
	global $DB;
	/*
	$select_nb_forum = "	SELECT count(FD.id) as nb
							FROM {forum_discussions} FD
							INNER JOIN {forum} A on FD.forum = A.id
   	 						WHERE A.course = ?";
   	 $obj=  $DB->get_record_sql($select_nb_forum,array($id));
   	 if (!empty($obj->nb))  return $obj->nb;
   	 */
	$nb = 0;
	$select_forums = "    SELECT f.id FROM {forum} f where course = ?";
	$obj_forum = $DB->get_records_sql($select_forums,array($id));
	foreach($obj_forum as $i=>$forum) {
		$sql = "SELECT p.discussion as did, COUNT(p.id) AS replies
                  	FROM {forum_posts} p
                       	JOIN {forum_discussions} d ON p.discussion = d.id
                 	WHERE d.forum = ? AND p.parent >0
             		GROUP BY p.discussion";
        	$obj_replies = $DB->get_records_sql($sql, array($forum->id));
		foreach($obj_replies as $j=>$replies) {
			$nb++;
			$nb += $replies->replies;
		}
		// calcul des discussions n'ayant pas de réponses
		$sql2 = "	SELECT count(id) as nb
				FROM {forum_discussions} 
				where forum = ?
				AND id not in (
					SELECT p.discussion 
                        		FROM {forum_posts} p
                        		JOIN {forum_discussions} d ON p.discussion = d.id
                      		  	WHERE d.forum = ? AND p.parent >0
				)";
		$obj=  $DB->get_record_sql($sql2,array($forum->id,$forum->id));
         	if (!empty($obj->nb))  $nb+=$obj->nb;
	}
	return $nb;
}

function getNbSurvey($id) {
	global $DB;
	$select_nb_survey = "	SELECT count(id) as nb
   	 						FROM {survey}
   	 						WHERE course = ?";
   	 $obj=  $DB->get_record_sql($select_nb_survey,array($id));
   	 if (!empty($obj->nb))  return $obj->nb;
   	 return 0;
}

function getNbFeedbacks($id) {
	global $DB;
	$select_nb_feedbacks = "	SELECT count(id) as nb
   	 							FROM {feedback}
   	 							WHERE course = ?";
   	 $obj=  $DB->get_record_sql($select_nb_feedbacks,array($id));
   	 if (!empty($obj->nb))  return $obj->nb;
   	 return 0;
}

function getNbDatabases($id) {
	global $DB;
	$select_nb_databases = "	SELECT count(id) as nb
   	 							FROM {data}
   	 							WHERE course = ?";
   	 $obj=  $DB->get_record_sql($select_nb_databases,array($id));
   	 if (!empty($obj->nb))  return $obj->nb;
   	 return 0;
}


function getNbGlossary($id) {
	global $DB;
	$select_nb_glossaries = "	SELECT count(id) as nb
   	 									FROM {glossary}
   	 									WHERE course = ?";
   	 $obj=  $DB->get_record_sql($select_nb_glossaries,array($id));
   	 if (!empty($obj->nb))  return $obj->nb;
   	 return 0;
}

function getNbTests($id) {
	global $DB;
	$select_nb_quizs = "	SELECT count(id) as nb
   	 									FROM {quiz}
   	 									WHERE course = ?";
   	 $obj=  $DB->get_record_sql($select_nb_quizs,array($id));
   	 if (!empty($obj->nb))  return $obj->nb;
   	 return 0;
}

function getNbExternalActivities($id) {
	global $DB;
	$select = "	SELECT count(id) as nb
   	 									FROM {grade_items}
   	 									WHERE courseid = ?
   	 									AND itemmodule = 'lti'";
   	 $obj=  $DB->get_record_sql($select,array($id));
   	 if (!empty($obj->nb))  return $obj->nb;
   	 return 0;
}

function getNbDevoirsRemis($id) {
	global $DB;
	$select_nb_devoirs_remis = "	SELECT count(distinct ASU.assignment, ASU.userid) as nb
   	 									FROM {assign_submission} ASU
										INNER JOIN {assign} A on ASU.assignment = A.id
   	 									WHERE A.course = ? 
										AND ASU.status='submitted'";
   	 $obj=  $DB->get_record_sql($select_nb_devoirs_remis,array($id));
   	 if (!empty($obj->nb))  return $obj->nb;
   	 return 0;
}

function getNbVues($id) {
	global $DB;
	$select_nb_vues = "	SELECT count(id) as nb
   	 									FROM {logstore_standard_log}
   	 									WHERE courseid = ?
   	 									AND contextlevel = 50
   	 									AND action ='viewed'";
   	 $obj=  $DB->get_record_sql($select_nb_vues,array($id));
   	 if (!empty($obj->nb))  return $obj->nb;
   	 return 0;
}


function ObservatoireEPI($annee) {
	global $DB;
	$array_ufr = array();
	$selectetab = "SELECT id  from {course_categories} where parent=? ;";
	$etab = $DB->get_record_sql($selectetab, array($annee));
	if (!empty($etab->id)) {
		$selectufr = "SELECT id, name from {course_categories} where parent = :parent  order by name;";
		$ufrs  = $DB->get_records_sql($selectufr, array('parent'=>$etab->id));
		foreach($ufrs as $i=>$ufr) {
			$array_ufr[] = array('<a href="view.php?id='. $ufr->id .'&annee='. $annee .'"  target="_BLANK">' . $ufr->name .'</a>');
		}
	}
	return $array_ufr;
}

function tooltipthis($content,$identifier) {
	$return = '<a href="#" class="tooltip">
				    '.$content.'
				    <span>
				        <img class="callout" src="img/callout.gif" />
				        <strong>'.get_string($identifier,'local_up1reportepiufr').'</strong>';
	if (!empty (get_string($identifier.'_msg','local_up1reportepiufr'))) $return .= 			'<br />'.get_string($identifier.'_msg','local_up1reportepiufr');
	
	$return .='    </span>
				</a>';
	
	return $return;
}

function nb_ajout_fichier_ressource($periode_debut,$periode_fin) {
	global $DB;
	$select = "select count(id) as nb from mdl_resource where timemodified > UNIX_TIMESTAMP('".$periode_debut."') and timemodified < UNIX_TIMESTAMP('".$periode_fin."') ";
	$obj=  $DB->get_record_sql($select);
         if (!empty($obj->nb))  return $obj->nb;
         return 0;
}


function nb_vue_fichier_ressource($periode_debut,$periode_fin) {
        global $DB;
        $select = "select count(id) as nb from mdl_logstore_standard_log where component='mod_resource' and action='viewed'  and timecreated > UNIX_TIMESTAMP('".$periode_debut."') and timecreated < UNIX_TIMESTAMP('".$periode_fin."') ";
        $obj=  $DB->get_record_sql($select);
         if (!empty($obj->nb))  return $obj->nb;
         return 0;
}

function nb_course_created($periode_debut,$periode_fin) {
        global $DB;
        $select = "select count(id) as nb from mdl_course where timecreated > UNIX_TIMESTAMP('".$periode_debut."') and timecreated < UNIX_TIMESTAMP('".$periode_fin."') ";
        $obj=  $DB->get_record_sql($select);
         if (!empty($obj->nb))  return $obj->nb;
         return 0;
}

function nb_forum_announce_created($periode_debut,$periode_fin) {
        global $DB;
        $select = "select count(distinct(post.id)) as nb from mdl_forum_posts post inner join mdl_forum_discussions disc on disc.id=post.discussion inner join mdl_forum forum on forum.id=disc.forum where forum.type='news' and created > UNIX_TIMESTAMP('".$periode_debut."') and created < UNIX_TIMESTAMP('".$periode_fin."') ";
        $obj=  $DB->get_record_sql($select);
         if (!empty($obj->nb))  return $obj->nb;
         return 0;
}
function nb_other_forum_created($periode_debut,$periode_fin) {
        global $DB;
        $select = "select count(distinct(post.id)) as nb from mdl_forum_posts post inner join mdl_forum_discussions disc on disc.id=post.discussion inner join mdl_forum forum on forum.id=disc.forum where forum.type='general' and created > UNIX_TIMESTAMP('".$periode_debut."') and created < UNIX_TIMESTAMP('".$periode_fin."') ";
        $obj=  $DB->get_record_sql($select);
         if (!empty($obj->nb))  return $obj->nb;
         return 0;
}

function nb_assign_created($periode_debut,$periode_fin) {
        global $DB;
        $select = "select count(id) as nb from mdl_assign where timemodified > UNIX_TIMESTAMP('".$periode_debut."') and timemodified < UNIX_TIMESTAMP('".$periode_fin."') ";
        $obj=  $DB->get_record_sql($select);
         if (!empty($obj->nb))  return $obj->nb;
         return 0;
}

function nb_assign_submitted($periode_debut,$periode_fin) {
        global $DB;
        $select = "select count(id) as nb from mdl_assign_submission where timecreated > UNIX_TIMESTAMP('".$periode_debut."') and timecreated < UNIX_TIMESTAMP('".$periode_fin."') ";
        $obj=  $DB->get_record_sql($select);
         if (!empty($obj->nb))  return $obj->nb;
         return 0;
}
function nb_quiz_created($periode_debut,$periode_fin) {
        global $DB;
        $select = "select count(id) as nb from mdl_quiz where timecreated > UNIX_TIMESTAMP('".$periode_debut."') and timecreated < UNIX_TIMESTAMP('".$periode_fin."') ";
        $obj=  $DB->get_record_sql($select);
         if (!empty($obj->nb))  return $obj->nb;
         return 0;
}
function nb_quiz_submitted($periode_debut,$periode_fin) {
        global $DB;
        $select = "select count(id) as nb from mdl_quiz_attempts where timefinish > UNIX_TIMESTAMP('".$periode_debut."') and timefinish < UNIX_TIMESTAMP('".$periode_fin."') ";
        $obj=  $DB->get_record_sql($select);
         if (!empty($obj->nb))  return $obj->nb;
         return 0;
}
function nb_real_bbb($periode_debut,$periode_fin) {
        global $DB;
        $select = "select count(id) as nb from mdl_bigbluebuttonbn_logs bbblog where (select count(distinct(bbblogjoin.id)) from mdl_bigbluebuttonbn_logs bbblogjoin where bbblogjoin.bigbluebuttonbnid = bbblog.id and bbblogjoin.log='join'  )>= 5 and bbblog.log like 'creat%' and bbblog.timecreated > UNIX_TIMESTAMP('".$periode_debut."') and bbblog.timecreated < UNIX_TIMESTAMP('".$periode_fin."') ";
        $obj=  $DB->get_record_sql($select);
         if (!empty($obj->nb))  return $obj->nb;
         return 0;
}
function nb_bbb_sessions($periode_debut,$periode_fin) {
        global $DB;
        $select ="select count(id) as nb from mdl_bigbluebuttonbn_logs bbb where bbb.log like 'creat%' and meta like '%true%' and timecreated > UNIX_TIMESTAMP('".$periode_debut."') and timecreated < UNIX_TIMESTAMP('".$periode_fin."') ";
        $obj=  $DB->get_record_sql($select);
         if (!empty($obj->nb))  return $obj->nb;
         return 0;
}
function nb_bbb_participants($periode_debut,$periode_fin) {
        global $DB;
        $select = "select count(distinct(bbblogjoin.id)) as nb from mdl_bigbluebuttonbn bbb inner join mdl_bigbluebuttonbn_logs bbblogjoin on bbblogjoin.bigbluebuttonbnid = bbb.id where bbblogjoin.log='join' and bbblogjoin.timecreated > UNIX_TIMESTAMP('".$periode_debut."') and bbb.timecreated < UNIX_TIMESTAMP('".$periode_fin."') ";
        $obj=  $DB->get_record_sql($select);
         if (!empty($obj->nb))  return $obj->nb;
         return 0;
}
