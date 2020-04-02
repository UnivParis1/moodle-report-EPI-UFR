<?php
/* TABLEAU ETUDIANTS inscrits*/
$SELECT = "	SELECT count(distinct tra.userid)  AS nb 
	        FROM {course} AS c 
	        INNER JOIN {context} AS ctx ON (c.id = ctx.instanceid AND ctx.contextlevel = 50)
	        INNER JOIN {role_assignments} AS tra ON tra.contextid = ctx.id 
	        INNER JOIN {course_categories} AS CC ON c.category = CC.id
	        INNER JOIN {user} AS U ON U.id = tra.userid
	        WHERE tra.roleid = 5
	        AND U.email LIKE '%malix.univ-paris1.fr' 
	        AND  ( CC.path LIKE ? OR CC.path LIKE ? )";
$requete_etab_dedoublonnee = true;
$count = false;
$calculautre = true;

