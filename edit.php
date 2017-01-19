<?php
print '<!DOCTYPE html><html>  <head>';
header('Content-Type: text/html; charset=utf-8');
print '<meta http-equiv="Content-type" content="text/html; charset=utf-8" />';
print '</head> <body>';

$ID = "";
$ID = htmlspecialchars($_GET["ID"]);
$lon = "";
$lon = htmlspecialchars($_GET["lon"]);
$lat = "";
$lat = htmlspecialchars($_GET["lat"]);

$points = file_get_contents('points.geojson');
$pointsarray = json_decode($points, true);

$settings = file_get_contents('settings.json');
$settingsarray = json_decode($settings, true);

if ($ID != "" && $lon != "" && $lat != "") {
    
    $i = 0;
    foreach ($pointsarray["features"] as $key => $item) {
        if ($item["properties"]["name"] == $ID){
            break;
        }
        $i++;
    }
    
    if ($i >= count($pointsarray["features"])) {
        $i = count($pointsarray["features"])-1;
    }
    
    print "<h3>".$ID."</h3>";
    
    print '<form action="edit.php" method="post">';
    
    print '<input type="hidden" name="ID" value="'.$ID.'">';
    
    $mytag = $pointsarray["features"][$i]["properties"]["tag"];
    
    print '<b>Tag:</b> <select name="tag">';
    foreach ($settingsarray["tags"] as $item) {
        $selected = "";
        if ($item == $mytag) $selected = "selected";
        print '<option value="'.$item.'" '.$selected.'>'.$item.'</option>';
    }
    print '</select><br><br>';
    
    
    print '<b>Latitude:</b><br>';
    print '<input type="text" name="lat" value="'.$lat.'"><br>';
    
    print '<b>Longitude:</b><br>';
    print '<input type="text" name="lon" value="'.$lon.'"><br>';
    
    $n = 0;
    foreach ($pointsarray["features"][$i]["properties"]["description"] as $key => $item) {
        print '<b>'.$key.':</b><br>';
        if ($ID == "new") $item = "";
        print '<input type="text" name="D'.$n.'" value="'.$item.'"><br>';
        $n++;
    }
    
    $verified = $pointsarray["features"][$i]["properties"]["verified"];
    if ($ID == "new") $verified = "";
    print '<b>Verified:</b><br>';
    print '<input type="text" name="verified" value="'.$verified.'" readonly="readonly"><br>';
    if ($ID != "new") print '<input type="checkbox" name="delete" value="delete"> Delete<br>';
    
    print ' <br><input type="submit" value="Salva">';
    print '</form>';
    
    
}


if ($_POST["ID"] != ""){
    
    $ID = $_POST["ID"];
    $lon = $_POST["lon"];
    $lat = $_POST["lat"];
    $tag = $_POST["tag"];
    $verified = $_POST["verified"];
    $verified = date("d/m/Y");
    
    
    $i = 0;
    foreach ($pointsarray["features"] as $key => $item) {
        if ($item["properties"]["name"] == $ID){
            break;
        }
        $i++;
    }
    
    
    if ($_POST['delete'] == 'delete' && $ID != "new") {
        print "<b>Deleting ".$ID."</b><br>";
        $new = json_decode('{"type":"FeatureCollection","features":[]}', true);
        foreach ($pointsarray["features"] as $item) {
            if($item["properties"]["name"] != $ID) {
                $new["features"][] = $item;
            }
            $i++;
        }
        
        
        $geojson = json_encode($new);
        file_put_contents('points.geojson', $geojson);
        
        print '<h3>Deleted!</h3>';
        
        
    } else {
        
        if ($ID == "new") {
            $ID = strval($i+1);
            $pointsarray["features"][$i] = $pointsarray["features"][0];
        }
        
        $pointsarray["features"][$i]["properties"]["name"] = $ID;
        $pointsarray["features"][$i]["geometry"]["coordinates"][0] = doubleval($lon);
        $pointsarray["features"][$i]["geometry"]["coordinates"][1] = doubleval($lat);
        $pointsarray["features"][$i]["properties"]["tag"] = $tag;
        $pointsarray["features"][$i]["properties"]["verified"] = $verified;
        
        $n = 0;
        foreach ($pointsarray["features"][$i]["properties"]["description"] as $key => $item) {
            $pointsarray["features"][$i]["properties"]["description"][$key] = $_POST["D".$n];
            $n++;
        }
        
        $geojson = json_encode($pointsarray);
        file_put_contents('points.geojson', $geojson);
        
        print '<h3>Written!</h3>';
        //print $geojson;
    }
    
}


print '  </body> </html>';
?>
