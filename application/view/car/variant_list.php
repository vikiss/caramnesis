<?php
 foreach ($this->variants as $variant) {
 if ($variant->text) { 
$variantdesc = $variant->text.'<br /><span class="small">'; 
$variantdesc.= $variant->litres.' ('.$variant->capacity.' cm³) ';
if ($variant->fuel) {$variantdesc.= _($variant->fuel).' ';};
$variantdesc.= $variant->power_kW_from.' kW / '.$variant->power_HP_from.' HP'; 
$variantdesc.= '<br />('.substr($variant->production_start, 0, 4).' -'.substr($variant->production_end, 0, 4).')';

print '<div class="left mb1 mr1 px1 black bg-kclite field" ><a class="nwvariant" data-variant-id="'.$variant->TYP_ID.'" data-year-min="'.substr($variant->production_start, 0, 4).'" data-year-max="'.substr($variant->production_end, 0, 4).'" href="'.$variant->text.'">'.$variantdesc.'</a></div>';
}
}
//$model->MOD_CDS_ID ?>