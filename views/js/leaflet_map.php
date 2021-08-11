<?php include '../app/database/koneksi.php'; ?>
<script >
  // Main Map
  var mymap = L.map('mapid').setView([-6.8408768, 107.790554], 8);

  var layerKostum = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1,
    accessToken: 'pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw'
  });
  mymap.addLayer(layerKostum);

  //Mengambil data GEOJson dan menentukan warna untuk tiap-tiap GEOJson
  <?php
    $kecamatan = [
      "wilayah_bekasi"=>"#173F5F", //Biru Tua
      "wilayah_cianjur"=>"#173F5F", //Biru Tua
      "wilayah_cirebon"=>"#173F5F", //Biru Tua
      "wilayah_garut"=>"#173F5F", //Biru Tua
      "wilayah_indramayu"=>"#173F5F", //Biru Tua
      "wilayah_karawang"=>"#0ec7a3", //Orange
      "wilayah_kota_cirebon"=>"#173F5F", //Biru Tua
      "wilayah_kota_sukabumi"=>"#173F5F", //Biru Tua
      "wilayah_pangandaran"=>"#173F5F", //Biru Tua
      "wilayah_subang"=>"#173F5F", //Biru Tua
      "wilayah_sukabumi"=>"#173F5F", //Biru Tua
      "wilayah_tasikmalaya"=>"#173F5F" //Biru Tuaz
    ];
  ?>
  //End

  // Mengloot Geojson Dari Luar
  function popUp(f,l){
    var out = [];
    if (f.properties){
        // for(key in f.properties){}
        out.push("<b>Wilayah: "+"</b>"+f.properties['kemendagri_nama']);
        l.bindPopup(out.join("<br />"));
    }
  }

  // Legend
  function iconByName(name) {
    return '<i class="icon icon-'+name+'"></i>';
  }

  function featureToMarker(feature, latlng) {
    return L.marker(latlng, {
      icon: L.divIcon({
        className: 'marker-'+feature.properties.amenity,
        html: iconByName(feature.properties.amenity),
        iconUrl: '../images/markers/'+feature.properties.amenity+'.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
      })
    });
  }

  var baseLayers = [
    {
      name: "Jawa Barat",
      layer: layerKostum
    }
  ];

  //Meloop GEOJson
  <?php
    foreach ($kecamatan as $key => $value) { ?>

      var warnaMap<?=$key?> = {
        "color": "<?=$value?>",
        "weight": 1,
        "opacity": 0.65
      };

      <?php
      $arrayKec[]='{
        name: "'.$key.'",
        layer: new L.GeoJSON.AJAX(["../views/js/geojson/'.$key.'.geojson"],{onEachFeature:popUp,style: warnaMap'.$key.',pointToLayer: featureToMarker}).addTo(mymap)
      }';
    }
  ?>

  var overLayers = [
    <?=implode(',', $arrayKec);?>
  ];
  // var panelLayers = new L.Control.PanelLayers(baseLayers, overLayers);
  // mymap.addControl(panelLayers);

  // Marker
  //Clustering marker pada bagian lokasi
  var markers_lokasi = L.markerClusterGroup();

  //Icon marker sesuai lokasi pantai
  var myIcon = L.icon({
      iconUrl: '<?=('../views/img/icon/icon_lokasi.png')?>',
      iconSize: [38, 45]
  });

  <?php
    $sql_map = "SELECT * FROM t_lokasi";

    $stmt = $pdo->prepare($sql_map);
    $stmt->execute();
    $rowLokasi = $stmt->fetchAll();

    foreach ($rowLokasi as $value) {
  ?>
    var marker = L.marker([<?=$value->latitude?>, <?=$value->longitude?>], {icon: myIcon})
    .bindPopup(
      "<b>Nama Lokasi: </b><?=$value->nama_lokasi?><br/>"+
      "<b>Foto Lokasi: <br/></b><img src='<?=$value->foto_lokasi?>' width='100%'><br/>"+
      "<div><a href='view_pilih_lokasi_wisata.php?id_lokasi=<?=$value->id_lokasi?>' class='btn-map' style='color: #fff'>Pilih Lokasi</a></div>"
      );
      markers_lokasi.addLayer(marker);
  <?php } ?>
  mymap.addLayer(markers_lokasi);
  
</script>