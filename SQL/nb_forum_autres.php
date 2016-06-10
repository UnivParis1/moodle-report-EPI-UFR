<?php
/* SOMME FORUMS AUTRE */
$SELECT = "	SELECT count(distinct F.id) as nb FROM mdl_forum F
						    INNER JOIN {course} C ON F.course = C.id
						    INNER JOIN {course_categories} CC on C.category = CC.id
						    WHERE type not like 'news'
						    AND (CC.path LIKE ? OR CC.path LIKE ?)";

$count = false;
$calculautre = false;