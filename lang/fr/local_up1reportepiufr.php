<?php

/**
 * Administrator reporting
 *
 * @package    local
 * @subpackage up1reportepiufr
 * @author 		El-Miqui CHEMLALI
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Settings
$string['pluginname'] = 'UP1 EPI Evaluation';

//Titre des pages
$string['title_index'] = 'Statistiques EPI';
$string['title_index_with_year'] = 'Statistiques EPI - [annee]';

$string['heading'] = 'Statistiques EPI';
$string['heading_with_year'] = 'Statistiques EPI - [annee]';
$string['choose_cat'] = 'Choisir l\'année universitaire :';
$string['stat_gene'] = 'Statistiques générales';
$string['observatoire'] = 'Observatoire des usages';

//bouton formulaire
$string['ok'] = 'Valider';

// tableau 2 index
$string['lien_epis_users'] = 'Espaces de cours et utilisateurs';
$string['lien_activity'] = 'Activités, devoirs et ressources';
$string['lien_forum'] = 'Forums et discussions';
$string['URL'] = 'Lien';
$string['dw_stat_by_cmp'] = 'Télécharger les statistiques par composante';
$string['dw_stat_by_dip'] = 'Télécharger les statistiques par diplôme ';
$string['nb_activites_devoir'] = 'Nombre d\'activités devoir';
$string['nb_consultation_forum_annonce'] = 'Nombre de consultations (forum view forum) forum des annonces (news)';
$string['nb_consultation_forum_autre'] = 'Nombre de consultations (forum view discussion) forum des autre qu\'annonces (non news)';
$string['nb_contribution_forum_annonce'] = 'Nombre de contributions des forums des annonces (news)';
$string['nb_contribution_forum_autre'] = 'Nombre de contributions des forums autre qu\'annonces (non news)';
$string['nb_devoirs_rendus'] = 'Nombre de devoirs rendus (assign submit)';
$string['nb_enseignants'] = 'Nombre d\'utilisateurs avec rôle enseignant ou associé';
$string['nb_epis'] = 'Nombre d\'espaces de cours';
$string['nb_etudiants_jamais_connectes'] = 'Nombre d\'utilisateurs avec rôle étudiant jamais connectés';
$string['nb_etudiants'] = 'Nombre d\' utilisateurs avec un rôle étudiant';
$string['nb_etudiants_cohort'] = 'Nombre d\' utilisateurs issues d\'une synchronisation de cohortes LDAP';
$string['nb_forum_annonces'] = 'Nombre de forums des annonces (news)';
$string['nb_forum_autres'] = 'Nombre de forums autre qu\annonces (non news)';
$string['nb_ressource_fichier'] = 'Nombre de ressources de type Fichier';
$string['nb_ressource_page'] = 'Nombre de ressources de type page';
$string['nb_ressource_url'] = 'Nombre de ressources de type URL';

$string['nb_activites_devoir_libcol'] = 'Nombre d\'activités devoir';
$string['nb_consultation_forum_annonce_libcol'] = 'Nombre de consultations (forum view forum) forum des annonces (news)';
$string['nb_consultation_forum_autre_libcol'] = 'Nombre de consultations (forum view discussion) forum des autre qu\'annonces (non news)';
$string['nb_contribution_forum_annonce_libcol'] = 'Nombre de contributions des forums des annonces (news)';
$string['nb_contribution_forum_autre_libcol'] = 'Nombre de contributions des forums autre qu\'annonces (non news)';
$string['nb_devoirs_rendus_libcol'] = 'Nombre de devoirs rendus (assign submit)';
$string['nb_enseignants_libcol'] = 'Nombre d\'utilisateurs avec rôle enseignant ou associé (utilisateurs uniques)';
$string['nb_epis_libcol'] = 'Nombre d\'espaces de cours';
$string['nb_etudiants_jamais_connectes_libcol'] = 'Nombre d\'utilisateurs avec rôle étudiant jamais connectés (utilisateurs uniques)';
$string['nb_etudiants_libcol'] = 'Nombre d\' utilisateurs avec un rôle étudiant (utilisateurs uniques)';
$string['nb_etudiants_cohort_libcol'] = 'Nombre d\' utilisateurs issues d\'une synchronisation de cohortes LDAP (utilisateurs uniques)';
$string['nb_forum_annonces_libcol'] = 'Nombre de forums des annonces (news)';
$string['nb_forum_autres_libcol'] = 'Nombre de forums autre qu\annonces (non news)';
$string['nb_ressource_fichier_libcol'] = 'Nombre de ressources de type Fichier';
$string['nb_ressource_page_libcol'] = 'Nombre de ressources de type page';
$string['nb_ressource_url_libcol'] = 'Nombre de ressources de type URL';

// Messages explicatifs
$string['msg_nb_activites_devoir'] = '';
$string['msg_nb_consultation_forum_annonce'] = '';
$string['msg_nb_consultation_forum_autre'] = '';
$string['msg_nb_contribution_forum_annonce'] = '';
$string['msg_nb_contribution_forum_autre'] = '';
$string['msg_nb_devoirs_rendus'] = '';
$string['msg_nb_enseignants'] = '';
$string['msg_nb_epis'] = '
						<h4>Nombre d’EPI</h4>
						<p>Un EPI qui dessert plusieurs composantes n’est compté qu’une seule fois et seulement dans sa composante de référence (rattachement ROF principal).</p>
						<p>Les EPI signalés par un + dans la colonne « Code » de la « Vue tableau" de l’index des cours peuvent donc n’être pas comptés comme EPI de la composante considérée s’il sont physiquement rattachés à une autre composante.</p>
						<p><u>Exemple : les EPI physiquement rattachés au Département de sociologie qui desservent l’UFR de Philosophie ne sont comptés qu’une seule fois sur la ligne du Département de sociologie.</u></p>';
$string['msg_nb_etudiants_jamais_connectes'] = '';
$string['msg_nb_etudiants'] = '	<h4>Nombre d’étudiants par composante : </h4>
								<p>La requête est effectuée par catégories au niveau « composante » sur le « user-id » d’où il suit que :</p>
								<p>
									<ul>
										<li>un étudiant inscrit à plusieurs diplômes au sein d’une même composante n’est compté qu’une seule fois</li>
										<li> un étudiant inscrit dans plusieurs composantes (par exemple en double cursus Droit et Histoire) est compté autant de fois que de composantes auxquelles il est inscrit.</li>
									</ul>
								</p>
<h4>Nombre d’étudiants total établissement :</h4>
<p>Le nombre total d’étudiants sur l’ensemble de l’établissement rétablit le biais ci-dessus et exclut les doublons d’étudiants inscrits dans plusieurs composantes. <u>Le nombre total d’étudiants sur l’établissement peut donc différer de la somme des étudiants par composante.</u></p>';
$string['msg_nb_etudiants_cohort'] = '';
$string['msg_nb_forum_annonces'] = '';
$string['msg_nb_forum_autres'] = '';
$string['msg_nb_ressource_fichier'] = '';
$string['msg_nb_ressource_page'] = '';
$string['msg_nb_ressource_url'] = '';
// tableau 2 index
$string['ufr'] = 'UFR';

//colonnes Observatoire
$string['col01'] = 'EPI';
$string['col02'] = 'Niveau de rattachement';
$string['col03'] = 'Adresse mail du responsable EPI';
$string['col04'] = 'Enseignants Resposable EPI';
$string['col05'] = 'Enseignants éditeurs';
$string['col06'] = 'Enseignants non éditeurs';
$string['col07'] = 'Étudiants inscrits';
$string['col08'] = 'Clé étudiant';
$string['col09'] = 'Clé visiteur';
$string['col10'] = 'Accès libre?';
$string['col11'] = '% de noms de sections';
$string['col12'] = 'Fichiers en téléchargement';
$string['col13'] = 'Types fichiers';
$string['col14'] = 'URL';
$string['col15'] = 'Pages';
$string['col16'] = 'Activité de type Forum';
$string['col17'] = 'Contributions aux Forums';
$string['col18'] = 'Activités de type Sondage';
$string['col19'] = 'Activités de type Feedback';
$string['col20'] = 'Activités de type BDD';
$string['col21'] = 'Activités de type Glossaire';
$string['col22'] = 'Activités de type Tests';
$string['col23'] = 'Activités externes';
$string['col24'] = 'Devoirs remis';
$string['col25'] = 'Notifications';
$string['col26'] = 'Vues totales';
$string['col27'] = 'Date de dernière modification';
// message tolltip
$string['col01_msg'] = 'Lien vers le cours';
$string['col02_msg'] = 'Il s\'agit du niveau de rattachement niveau de rattachement <br />
			(L1,L2,L3,M1,M2). Cette information est renseigné que pour <br />
			les cours créés ou dupliqués à partir du ROF cache';
$string['col03_msg'] = 'Il put  s\'agir de l\'adresse Email de l\'enseignant éditeur,<br />
			 si l\'EPI ne possède pas d\'enseignant reponsable et qu\'il <br />
			ne possède qu\'un seul enseignant éditeur.';
$string['col04_msg'] = '';
$string['col05_msg'] = '';
$string['col06_msg'] = '';
$string['col07_msg'] = '';
$string['col08_msg'] = 'L\'EPI possède une clé d\inscription à destination des étudiants.';
$string['col09_msg'] = 'L\'EPI possède une clé d\inscription à destination des visteur.';
$string['col10_msg'] = 'L\'EPI est en accès libre.';
$string['col11_msg'] = 'Si 1/3 alors 33%, Si 2/3 alors 66% et 100% si 3/3';
$string['col12_msg'] = '';
$string['col13_msg'] = 'Seuls les formats de types PPT, DOC ou DOCX, et PDF <br />
			sont classés les autres sont classés dans une catégorie AUTRE.';
$string['col14_msg'] = '';
$string['col15_msg'] = '';
$string['col16_msg'] = '';
$string['col17_msg'] = '';
$string['col18_msg'] = '';
$string['col19_msg'] = '';
$string['col20_msg'] = '';
$string['col21_msg'] = '';
$string['col22_msg'] = '';
$string['col23_msg'] = '';
$string['col24_msg'] = '';
$string['col25_msg'] = '';
$string['col26_msg'] = '';
$string['col27_msg'] = '';


$string['last_ligne'] = 'Résumé de l\'UFR';
$string['graph01'] = 'Répartition des activités et ressources';
$string['graph02'] = 'Résumé de l\'UFR';
$string['graph03'] = 'Tendance de la collaboration entre enseignants';
$string['graph04'] = 'Examen des ressources utilisés';

//Libéllé statistiques générales
$string['diplome'] = 'Diplôme';
$string['nombre'] = 'Nombre';

$string['libelle'] = 'Libellé';
$string['licence'] = 'Licence';
$string['master'] = 'Master';
$string['doctorat'] = 'Doctorat';
$string['autre'] = 'Autre';
$string['global'] = 'Global';

$string['global_nb_epis'] = 'Global';
$string['global_nb_activites_devoir'] = 'Global';
$string['global_nb_devoirs_rendus'] = 'Global';
$string['global_nb_ressource_page'] = 'Global';
$string['global_nb_ressource_url'] = 'Global';
$string['global_nb_ressource_fichier'] = 'Global';
$string['global_nb_forum_annonces'] = 'Global';
$string['global_nb_contribution_forum_annonce'] = 'Global';
$string['global_nb_forum_autres'] = 'Global';
$string['global_nb_consulation_forum_autre'] = 'Global';
$string['global_nb_contribution_forum_autre'] = 'Global';

$string['global_nb_enseignants'] = 'Total utilisateurs uniques';
$string['global_nb_etudiants'] = 'Total utilisateurs uniques';
$string['global_nb_etudiants_jamais_connectes'] = 'Total utilisateurs uniques';