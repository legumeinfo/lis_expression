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
 *Oct 2018: Support for new ajax format,
 *          neighbors from new neighbor tables with correlation based filtering,
 *          and some UI for the above and improvements.
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
      //   Not able to achieve this in Drupal form (On page load, submit form with default/available values) ?? Check this observation again when multiple datasets available.
      
?>

<script>
  geneUniquename = "<?php  echo $gene_uniquename; ?>"; //Quote required
  geneName = "<?php echo $gene_name; ?>";
  genus = "<?php echo $genus; ?>";
  species = "<?php echo $species; ?>";
</script>



Available datasets:&nbsp;&nbsp;&nbsp;
<select id='dsetsel'   onchange="getExpressionData(this.value, geneUniquename, genus, species);">
  <!--<option value=''>Choose a dataset</option>-->
  <?php
    foreach ($datasets_r as $d) {
      //$options[$d['acn']] = $d['acn'].": ".$d['snm'];
      echo "<option value=" . $d['acn']. ">" . $d['snm'] . "</option>";      
    }
  ?>  
</select>
&nbsp;&nbsp;&nbsp;&nbsp;

<!--  =================== NAVIGATION TABS ========================  -->

<!-- style for the nav-tabs -->
<style>
  .navbar{list-style-type:none;margin:0;padding:0;overflow:hidden} /*Remove bullets, margin padding set by browser*/
  .navbar{background-color:#d5d5d5} /* the ul element is light-gray*/
  .navbar li{float:left} /*horizontal navbar by floating the <li>*/
  .navbar li a{display:block;padding:8px 8px;} /*links as block elements makes the whole link area clickable*/
  /*.navbar li{border:none;outline:none;width:100%}*/
  .navbar li a{color:#000;} /*initial color is black*/
  .navbar li a:hover{color:#000;background-color:#fff}  /*on hover: dark letters on gray bkgd*/
  /*.navbar li a:focus{color:#000;background-color:#fff}*/ /*on focus: bkgd white, letter black.  Focus makes highlight disappear after any other part of the page is clicked*/
  .active {background-color:#fff}  /* To make highlight persistent. JS function defined later in this file to make an item active(highlighted) on click. */
</style>

<!-- html for tabs -->

<div id="NavBar"  class="container">
  <!-- <link rel="stylesheet" href="https://www.w3schools.com/lib/w3.css"> -->
  <ul class="navbar">
  
    <li class="menu_item  active"> <a  title="Show expression of this gene"  href="javascript:void(0)"
        onclick="getExpressionData(datasetDropdown.value, geneUniquename, genus, species)">
        <b>THIS GENE <br/>  </b></a>
        </li>

    <li  class="menu_item"><a  title="Show expression of other genes with very similar expression pattern"  href="javascript:void(0)"  
        onclick="get_profile_neighbors_data ('display_data', datasetDropdown.value, geneUniquename, genus, species); ">
        <b>PROFILE <br/>NEIGHBORS</b></a>
        </li>
    
    <li  class="menu_item"><a  title="Show expression of gene family members"  href="javascript:void(0)" onclick="get_family_members_data ('display_data', datasetDropdown.value, geneUniquename, genus, species); "><b>GENE FAMILY <br/>MEMBERS</b></a>
        </li>
    
    <li  class="menu_item"><a  title="Show metdata for the dataset and its samples"  href="javascript:void(0)" onclick="getDatasetMetadata(datasetDropdown.value);"><b>DATASET<br/> DETAILS</b></a>
        </li>

    <!-- DELETE LATER-->
    <!--<li  class="menu_item"><a  title="Show expression of other genes with very similar expression pattern"  href="javascript:void(0)"  -->
    <!--    onclick="get_profile_neighbors_data ('display_data', datasetDropdown.value, geneUniquename, genus, species); ">-->
    <!--    <b>OBSOL. PROFILE <br/>NEIGHBORS (REMOVE)</b></a>-->
    <!--    </li>-->
    
    
  </ul>
</div>


<!--  GENERIC DATA DISPLAY DIV -->
<div id="generic">
  <!--CONTAINERS STARTS:<br/>-->
  <div id="control_display_type_radios"></div>
  <!-- Animation while loading -->
  <div id="wait" style="display:none;width:69px;height:89px;border:0 solid black;/*position:relative;top:50%;left:50%;padding:2px;*/">
    <img src="<?php echo '/'.drupal_get_path('module', 'lis_expression').'/demo_wait.gif'; ?>"   width="64" height="64" />
        <!--<br>Loading Data...-->
  </div>
  <div id="message_for_display_data"></div>
  <div id="display_data"  style="width:1050px;/*height:600px;*/"></div>
  <!--CONTAINER ENDS:<br/>-->
</div>

<!-- Script to load expression data for the current gene on page load   -->
<script>
  //Initial on-page-load with top item in dataset dropdown.
      //  **(KEEP THIS AT THE END OF THIS TEMPLATE FILE.
      //     After all the functions and data are defined)
  var datasetDropdown = document.getElementById('dsetsel');
  (function($) {
      getExpressionData(datasetDropdown.value, geneUniquename);     
  })(jQuery);  
</script>

<hr/>  <!--  Line after the single gene   
<!--<hr style="height: 5px; background-color: black;" />-->

<script>
  //Script for animation while loading
(function($){
    $(document).ajaxStart(function(){
        $("#wait").css("display", "block");
    });
    $(document).ajaxComplete(function(){
        $("#wait").css("display", "none");
    });
})(jQuery);
</script>

<!-- Script for highlighting the Nav-bar tabs -->
<script>
  //Keep this at the end after the navbar is loaded
  // Add active class to the current navbar (highlight it hoghlight persistent)

var navBar = document.getElementById("NavBar");
var menuItems = navBar.getElementsByClassName("menu_item");
for (var i = 0; i < menuItems.length; i++) {
    menuItems[i].addEventListener("click", function() {
        var current = document.getElementsByClassName("active");
        current[0].className = current[0].className.replace(" active", "");
        this.className += " active";
        //alert(this.className);
    });
}

</script>

    
<!--DEBUG-->
<?php //phpinfo() ?>


<!--
////////////////   SCRATCH & OBSOLETES  ///////////////
///////////////////////////////////////////////////////
-->



