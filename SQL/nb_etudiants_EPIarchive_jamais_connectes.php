<?php
/* ETUDIANTS JAMAIS CONNECTES */
$SELECT = "		SELECT count( distinct U.id ) as nb
				FROM {user} U
				WHERE U.lastlogin = 0
				AND U.id  IN  ( 
					SELECT distinct tra.userid  AS id 
				        FROM {course} AS c 
				        JOIN {context} AS ctx ON (c.id = ctx.instanceid and ctx.contextlevel = 50)
				        JOIN {role_assignments} AS tra ON tra.contextid = ctx.id 
				        JOIN {course_categories} AS CC ON c.category = CC.id
				        JOIN {user} AS U ON U.id = tra.userid
				        WHERE tra.roleid = 26
				        AND (email LIKE '%malixuniv-paris1.fr' or email like '%etuuniv-paris1.fr') 
				        AND ( CC.path LIKE ? OR CC.path LIKE ? ))";
$requete_etab_dedoublonnee = true;
$count = false;
$calculautre = true;
