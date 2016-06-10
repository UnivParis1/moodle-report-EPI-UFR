<?php
/* SOMME FORUMS_ANNONCES DEVOIR */
$SELECT= "	SELECT count(distinct FR.id) as nb FROM {forum_read} FR
		    INNER JOIN {forum} F ON FR.forumid = F.id
		    INNER JOIN {course} C ON F.course = C.id
		    INNER JOIN {course_categories} CC ON C.category = CC.id
		    WHERE F.type LIKE 'news'
		    AND (CC.path LIKE ? OR CC.path LIKE ? )";
$count = false;
$calculautre = false;