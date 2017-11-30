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
  $genus = $feature->organism_id->genus;
  $species = $feature->organism_id->species;
  //print $genus."::".$species;
?>

<script>
var genus   =  "<?php  echo $genus;   ?>";
var species =  "<?php  echo $species; ?>";
</script>

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
<fieldset style="display: inline-block; padding-left: 10px;">
<input type="radio" name="display_type" value="plot"  onclick="drawLinePlot (CONTAINER_GENE);"  checked > Line plot &nbsp;&nbsp;&nbsp;
<input type="radio" name="display_type" value="bar"  onclick="drawBarPlot(CONTAINER_GENE);"> Bar graph &nbsp;&nbsp;&nbsp;
<input type="radio" name="display_type" value="heatmap"  onclick="drawHeatmap(CONTAINER_GENE)" > Heatmap &nbsp;&nbsp;&nbsp;
<input type="radio" name="display_type" value="table" onclick="document.getElementById('display_gene_data').innerHTML = div_content_table;"> Table <br/>
</fieldset>
<div>For gene model: <b><?php echo $gene_uniquename; ?></b></div> <!-- **May have to remove it -->

<!-- DIVs for display of this gene's data-->
<div id="display_gene_data"  style="width:850px;/*height:400px;*/"></div>
<hr/>  <!--  Line after the single gene   -->


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
          plot_bgcolor: '#c7c7c7',
	  yaxis: {title: 'TPM'}
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
	  yaxis: {title: 'TPM'}
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
            colorscale: 'Rd',
	    colorbar: {title:'TPM', titleside:'right'}
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

<h2>Profile Neighbors</h2>
Genes with similar expression profile (with r &#8805 0.8); first 20 for now.&nbsp;&nbsp;&nbsp;<br/>


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
  
  //print_r($neighbor_members_r);print "<br/>"; //debug keep
  
  
    /*  TEST FOR NO NEIGHBORS
    *TEST  $neighbor_members_r if all the members start with 'NA' then don't plot but just add a message
    *php: array_key_exist[ don't know if it takes regex], array_walk
    *js: <array>.every() // checks each member of an array
   
   */  
  
  //<<<<<<<
  //bool array_key_exists ( mixed $key , array $array );
  //if (in_array('NA', $neighbor_members_r)) {
  //  print "NEIGHBORS HAVE  NA"."<br/>";
  //}  //Discard later
  
  function NA_match($item) {  #If there is NA* in value return false
    //preg_match('/NA/', $item);
    if (preg_match('/NA/', $item)) {
      //print "F ";
      return FALSE;
    } else {return TRUE;}
  }
  
  //$xxx = array_diff($neighbor_members_r, array('NA'));
  //print "xxx: ";print_r($xxx); print "<br/>";
  $neighbor_members_r = array_filter($neighbor_members_r, "NA_match");
  //print "Filtered NA out: "; //debug, keep
  //print_r($neighbor_members_r); print "<br/>"; // debug //keep
  
  //Test if neighbors array Empty
  if (!$neighbor_members_r) {
    $has_neighbors = FALSE;
    //print "neighbors array  EMPTY<br/>"; // debug, keep
  } else {
    $has_neighbors = TRUE;
    //print "HAS NEIGHBORS<br/>"; // debug, keep
  }
  
  //print "HasNEIGH:".$has_neighbors."<br/>";  
  
  
  //>>>>>>>

  
  //<<<<<<<<<<<<<  Neighbors name to legumemine
  
  //CAUTION     !!!!   UNDER DEVELOPMENT     !!!!
    
  //Function: Get Display/Short Names of neighbors from uniquenames
      //Given an array of chado.uniquenames, should return array of display names
      //print "ORG-outside fn:".$organism_id."<br/>";
  function get_gene_shortname_list($uniquename_list, $organism_id) {  //$uniquename_list must be an array
    //Given an array of gene chado.feature.uniquenames return array of corresponding chado.feature.names
    
        //example uniquename: cicar.ICC4958.v2.0.Ca_01152
        //$organism_id  already defined in the beginning.
        //$neighbor_members_r is the array of gene uniquenames vailable
        
    //$organism_id;
    //print "ORG-inside f():".$organism_id;
    $uniquename_list_str = "('".implode("','", $uniquename_list)."')"; //convert to coma-sep string for sql in clause
    //print "<br/>uniquenames: ".$uniquename_list_str."<br/>";
       
    
    $sql = "select name from chado.feature where type_id=(select cvterm_id from chado.cvterm where name='gene') and organism_id = :organism_id and uniquename in ". $uniquename_list_str;  //These three beacause `UNIQUE CONSTRAINT, btree (organism_id, uniquename, type_id)` in feature table
    //print "</br>"."org:".$organism_id."-----".$sql."</br>";
    
        //Example lists:
/*
*cajca.ICPL87119.gnm1.ann1.C.cajan_00002,cajca.ICPL87119.gnm1.ann1.C.cajan_00003,cajca.ICPL87119.gnm1.ann1.C.cajan_00004,cajca.ICPL87119.gnm1.ann1.C.cajan_00005,cajca.ICPL87119.gnm1.ann1.C.cajan_00007
*
cicar.ICC4958.v2.0.Ca_00038,cicar.ICC4958.v2.0.Ca_01148,cicar.ICC4958.v2.0.Ca_01167,cicar.ICC4958.v2.0.Ca_01216,cicar.ICC4958.v2.0.Ca_01228


 */
    
    //$query_result = db_query($sql, array(':organism_id' => $organism_id, ':uniquename_list_str' => $uniquename_list_str));
    $query_result = db_query($sql, array(':organism_id' => $organism_id,));
//REMOVE line    //db_query($sql_profile_neighbors, array(':dataset_id' => $dataset_id, ':gene_uniquename' => $gene_uniquename));
    
    //$gene_names_r = $query_result->fetchAll();
    $gene_names_r = $query_result->fetchCol();
    //print "names:".":count- ".count($gene_names_r)."<pre>";
    //print_r($gene_names_r);
    //print "</pre>";
    
    return $gene_names_r;
    
  }  //end: fn get_gene_shortname

  $gene_names_r = get_gene_shortname_list($neighbor_members_r, $organism_id); //an array of gene names
  $gene_names_str = implode("%0A", $gene_names_r); //to string; sep is "%0A", line ending
  $url_string_to_legume_mine = "https://intermine.legumefederation.org/legumemine/bag.do?type=Gene&text=".$gene_names_str;
  //print "<a target=\"_blank\"    href=\"" . $url_string_to_legume_mine . "\">". "To LegumeMine fro further analysis</a>";


  //>>>>>>>>>>>>  neighbors name 

 
  
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
  //print "<hr>";
  //print $gene_expval_j;
  //==============================================
?>

<script>
var has_neighbors = <?php echo $has_neighbors; ?>;
</script>


<!--Containers for profile neighbors-->
<?php
  
  if ($has_neighbors) {
  echo "<fieldset style=\"display: inline-block; padding-left: 10px;\">";
  echo "<input type=\"radio\" name=\"display_type_neighbors\" value=\"lineplot\"  onclick=\"drawProfileNeighborsStackedLinePlots (CONTAINER_NEIGHBORS);\"  checked > Line plot &nbsp;&nbsp;&nbsp;";
  echo "<input type=\"radio\" name=\"display_type_neighbors\" value=\"heatmap\"  onclick=\"drawProfileNeighborsHeatmap(CONTAINER_NEIGHBORS);\"> Heatmap** &nbsp;&nbsp;&nbsp;";
  echo "</fieldset>";
  echo "(**The <strong>heatmap has links</strong> to profile neighbors)";
  echo "<br/>"."<a target=\"_blank\"    href=\"" . $url_string_to_legume_mine . "\">". "Send the list of profile neighbors to LegumeMine for further analysis.</a>";
  } else {
    echo "<span style=\"font-size: large; color: DarkRed;\"> <br/>**** This genemodel has no neighbors with r &#8805 0.8</span>";
  }
?>

<br/><br/>
<div id="display_profile_neighbors_data"  style="width:850px;/*height:400px;*/"></div>




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
  var G_anchorText = [];
  var V = [];
  for (var x in gene_expval_j) {
    G.push(x);
    linkToGene = "<a  href=\"/feature/"+genus+"/"+species+"/gene/"+x+"#pane=geneexpressionprofile"+"\""+ ">"+x+"</a>";
    G_anchorText.push(linkToGene);
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
      ydata = G_anchorText;  //the gene names are now urls to the gene page.
      zdata = Vall;
      
      var data_neighbors_hmap = [
        {
          x:k,
          y: ydata,
          z: zdata,
          type: 'heatmap',
	  colorbar: {title:'TPM', titleside:'right'},
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
	yaxis: {title: 'TPM'},
        height: graphicHeight
      };
      
      //Plotly.newPlot(container, data_traces_for_scatter);  //w/o layout; only a few visivle
      Plotly.newPlot(container, data_traces_for_scatter, layout);  // layout brings whole range into view
  }
</script>

<script>
  if (has_neighbors) {
    drawProfileNeighborsStackedLinePlots (CONTAINER_NEIGHBORS);
  }
  //Initial drawing is Lineplot for neighbors
  //drawProfileNeighborsStackedLinePlots (CONTAINER_NEIGHBORS);
</script>



<!-- =================================================================== -->
<!--Gene Family members-->
<!--  ==================================================================== -->
<hr>
<h2>Gene Family Members</h2>
Expression profile of genes in the same family.&nbsp;

<?php

//KEEP
$sql_gene_family = "select gfa1.gene_family_assignment_id AS source_family_id,gfa1.gene_id AS source_gene_id,gfa1.family_label AS source_family, gfa2.gene_id AS target_gene_id,gfa2.family_label AS target_family, f.feature_id AS target_feature_id,f.uniquename AS target_uniquename,f.organism_id AS target_organism_id  FROM gene_family_assignment as gfa1 INNER JOIN gene_family_assignment as gfa2 ON gfa2.family_label = gfa1.family_label INNER JOIN chado.feature AS f ON f.feature_id = gfa2.gene_id WHERE f.organism_id = :organism_id AND gfa1.gene_id = :feature_id";
//print $sql_gene_family;
$query_gene_family = db_query($sql_gene_family, array(':organism_id' => $organism_id, ':feature_id' => $feature_id));

//$result = $result_gene_family->fetchAll();
$result = $query_gene_family->fetchCol(6); //A simple array. Col 6 is target_uniquename
//print(json_encode($result));
//print "<pre>"; print_r($result); print "</pre>";
$fam_members = $result;  //array
//print count($fam_members)."<br>";
$member_unique_names = "Member-unique-names: "."'".implode("','",$fam_members)."'"; // becomes quoted and , separated list
//print $member_unique_names."<hr>";

//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
if (count($fam_members) > 1) {
  $has_other_fam_members = TRUE;
  print "(No. of members in this species: ".count($fam_members).")"."<br/>";
} else {
  $has_other_fam_members = FALSE;
  print "(No. of members in this species: ".count($fam_members).")"."<br/>";
}

//Containers for Family members

  
  if ($has_other_fam_members) {

    echo "<fieldset style=\"display: inline-block; padding-left: 10px;\">";
    echo "<input type=\"radio\" name=\"display_type_family\" value=\"lineplot\"  onclick=\"drawFamilyMembersStackedLinePlots (CONTAINER_FAMILY);\"  checked > Line plot &nbsp;&nbsp;&nbsp;";
    echo "<input type=\"radio\" name=\"display_type_family\" value=\"heatmap\"  onclick=\"drawFamilyMembersHeatmap(CONTAINER_FAMILY);\"> Heatmap** &nbsp;&nbsp;&nbsp;";
    echo "</fieldset>";
    echo "(**The <strong>heatmap has links</strong> to the other family members)";

  } else {
    echo "<span style=\"font-size: large; color: DarkRed;\"> <br/>**** This genemodel has no other gene family members in this species.</span>";
  }


//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>


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

<br/><br/>
<div id="display_family_members_data"  style="width:850px;/*height:400px;*/position:relative;"></div>

<script>
var hasOtherFamMembers = <?php echo $has_other_fam_members; ?>;
</script>


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
  var G_fam_anchorText = []; //anchor text with url to gene page
  var V_fam = [];
  for (var x in gene_expval_fam_j) {
    G_fam.push(x);
    linkToGene = "<a  href=\"/feature/"+genus+"/"+species+"/gene/"+x+"#pane=geneexpressionprofile"+"\""+ ">"+x+"</a>";
    G_fam_anchorText.push(linkToGene);
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
  //ydata = G_fam; //original (just the gene name) and WORKS
  ydata = G_fam_anchorText; // anchor/link text with url to gene page
  zdata = Vall_fam;
  
  var data_fam_hmap = [
    {
      x:k,
      y: ydata,
      z: zdata,
      type: 'heatmap',
      colorscale: 'Rd',
      colorbar: {title:'TPM', titleside:'right'},
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
                //other options: , visible: 'legendonly'
        data_traces_for_scatter.push(trace);
      }
      
      graphicHeight = 130+(genes_fam.length)*30; //original
      //graphicHeight = 630+(genes.length)*30; //experimental
      //graphicHeight = 1000;
      //alert(graphicHeight);
      
      var layout = {  
        margin: { t: 0, l:30, b: 130},
        height: graphicHeight,
        yaxis: {title: 'TPM'}
      };
      
      Plotly.newPlot(container, data_traces_for_scatter, layout);  // w/ layout brings whole range into view
      //Plotly.newPlot(container, data_traces_for_scatter);  //w/o layout; only a few visible
      
      CONTAINER_FAMILY.on('plotly_click', function(){
        alert('You clicked this Plotly chart!');
      });
      
  }
</script>

<script>
  if (hasOtherFamMembers) {
    drawFamilyMembersStackedLinePlots (CONTAINER_FAMILY);
  }
  //Initial drawing is Lineplot for neighbors
  //drawFamilyMembersStackedLinePlots (CONTAINER_FAMILY);
</script>

