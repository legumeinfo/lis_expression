<?php
  //From Ethy's tripal_gene module: But this doesn't work() the toc shows up in marker page too.
  //$feature = $variables['node']->feature;
  //if ($feature->type_id->name != 'gene') return;
  //
  //The code works but doesn't solve the problem(=pane only for gene page)
  //$feature = $variables['node']->feature;
  //if ($feature->type_id->name != 'gene') {
  //  print "Not a gene";
  //  };
  
  //FAILS TO WORK
  //$feature = $variables['node']->feature;
  //if ($feature->type_id->name == 'gene') {
  //  //return;
  //  $node->content['lis_expression'] = array(
  //        '#markup' => theme('lis_expression_profiles', array('node' => $node)),
  //        '#tripal_toc_id'    => 'geneexpressionprofile',
  //        '#tripal_toc_title' => 'Gene Expression Profile',
  //        '#weight' => -80,
  //      );
  //}


  

  $feature = $variables['node']->feature;
//if ($feature->type_id->name != 'gene') return;
  $gene_uniquename = $feature->uniquename;
  $gene_name = $feature->name;
        //print "uniquename: ".$feature->uniquename."<br/>";
        //print "name: ".$gene_name."<br/>";
        ////print $gene_uniquename."<br/>";
        //print "<hr/>";
  
  //Get all the datasets available for this genemodel
  //-------------------------------------------------
  $sql_get_datasets = "SELECT DISTINCT d.dataset_id,d.shortname FROM ongenomesimple.dataset d, ongenomesimple.expressiondata e WHERE d.dataset_id=e.dataset_id and e.genemodel_id = :gene_uniquename";
  $result_get_datasets = db_query($sql_get_datasets, array(':gene_uniquename' => $gene_uniquename));
  
  // IMPORTANT: If no dataset avail for this gene, then, no content should be created
  //----------------------------------------------------------------------
  
  //Keep this before any content created
  //If no content, the corresponding pane doesn't appear in toc
  //$feature = $variables['node']->feature;
  if ($feature->type_id->name != 'gene')   {
    return; //Quit if it isn't a gene page or if there isn't any expression dataset for this gene
  } elseif ( $result_get_datasets->rowCount() == 0 ) {
    return;
  }
  //............................................................
  
?>

<!-- Page Title  -->
<script>
  titleLabel = "<?php echo "Expression (".$feature->name.")"; ?>";
  (function($) {
    $('.geneexpressionprofile-tripal-data-pane-title.tripal-data-pane-title').html('Expression: <?php echo $feature->name?>');
    //jQuery('.figure-tripal-data-pane-title.tripal-data-pane-title').html(titleLabel);
  })(jQuery);    
</script>
  
  
  
<?php
  
  print "This genemodel is part of the following datasets shortname(dataset_id): "."<br/>";
  foreach ($result_get_datasets as $rec) {
    print "<b>".$rec->shortname."</b>"." (dataset_id:".$rec->dataset_id.")"."<br/>";
  }
  print "<hr/>";
  
  $feature_id = $feature->feature_id;
  
  $organism_id = $feature->organism_id->organism_id;
  print "feature-id: ".$feature_id."<br/>";
  print "organism-id: ".$organism_id."<br/>";
  
  print "<hr />";
  
  //Get expression for this genemodel_d for a dataset 
  //-------------------------------------------------
  
  $sql_expression = "select d.shortname as d_shortname,s.shortname as s_shortname,e.genemodel_id,e.exp_value  from ongenomesimple.dataset as d, ongenomesimple.sample as s ,ongenomesimple.expressiondata as e where d.dataset_id = s.dataset_id and d.dataset_id=e.dataset_id and e.dataset_sample_id=s.sample_id and e.genemodel_id='".$gene_uniquename."'";  // CHANGE IT: with place holder :uniquename
  //$sql = "select d.shortname as d_shortname,s.shortname as s_shortname,e.genemodel_id,e.exp_value  from ongenomesimple.dataset as d, ongenomesimple.sample as s ,ongenomesimple.expressiondata as e where d.dataset_id = s.dataset_id and d.dataset_id=e.dataset_id and e.dataset_sample_id=s.sample_id and e.genemodel_id='cicar.ICC4958.v2.0.Ca_00006'";  // CHANGE IT: with place holder :uniquename
  //cicar.ICC4958.v2.0.Ca_00006
  
  $result_expression = db_query($sql_expression);
  
  print "For gene model: <b>".$gene_uniquename."</b>";
  print "<table>";
  print "<tr style=''><td>Sample shortname</td><td>Value (TPM)</td></tr>";
  foreach ($result_expression as $rec) {
      print "<tr>";
      print   "<td>".$rec->s_shortname."</td>";
      print   "<td>".$rec->exp_value."</td>";
      print "</tr>";
  }
  print "</table>";
  
  print "<hr/>";
  
  //$result = db_query($sql);
  //$x = $result->fetchAllAssoc('s_shortname');//works
  ////$x = $result->fetchAllAssoc();// fails
  //$n = $result->rowCount();
  //print 'n: '.$n."<br/>";
  //print_r($x);
  //print "<hr/><br/>";//"<br/><br/>";
  //$j = json_encode($x);
  //print "From json_encode(): <br/>".$j;  //works. $j looks like a json string
  
  //WORKS well
  $x = db_query($sql_expression);
  $x_array = $x->fetchAll();
  $j = json_encode($x_array);
  //print_r($x_array);
  print "<pre>";
  //var_dump($x_array);
  //var_dump($j);
  //print $j;
  print "</pre>";
  
  $x = db_query($sql_expression);
  $j2 = json_encode($x->fetchAllKeyed(1,3));
  //print "json k:v : ".$j2;
  
?>


<?php //  plotly Testing    ?> 

<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
<!--<div id="tester" style="width:600px;height:250px;"></div>

<script>
	TESTER = document.getElementById('tester');
	Plotly.plot( TESTER, [{
	x: [1, 2, 3, 4, 5],
	y: [1, 2, 4, 8, 16] }], {
	margin: { t: 0 } } );
</script>-->

<h2>div id=tester2 (plot):</h2>
<div id="tester2" style="width:850px;height:400px;">  
</div>

<h2>div id=tester3 (bar):</h2>
<div id="tester3" style="width:850px;height:400px;">
</div>

<h2>div id=tester4 (heatmap):</h2>
<div id="tester4" style="width:850px;height:400px;">
</div>

<h2>div id=tester5 (heatmap-stacked-genefamily):</h2>
<!--<div id="tester5" style="width:850px;height:400px;">-->
<div id="tester5" >
</div>


<script>
  
  var geneName = "<?php echo $gene_name; ?>";
  var jd = <?php echo $j2; ?>;

  var k = Object.keys(jd);
  var vals = Object.keys(jd).map(function (key) {
    return jd[key];
  });
  
  //vals = vals.map(Number);
  //alert(vals);
  item_count = k.length;
  
  
	TESTER2 = document.getElementById('tester2');
  TESTER3 = document.getElementById('tester3'); // the div-id
  TESTER4 = document.getElementById('tester4'); // the div-id
  TESTER5 = document.getElementById('tester5'); // the div-id 
  
  //---------SCATTER
	Plotly.plot( TESTER2, [{
    //x: ['abc1','abc2','abc3','abc4','abc5'],
    x: k,//Object.keys(jd),
    //y: [1,3,5,7,9], }],
    y: vals,
    
    type: 'scatter'
    //type: 'bar',
    //orientation: 'h'
    
    }],

    {
      margin: { t: 0, b: 130 },
      paper_bgcolor: '#f5f5f5',
      plot_bgcolor: '#c7c7c7'
    }

  );
  //..........................

  //------BAR
  
  var dataBar = [     // data for plotting between [ ]
    {
      x:k,
      y: vals,
      type: 'bar'
    }
  ];
  var layout = {  
    margin: { t: 0, b: 130 },
    height: 350,
    //xaxis: { side: 'bottom', gridwidth: 1 } // xaxis label at bottom (default)
  };
  Plotly.newPlot(TESTER3, dataBar, layout);
  

  
  
  //-----HEATMAP
  var dataHeatmap = [     // data for plotting between [ ]
    {
      x:k,
      y: [geneName],
      z: [vals],
      type: 'heatmap',
      colorscale: 'Rd'
    }
  ];
  var layout = {  
    margin: { t: 0, l:250, b: 130 },
    height: 180,
    xaxis: { side: 'bottom', gridwidth: 1 } // xaxis label at bottom (default)
  };
  Plotly.newPlot(TESTER4, dataHeatmap, layout);
  
  
  

  

  
  
  //ydata = ["gene1","gene2","gene1"];
  ydata = [geneName,geneName, geneName]; //CAUTION: Only takes unique strings in the array. If a string is repeated, only the first one is used, the other one is not used in the y label.
  geneCount = ydata.length;
  // ydata in console: ["cicar.ICC4958.Ca_00750", "cicar.ICC4958.Ca_00750", "cicar.ICC4958.Ca_00750"]
  
  zdata = [vals,vals,vals]; // array of arrays
  
  var data1 = [     // data for plotting between [ ]
    {
      x:k,
      //y: ['gene1','gene2','gene3'],
      y: ydata,
      z: zdata,
      type: 'heatmap',
      colorscale: 'Rd',
      ygap:3
    }
  ];
  graphicHeight = geneCount*80;
  var layout = {  
    margin: { t: 0, l:180, b: 130},
    height: graphicHeight
    //xaxis: { side: 'bottom', gridwidth: 1 } // xaxis label at bottom (default)
  };
  //Plotly.newPlot(TESTER5, data1, layout);
  //alert(Object.prototype.toString.call(geneName));
</script>


<?php
// Get Gene Family members if exist in same organism:
//
//a. get organism_id from feature, b. get gene family(ies) it belongs to(display it)
//c. Show if other family members exist in the same organism_id,  d. ask if they want to display the expression of family members(if exist), e. get the family members (perhaps, annotation too to make sense right here in the context). f. display expression pattern of other members too in heatmap/scatter plot.

//FOR TESTING
//$sql_gene_family = "select gfa1.gene_family_assignment_id AS source_family_id,gfa1.gene_id AS source_gene_id,gfa1.family_label AS source_family, gfa2.gene_id AS target_gene_id,gfa2.family_label AS target_family, f.feature_id AS target_feature_id,f.uniquename AS target_uniquename,f.organism_id AS target_organism_id  FROM gene_family_assignment as gfa1 INNER JOIN gene_family_assignment as gfa2 ON gfa2.family_label = gfa1.family_label INNER JOIN chado.feature AS f ON f.feature_id = gfa2.gene_id WHERE f.organism_id = $organism_id AND gfa1.gene_id = $feature_id";
//print $sql_gene_family;
//$result_gene_family = db_query($sql_gene_family);

//KEEP
$sql_gene_family = "select gfa1.gene_family_assignment_id AS source_family_id,gfa1.gene_id AS source_gene_id,gfa1.family_label AS source_family, gfa2.gene_id AS target_gene_id,gfa2.family_label AS target_family, f.feature_id AS target_feature_id,f.uniquename AS target_uniquename,f.organism_id AS target_organism_id  FROM gene_family_assignment as gfa1 INNER JOIN gene_family_assignment as gfa2 ON gfa2.family_label = gfa1.family_label INNER JOIN chado.feature AS f ON f.feature_id = gfa2.gene_id WHERE f.organism_id = :organism_id AND gfa1.gene_id = :feature_id";
print $sql_gene_family;
$query_gene_family = db_query($sql_gene_family, array(':organism_id' => $organism_id, ':feature_id' => $feature_id));

//$result = $result_gene_family->fetchAll();
$result = $query_gene_family->fetchCol(6); //A simple array. Col 6 is target_uniquename
//print(json_encode($result));
print "<pre>"; print_r($result); print "</pre>";
$fam_members = $result; //print count($fam_members);
$member_unique_names = "Member-unique-names: "."'".implode("','",$fam_members)."'"; // becomes quoted and , separated list
//print $member_unique_names."<hr>";
//------------------------------------

$gene_expval_r = array(); //Array of gene exp values for each member gene with [sample] => value

foreach ($fam_members as $member) {
  //print "<br>".$member."<br/>";
  
  $sql_exp = "select d.shortname as d_shortname,s.shortname as s_shortname,e.genemodel_id,e.exp_value  from ongenomesimple.dataset as d, ongenomesimple.sample as s ,ongenomesimple.expressiondata as e where d.dataset_id = s.dataset_id and d.dataset_id=e.dataset_id and e.dataset_sample_id=s.sample_id and e.genemodel_id = "."'".$member."'";
  //print $sql_exp."<br>";
  $query_exp = db_query($sql_exp)->fetchAllKeyed(1,3);
  //print_r($query_exp)."<br>";
  $gene_expval_r[$member] = $query_exp;
}  

//print "<br>....<br>";
//print "<pre>"; print_r($gene_expval_r); print "</pre>"; //Works ok
$gene_expval_j = json_encode($gene_expval_r);
print "<hr>";
//print $gene_expval_j;

  //Need gene uniquename
  //$sql_expression = "select d.shortname as d_shortname,s.shortname as s_shortname,e.genemodel_id,e.exp_value  from ongenomesimple.dataset as d, ongenomesimple.sample as s ,ongenomesimple.expressiondata as e where d.dataset_id = s.dataset_id and d.dataset_id=e.dataset_id and e.dataset_sample_id=s.sample_id and e.genemodel_id in ($member_unique_names)"; 
//print $sql_expression;  
//==============================================
?>

<?php //Stacked Heatmap via plotly from exp values of each memebr of family ?>

<!--<div><h1>Hello</h1></h1></div>-->
<script>
  var gene_expval_j = <?php echo $gene_expval_j; ?>;
  //alert('HELLO');
  //alert(JSON.stringify($gene_expval_j, null, ' '));//works in alert
  
  var genes = Object.keys(gene_expval_j); // genes are keys
  //alert(genes);
  var exp_vals = Object.keys(gene_expval_j).map(function (key) {
    return gene_expval_j[key];
  });
  //alert(exp_vals);
  
  var G = [];
  var V = [];
  for (var x in gene_expval_j) {
    G.push(x);
  }
  console.log(G);
  
  
  for (var i=0; i < G.length; i++) {
    V.push(gene_expval_j[G[i]]);
  }
  
  V2 = [];
  Vall =[];
  for (i = 0; i < V.length; i++) {
    for (c=0; c<k.length; c++) {
      //console.log(V[i][k[c]]);
      V2.push(V[i][k[c]]);
    }
    Vall.push(V2);
    V2 = [];
  }
  
  //
 
 
  
  
</script>



<!--<h2>div id=tester6 (heatmap-Family):</h2>-->
<!--<div id="tester5" style="width:850px;height:400px;">-->
<div id="tester6" >
</div>
<script>
  TESTER6 = document.getElementById('tester6'); // the div-id
  
  //xdata is k;
  ydata = G;
  zdata = Vall;
  
  var data_fam_hmap = [
    {
      x:k,
      y: ydata,
      z: zdata,
      type: 'heatmap',
      colorscale: 'Rd',
      ygap:0.45
    }
  ];
  graphicHeight = 130+(genes.length)*30;
  //alert(graphicHeight);
  
  var layout = {  
    margin: { t: 0, l:200, b: 130},
    height: graphicHeight
  };
  
  Plotly.newPlot(TESTER5, data_fam_hmap, layout);
  
  
</script>



<h2>PROFILE NEIGHBORS</h2>
<?php
// GET PROFILE NEIGHBORS

$sql_profile_neighbors = "SELECT genemodel_id, profile_neighbors FROM ongenomesimple.profileneighbors WHERE dataset_id = 1 AND genemodel_id = :gene_uniquename";

print "gene-unique-name: ".$gene_uniquename."<br/>";
print $sql_profile_neighbors;
$query_profile_neighbors = db_query($sql_profile_neighbors, array(':gene_uniquename' => $gene_uniquename));
//$gene_uniquename

$result = $query_profile_neighbors->fetchCol(1); //A simple array. Col 1 has profile neighbors as a ';' separated string
//print(json_encode($result));
print "<pre>"; print_r($result); print "</pre>";
//->fetchAll()
$profileneighbors_entire_string = $result[0];
$profileneighbors_r =  explode(";", $profileneighbors_entire_string);
print_r($profileneighbors_r); print "<hr/>";
$profileneighbors_r = array_slice($profileneighbors_r, 0, 20);  // only take a few members from array (experimenting)

$neighbor_members_r = array();
foreach ($profileneighbors_r as $pair) {
  //print $pair."<br/>";
  $neighbor = explode(":", $pair);
  //print $neighbor[0]."<br/>";
  $neighbor_members_r[] = $neighbor[0];
}

print_r($neighbor_members_r);


##gene exp of neighbors:

$gene_expval_r = array(); //Array of gene exp values for each member gene with [sample] => value

foreach ($neighbor_members_r as $member) {
  //print "<br>".$member."<br/>";
  
  $sql_exp = "select d.shortname as d_shortname,s.shortname as s_shortname,e.genemodel_id,e.exp_value  from ongenomesimple.dataset as d, ongenomesimple.sample as s ,ongenomesimple.expressiondata as e where d.dataset_id = s.dataset_id and d.dataset_id=e.dataset_id and e.dataset_sample_id=s.sample_id and e.genemodel_id = "."'".$member."'";
  //print $sql_exp."<br>";
  $query_exp = db_query($sql_exp)->fetchAllKeyed(1,3);
  //print_r($query_exp)."<br>";
  $gene_expval_r[$member] = $query_exp;
}  

//print "<br>....<br>";
//print "<pre>"; print_r($gene_expval_r); print "</pre>"; //Works ok
$gene_expval_j = json_encode($gene_expval_r);
print "<hr>";
//print $gene_expval_j;
//==============================================
?>

<?php //Stacked Heatmap via plotly from exp values of each memebr from neighbors ?>


<script>
  var gene_expval_j = <?php echo $gene_expval_j; ?>;
  //alert('HELLO');
  //alert(JSON.stringify($gene_expval_j, null, ' '));//works in alert
  
  var genes = Object.keys(gene_expval_j); // genes are keys
  //alert(genes);
  var exp_vals = Object.keys(gene_expval_j).map(function (key) {
    return gene_expval_j[key];
  });
  //alert(exp_vals);
  
  var G = [];
  var V = [];
  for (var x in gene_expval_j) {
    G.push(x);
  }
  console.log(G);
  
  
  for (var i=0; i < G.length; i++) {
    V.push(gene_expval_j[G[i]]);
  }
  
  V2 = [];
  Vall =[];
  for (i = 0; i < V.length; i++) {
    for (c=0; c<k.length; c++) {
      //console.log(V[i][k[c]]);
      V2.push(V[i][k[c]]);
    }
    Vall.push(V2);
    V2 = [];
  }
  
  //
 
 
  
  
</script>



<!--<h2>div id=tester6 (heatmap-Family):</h2>-->
<!--<div id="tester5" style="width:850px;height:400px;">-->
<h2>Profile noeghbors Stacked Heatmap</h2>
<div id="tester7" style="width:850px;">
</div>
<script>
  TESTER7 = document.getElementById('tester7'); // the div-id
  
  //xdata is k;
  ydata = G;
  zdata = Vall;
  
  var data_neighbors_hmap = [
    {
      x:k,
      y: ydata,
      z: zdata,
      type: 'heatmap',
      //colorscale: 'Picnic',
      colorscale: 'Rd',   //Hot,Jet,Greens*,Greys,Picnic*,Portland,RdBu,YIGnBu,YIOrRd,Bluered,Earth,Electric,Blackbody,Reds*(Rd),Blues
      ygap:0.15,
      xgap:0.15
    }
  ];
  graphicHeight = 130+(genes.length)*30;
  //graphicHeight = 1000;
  //alert(graphicHeight);
  
  var layout = {  
    margin: { t: 0, l:200, b: 130},
    height: graphicHeight
  };
  
  Plotly.newPlot(TESTER7, data_neighbors_hmap, layout);
  
  
</script>


<!--  STACKED LINE-PLOTS FOR NEIGHBORS  -->
<!--  ================================  -->

<h2>Tester8 (Profile neighbors, stacked line-plots):</h2>
<div id="tester8" style="width:850px;">
</div>
<script>
  TESTER8 = document.getElementById('tester8'); // the div-id
  
  data_traces_for_scatter = []; //Constructing array of individual neighbor traces
  for (var i=0; i < G.length; i++) {
    trace = {x: k, y: Vall[i], type: 'scatter', name: G[i]}; //To try name:'gene_name'
    data_traces_for_scatter.push(trace);
  }
  
  var layout = {  
    margin: { t: 0, l:200, b: 130},
    height: graphicHeight
  };
  
  Plotly.newPlot(TESTER8, data_traces_for_scatter);  //w/o layout; only a few visivle
  //Plotly.newPlot(TESTER8, data_traces_for_scatter, layout);  // layout brings whole range into view



</script>





<!--   ===============================================================       -->
<?php
// SCRATCH PAD

//drupal_json_output($data);  //works but fills the page with json instead of rendered html
/*
SELECT fp.value
          FROM chado.featureprop fp, chado.feature f
          WHERE fp.type_id = (SELECT cvterm_id FROM chado.cvterm
                  WHERE NAME = 'gene family')
          AND (f.uniquename = :gene OR f.name = :gene)
          AND fp.feature_id = f.feature_id

*/

/*
 *SEARCH FOR FENE FAMILY MEMBERS

SELECT fp.value FROM chado.featureprop fp, chado.feature f WHERE fp.type_id = (SELECT cvterm_id FROM chado.cvterm  WHERE NAME = 'gene family')  AND (f.uniquename = 'cicar.ICC4958.v2.0.Ca_00750')        AND fp.feature_id = f.feature_id;     ##

result: phytozome_10_2.59244430


$  psql -c "select gfa1.gene_family_assignment_id,gfa1.gene_id,gfa1.family_label,gfa2.gene_id,gfa2.family_label, f.feature_id,f.uniquename,f.organism_id, f2.feature_id,f2.uniquename,f2.organism_id FROM gene_family_assignment as gfa1 INNER JOIN gene_family_assignment as gfa2 ON gfa2.family_label = gfa1.family_label INNER JOIN feature AS f ON f.feature_id=gfa1.gene_id INNER JOIN feature AS f2 ON f2.feature_id=gfa2.gene_id WHERE f.organism_id=33 AND f.uniquename='cicar.ICC4958.v2.0.Ca_08847' AND f2.organism_id=33;"
 gene_family_assignment_id | gene_id |      family_label       | gene_id |      family_label       | feature_id |         uniquename          | organism_id | feature_id |         uniquename          | organism_id 
---------------------------+---------+-------------------------+---------+-------------------------+------------+-----------------------------+-------------+------------+-----------------------------+-------------
                    709858 | 9347769 | phytozome_10_2.59287189 | 9327467 | phytozome_10_2.59287189 |    9347769 | cicar.ICC4958.v2.0.Ca_08847 |          33 |    9327467 | cicar.ICC4958.v2.0.Ca_06342 |          33
                    709858 | 9347769 | phytozome_10_2.59287189 | 9347775 | phytozome_10_2.59287189 |    9347769 | cicar.ICC4958.v2.0.Ca_08847 |          33 |    9347775 | cicar.ICC4958.v2.0.Ca_08848 |          33
                    709858 | 9347769 | phytozome_10_2.59287189 | 9347769 | phytozome_10_2.59287189 |    9347769 | cicar.ICC4958.v2.0.Ca_08847 |          33 |    9347769 | cicar.ICC4958.v2.0.Ca_08847 |          33
(3 rows)

OR

$ psql -c "select gfa1.gene_family_assignment_id,gfa1.gene_id,gfa1.family_label,gfa2.gene_id,gfa2.family_label, f.feature_id,f.uniquename,f.organism_id  FROM gene_family_assignment as gfa1 INNER JOIN gene_family_assignment as gfa2 ON gfa2.family_label = gfa1.family_label INNER JOIN feature AS f ON f.feature_id=gfa2.gene_id WHERE f.organism_id=33 AND gfa1.gene_id=9347769;"


verified:
$ psql -c "select gfa.gene_family_assignment_id,gfa.gene_id,gfa.family_label, f.feature_id,f.organism_id,f.uniquename from gene_family_assignment gfa, feature f where f.feature_id=gfa.gene_id and gfa.gene_id in (9327467,9347775,9347769);"
 gene_family_assignment_id | gene_id |      family_label       | feature_id | organism_id |         uniquename          
---------------------------+---------+-------------------------+------------+-------------+-----------------------------
                    660991 | 9327467 | phytozome_10_2.59287189 |    9327467 |          33 | cicar.ICC4958.v2.0.Ca_06342
                    709858 | 9347769 | phytozome_10_2.59287189 |    9347769 |          33 | cicar.ICC4958.v2.0.Ca_08847
                    676078 | 9347775 | phytozome_10_2.59287189 |    9347775 |          33 | cicar.ICC4958.v2.0.Ca_08848







*/




?>

<!--
<div>
  <h1>Template for lis_expression module </h1>
  
  Note:<br/>(With any random name for content in hook_node_view(); 
       $node->content['xyz_anything'])
</div>
-->









