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


<?php //================================================================  ?>

<div id='dataset-list'>
  <?php
    print drupal_render(drupal_get_form('lis_expression_form_datasets_dropdown',$gene_uniquename)); //WORKS(renders the dropdown in the template)
      //the formid is defined in .module fn lis_expression_form_datsets_dropdown ()
    print "<hr/>";
  ?>
  
</div> <!-- dataset_list -->

    
  
<div id='main-div'>
  Main Div:
  <p>Before changing dropdown.</p>
  
  <?php echo "Dataset shortname (from preprocess in template): ".$dataset_shortname; ?>
  
</div> <!-- main-div -->

<!--  --------------------------------------------------------------------------  -->

<div id='extra-div'> 
  <hr style="border-width: 3px;">
  Extra Div: </br>
  <?php
    print $dataset_count."."."<b>"."&nbsp;&nbsp;".$dataset_accession_no.": "."</b>".$dataset_shortname."<br/>";
    print "xyz = $xyz"; //fails; this div content is not updated on form submit
  ?>

</div>

  <br/>
<?php
  drupal_get_form('lis_expression_testform');
  $options = array('cicar1'=>'Ds-1', 'phavu1'=>'Ds-2', 'cajca1'=>'Ds-3');
  
?>
  A test form:
  <form id='lis_expression_testform'   target='_self'>
     <select  name='s1' class="form-select ajax-processed" onchange="getval(this);"> 
  <!--  <select  name='s1' >  -->
        <option value= "<?php echo array_keys($options)[0];  ?>" > Ds-1
          
        </option>
        <option value= "<?php echo array_keys($options)[1];  ?>" >Ds-2
          
        </option>
        <option value= "<?php echo array_keys($options)[2];  ?>"  >Ds-3
          
        </option>
     </select>
  <!--  <input type="hidden" name="form_id" value="lis_expression_testform">-->
    
    
  </form>


<script>
// This doesn't work, WHY ?????????????????
/*
    $('s1').change(function($){
    alert("Submitted");
    })(jQuery);
*/
//

function getval(sel)
{
    alert(sel.value);
}

</script>  
  
  
<script>
// This doesn't work, WHY ?????????????????
//      (submit the dropdown form on first page load)
 // $(document).ready(function () {
    //(function($) {
    // $('#lis_expression_form_datasets_dropdown').submit();
    //})(jQuery);
//}
// 
</script>  
  
  



<?php
  //print '<pre>';
  //var_dump(get_defined_vars()); 
  //print '</pre>';
?>