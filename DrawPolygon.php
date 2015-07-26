
<!DOCTYPE html>
<?php
$servername = "localhost";
$username = "**********";
$password = "**********";
$db="map";

// Create connection
$conn = new mysqli($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_GET['str']))
  $str= $_GET['str'];
else
  $str="{error not recieved}";
$ar = json_decode($str,true);


//echo $ar[0]['path']."</br>";

for($i=0;$i<sizeof($ar);$i++){
 $shapeName=$ar[$i]['shapeName'];
  $path=$ar[$i]['path']; 
  

  $shapeType=$ar[$i]['shapeType'];
$query="insert INTO polygon(zone_name,zone_type,path)values('$shapeName','$shapeType','$path')";

//if (mysqli_query($conn, $query)) {
if ($conn->query($query)) {
  //echo "inserted";
}else{
  die(" insert failed: " .$conn->error);
}
//printf("Last inserted record has id %d\n", mysql_insert_id());




// for second db



$query1="select max(id) as maxid from polygon";
$result=mysqli_query($conn, $query1);
  if (mysqli_num_rows($result) > 0) {
      while ($row=mysqli_fetch_array($result) ){
        $maxid= $row['maxid'];
      }
  }

$coordinates=explode("),(",$path);
  $cordMaster= array();

  for($j=0;$j<sizeof($coordinates);$j++){

    $coordinates[$j]=trim($coordinates[$j],"(");
      $coordinates[$j]=trim($coordinates[$j],")");
    }
  for($j=0;$j<sizeof($coordinates);$j++){
  //create array of prev and nxt
  if($j==0){
  $cordMaster[]= array(
  
    'coordinates'=>$coordinates[$j],
    'next'=>$coordinates[$j+1],
    'prev'=>$coordinates[sizeof($coordinates)-1]
    );
  }else if($j==sizeof($coordinates)-1){
    $cordMaster[]= array(
    'coordinates'=>$coordinates[$j],
    'next'=>$coordinates[0],
    'prev'=>$coordinates[$j-1]
    );
  }else{
    $cordMaster[]= array(
    'coordinates'=>$coordinates[$j],
    'next'=>$coordinates[$j+1],
    'prev'=>$coordinates[$j-1]
    );
     
}
    $cordlatlng=$cordMaster[$j]['coordinates'];$prev=$cordMaster[$j]['prev'];$next=$cordMaster[$j]['next'];
    $query2="insert INTO latlng(point,prev,next,polygon)values('$cordlatlng','$prev','$next',$maxid)";
    if(mysqli_query($conn, $query2)){
      //echo "got latlng"."<br />";
    }
    else{
      echo " failed lat lng".$conn->error;
    }
}   
  

}

//create fuction for geojson
if(isset($_GET['val'])){
  createGeoJson();
}

function createGeoJson(){ 

      // Create connection
$conn = new mysqli("localhost", "zomato", "zomato","map");
$
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

      $joinQuery="select p.zone_name,p.zone_type,l.point,l.prev,l.next,l.Polygon from polygon p,latlng l where l.polygon=p.id ";
      $result=mysqli_query($conn, $joinQuery);
           $count=(mysqli_num_rows($result);
          $row=mysqli_fetch_array($result);

        
    
            
  }


?>




<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Using closures in event listeners</title>
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0;
        padding: 0;
      }

    </style>
     <!-- jquery independent of bootstrap -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

  <!-- jQuery library -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

  <!-- Latest compiled JavaScript -->
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>



    


    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>
    <!--<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=drawing"></script>-->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false&amp;libraries=drawing,geometry"></script>
    <script>

  var saveObject=[];
  var overlays=[];
  /*-----------------------------------------hash table ---------------------------------------------*/


function HashTable(obj)
{
    this.length = 0;
    this.items = {};
    for (var p in obj) {
        if (obj.hasOwnProperty(p)) {
            this.items[p] = obj[p];
            this.length++;
        }
    }

    this.setItem = function(key, value)
    {
        var previous = undefined;
        if (this.hasItem(key)) {
            previous = this.items[key];
        }
        else {
            this.length++;
        }
        this.items[key] = value;
        return previous;
    }

    this.getItem = function(key) {
        return this.hasItem(key) ? this.items[key] : undefined;
    }

    this.hasItem = function(key)
    {
        return this.items.hasOwnProperty(key);
    }
   
    this.removeItem = function(key)
    {
        if (this.hasItem(key)) {
            previous = this.items[key];
            this.length--;
            delete this.items[key];
            return previous;
        }
        else {
            return undefined;
        }
    }

    this.keys = function()
    {
        var keys = [];
        for (var k in this.items) {
            if (this.hasItem(k)) {
                keys.push(k);
            }
        }
        return keys;
    }

    this.values = function()
    {
        var values = [];
        for (var k in this.items) {
            if (this.hasItem(k)) {
                values.push(this.items[k]);
            }
        }
        return values;
    }

    this.each = function(fn) {
        for (var k in this.items) {
            if (this.hasItem(k)) {
                fn(k, this.items[k]);
            }
        }
    }

    this.clear = function()
    {
        this.items = {}
        this.length = 0;
    }
}
        


/*-----------------------------------------hash table ---------------------------------------------*/
var saveObjectOverlays = new HashTable();
var overlaysSaveObject = new HashTable();
var map // Global declaration of the map
var i=0;
      
      var iw = new google.maps.InfoWindow(); // Global declaration of the infowindow
      var lat_longs = new Array();
      var markers = new Array();
      var drawingManager;
      function initialize() {
          var mapOptions = {
          zoom: 8,
            center: new google.maps.LatLng(28.453, 77.075)
            };
        map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
        drawingManager = new google.maps.drawing.DrawingManager({
        drawingMode: google.maps.drawing.OverlayType.POLYGON,
          drawingControl: true,
          drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_CENTER,
          drawingModes: [google.maps.drawing.OverlayType.POLYGON]
        },
            polygonOptions: {
              editable: true,
              draggable: true,
                      fillColor: '#cccccc',
                      fillOpacity: 0.5,
                      strokeColor: '#000000',

            }
      });
      drawingManager.setMap(map);
      
      google.maps.event.addListener(drawingManager, "overlaycomplete", function(event) {
        //$('#myModal').modal({show:true});
        var newShape = event.overlay;
        newShape.type = event.type;

         overlayClickListener(event.overlay);
                $('#vertices').val(event.overlay.getPath().getArray());
                $('#area').val(google.maps.geometry.spherical.computeArea(event.overlay.getPath()));
               
                
                
      });

            //google.maps.event.addListener(drawingManager, "overlaycomplete", function(event){
                
               
            //});
        }
function overlayClickListener(overlay) {
  overlays.push(overlay.getPath().getArray());
 google.maps.event.addListener(overlay, "click", function(event){
        $('#vertices').val(overlay.getPath().getArray());
        $('#area').val(google.maps.geometry.spherical.computeArea(overlay.getPath()));
        
          
    });


    
    google.maps.event.addListener(overlay.getPath(), 'set_at', function() {
            console.log("test");
        });
}


 //initialize();
google.maps.event.addDomListener(window, 'load', initialize);
$(function(){
    $('#save').click(function(){
      var container={
       path:document.getElementById('vertices').value,
         area:document.getElementById('area').value,
         shapeName:document.getElementById('zoneName').value,
         shapeType:document.getElementById('zoneType').value
      }
      if(saveObjectOverlays.hasItem(container.path)){
          alert("already present");
        }else{
          
          saveObject.push(container);
          var len=saveObject.length-1;
            for(var i=0;i<overlays.length;i++){
                if(container.path==overlays[i]){
               // overlaysSaveObject.setItem(overlays[i],saveObject[len]);
                //overlaysSaveObject[overlays[i]]=container;
                saveObjectOverlays.setItem(saveObject[len].path,overlays[i]);
                //saveObjectOverlays[container]=overlays[i];
                alert("saved ");
                
                break;
                
            }

          }
        }

                $('#vertices').val(null);
                $('#area').val(null);
                $('#zoneName').val(null);
                $('#zoneType').val(null);


    });
    
   $('#test').click(function(){
    
            var str=JSON.stringify(saveObject);

              alert(str);

         window.location.href = "day5.php?str=" + str; 
        
   });
   $('#getData').click(function(){
        window.location.href = "day5.php?val=getData" ;
   });


});




</script>


</head>


<body>


  <div class="container-fluid">
    <div class="row">
      <div class="col-md-9 col-lg-9 col-sm-9 col-xs-9">
        <div id="map_canvas" style="width:100%; height:700px;"></div>
          </div>
          <div class="col-md-3 col-lg-3 col-sm-3 col-xs-3">
          <h4>Coordinates</h4>
            <textarea type="text" rows="5" name="vertices" value="" id="vertices" style="width:100%;">  </textarea>
            <h4>Area</h4>
                <input type="text"  name="area" value="" id="area"  style="width:100%;">
              <h4>Zone Name</h4>
                <input type="text"  name="zoneName" value="" id="zoneName" placeholder="Zone Name" style="width:100%;">
                <h4>Zone Type</h4>
                <input type="text"  name="zoneType" value="" id="zoneType" placeholder="Zone Type" style="width:100%;">
              <button class="btn button-primary" type="button" name="save" value="Save!" id="save">Add</button>
              <button class="btn button-default" type="button" name="test" value="test!" id="test">Save to dataBase</button>
                  <button class="btn button-default" type="button" name="func" value="func" id="getData">Get data</button>
              <br>
       
              
        </div>
      </div>
</div>



  <!-- Modal -->
  <div class="modal fade " id="myModal" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Name and Zone Type</h4>
        </div>
        <div class="modal-body row-fluid">
            <h5>Name</h5>
          <input type="text"  name="polygonName" value="" id="polygonName" style="width:100%" placeholder="Polygon Name"/><br>
          <h5>Zone Type</h5>
          <input type="text"  name="polygonType" value="" id="polygonType" style="width:100%" placeholder="Polygon Name"/><br>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" id="submit">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="" class="btn btn-default" data-dismiss="modal">Close</button>

          
        </div>
      </div>
      
    </div>
  </div>

</body>
</html>
