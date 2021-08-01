<?php

$presentations = json_decode(file_get_contents('/data/presentations.json'));
$users = json_decode(file_get_contents('/data/users.json'), true);

$map = [];
foreach ($presentations as $presentation) {
  $i = $presentation->key;
  $key = ""
    . hash_hmac('sha256', $i . "", 'i3lock-gpl.sytes.net')
    . hash_hmac('sha256', $i . "", 'stupid')
    . hash_hmac('sha256', $i . "", 'hack')
    . hash_hmac('sha256', $i . "", 'athon')
    . hash_hmac('sha256', $i . "", 'z')
    ;
  $map[$key] = $presentation;
}

if ($_GET['key'] === 'first') {
  echo array_keys($map)[0];
  die();
}

$presentation = $map[$_GET['key']];

if (empty($presentation)) {
  die('wron');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $presentation->key ?></title>
  <style>
    body{font-family:Krungthep;}
    .g { background: black;color: white;}
  </style>
</head>
<body style="background:#<?= $presentation->color ?>">
  
<h1>
  <span class="g"><?= $presentation->key ?> — <?= htmlspecialchars($presentation->name) ?></span>
</h1>

<?php
$used=[];
function pmember ($id) {
  global $users,$used;
  if (!isset($users[$id])) return;
  if (isset($used[$id])) return;
  $used[$id] = 1;
  $u = $users[$id];
  echo '<span style="background:white">' . htmlspecialchars($u['name']) . '</span> ';
}
foreach ($presentation->admins as $id) { pmember($id); }
foreach ($presentation->members as $id) { pmember($id); }
?>


<?php $s = $presentation->submissions[0]; ?>
<h2><span class="g"><?= htmlspecialchars($s->name) ?></span></h2>
<p><span class="g">
  <?= nl2br(htmlspecialchars($s->description)) ?>
</span></p>
<?php
if(preg_match('~(?:be/|v=)([^/?&]+)~i', $s->link, $m)){
  ?>
  <iframe width="560" height="315" src="https://www.youtube.com/embed/<?=$m[1]?>?rel=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
  <?php
}
?>

<ul style="column-count: 5">
  <?php foreach ($map as $k => $p): ?>
    <?php if (!empty($p->submissions)): ?>
      <li style="overflow:hidden;max-height:200px;margin-bottom:20px;background:white;">
      <?php
if(preg_match('~(?:be/|v=)([^/?&]+)~i', $p->submissions[0]->link, $m)){
  ?>
  <img src="https://i.ytimg.com/vi/<?= $m[1] ?>/hqdefault.jpg" style="width:64px;float:right">
  <?php
}
?>
        <a style="background:white" href="?key=<?= $k ?>"><?= $p->key ?> <?= htmlspecialchars($p->name) ?></a> : <?= htmlspecialchars($p->submissions[0]->name) ?>
        <br>
        
      </li>
    <?php endif; ?>
  <?php endforeach; ?>
</ul>
</body>
</html>