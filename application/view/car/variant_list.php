<div class="columns">
<?php
$i = 1;
 foreach ($this->variants as $variant) {
 if ($variant->text) {
$variantdesc = '<span class="bold">'.$variant->text.'</span><br /><span class="small">';
$variantdesc.= $variant->litres.' ('.$variant->capacity.' cm³) ';
if ($variant->fuel) {$variantdesc.= _($variant->fuel).' ';}
if ($variant->power_kW_from) {$variantdesc.= '<br />'.$variant->power_kW_from.' kW / '.$variant->power_HP_from.' HP';}
$variantdesc.= '<br />('.substr($variant->production_start, 0, 4).' -'.substr($variant->production_end, 0, 4).')';
$margin  = ($i > 1) ? 'mt2' : '';
print '<div class="smallish '.$margin.'" >';
print '<a class="nwvariant" data-variant-id="'.$variant->TYP_ID;
print '" data-year-min="'.substr($variant->production_start, 0, 4).'" data-year-max="'.substr($variant->production_end, 0, 4);
print '" href="'.$variant->text.'">'.$variantdesc.'</a></div>';
$i++;
}
}
//$model->MOD_CDS_ID ?></div>
