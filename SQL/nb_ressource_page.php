<?php
/* SOMME DES RESSOURCES PAGE */
$SELECT = "	SELECT count(*) AS nb FROM {page} P
		    INNER JOIN {course} C ON P.course = C.id
		    INNER JOIN {course_categories} CC ON C.category = CC.id 
    		WHERE (CC.path LIKE ? OR CC.path LIKE ?)";

$count = false;
$calculautre = false;