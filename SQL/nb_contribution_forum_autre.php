<?php
 /* SOMME FORUMS CONTRIBUTION DES ANNONCES */
$SELECT = " SELECT distinct FD.id FROM {forum_discussions} FD
			INNER JOIN {forum} A ON FD.forum = A.id
			INNER JOIN {course} C ON A.course = C.id
			INNER JOIN {course_categories} CC ON C.category = CC.id
			WHERE A.type NOT LIKE 'news'
			AND (CC.path LIKE ? OR CC.path LIKE ? )";
$count = true;
$calculautre = false;