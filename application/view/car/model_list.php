<div class="columns"><?php $i = 1;
 foreach ($this->models as $model) {
 $daterange = substr($model->production_start, 0, 4).' -'.substr($model->production_end, 0, 4);
 $name = explode('{', $model->text);
     if (!strpos($name[0], '[USA]')) {
$margin  = ($i > 1) ? 'mt1' : '';
print '<div class="'.$margin.' smallish" ><a class="nwmodel bold" data-model-id="'.$model->id.'" href="'.$name[0].'">'.preg_replace("/\([^)]+\)/","",$name[0]).'</a>';
print '<br /><a class="nwmodel" data-model-id="'.$model->id.'" href="'.$name[0].'">';
if (key_exists(1, $name)) {array_shift($name); foreach ($name as $altname) {print '<span class="bold">'.preg_replace("/\([^)]+\)/","",$altname).'</span><br />';}}
print $daterange;
print '</a></div>'; $i++;
}
}
//$model->MOD_CDS_ID ?></div>
