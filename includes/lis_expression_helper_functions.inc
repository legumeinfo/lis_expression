<?php

/**
 *@file
 *lis_expression_helper_functions.inc
 *Goal: Hold helper functions for the lis_expression module
 */

 /*
  *1. function uniquename_to_datasets($gene)
  *2. function get_exprsn_data_as_json ($acc, $gene)
  *3. function get_dataset_metadata_as_json ($acc)
  *4. function get_profile_neighbors_expression_json ($acc, $gene)
  *5. function get_family_members_expression_json ($acc, $gene)
  *
  */

 
 
 

/**
 *1. Function  uniquename_to_datasets
 *  From a gene_uniquename, makes an array of available datasets(uniquename,name,acc-no,dataset_id,shortname,etc.)
 */

      //Used in lis_exp.tpl.php  
  function uniquename_to_datasets($gene) {
      //From a gene_uniquename,
      //returns array of available datasets(acc-no,shortname)
      
      //DELETE//  //$sql = "SELECT d.accession_no, d.shortname, FROM ongenome.dataset d, ongenome.expressiondata e, ongenome.genemodel gm  WHERE d.dataset_id=e.dataset_id  and e.genemodel_id = gm.genemodel_id and gm.chado_uniquename = :gene_uniquename";  //ongenome
      $sql = "select d.dataset_id, d.accession_no,d.shortname, d.name FROM ongenome.genemodel gm, ongenome.genome g, ongenome.dataset d WHERE gm.genome_id=g.genome_id and d.genome_id=g.genome_id and gm.chado_uniquename = :gene_uniquename"; //'cicar.ICC4958.v2.0.Ca_00006'"; 
      $queried = db_query($sql, array(':gene_uniquename' => $gene));
      //print_r($queried);
      
      $datasets_r=array();
      //print_r($datasets_r);
      foreach ($queried as $rec) {
          //$count = $count + 1;
          $ds_r=array();
          $ds_r['acn'] = $rec->accession_no;
          $ds_r['snm'] = $rec->shortname;
        
          $datasets_r[] = $ds_r;   
      }  //4each
      
      //drupal_set_message(print_r($datasets_r));
      //print_r($datasets_r);
      return $datasets_r; //return an array of assoc. arrays of datasets related to this gene
    
  } //fn uniquename_to_datasets()



// GET JSON FROM AJAX CALLS: gene-Exprsn, dataset-samples-metadata
//-------------------------
/*2. function get_exprsn_data_as_json ($acc, $gene)
 *
 */

  function get_exprsn_data_as_json ($acc, $gene) {
    //Given gene-uniquename and dataset-accession-no:
    // $gene: ongenome.genemodel.chado_uniquename; $acc: ongenome.dataset.accession_no
    // Callback for hook_menu_alter 'lis_expression/%/%/json' (/lis_expression/phavu1/Phvul.001G000600.v1.0/json)
    
    //print "NOW: $acc $gene";
  
      $sql_expression = "SELECT d.shortname as d_shortname, s.sample_uniquename as s_shortname, gm.genemodel_name,  e.exp_value  FROM  ongenome.dataset as d, ongenome.sample as s , ongenome.dataset_sample ds, ongenome.expressiondata as e, ongenome.genemodel as gm WHERE  d.dataset_id = ds.dataset_id and d.dataset_id=e.dataset_id and e.dataset_sample_id=ds.sample_id and ds.sample_id = s.sample_id and e.genemodel_id = gm.genemodel_id and gm.chado_uniquename = :gene_uniquename and d.accession_no = :accession_no";
      
      //This combination works the best with
      //     `url: "/lis_expression/"+dsetAcc+"/"+gene+"/json",` in
      //      template ajax call.  The initial`/` is required to interprete the path correctly
      $result_expression = db_query($sql_expression, array(':gene_uniquename' => $gene, ':accession_no' => $acc));
      $result_expression_j = json_encode($result_expression->fetchAllKeyed(1,3));  //j2 in old code
      print $result_expression_j;  //RETURNS a JS 'string' after ajax call
          //echo $result_expression_j;  //perhaps ok like print
          //return $result_expression_j; //FAILS: returns undesirable html and the json
      
      drupal_exit();  //to stop further execution in Drupal
  
  }  //get_exprsn_data_as_json()



/*
 *3. function get_dataset_metadata_as_json ($acc)
 *  (Function to Get dataset-samples metadata in json format in one ajax call)
 *
 */


  function get_dataset_metadata_as_json ($acc) {
      
      //Given a dataset acc, get all iots metadata including sample details as json
          //$acc: ongenome.dataset.accession_no
      // Callback for hook_menu_alter: 'lis_expression/dataset_metadata/%/json' (/lis_expression/dataset_metadata/phavu1/json)
      
      $sql_dataset = "SELECT   ds.dataset_id AS ds_datasetid, ds.accession_no AS ds_accession, ds.shortname AS ds_shortname, ds.name AS ds_name, ds.description AS ds_description, src.name AS src_name, src.origin AS src_origin, src.description AS src_description, src.bioproj_acc AS src_bioproj_acc, src.bioproj_title AS src_bioproj_title, src.bioproj_description AS src_bioproj_description, src.sra_proj_acc AS src_sra_proj_acc, src.geo_series AS src_geo_series, gn.name AS gn_name, gn.shortname AS gn_shortname, gn.description AS gn_description, gn.source AS gn_source, gn.build AS gn_build, gn.annotation AS gn_annotation, m.name AS m_name, m.shortname AS m_shortname, m.version AS m_version, m.description AS m_description, m.details AS m_details, o.name AS o_name, o.genus AS o_genus, o.species AS o_species, o.subspecies AS o_subspecies, o.cultivar_type AS o_cultivar_type, o.line AS o_line, o.abbrev AS o_abbrev  FROM ongenome.dataset AS ds, ongenome.datasetsource AS src, ongenome.genome AS gn, ongenome.method AS m, ongenome.organism AS o WHERE src.datasetsource_id=ds.datasetsource_id AND gn.genome_id=ds.genome_id AND m.method_id=ds.method_id AND o.organism_id=gn.organism_id AND ds.accession_no = :accession_no";
      //print $sql_dataset;  //print $acc;
      
      $result_dataset = db_query($sql_dataset, array(':accession_no' => $acc,));
              //print gettype($result_dataset);
              //$result_dataset_j = json_encode($result_dataset->fetchAllKeyed(1,3));
      $result_dataset_j = json_encode($result_dataset->fetchAllAssoc('ds_accession')); //
              //Gives assoc array: {"phavu1":{"datasetid":"2","datasetaccession":"phavu1","shortname":"Bean expression atlas Negro jamapa", ....}}.  `After JSON.parse`.phavu1['shortname']=Bean expression atlas Negro jamapa
              //$result_dataset_j = json_encode($result_dataset->fetchAll()); // gives array and can't get values by field name.
      
  //  Getting sample-json-data in this ajax call   
      $sql_samples = "SELECT s.sample_id, s.datasetsource_id, s.organism_id, s.sample_uniquename, s.name, s.shortname, s.description, s.age, s.dev_stage, s.plant_part, s.treatment, s.other_attributes, s.ncbi_accessions FROM ongenome.sample AS s, ongenome.dataset_sample AS dsm, ongenome.dataset AS ds, ongenome.organism AS o WHERE s.sample_id=dsm.sample_id AND dsm.dataset_id=ds.dataset_id AND o.organism_id=s.organism_id AND ds.accession_no = :accession_no  ORDER BY s.sample_id";
      $result_samples = db_query($sql_samples, array(':accession_no' => $acc,));
      $result_samples_j = json_encode($result_samples->fetchAllAssoc('sample_id')); // a string
      $result_samples_j = "{\"samples\": " . $result_samples_j .  "}";  //into a valid json str.
      
      //Combine result and sample jsons
      $dataset_samples_combo_j =  json_encode(array_merge(json_decode($result_dataset_j, true),  json_decode($result_samples_j, true)));
      $dataset_samples_combo_j = "{\"metadata\": " . $dataset_samples_combo_j . "}"; //into a valid json with a name, 'metadata'
  
      print $dataset_samples_combo_j;
      //print $result_dataset_j;  // yields string and needs JSON.parse when harvested elsewhere to make an obj.
      
      drupal_exit();  //to stop further execution in Drupal
      
  }  //get_dataset_metadata_as_json()



/*
 *Function:
 *4. get_profile_neighbors_expression_json ()
 *
 *  Callback from .module, _menu_alter, 'lis_expression/profile_neighbors/%/%/json'
 *
 *
 */

  function get_profile_neighbors_expression_json ($acc, $gene) {
          //Given a gene(uniquename) and a dataset acc (from ongenome.dataset), get the profile neighbors list from ongenome.
          
      $sql_profile_neighbors = "SELECT genemodel_uniquename, profile_neighbors  " .
                               " FROM ongenome.profileneighbors"."_".$acc. "  ". 
                               " WHERE genemodel_uniquename = :gene_uniquename ";
      
      $query_profile_neighbors = db_query($sql_profile_neighbors, array(':gene_uniquename' => $gene));
      $result = $query_profile_neighbors->fetchCol(1); //A simple array. Col 1 has profile neighbors as a ';' separated string
                  //$result = $query_profile_neighbors->fetchAllKeyed(0, 1); //
                  //$result = $query_profile_neighbors->fetchAllAssoc('genemodel_uniquename'); //
                  //print_r($result);
      
      $profileneighbors_entire_string = $result[0];
      $neighbors_list_r =  explode(";", $profileneighbors_entire_string); // _r into an array
      $neighbors_list_r = array_slice($neighbors_list_r, 0, 25);

      //Filtering out 'NA's and empties
      function NA_match($item) {  #If there is NA* in value return false
          //preg_match('/NA/', $item);
          if (preg_match('/NA|^[\s]*[\s]*$/', $item)) {  //is 'NA' OR 'empty OR white spaces' 
          //if (preg_match('/NA/', $item)) {  
              //print "F ";
              return FALSE;
          } else {return TRUE;}
      }
      $neighbors_list_r = array_filter($neighbors_list_r, "NA_match"); //Now a filtered array
      
        
      $profneighbors_hr = array(); //initiate assoc array(hr for hash array)

      foreach ($neighbors_list_r as $pair) {
          //print $pair."<br/>";
          $neighbor_corr = explode(":", $pair);
          $neighbor = $neighbor_corr[0];  // the neighbor gene
          $corr = $neighbor_corr[1];  // and its corr value
              //Forming an assoc array of this neighbor with corr and expr as keys.
              //  'expr' is NULL for now. (Later expr will be injected into this array
              //  with  sample-name and expr-value pairs)
          
          //print $acc;
          // Expression array (sample=>value)
          $sql_exp =  "SELECT ".
                        "d.shortname AS d_shortname, s.sample_uniquename AS s_shortname, "
                        ." gm.genemodel_name, e.exp_value "
                      ." FROM "
                        ." ongenome.dataset AS d, ongenome.sample AS  s, "
                        ." ongenome.expressiondata AS e, ongenome.dataset_sample AS ds, "
                        ." ongenome.genemodel AS gm "
                      ." WHERE "
                        ." d.dataset_id = ds.dataset_id AND d.dataset_id=e.dataset_id "
                        ." AND e.dataset_sample_id=ds.sample_id AND s.sample_id=ds.sample_id "
                        ." AND e.genemodel_id=gm.genemodel_id AND gm.chado_uniquename = :member"; //AND d.accession_no = :acc"; //print "$acc";
                        //AND d.accession_no = :acc
                        //QUESTION: it get's a gene's expr data without the accession no ??. It looks alright because there is only one accession; might create proble when >1 datasets. Check if suspicion is right??
                        
          $query_exp = db_query($sql_exp, array(':member' => $neighbor))->fetchAllKeyed(1,3);//ACTUAL
          //$query_exp = db_query($sql_exp, array(':member' => $neighbor, ':acc' => $acc))->fetchCol(1,3); //':acc' => $acc, 
          
          //Get gene common name, instead of uniquename, for compiling list for a link to mines
          $sql_gene_name = "SELECT gm.genemodel_name "
                            . " FROM ongenome.dataset AS d, ongenome.genemodel AS gm "
                            . " WHERE gm.genome_id=d.genome_id AND  d.accession_no= :accession_no "
                            . " AND gm.chado_uniquename= :gene_uniquename";
          $query_gene_name = db_query($sql_gene_name, array(':accession_no' => $acc, ':gene_uniquename' => $neighbor ))->fetchField();

          //Inject 'name', 'corr' and 'expr' into the main array with key as $neighbor
          $profneighbors_hr[$neighbor] = array('name' => $query_gene_name, 'corr' => $corr, 'expr' => $query_exp);

      }  //foreach
      
      //print "<pre>";print_r($profneighbors_hr);print "</pre>";print "<hr>"; //debug keep
      //print_r($profneighbors_hr['cicar.ICC4958.v2.0.Ca_18100']); 


      //print_r($neighbors_list_r);print "<br>count: ".count($result);
      //print "<br>";
      
    
      //print "<hr>". json_encode($profneighbors_hr['cicar.ICC4958.v2.0.Ca_18100']['expr']);
      

      
      print "{\"".$gene."\":".json_encode($profneighbors_hr)."}"; //ACTUAL
      
      
  
      drupal_exit();  //to stop further execution in Drupal
    
  }  //get_profile_neighbors_expression_json ()
  

  /*
 *Function:
 *5. get_family_members_expression_json ()
 *
 *  Callback from .module, _menu_alter, ''lis_expression/genefamily_members/%/%/json''
 *      (..../lis_expression/genefamily_members/phavu1/Phvul.001G000600.v1.0/json)
 *
 */
  
  function get_family_members_expression_json ($acc, $gene) {
      // Given a dataset acc (from ongenome.dataset) and a gene(uniquename),
      // get the gene family members from Chado and their expression
      // in json format from ongenome.
      //Return empty if no family members **IMP
      
          //Get Chado.organism_id and feature_id for next sql query
      $sql_feature_org_ids = "SELECT feature_id, organism_id FROM chado.feature WHERE uniquename = :gene_uniquename";
      $result_feature_org_ids = db_query($sql_feature_org_ids, array(':gene_uniquename' => $gene))->fetchAll();
      
      foreach ($result_feature_org_ids as $record) {
          $feature_id = $record->feature_id;
          $organism_id = $record->organism_id;
      }

          //Get list of gene-family members
      $sql_gene_family = "SELECT gfa1.gene_family_assignment_id AS source_family_id, gfa1.gene_id AS source_gene_id, gfa1.family_label AS source_family, gfa2.gene_id AS target_gene_id, gfa2.family_label AS target_family, f.feature_id AS target_feature_id, f.uniquename AS target_uniquename, f.organism_id AS target_organism_id  FROM gene_family_assignment AS gfa1 INNER JOIN gene_family_assignment AS gfa2 ON gfa2.family_label = gfa1.family_label INNER JOIN chado.feature AS f ON f.feature_id = gfa2.gene_id WHERE f.organism_id = :organism_id AND gfa1.gene_id = :feature_id";
      $query_gene_family = db_query($sql_gene_family, array(':organism_id' => $organism_id, ':feature_id' => $feature_id));

                                                  //$result = $query_gene_family->fetchAll();
      $result = $query_gene_family->fetchCol(6); //A simple array. Col 6 is target_uniquename
//print_r($result);
      $fam_members = $result;  //array
      //Return Empty if no members
      if (!$fam_members) {
          return;
      }

      //NOT USING//$member_unique_names = "{"."\"FamMemberUniqueNames\":". "[" ."\"".implode("\",\"",$fam_members)."\"". "]" . "}"; // Now a valid json structure of just the member list (no expression data)
      
      
      //Get expression of fam-members
      
      foreach ($fam_members as $member) {
          //print "<br>".$member."<br/>";
      
          //$sql_exp_fam = "select d.shortname as d_shortname,s.shortname as s_shortname,e.genemodel_id,e.exp_value  from ongenomesimple.dataset as d, ongenomesimple.sample as s ,ongenomesimple.expressiondata as e where d.dataset_id = s.dataset_id and d.dataset_id=e.dataset_id and e.dataset_sample_id=s.sample_id and e.genemodel_id = "."'".$member."'";
          $sql_exp_fam = "SELECT d.shortname AS d_shortname, s.sample_uniquename AS s_shortname, gm.genemodel_name, e.exp_value  FROM ongenome.dataset AS d, ongenome.sample AS s ,ongenome.expressiondata AS e, ongenome.dataset_sample AS ds, ongenome.genemodel AS gm WHERE d.dataset_id = ds.dataset_id and d.dataset_id=e.dataset_id and e.dataset_sample_id=ds.sample_id and s.sample_id=ds.sample_id and e.genemodel_id=gm.genemodel_id and d.accession_no = :accession_no and gm.chado_uniquename = :member";
          //print $sql_exp."<br>";
          //$query_exp_fam = db_query($sql_exp_fam)->fetchAllKeyed(1,3);
          $query_exp_fam = db_query($sql_exp_fam, array(':member' => $member, ':accession_no' => $acc))->fetchAllKeyed(1,3);
          //print_r($query_exp)."<br>";
          $gene_expval_fam_r[$member] = $query_exp_fam;
      } //foreach  

      $gene_expval_fam_j = json_encode($gene_expval_fam_r);  // a valid json
      
      //print $member_unique_names; //only member-list, no expr values
      print "{" . "\"" .$gene. "\"" . ":" . $gene_expval_fam_j . "}";  //Add original gene name at beginning of json 
   
  }  //get_family_members_expression_json
  
  

  /*
  * Just returns the theme for the lis_expression summary page.
  * From hook_menu  in 'lis_expression/all'
  */
  function summarize_all_lis_expression_callback() {
      
      return theme ('lis_expression_all', array());
      
  }   //function summarize_all_lis_expression()
  
  
  /*
  * Just returns the theme for the lis_expression demo page for workshop.
  * From hook_menu in 'lis_expression/demo'
  */
  function lis_expression_demo_callback () {
      return theme ('lis_expression_demo', array());
  }















                    ///////////////////////////////////////////////////////////
                    /////////////    SCRATCH & OBSOLETES     //////////////////
                    ///////////////////////////////////////////////////////////


/**
 *Function create_html_main_div_genelevel_data
 *    Generate html content for the current genel-evel expression data.
 *    Should include table, bar chart, heatmap, line chart
 *
 */


/*
function create_html_main_div_genelevel_data ($gene, $acc) {
  //Given gene-uniquename and dataset-accession-no:
  //Generate html content for the current genel-evel expression data.
  //Should include table, bar chart, heatmap, line chart
  // $gene: ongenome.genemodel.chado_uniquename; $acc: ongenome.dataset.accession_no
  
    $html  = 'Gene level expression data as line-chart, bar-plot, heatmap and table here ';
    $html .= 'for '. $gene. ' in '. $acc . '.<br/>';
    
    

    $html .= <<<QUOTING_HTML
    <!--  Select display_type; radio buttons -->
    <fieldset style="display: inline-block; padding-left: 10px;">
    <input type="radio" name="display_type" value="plot"  onclick="drawLinePlot (CONTAINER_GENE);"  checked > Line plot &nbsp;&nbsp;&nbsp;
    <input type="radio" name="display_type" value="bar"  onclick="drawBarPlot(CONTAINER_GENE);"> Bar graph &nbsp;&nbsp;&nbsp;
    <input type="radio" name="display_type" value="heatmap"  onclick="drawHeatmap(CONTAINER_GENE)" > Heatmap &nbsp;&nbsp;&nbsp;
    <input type="radio" name="display_type" value="table" onclick="document.getElementById('display_gene_data').innerHTML = div_content_table;"> Table <br/>
    </fieldset>
    <div>For gene model: <b>".$gene."</b></div> <!-- **May have to remove it -->
    
    <!-- DIVs for display of this gene's data-->
    <div id="display_gene_data"  style="width:850px;"></div>
    <hr/>  <!--  Line after the single gene   -->
    
    <script>
    //document.getElementById('display_gene_data').innerHTML = div_content_table;
    </script>
    
<!-- Expression data from db_query into JSON format -->
QUOTING_HTML;


    $sql_expression = "SELECT d.shortname as d_shortname, s.sample_uniquename as s_shortname, gm.genemodel_name,  e.exp_value  FROM  ongenome.dataset as d, ongenome.sample as s , ongenome.dataset_sample ds, ongenome.expressiondata as e, ongenome.genemodel as gm WHERE  d.dataset_id = ds.dataset_id and d.dataset_id=e.dataset_id and e.dataset_sample_id=ds.sample_id and ds.sample_id = s.sample_id and e.genemodel_id = gm.genemodel_id and gm.chado_uniquename = :gene_uniquename and d.accession_no = :accession_no";    
    $result_expression = db_query($sql_expression, array(':gene_uniquename' => $gene, ':accession_no' => $acc));
   


  
    //Expression data into JSON format in php
    //$result_expression 'HAD' expression data from db_query BUT NEEDS TO BE REPEATED
    //$result_expression = db_query($sql_expression, array(':gene_uniquename' => $gene_uniquename));
    $result_expression_j = json_encode($result_expression->fetchAllKeyed(1,3));  //j2 in old code
    //print "json k:v : ".$result_expression_j; //gets sample_uniquename(1) and exp_value(3)
    
    
    $html .= <<<QUOTING_HTML_1B
      <div id='ZZZZ'></div>
      
        <script type="text/javascript">
          var jd_gene = <?php echo $result_expression_j; ?>;
          
        </script>
        <p>HELLO YYY</p>
      
QUOTING_HTML_1B;
//Problem in above html:  The script tag doesn't appear for some reason and is removed automatically.
    
    //Create divtag for genelevel data   ***DO THIS FIRST FOR TESTING
    $html .= "<!-- DIVs for display of this gene's data-->";
    $html .= "<div id=\"display_gene_data\"  style=\"width:850px;\"></div>";
    //$html .= "<div id=\"display_gene_data\"  style=\"width:850px;height:400px;\"></div>"; //not good
    $html .= "<hr/>  <!--  Line after the single gene   -->";
    
    
    //$result_expression 'HAD' expression data from db_query BUT NEEDS TO BE REPEATED here; it has been accessed once already
    $result_expression = db_query($sql_expression, array(':gene_uniquename' => $gene, ':accession_no' => $acc));
    
    //table
    //display_type: Table
    $div_content_table =  "<table>";
    $div_content_table .=  "<tr style=''><td><b>Sample (shortname)</b></td><td><b>Expr. Value (TPM)</b></td></tr>";
    foreach ($result_expression as $rec) {
        $div_content_table .=  "<tr>";
        $div_content_table .=    "<td>".$rec->s_shortname."</td>";
        $div_content_table .=    "<td>".$rec->exp_value."</td>";
        $div_content_table .=  "</tr>";
    }
    $div_content_table .=  "</table>";
    
    $html .= $div_content_table;  
    
    
    
    
    return $html;
  
}  // fn create_html_main_div_genelevel_data

*/