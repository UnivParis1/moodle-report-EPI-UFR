<?php
/* SOMME DES RESSOURCES FICHIER */
$SELECT  = "	SELECT count(F.id) as nb 
		FROM {files} F  
		INNER JOIN {context} AS CTX ON F.contextid = CTX.id  
		INNER JOIN {course_modules} AS CM ON CTX.instanceid=CM.id 
		INNER JOIN {course} AS C ON CM.course=C.id 
		INNER JOIN {course_categories} AS CC ON C.category = CC.id
		WHERE (F.component = 'mod_resource' OR F.component = 'mod_folder') 
		AND F.filename!='.'
		AND ( CC.path LIKE ? OR CC.path LIKE ? )";

$count = false;
$calculautre = false;