<?php
$lat_val = (isset($lat) && is_numeric($lat) && $lat != 0) ? $lat : 13.7563;
$lng_val = (isset($lng) && is_numeric($lng) && $lng != 0) ? $lng : 100.5018;
$mapsKey = defined('MAPS_API_KEY') ? MAPS_API_KEY : ConfigManager::getValue('MAPS_API_KEY');
?>
<script src="<?= adminAssets("plugins/locationpicker/locationpicker.jquery.min.js") ?>"></script>
<script type="text/javascript" src='https://maps.googleapis.com/maps/api/js?key=<?= $mapsKey ?>&callback=Function.prototype&libraries=places'></script>
<script>


    $('#somecomponent_<?=$var?>').locationpicker({
        location: {latitude: <?=$lat_val?>, longitude:<?=$lng_val?>},
        radius: 300,
        inputBinding: {
            latitudeInput: $('#lat_<?=$var?>'),
            longitudeInput: $('#lng_<?=$var?>'),
            radiusInput: $('#radius_<?=$var?>'),
            locationNameInput: $('#places_<?=$var?>')
        },
        enableAutocomplete: true
    });


</script>