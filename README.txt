#Amerigo
Amerigo is webapp based on OpenLayers and OpenStreetMap, that allows you to create your own collaborative map.

##Guide for web hosting
For Amerigo to work, you need a PHP web server.

First, you need to edit the file *settings.json* with the setup you'd like for your map:
```json
{
    "newpoint":"Add new point",
    "editpoint":"Edit point",
    "title":"My customized map",
    "center": [12.482778, 41.893056],
    "zoom":"3",
    "zoomto":"15",
    "tags": ["first category","second category"],
    "datasource":"points.php"
}
```

- **newpoint** is the text that will appear on the link that allows the user to add a new point on the map.
- **editpoint** is the text that will appear on the link that allows the user to edit an existing point on the map.
- **title** is the title of your map
- **center** represents the coordinates of the initial center of the map, which are written as [longitude, latitude]
- **zoom** is the initial zoom of the map 
- **zoomto** is the zoom of the map when a point has been selected
- **tags** is a list of categories in which the points will be divided
- **datasource** is the source of the GEOJSON points. Usually, it's just the points.php file in the same folder as settings.json and index.html, but you can also choose another source. You should not change this value unless you know what you are doing.

Now you need to define your first point on the map, editing *points.geojson*

```json
{
"type":"FeatureCollection",
"features":[
    {
    "type":"Feature",
    "geometry":{
        "type":"Point",
        "coordinates":[12.482778,41.893056]
    },
    "properties":{
        "name":"1",
        "tag":"first category",
        "verified":"17\/01\/2017",
        "description":{
            "Name":"Rome",
            "Address":"Center of Rome, Italy",
            "Business":"Shopping center",
            "Open 24/7":"Yes"}
    }
    }
]}
```
Every point (or feature) has 4 properties:
- **name** is an ID, which is automatically added by the webapp. You should not change this value.
- **tag** is the category where you would like to insert the point. This must be one of the categories defined in the settings.json file.
- **verified** is automatically added byt he webapp, and represents the date of creation (or last editing) of the point. This is useful because if the point has been created too much time ago, maybe it's not valid anymore and it may need updating.
- **description** this is a completely arbitrary array: you may write here anything you want as description of the point. It's basically a table where the first column contains title of paragraphs, and the second column contains the text of the paragraphs. Every other point will be automatically created using the same titles for description paragraphs.

Now you can upload everything inside a folder on your server.

##Guide for users

First of all, the map shows all the points. If you want to filter point by tags, just uncheck the checboxes in the top right angle of the map.

If you click on a point, a popup appears with the description fo the point. It's also possible to edit the point description, tag, and position clicking on the link inside the popup.

You can add a new point searching for it in the search field, and it's also possible to add a new point with a double click on the map. In both cases, you just need to click on the link in the popup.