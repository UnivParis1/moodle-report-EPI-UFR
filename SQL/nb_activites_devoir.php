<?php
/* SOMME ACTIVITÉS DEVOIR */
$SELECT = "	SELECT count(A.id) AS nb FROM {assign} A
		    INNER JOIN {course} C on A.course = C.id
		    INNER JOIN {course_categories} CC on C.category = CC.id
		    WHERE (CC.path LIKE ? OR CC.path LIKE ? )";

$count = false;
$calculautre = false;