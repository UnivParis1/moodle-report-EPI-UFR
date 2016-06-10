<?php 
/* SOMME ESPACES DE COURS */
$SELECT = "	SELECT count( distinct C.id) as nb
        	FROM {course} C 
        	INNER JOIN {course_categories} CC on ( C.category =  CC.id) 
        	WHERE (CC.path LIKE ? OR CC.path LIKE ? )";
$count = false;
$calculautre = false;