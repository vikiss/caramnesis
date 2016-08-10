<ul><?php
 foreach ($this->models as $model) {
 $daterange = substr($model->production_start, 0, 4).' -'.substr($model->production_end, 0, 4);
 $name = explode('{', $model->text);  
print '<div class="left mb1 mr1 px1 black bg-kclite field" ><a class="nwmodel" data-model-id="'.$model->id.'" href="'.$name[0].'">'.$name[0];
print '<div class="small">';
print $daterange.'<br />';
if (key_exists(1, $name)) {array_shift($name); foreach ($name as $altname) {print $altname.'<br />';}}
print '</div></a></div>';

}
//$model->MOD_CDS_ID ?></ul>

	