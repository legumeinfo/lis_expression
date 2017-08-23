<?php
/**
 *The template to display gene expression in a pane within the gene feature page
 *
 *
 *03Aug2017: Now data from ongenome schema
 *(Earlier from ongenomesomple schema, in templates .old1 and .old2)
 *
 *
 *
 */
?>


<?php
  //The pane should be visible only 'in a gene page' and 'only if expression data is available'
  //
  //  **THIS SEC MUST BE AT TOP BEFORE ANY PAGE/NODE CONTENT IS CREATED**
  //  (Any page/node content makes the pane appear in all feature nodes TOC)
  //---------------------------------------------------------------------------

  //Get the feature node from $variables
  $feature = $variables['node']->feature;
  
  $gene_uniquename = $feature->uniquename;
  $gene_name = $feature->name;
  
  //Get all the datasets available for this genemodel
  //ong-simple//$sql_get_datasets = "SELECT DISTINCT d.dataset_id,d.shortname FROM ongenomesimple.dataset d, ongenomesimple.expressiondata e WHERE d.dataset_id=e.dataset_id and e.genemodel_id = :gene_uniquename";
  $sql_get_datasets = "SELECT DISTINCT d.dataset_id,d.shortname, d.accession_no FROM ongenome.dataset d, ongenome.expressiondata e, ongenome.genemodel gm  WHERE d.dataset_id=e.dataset_id  and e.genemodel_id = gm.genemodel_id and gm.chado_uniquename = :gene_uniquename";  //ongenome
  
  $result_get_datasets = db_query($sql_get_datasets, array(':gene_uniquename' => $gene_uniquename));
  $dataset_counts = $result_get_datasets->rowCount();
  
  //Quit if feature is not a gene or no expression data avail 
  if ($feature->type_id->name != 'gene')   {  
      return; //Quit if it isn't a gene page or if there isn't any expression dataset for this gene
  } elseif ( $result_get_datasets->rowCount() == 0 ) {
      return; //Quit if there isn't any expression dataset for this gene
  }
  //...........................................................................
?>

<!--
Page title (Include gene name)
(The default title is just the panel name in TOC. This replaces that with the gene name)
-------------------------------------------------------------------------------
-->

<script>
  titleLabel = "<?php echo "Expression (".$feature->name.")"; ?>";
  (function($) {
      $('.geneexpressionprofile-tripal-data-pane-title.tripal-data-pane-title').html(titleLabel);
    //jQuery('.figure-tripal-data-pane-title.tripal-data-pane-title').html(titleLabel);
  })(jQuery); 
</script>


<?php
  //Dataset information, display
  //---------------------------------------------------------------------------
  
  // List available datasets. Give shortname(dataset_id). 
  print "Gene expression data is available for this genemodel in the following dataset(s):"."<br/>"; 
  $count = 0;
  $accession_no = '';
  $dataset_shortname = '';
  foreach ($result_get_datasets as $rec) {
      $count = $count + 1;
      $dataset_id = $rec->dataset_id;
      $dataset_accession_no = $rec->accession_no;
      $dataset_shortname = $rec->shortname;
      print $count."."."<b>"."&nbsp;&nbsp;".$dataset_accession_no.": "."</b>".$dataset_shortname."<br/>";
      //print "<b>".$rec->shortname."</b>"." (dataset_id:".$rec->dataset_id.")"."<br/>";
  }
  print "<hr/>";
?>

<?php //TO DO: Iterate through each of the datasets (at this point) ?>

<?php
  // Get feature_id and organism_id for subsequent use
  $feature_id = $feature->feature_id;
  $organism_id = $feature->organism_id->organism_id;
  //print "feature-id: ".$feature_id."; ";   print "organism-id: ".$organism_id. "; ";  print "dataset_id: ".$dataset_id; print "<br/>"; print "<hr />";
?>

<?php
  //Get expression data for this genemodel_id for a dataset 
  //---------------------------------------------------------------------------
  
  //$sql_expression = "select d.shortname as d_shortname,s.shortname as s_shortname,e.genemodel_id,e.exp_value  from ongenomesimple.dataset as d, ongenomesimple.sample as s ,ongenomesimple.expressiondata as e where d.dataset_id = s.dataset_id and d.dataset_id=e.dataset_id and e.dataset_sample_id=s.sample_id and e.genemodel_id = :gene_uniquename";  // CHANGE IT: with place holder :uniquename
  
  $sql_expression = "SELECT d.shortname as d_shortname, s.sample_uniquename as s_shortname, gm.genemodel_name,  e.exp_value  FROM  ongenome.dataset as d, ongenome.sample as s , ongenome.dataset_sample ds, ongenome.expressiondata as e, ongenome.genemodel as gm WHERE  d.dataset_id = ds.dataset_id and d.dataset_id=e.dataset_id and e.dataset_sample_id=ds.sample_id and ds.sample_id = s.sample_id and e.genemodel_id = gm.genemodel_id and gm.chado_uniquename = :gene_uniquename";
  
  $result_expression = db_query($sql_expression, array(':gene_uniquename' => $gene_uniquename));
    
        //$sql = "select d.shortname as d_shortname,s.shortname as s_shortname,e.genemodel_id,e.exp_value  from ongenomesimple.dataset as d, ongenomesimple.sample as s ,ongenomesimple.expressiondata as e where d.dataset_id = s.dataset_id and d.dataset_id=e.dataset_id and e.dataset_sample_id=s.sample_id and e.genemodel_id='cicar.ICC4958.v2.0.Ca_00006'";  // CHANGE IT: with place holder :uniquename
        //cicar.ICC4958.v2.0.Ca_00006
?>

<!--  data into Table format  -->
<?php
  
  //display_type: Table
  $div_content_table .=  "<table>";
  $div_content_table .=  "<tr style=''><td><b>Sample (shortname)</b></td><td><b>Expr. Value (TPM)</b></td></tr>";
  foreach ($result_expression as $rec) {
      $div_content_table .=  "<tr>";
      $div_content_table .=    "<td>".$rec->s_shortname."</td>";
      $div_content_table .=    "<td>".$rec->exp_value."</td>";
      $div_content_table .=  "</tr>";
  }
  $div_content_table .=  "</table>";
?>

<!--  Table html content into javascript var  -->

<script>
  div_content_table = "<?php echo $div_content_table; ?>";  // Quote works ok
  //getElementById('display_gene_data').innerHTML = div_content_table;
</script>



<!--  Select display_type; radio buttons -->

<input type="radio" name="display_type" value="plot"  onclick="drawLinePlot (CONTAINER_GENE);"  checked > Line plot &nbsp;&nbsp;&nbsp;
<input type="radio" name="display_type" value="bar"  onclick="drawBarPlot(CONTAINER_GENE);"> Bar graph &nbsp;&nbsp;&nbsp;
<input type="radio" name="display_type" value="heatmap"  onclick="drawHeatmap(CONTAINER_GENE)" > Heatmap &nbsp;&nbsp;&nbsp;
<input type="radio" name="display_type" value="table" onclick="document.getElementById('display_gene_data').innerHTML = div_content_table;"> Table <br/>

<div>For gene model: <b><?php echo $gene_uniquename; ?></b></div> <!-- **May have to remove it -->

<!-- DIVs for display of this gene's data-->
<div id="display_gene_data"  style="width:850px;/*height:400px;*/"></div>
<hr/>


<script>
  //document.getElementById('display_gene_data').innerHTML = div_content_table;
</script>

<!-- Expression data from db_query into JSON format -->
<?php
  
  //Expression data into JSON format in php
  //$result_expression 'HAD' expression data from db_query BUT NEEDS TO BE REPEATED
  $result_expression = db_query($sql_expression, array(':gene_uniquename' => $gene_uniquename));
  $result_expression_j = json_encode($result_expression->fetchAllKeyed(1,3));  //j2 in old code
  //print "json k:v : ".$result_expression_j;
?>


<!--  Plotly library  -->
<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>


<!--Get php json formatted data to JS
    and
    define plot data
-->
<script>
  var geneName = "<?php echo $gene_name; ?>";
  
  var jd_gene = <?php echo $result_expression_j; ?>;  // Don't use quote here unlike before "<?php echo $result_expression_j; ?>". Quoted FAILS. Don't understand WHY?? May be something to do with if it is a string vs. other data str like array.
    //jd (json data)
  
  var k = Object.keys(jd_gene); //k-keys
  var vals = Object.keys(jd_gene).map(function (key) {
      return jd_gene[key];
  });
  
  var CONTAINER_GENE = document.getElementById('display_gene_data');
</script>


<!--Define Draw functions, line, bar, heatmap for this gene-->
<script>
  
  //---------SCATTER
  function drawLinePlot (container) {
      container.innerHTML = ''; //TRY removing this to see effect; //empty the container first before drawing
  
      var dataLinePlot =  [
          trace = {
              //x: ['abc1','abc2','abc3','abc4','abc5'],
              x: k,//Object.keys(jd),
              //y: [1,3,5,7,9], }],
              y: vals,
              
              type: 'scatter'
              //type: 'bar',
              //orientation: 'h'
          }
      ];
    
      var layout = {
          margin: { t: 0, b: 130 },
          paper_bgcolor: '#f5f5f5',
          plot_bgcolor: '#c7c7c7'
      };
    
      Plotly.newPlot(container, dataLinePlot, layout);
      //..........................
  }
  
  
  //------BAR
  function drawBarPlot (container) {
      container.innerHTML = '';
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
      Plotly.newPlot(container, dataBar, layout);
  }
  
  
    //-----HEATMAP
  function drawHeatmap (container) {
      container.innerHTML = '';
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
      Plotly.newPlot(container, dataHeatmap, layout);
  }
  
  
  
</script>

<script>
  // Initial drawing, LinePlot,  when page loads
  //--------------------------------
  drawLinePlot (CONTAINER_GENE);
</script>


<!--
=================================================================== 
SEC: Profile Neighbors and Gene family members
===================================================================
-->

<h2>Profile Neighbors (Genes with similar expression profile; first 20 for now)</h2>

<!--Containers for profile neighbors-->
<input type="radio" name="display_type_neighbors" value="lineplot"  onclick="drawProfileNeighborsStackedLinePlots (CONTAINER_NEIGHBORS);"  checked > Line plot &nbsp;&nbsp;&nbsp;
<input type="radio" name="display_type_neighbors" value="heatmap"  onclick="drawProfileNeighborsHeatmap(CONTAINER_NEIGHBORS);"> Heatmap &nbsp;&nbsp;&nbsp;
<br/><br/>
<div id="display_profile_neighbors_data"  style="width:850px;/*height:400px;*/"></div>

<?php
  // GET PROFILE NEIGHBORS
  
  //$sql_profile_neighbors = "SELECT genemodel_id, profile_neighbors FROM ongenomesimple.profileneighbors WHERE dataset_id = 1 AND genemodel_id = :gene_uniquename";
  //$sql_profile_neighbors = "SELECT genemodel_uniquename, profile_neighbors FROM ongenome.profileneighbors WHERE dataset_id = :dataset_id AND genemodel_uniquename = :gene_uniquename";
  $sql_profile_neighbors = "SELECT genemodel_uniquename, profile_neighbors FROM ongenome.profileneighbors"."_".$dataset_accession_no."  WHERE dataset_id = :dataset_id AND genemodel_uniquename = :gene_uniquename";
  
  //print "gene-unique-name: ".$gene_uniquename."<br/>";
  //print $sql_profile_neighbors;
  $query_profile_neighbors = db_query($sql_profile_neighbors, array(':dataset_id' => $dataset_id, ':gene_uniquename' => $gene_uniquename));
  //$gene_uniquename
  
  
  $result = $query_profile_neighbors->fetchCol(1); //A simple array. Col 1 has profile neighbors as a ';' separated string
  //print(json_encode($result));
  //print "<pre>"; print_r($result); print "</pre>";
  
  $profileneighbors_entire_string = $result[0];
  //print $profileneighbors_entire_string;
  $profileneighbors_r =  explode(";", $profileneighbors_entire_string); // _r into an array
  //print_r($profileneighbors_r); print "<hr/>";
  $profileneighbors_r = array_slice($profileneighbors_r, 0, 20);  // only take a few members from array (EXPERIMENTING with 20 members)
  //print_r($profileneighbors_r);
  
  $neighbor_members_r = array(); //Just the members without the corr value
  foreach ($profileneighbors_r as $pair) {
    //print $pair."<br/>";
    $neighbor = explode(":", $pair);
    //print $neighbor[0]."<br/>";
    $neighbor_members_r[] = $neighbor[0];
  }
  
  //print_r($neighbor_members_r);


/*  TEST FOR NO NEIGHBORS
 *TEST  $neighbor_members_r if all the members start with 'NA' then don't plot but just add a message
 *php: array_key_exist[ don't know if it takes regex], array_walk
 *js: <array>.every() // checks each member of an array

*/  
  
  ##gene exp of neighbors:
  
  $gene_expval_r = array(); //Array of gene exp values for each member gene with [sample] => value
  
  foreach ($neighbor_members_r as $member) {
    //print "<br>".$member."<br/>";
    
    //$sql_exp = "select d.shortname as d_shortname,s.shortname as s_shortname,e.genemodel_id,e.exp_value  from ongenomesimple.dataset as d, ongenomesimple.sample as s ,ongenomesimple.expressiondata as e where d.dataset_id = s.dataset_id and d.dataset_id=e.dataset_id and e.dataset_sample_id=s.sample_id and e.genemodel_id = "."'".$member."'";
    $sql_exp = "SELECT d.shortname as d_shortname, s.sample_uniquename as s_shortname, gm.genemodel_name, e.exp_value  FROM ongenome.dataset as d, ongenome.sample as s ,ongenome.expressiondata as e, ongenome.dataset_sample as ds, ongenome.genemodel as gm WHERE d.dataset_id = ds.dataset_id and d.dataset_id=e.dataset_id and e.dataset_sample_id=ds.sample_id and s.sample_id=ds.sample_id and e.genemodel_id=gm.genemodel_id and gm.chado_uniquename = :member";
    //print $sql_exp."<br>";
    $query_exp = db_query($sql_exp, array(':member' => $member))->fetchAllKeyed(1,3);
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

<!--Stacked Heatmap via plotly from exp values of each memebr from neighbors -->

<!--Organize profile neighbor data before passing onto Plotly-->
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

<!--  Neighbors Heatmap: Plotly function  -->
<script>
  CONTAINER_NEIGHBORS = document.getElementById('display_profile_neighbors_data'); // the div-d for drawing neighbors heatmap
</script>

<script>
  function drawProfileNeighborsHeatmap (container) {
      //TESTER7 = document.getElementById('tester7'); // the div-id
      CONTAINER_NEIGHBORS.innerHTML = "";
      
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
      
      Plotly.newPlot(container, data_neighbors_hmap, layout);
  }
</script>


<!--  Neighbors Stacked LINE-PLOTS Plotly function  -->
<script>
  
  function drawProfileNeighborsStackedLinePlots (container) {
      
      CONTAINER_NEIGHBORS.innerHTML = "";
      
      data_traces_for_scatter = []; //Constructing array of individual neighbor traces
      for (var i=0; i < G.length; i++) {
        trace = {x: k, y: Vall[i], type: 'scatter', name: G[i]}; //To try name:'gene_name'
        data_traces_for_scatter.push(trace);
      }
      
      graphicHeight = 130+(genes.length)*25; //original
      //graphicHeight = 1000;
      //alert(graphicHeight);
      
      var layout = {  
        margin: { t: 0, l:50, b: 130},
        height: graphicHeight
      };
      
      //Plotly.newPlot(container, data_traces_for_scatter);  //w/o layout; only a few visivle
      Plotly.newPlot(container, data_traces_for_scatter, layout);  // layout brings whole range into view
  }
</script>

<script>
  //Initial drawing is Lineplot for neighbors
  drawProfileNeighborsStackedLinePlots (CONTAINER_NEIGHBORS);
</script>

<!-- =================================================================== -->
<!--Gene Family members-->
<!--  ==================================================================== -->

<h2>Gene Family Members (Expression profile of genes in the same family)</h2>
<!--Containers for Family members-->
<input type="radio" name="display_type_family" value="lineplot"  onclick="drawFamilyMembersStackedLinePlots (CONTAINER_FAMILY);"  checked > Line plot &nbsp;&nbsp;&nbsp;
<input type="radio" name="display_type_family" value="heatmap"  onclick="drawFamilyMembersHeatmap(CONTAINER_FAMILY);"> Heatmap &nbsp;&nbsp;&nbsp;
<br/><br/>
<div id="display_family_members_data"  style="width:850px;/*height:400px;*/position:relative;"></div>


<?php

//KEEP
$sql_gene_family = "select gfa1.gene_family_assignment_id AS source_family_id,gfa1.gene_id AS source_gene_id,gfa1.family_label AS source_family, gfa2.gene_id AS target_gene_id,gfa2.family_label AS target_family, f.feature_id AS target_feature_id,f.uniquename AS target_uniquename,f.organism_id AS target_organism_id  FROM gene_family_assignment as gfa1 INNER JOIN gene_family_assignment as gfa2 ON gfa2.family_label = gfa1.family_label INNER JOIN chado.feature AS f ON f.feature_id = gfa2.gene_id WHERE f.organism_id = :organism_id AND gfa1.gene_id = :feature_id";
//print $sql_gene_family;
$query_gene_family = db_query($sql_gene_family, array(':organism_id' => $organism_id, ':feature_id' => $feature_id));

//$result = $result_gene_family->fetchAll();
$result = $query_gene_family->fetchCol(6); //A simple array. Col 6 is target_uniquename
//print(json_encode($result));
//print "<pre>"; print_r($result); print "</pre>";
//print "HELLO HELLO WORLD";
$fam_members = $result; //print count($fam_members);
$member_unique_names = "Member-unique-names: "."'".implode("','",$fam_members)."'"; // becomes quoted and , separated list
//print $member_unique_names."<hr>";
//------------------------------------

$gene_expval_fam_r = array(); //Array of gene exp values for each member gene with [sample] => value

foreach ($fam_members as $member) {
  //print "<br>".$member."<br/>";
  
  //$sql_exp_fam = "select d.shortname as d_shortname,s.shortname as s_shortname,e.genemodel_id,e.exp_value  from ongenomesimple.dataset as d, ongenomesimple.sample as s ,ongenomesimple.expressiondata as e where d.dataset_id = s.dataset_id and d.dataset_id=e.dataset_id and e.dataset_sample_id=s.sample_id and e.genemodel_id = "."'".$member."'";
$sql_exp_fam = "SELECT d.shortname as d_shortname, s.sample_uniquename as s_shortname, gm.genemodel_name, e.exp_value  FROM ongenome.dataset as d, ongenome.sample as s ,ongenome.expressiondata as e, ongenome.dataset_sample as ds, ongenome.genemodel as gm WHERE d.dataset_id = ds.dataset_id and d.dataset_id=e.dataset_id and e.dataset_sample_id=ds.sample_id and s.sample_id=ds.sample_id and e.genemodel_id=gm.genemodel_id and gm.chado_uniquename = :member";
  //print $sql_exp."<br>";
  //$query_exp_fam = db_query($sql_exp_fam)->fetchAllKeyed(1,3);
  $query_exp_fam = db_query($sql_exp_fam, array(':member' => $member))->fetchAllKeyed(1,3);
  //print_r($query_exp)."<br>";
  $gene_expval_fam_r[$member] = $query_exp_fam;
}  

//print "<br>....<br>";
//print "<pre>"; print_r($gene_expval_r); print "</pre>"; //Works ok
$gene_expval_fam_j = json_encode($gene_expval_fam_r);
//print "<hr>";
//print $gene_expval_j;
//==============================================  
?>

<?php //Stacked Heatmap via plotly from exp values of each memebr of family ?>

<script>
  var gene_expval_fam_j = <?php echo $gene_expval_fam_j; ?>;
  //alert('HELLO');
  //alert(JSON.stringify($gene_expval_j, null, ' '));//works in alert
  
  var genes_fam = Object.keys(gene_expval_fam_j); // genes are keys
  //alert(genes);
  var exp_vals_fam = Object.keys(gene_expval_fam_j).map(function (key) {
    return gene_expval_fam_j[key];
  });
  //alert(exp_vals);
  
  var G_fam = [];
  var V_fam = [];
  for (var x in gene_expval_fam_j) {
    G_fam.push(x);
  }
  console.log(G_fam);
  
  
  for (var i=0; i < G_fam.length; i++) {
    V_fam.push(gene_expval_fam_j[G_fam[i]]);
  }
  
  V2_fam = [];
  Vall_fam =[];
  for (i = 0; i < V_fam.length; i++) {
    for (c=0; c<k.length; c++) {
      //console.log(V[i][k[c]]);
      V2_fam.push(V_fam[i][k[c]]);
    }
    Vall_fam.push(V2_fam);
    V2_fam = [];
  }
  
  //

</script>


<script>
  CONTAINER_FAMILY = document.getElementById('display_family_members_data');
</script>

<script>
  function drawFamilyMembersHeatmap (container) {
  CONTAINER_FAMILY.innerHTML = "";
  //xdata is k;
  ydata = G_fam;
  zdata = Vall_fam;
  
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
  graphicHeight = 130+(genes_fam.length)*30;
  //alert(graphicHeight);
  
  var layout = {  
    margin: { t: 0, l:200, b: 130},
    height: graphicHeight
  };
  
  Plotly.newPlot(container, data_fam_hmap, layout);
  }
</script>



<!--  Gene Family Stacked LINE-PLOTS Plotly function  -->
<script>
  
  function drawFamilyMembersStackedLinePlots (container) {
      
      CONTAINER_FAMILY.innerHTML = "";
      
      data_traces_for_scatter = []; //Constructing array of individual neighbor traces
      for (var i=0; i < G_fam.length; i++) {
        trace = {x: k, y: Vall_fam[i], type: 'scatter', name: G_fam[i]}; //To try name:'gene_name'
        data_traces_for_scatter.push(trace);
      }
      
      graphicHeight = 130+(genes_fam.length)*30; //original
      //graphicHeight = 630+(genes.length)*30; //experimental
      //graphicHeight = 1000;
      //alert(graphicHeight);
      
      var layout = {  
        margin: { t: 0, l:30, b: 130},
        height: graphicHeight
      };
      
      Plotly.newPlot(container, data_traces_for_scatter, layout);  // w/ layout brings whole range into view
      //Plotly.newPlot(container, data_traces_for_scatter);  //w/o layout; only a few visible
  }
</script>

<script>
  //Initial drawing is Lineplot for neighbors
  drawFamilyMembersStackedLinePlots (CONTAINER_FAMILY);
</script>

