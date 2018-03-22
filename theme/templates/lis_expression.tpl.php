<?php
/**
 *The template to display gene expression in a pane within the gene feature page
 *
 *06Dec2017: Trying Refactoring with capacity to handle multiple datasets per gene, etc.
 *    (trying): Most of code functionality in .module and only necessary part in .tpl.php
 *03Aug2017: Now data from ongenome schema
 *(Earlier from ongenomesomple schema, in templates .old1 and .old2)
 *
 *March 2018: Support for multiple datasets; Ajax calls towards webservice; Metadata presentation; Tabs for different sections
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

<!--Commented out after Tabs were used.-->
<!--<a    onclick="getDatasetMetadata(datasetDropdown.value); (function($) {
$(document).ready(function(){
$('fieldset#datasetMetadata').toggle('5000');
});
})(jQuery);"> &plusmn; Dataset Details </a>(Click to Expand & Collapse)
<script>
    var datasetDropdown = document.getElementById('dsetsel');
</script>
<br/>
-->
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

<style>
  /*  LATER DELETE classes not necessary for here*/
.w3-navbar{list-style-type:none;margin:0;padding:0;overflow:hidden}
.w3-navbar li{float:left}
.w3-navbar li a,.w3-navitem,.w3-navbar li .w3-btn,.w3-navbar li .w3-input{display:block;padding:8px 8px}
.w3-navbar li .w3-btn,.w3-navbar li .w3-input{border:none;outline:none;width:100%}
.w3-navbar li a{color:#000;} /*trying initial color*/
.w3-navbar li a:hover{color:#000;background-color:#ccc}
.w3-navbar li a:focus{color:#000;background-color:#fff} /*trying focus*/
.w3-navbar .w3-dropdown-hover,.w3-navbar .w3-dropdown-click{position:static}
.w3-navbar .w3-dropdown-hover:hover,.w3-navbar .w3-dropdown-hover:first-child,.w3-navbar .w3-dropdown-click:hover{background-color:#ccc;color:#000}
.w3-navbar a,.w3-topnav a,.w3-sidenav a,.w3-dropdown-content a,.w3-accordion-content a,.w3-dropnav a,.w3-navblock a{text-decoration:none!important}
.w3-navbar .w3-opennav.w3-right{float:right!important}.w3-topnav{padding:8px 8px}

.w3-navblock .w3-dropdown-hover:hover,.w3-navblock .w3-dropdown-hover:first-child,.w3-navblock .w3-dropdown-click:hover{background-color:#ccc;color:#000}
.w3-navblock .w3-dropdown-hover,.w3-navblock .w3-dropdown-click{width:100%}.w3-navblock .w3-dropdown-hover .w3-dropdown-content,.w3-navblock .w3-dropdown-click .w3-dropdown-content{min-width:100%}

.w3-topnav a{padding:0 8px;border-bottom:3px solid transparent;-webkit-transition:border-bottom .25s;transition:border-bottom .25s}
.w3-topnav a:hover{border-bottom:3px solid #fff}.w3-topnav .w3-dropdown-hover a{border-bottom:0}
.w3-opennav,.w3-closenav{color:inherit}.w3-opennav:hover,.w3-closenav:hover{cursor:pointer;opacity:0.8}

.w3-grey,.w3-hover-grey:hover,.w3-gray,.w3-hover-gray:hover{color:#000!important;background-color:#d5d5d5!important} /*9e9e9e*/
  
</style>

<div class="w3-container">
  <!-- <link rel="stylesheet" href="https://www.w3schools.com/lib/w3.css"> -->
  <ul class="w3-navbar w3-gray">
    <li><a  title="Show expression of this gene"  href="javascript:void(0)"
        onclick="getExpressionData (datasetDropdown.value, geneUniquename, genus, species); ">
        <b>THIS GENE</b></a></li>
    
    <li><a  title="Show expression of other genes with very similar expression pattern"  href="javascript:void(0)"  
        onclick="get_profile_neighbors_data ('display_data', datasetDropdown.value, geneUniquename, genus, species); ">
        <b>PROFILE <br/>NEIGHBORS</b></a></li>
    
    <li><a  title="Show expression of gene family members"  href="javascript:void(0)" onclick="get_family_members_data ('display_data', datasetDropdown.value, geneUniquename, genus, species); "><b>GENE FAMILY <br/>MEMBERS</b></a></li>
    
    <li><a  title="Show metdata for the dataset and its samples"  href="javascript:void(0)" onclick="getDatasetMetadata(datasetDropdown.value);"><b>DATASET<br/> DETAILS</b></a></li>
    
  </ul>
</div>


<!--  GENERIC DATA DISPLAY DIV -->
<div id="generic">
  <!--CONTAINER STARTS:<br/>-->
  <div id="control_display_type_radios"></div>
  <div id="display_data"  style="width:850px;/*height:400px;*/"></div>
  <!--CONTAINER ENDS:<br/>-->
</div>


<script>
  //Initial on-page-load with top item in dataset dropdown.
      //  **(KEEP THIS AT THE END OF THIS TEMPLATE FILE.
      //     After all the functions and data are defined)
  var datasetDropdown = document.getElementById('dsetsel');
  (function($) {
      getExpressionData(datasetDropdown.value, geneUniquename);
      //aftertabs
      //get_profile_neighbors_data ("display_profile_neighbors_data", datasetDropdown.value, geneUniquename, genus, species);
      //get_family_members_data ("display_family_members_data", datasetDropdown.value, geneUniquename, genus, species);
      
  })(jQuery);  
</script>

<!--<div id="wait" style="display:none;width:69px;height:89px;border:1px solid black;position:absolute;top:50%;left:50%;padding:2px;"><img src='demo_wait.gif' width="64" height="64" /><br>Loading..</div>-->
<hr/>  <!--  Line after the single gene   -->
<!--<hr style="height: 5px; background-color: black;" />-->

    
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


