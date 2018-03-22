<?php
/**
 *The template to display gene expression in a pane within the gene feature page
 *
 *06Dec2017: Trying Refactoring with capacity to handle multiple datasets per gene, etc.
 *    (trying): Most of code functionality in .module and only necessary part in .tpl.php
 *03Aug2017: Now data from ongenome schema
 *(Earlier from ongenomesomple schema, in templates .old1 and .old2)
 *
 */
?>

<?php
  //Pane Visibility:
  //      only 'in a gene page' and 'only if expression data is available'
  //
  //      **THIS SEC MUST BE AT TOP BEFORE ANY PAGE/NODE CONTENT IS CREATED**
  //      (Any page/node content makes the pane appear in all feature nodes TOC)
  //---------------------------------------------------------------------------

  //Quit if feature is not a gene or no expression data avail 
  if ($feature_type_name != 'gene')   {  
      return; //Quit if it isn't a gene page or if there isn't any expression dataset for this gene
  } elseif ( $dataset_count == 0 ) {
      return; //Quit if there isn't any expression dataset for this gene
  }
  //...........................................................................
?>

<script>
  //Page Title: Include gene name
  //(The default title is just the panel name in TOC. This replaces that with the gene name)
  titleLabel = "<?php echo "Expression (".$gene_name.")"; ?>";
  (function($) {
      $('.geneexpressionprofile-tripal-data-pane-title.tripal-data-pane-title').html(titleLabel);
    //jQuery('.figure-tripal-data-pane-title.tripal-data-pane-title').html(titleLabel);
  })(jQuery); 
</script>

<!--  ================================================================  -->

<!--  Available Datasets Dropdown  -->

<?php
  //Get the array of datasets for this genemodel
  $datasets_r = uniquename_to_datasets($gene_uniquename);  //In lis_expression_helper_functions.inc (From a gene_uniquename, returns array of available datasets(acc-no,shortname))
  //echo print_r($datasets_r)."<br/>";
  //** NOTE: ideally the page should load with the first option element.
  //   Not able to achieve this in Drupal form (On page load, submit form with default/available values) 
?>

<script>
  geneUniquename = "<?php  echo $gene_uniquename; ?>"; //Quote required
  geneName = "<?php echo $gene_name; ?>";
  genus = "<?php echo $genus; ?>";
  species = "<?php echo $species; ?>";
</script>


Available datasets:&nbsp;&nbsp;&nbsp;
<select id='dsetsel'   onchange="getExpressionData(this.value, geneUniquename, genus, species); /*get_profile_neighbors_data (this.value, geneUniquename)*/">
  <!--<option value=''>Choose a dataset</option>-->
  <?php
    foreach ($datasets_r as $d) {
      //$options[$d['acn']] = $d['acn'].": ".$d['snm'];
      echo "<option value=" . $d['acn']. ">" . $d['snm'] . "</option>";      
    }
  ?>  
</select>
&nbsp;&nbsp;&nbsp;&nbsp;

<!-- Link:  Dataset Metadata, collapsible  -->
<a    onclick="getDatasetMetadata(datasetDropdown.value); (function($) {
$(document).ready(function(){
$('fieldset#datasetMetadata').toggle('5000');
});
})(jQuery);"> &plusmn; Dataset Details </a>(Click to Expand & Collapse)
<script>
    var datasetDropdown = document.getElementById('dsetsel');
</script>
<br/>

<!--
===================TABS but???========================<br>
Also see if Drupal hook_menu tabs are feasible. (Can't find a way to position them here in the page)

The example code is in my '~/liswork/mymoduledevo/tripal_exp_profiles/theme/templates/tripal_feature_exp_profiles_master.tpl.php'
-->
<!--
<div class="w3-container">
  <link rel="stylesheet" href="https://www.w3schools.com/lib/w3.css">
  <ul class="w3-navbar w3-gray">
    <li><a href="javascript:void(0)" onclick="openCity('expression');"><b>DATASET<br/> DETAILS</b></a></li>
    <li><a href="javascript:void(0)" onclick="openCity('expression');"><b>NEIGHBORS</b></a></li>
    <li><a href="javascript:void(0)" onclick="openCity('expression');"><b>FAMILY MEMBERS</b></a></li>
    
  </ul>
</div>
-->

<!--//<<<<<<<  Trying TABS   <<<<<<<<<-->
<!--
<style>
  /*  LATER DELETE classes not necessary for here*/
.w3-navbar{list-style-type:none;margin:0;padding:0;overflow:hidden}
.w3-navbar li{float:left}
.w3-navbar li a,.w3-navitem,.w3-navbar li .w3-btn,.w3-navbar li .w3-input{display:block;padding:8px 16px}
.w3-navbar li .w3-btn,.w3-navbar li .w3-input{border:none;outline:none;width:100%}
.w3-navbar li a:hover{color:#000;background-color:#ccc}
.w3-navbar .w3-dropdown-hover,.w3-navbar .w3-dropdown-click{position:static}
.w3-navbar .w3-dropdown-hover:hover,.w3-navbar .w3-dropdown-hover:first-child,.w3-navbar .w3-dropdown-click:hover{background-color:#ccc;color:#000}
.w3-navbar a,.w3-topnav a,.w3-sidenav a,.w3-dropdown-content a,.w3-accordion-content a,.w3-dropnav a,.w3-navblock a{text-decoration:none!important}
.w3-navbar .w3-opennav.w3-right{float:right!important}.w3-topnav{padding:8px 8px}

.w3-navblock .w3-dropdown-hover:hover,.w3-navblock .w3-dropdown-hover:first-child,.w3-navblock .w3-dropdown-click:hover{background-color:#ccc;color:#000}
.w3-navblock .w3-dropdown-hover,.w3-navblock .w3-dropdown-click{width:100%}.w3-navblock .w3-dropdown-hover .w3-dropdown-content,.w3-navblock .w3-dropdown-click .w3-dropdown-content{min-width:100%}

.w3-topnav a{padding:0 8px;border-bottom:3px solid transparent;-webkit-transition:border-bottom .25s;transition:border-bottom .25s}
.w3-topnav a:hover{border-bottom:3px solid #fff}.w3-topnav .w3-dropdown-hover a{border-bottom:0}
.w3-opennav,.w3-closenav{color:inherit}.w3-opennav:hover,.w3-closenav:hover{cursor:pointer;opacity:0.8}

.w3-grey,.w3-hover-grey:hover,.w3-gray,.w3-hover-gray:hover{color:#000!important;background-color:#9e9e9e!important}
  
</style>
-->
<!--<div class="w3-container">-->
  <!-- <link rel="stylesheet" href="https://www.w3schools.com/lib/w3.css"> -->
<!--  <ul class="w3-navbar w3-gray">
    <li><a href="javascript:void(0)" onclick="openCity('expression');"><b>DATASET<br/> DETAILS</b></a></li>
    <li><a href="javascript:void(0)" onclick="get_profile_neighbors_data ('display_profile_neighbors_data', datasetDropdown.value, geneUniquename, genus, species); "><b>PROFILE <br/>NEIGHBORS</b></a></li>
    <li><a href="javascript:void(0)" onclick="openCity('expression');"><b>FAMILY <br/>MEMBERS</b></a></li>
    
  </ul>-->
<!--</div>-->

<!--//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>-->

<!-- TO DO: Provision for showing dataset details from ongenome database and link to bioproj, etc. -->
<!--DIV for Dataset Details:<br/>-->
<div  id="datasetDetails">
  <fieldset id="datasetMetadata"  style="display: none; padding-left: 0.5em;">
    
  </fieldset>
</div>
<script>    var datasetMetadataFieldset = document.getElementById('datasetMetadata');

//scratchPad
//(function($) {
//$(document).ready(function(){
//$('fieldset#cite_lis').toggle('5000');
//});
//})(jQuery);

</script>
<!--    Plan: 
      x   a. Define function in module to get dataset details (like fn get_exprsn_data_as_json ($acc, $gene)).
      x   b. Add another item to _menu_alter function with callback to the above function.
          c. Serve as collapsible content in tpl.php.
-->    
    


<!--  Select display_type; radio buttons -->
<fieldset style="display: inline-block; padding-left: 10px; position: relative; top:0px;">
<input id='radioLinePlot'  type="radio" name="display_type" value="plot"  onclick="drawLinePlot ('display_gene_data', jd_gene);"  checked > Line plot &nbsp;&nbsp;&nbsp;
<input type="radio" name="display_type" value="bar"  onclick="drawBarPlot('display_gene_data', jd_gene);"> Bar graph &nbsp;&nbsp;&nbsp;
<input type="radio" name="display_type" value="heatmap"  onclick="drawHeatmap('display_gene_data', jd_gene, geneName)" > Heatmap &nbsp;&nbsp;&nbsp;
<input type="radio" name="display_type" value="table" onclick="makeTableWithJsonData ('display_gene_data', jd_gene);"> Table <br/>
</fieldset>

<!-- ----------------------------------------------------------------->
<!--<br/>-->
<!-- DIVs for display of this gene's data-->
<div id="display_gene_data"  style="width:850px;/*height:400px;*/"></div>

<hr/>  <!--  Line after the single gene   -->


                                <!--  SECTION: PROFILE NEIGHBORS  -->
                                <!--  --------------------------  -->

<h2>Profile Neighbors</h2>
<!-- Genes with similar expression profile (with r &#8805 0.8); first 20 for now.&nbsp;&nbsp;&nbsp;<br/> -->
Genes with similar expression profile (with r &#8805 0.8); top 50 for now.&nbsp;&nbsp;&nbsp;<br/>
<div id="display_profile_neighbors_data"  style="width:850px;/*height:400px;*/"></div>
<div id="display_profile_neighbors_data2"  style="width:850px;/*height:400px;*/"></div>


<hr/>
<h2>Family Members</h2>
Expression of gene family members in this datset.<br/><br/>
<div id="display_family_members_data"  style="width:850px;/*height:400px;*/position:relative;"></div>
<div id="display_family_members_data2"  style="width:850px;/*height:400px;*/position:relative;"></div>







<!-----------------  JS Functionalities  -------------------------------------------------------->

  <!--  Plotly library load -->
<!--<script src="https://cdn.plot.ly/plotly-latest.min.js"></script> Now loaded in .module from ../js/, not cdn-->
<!--   /js/lis_expression_jsFunctions.js loaded in .module at the start  -->



<script>
  //For testing, DELETE LATER
  function putInDiv (divID, jsonObj) {
    jQuery('#' + divID).html(jsonObj);
  }
</script>


<script>
  //Initial on-page-load with top item in dataset dropdown.
      //  **(KEEP THIS AT THE END OF THIS TEMPLATE FILE.
      //     After all the funcyions and data are defined)
  var datasetDropdown = document.getElementById('dsetsel');
  (function($) {
      getExpressionData(datasetDropdown.value, geneUniquename);
      get_profile_neighbors_data ("display_profile_neighbors_data", datasetDropdown.value, geneUniquename, genus, species);
      get_family_members_data ("display_family_members_data", datasetDropdown.value, geneUniquename, genus, species);
      
  })(jQuery);  
</script>

<script>
  //Initial loading of neighbors
    //get_profile_neighbors_data ("display_profile_neighbors_data", datasetDropdown.value, geneUniquename, genus, species); 
      //get_fanily_members_data ("display_family_members_data2", datasetDropdown.value, geneUniquename, genus, species); // fn (container, dsetAcc, geneName, genus, species)
    //get_family_members_data ("display_family_members_data", datasetDropdown.value, geneUniquename); // fn (container, dsetAcc, geneName, genus, species)
  
  
</script>

<!--xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-->
<!--DEBUG-->
<?php //phpinfo() ?>


<!--
////////////////   SCRATCH & OBSOLETES  ///////////////
///////////////////////////////////////////////////////
-->

<!--  Get expression data via jQ-ajax (trying, learn the trick)  -->
<!--
<script>
  //Need gene_uniquename, dataset_acc: Get data as json preferably
/*  
  function getExpressionData(dsetAcc, gene) {
    //Nedd a php script to query db and spit json
    //console.log(dsetAcc.value, " : for ", gene);
    console.log(dsetAcc, " : for ", gene);
    //dsetAcc = dsetAcc.value;
    gene = gene;
    
    document.getElementById('radioLinePlot').checked = true; //Reset display type to line plot after dataset is selected.

        //FAILS. Path is interpreted differently. https://localhost:5800/feature/Phaseolus/vulgaris/gene/test.php 404 (Not Found)
    (function($){
                
          jQuery.ajax({ type: "GET",
                   //url: "includes/lis_expression_test.inc",                 
                   //url: "lis_expression/"+dsetAcc+"/"+gene+"/json",
            url: "/lis_expression/"+dsetAcc+"/"+gene+"/json",
                   //url: "/lis_expression_ajax/"+dsetAcc+"/"+gene+"/json",
                   //url: "/www/drupal7/sites/all/modules/lis_expression/lis_expression_test.inc",
                   
                   //async: true, //is default
                   //dataType: 'json',
                   //contentType:"application/json; charset=utf-8",
                   //cache: false,
                   //data: {dset_acc: dsetAcc, gene : gene},
                   
                   success : function(response)
                   {
                       responseString = response; //captured into another var; typeof(responseString) is string
                       jd_gene = JSON.parse(responseString); //jd_gene: JasonData_forGene
                       console.log(responseString);
                       drawLinePlot ('display_gene_data', jd_gene); // in /js/lis_expression_jsFunctions.js'
                       //drawBarPlot ('div002', jd_gene);
                     
                   }
          });   //ajax        
      
    })(jQuery); //
        
  } //getExpressionData()
*/  
</script>
-->


