<?php
/* SOMME DES RESSOURCES URL */
$SELECT = "	SELECT count(*) AS nb FROM {url} P
		    INNER JOIN {course} C ON P.course = C.id
		    INNER JOIN {course_categories} CC ON C.category = CC.id 
    		WHERE (CC.path LIKE ? OR CC.path LIKE ?)";

$count = false;
$calculautre = false;