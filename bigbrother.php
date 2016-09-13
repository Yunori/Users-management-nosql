#!/usr/bin/env php
<?php
// bigbrother.php for bigbrother in /home/bertoc_t/lol
// 
// Made by Bertocco Thomas-Killian
// Login   <bertoc_t@etna-alternance.net>
// 
// Started on  Thu Jan  8 17:38:28 2015 Bertocco Thomas-Killian
// Last update Thu Jan  8 17:38:29 2015 Bertocco Thomas-Killian
//

function bigbrother($argv, $argc)
{
$functions = array('add_student' => 'add_student', 'del_student' => 'del_student', 'update_student' => 'update_student', 'show_student' => 'show_student', 'add_comment' => 'add_comment');
if($argc != 3)
echo("Vous devez entrer deux arguments.\n");
else if(!array_key_exists($argv[1], $functions))
echo("fonction non reconnu.\n");
else if(np($argv[2]) == 0)
echo("Veuillez entrer un pseudo correct (xxxxxx_x)\n");
else if(vp($argv[2]) == 0 && $argv[1] != "add_student")
echo ("Cet utilisateur n'existe pas\n");
else
{
call_user_func($argv[1], $argv[2]);
}
}
bigbrother($argv, $argc);

function add_comment($pseudo)
{
$n = 0;
$new = NULL;
echo "Vous devez marquer le debut et la fin de votre commentaire avec un \".\ncommentaire :\n";
while($n != 2)
{
echo "$>";
$enter = fopen("php://stdin", "r");
$get = fgets($enter);
for($z = 0; isset($get[$z]) && $n != 2; $z++)
{
if($get[$z] == '"')
$n++;
if($n == 1 || $n == 2 && $get[$z] == '"')
$new = $new.$get[$z];
}
fclose($enter);
}
$collection = connection();
rmi($new, $pseudo, $collection);
}

function add_student($pseudo)
{
if(vp($pseudo) == 0)
{
$quest = array("nom ?", "promo ?", "email ?", "telephone ?");
$preg = array('preg_match("/^[-a-zA-Z]+[ ]?[-a-zA-Z]*$/", $res, $aa)', 'preg_match("/\d{4}/", $res, $aa)', 'preg_match("/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/", $res, $aa)', 'preg_match("/\d{10}/", $res, $aa)');
$n = 0;
while($n <= 3)
{
$enter = fopen("php://stdin", "r");
echo $quest[$n]."\n"."$>";
$res = trim(fgets($enter));
eval($preg[$n].";");
if(!isset($aa[0]) || $aa[0] != $res)
echo ("mauvaise info\n");
else
{
$n++;
${'a'.$n} = $res;
}
fclose($enter);
}
$ajout = array("pseudo" => $pseudo, "nom" => $a1, "promo" => $a2, "email" => $a3, "telephone" => $a4, "commentaires" => "");
insert($ajout);
}
else
echo "pseudo deja utilise\n";
}

function connection()
{
$m = new MongoClient();
$db = $m->bdd;
$collection = $db->createCollection('students');
$m->close();
return $collection;
}

function del_student($pseudo)
{
while(!isset($res) || ($res != "non" && $res != "oui"))
{
$enter = fopen("php://stdin", "r");
echo ("Etes-vous sur ?\n");
echo "$>";
$res = trim(fgets($enter));
if($res != "non" && $res != "oui")
echo ("Veuillez entrer oui ou non\n");
fclose($enter);
}
if($res == "oui")
{
$collection = connection();
$collection->remove(array('pseudo'=>$pseudo));
echo ("Utilisateur supprime !\n");
}
}

function insert($tab)
{
$collection = connection();
$collection->insert($tab);
}

function np($pseudo)
{
$n = 1;
preg_match("/[a-z]{6}[_]{1}[a-z]{1}/", $pseudo, $f);
if(empty($f[0]) || $f[0] != $pseudo)
{
$n = 0;
}
return $n;
}

function rmi($comment, $pseudo, $collection)
{
date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, "fr_FR");
$p = strftime("%e %B %Y");
$finc = $collection->find(array("pseudo" => $pseudo));
foreach ($finc as $tab)
$old = $tab['commentaires'];
if($tab['commentaires'] == '')
$nf = "\n".$p." :\n".$comment."\n";
else
$nf = "\n"."~~~~~~~~~~~~\n\n".$p." :\n".$comment."\n";
$new = str_replace("\n", "\n\t", $nf);
$new = $old.$new;
$collection->update(array('pseudo' => $pseudo), array('$set' => array('commentaires' => $new)));
echo "Commentaire ajoute.\n";
}

function show_student($pseudo)
{
$collection = connection();
$res = $collection->find(array("pseudo" => $pseudo));
foreach ($res as $tab)
$keys = array_keys($tab);
for($n = 0; isset($keys[$n]); $n++)
if($keys[$n] != "_id" && $keys[$n] != "telephone" && $keys[$n] != "commentaires")
echo $keys[$n]."\t\t: ".$tab[$keys[$n]]."\n";
else if ($keys[$n] == "telephone")
echo $keys[$n]."\t: ".$tab[$keys[$n]]."\n";
else if($keys[$n] == "commentaires" && $tab[$keys[$n]] != '')
echo $keys[$n]."\t: ".$tab[$keys[$n]]."\n";
}

function update_student($pseudo)
{
$cle = array("pseudo", "nom", "promo", "email", "telephone");
$preg = array('pseudo' => 'preg_match("/[a-z]{6}[_]{1}[a-z]{1}/", $new, $aa)', 'nom' => 'preg_match("/^[-a-zA-Z]+[ ]?[-a-zA-Z]*$/", $new, $aa)', 'promo' => 'preg_match("/\d{4}/", $new, $aa)', 'email' => 'preg_match("/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/", $new, $aa)', 'telephone' => 'preg_match("/\d{10}/", $new, $aa)');
$collection = connection();
while(!isset($key) || !in_array($key, $cle))
{
echo ("Que voulez-vous modifier ?\n"."(choix: pseudo, nom, promo, email, telephone)\n"."$>");
$enter = fopen("php://stdin", "r");
$key = trim(fgets($enter));
fclose($enter);
}
echo ("Veuillez la nouvelle valeur\n");
while(!isset($new) || !isset($aa[0]) || $new != $aa[0])
{
$enter = fopen("php://stdin", "r");
$new = trim(fgets($enter));
eval($preg[$key].";");
if(!isset($aa[0]) || $new != $aa[0])
echo ("Valeur incorrecte\n");
fclose($enter);
}
$collection->update(array('pseudo' => $pseudo), array('$set' => array($key => $new)));
echo "utilisateur modifié !\n";
}

function vp($pseudo)
{
$n = 0;
$m = new MongoClient();
$db = $m->bdd;
$collection = $db->createCollection('students');
$res = $collection->find(array("pseudo" => $pseudo));
foreach ($res as $key)
if($key["pseudo"] == $pseudo)
$n = 1;
$close = $m->close();
return $n;
}
?>