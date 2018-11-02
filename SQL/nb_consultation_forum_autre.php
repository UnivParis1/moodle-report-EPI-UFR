<?php
 /* SOMME FORUMS_ANNONCES AUTRE */
 $SELECT= "	SELECT  distinct FR.id FROM {forum_read} FR
		    INNER JOIN {forum} F ON F.id=FR.forumid
                    INNER JOIN {forum_discussions} A ON FR.discussionid = A.id
		    INNER JOIN {course} C ON A.course = C.id
		    INNER JOIN {course_categories} CC ON C.category = CC.id
		    WHERE F.type NOT LIKE 'news'
		    AND (CC.path LIKE ? OR CC.path LIKE ? )";
$count = true;
$calculautre = false;
//forum_discussions
