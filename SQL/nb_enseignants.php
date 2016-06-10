<?php
$SELECT = "	SELECT count(distinct tra.userid)  AS nb 
	        FROM {course} AS c 
	        INNER JOIN {context} AS ctx ON (c.id = ctx.instanceid and ctx.contextlevel = 50)
	        INNER JOIN {role_assignments} AS tra ON tra.contextid = ctx.id 
	        INNER JOIN {course_categories} AS CC ON c.category = CC.id
	        WHERE tra.roleid in (3,4,18,19) 
	        AND ( CC.path LIKE ? OR CC.path LIKE ? )";
$count = false;
$calculautre = true;
$requete_etab_dedoublonnee = true;
