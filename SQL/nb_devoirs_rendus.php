<?php
/* SOMME DES DEVOIRS RENDUS */
$SELECT = "	SELECT count(ASU.id) AS nb FROM {assign_submission} ASU
			INNER JOIN {assign} A ON ASU.assignment = A.id
			INNER JOIN {course} C ON A.course = C.id
			INNER JOIN {course_categories} CC ON C.category = CC.id 
			WHERE (CC.path LIKE ? OR CC.path LIKE ? )
			AND ASU.status='submitted'";
$count = false;
$calculautre = false;