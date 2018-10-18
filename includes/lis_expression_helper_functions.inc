<?php

/**
 *@file
 *lis_expression_helper_functions.inc
 *Goal: Helper PHP functions for the lis_expression module
 */

 /*
  *1. function uniquename_to_datasets($gene)
  *2. function get_exprsn_data_as_json ($acc, $gene)  #For a single gene
  *3. function get_dataset_metadata_as_json ($acc)
  *4. function get_profile_neighbors_expression_json ($acc, $gene)
  *5. function get_family_members_expression_json ($acc, $gene)
  *6. Function summarize_all_lis_expression_callback()
  *7. Function lis_expression_demo_callback ()
  *
  */

 
 
 

/**
 *1. Function  uniquename_to_datasets
 *  From a gene_uniquename, makes an array of available datasets(uniquename,name,acc-no,dataset_id,shortname,etc.)
 */

      //Used in lis_exp.tpl.php for avail datasets
  function uniquename_to_datasets($gene) {
      //From a gene_uniquename,
      //returns array of available datasets(acc-no,shortname)
      
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
 *(For a single gene)
 *Used: .mod file, _menu_alter, callback for %/%/json
 *
 */

  function get_exprsn_data_as_json ($acc, $gene) {
    // Given gene-uniquename and dataset-accession-no:
    // $gene: ongenome.genemodel.chado_uniquename; $acc: ongenome.dataset.accession_no
    // Callback for hook_menu_alter 'lis_expression/%/%/json' (/lis_expression/phavu1/Phvul.001G000600.v1.0/json)
    
    //print "NOW: $acc $gene";
  
      $sql_expression = "SELECT d.shortname as d_shortname, s.sample_uniquename as s_shortname, gm.genemodel_name,  e.exp_value  FROM  ongenome.dataset as d, ongenome.sample as s , ongenome.dataset_sample ds, ongenome.expressiondata as e, ongenome.genemodel as gm WHERE  d.dataset_id = ds.dataset_id and d.dataset_id=e.dataset_id and e.dataset_sample_id=ds.dataset_sample_id and ds.sample_id = s.sample_id and e.genemodel_id = gm.genemodel_id and gm.chado_uniquename = :gene_uniquename and d.accession_no = :accession_no";
      
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
      
      //Given a dataset acc, get all metadata including sample details as json
      //$acc: ongenome.dataset.accession_no
      // Callback for hook_menu_alter: 'lis_expression/dataset_metadata/%/json' (/lis_expression/dataset_metadata/phavu1/json)
      
      // Dataset metadata:
      $sql_dataset = "SELECT   ds.dataset_id AS ds_datasetid, ds.accession_no AS ds_accession, ds.shortname AS ds_shortname, ds.name AS ds_name, ds.description AS ds_description, src.name AS src_name, src.origin AS src_origin, src.description AS src_description, src.bioproj_acc AS src_bioproj_acc, src.bioproj_title AS src_bioproj_title, src.bioproj_description AS src_bioproj_description, src.sra_proj_acc AS src_sra_proj_acc, src.geo_series AS src_geo_series, gn.name AS gn_name, gn.shortname AS gn_shortname, gn.description AS gn_description, gn.source AS gn_source, gn.build AS gn_build, gn.annotation AS gn_annotation, m.name AS m_name, m.shortname AS m_shortname, m.version AS m_version, m.description AS m_description, m.details AS m_details, o.name AS o_name, o.genus AS o_genus, o.species AS o_species, o.subspecies AS o_subspecies, o.cultivar_type AS o_cultivar_type, o.line AS o_line, o.abbrev AS o_abbrev  FROM ongenome.dataset AS ds, ongenome.datasetsource AS src, ongenome.genome AS gn, ongenome.method AS m, ongenome.organism AS o WHERE src.datasetsource_id=ds.datasetsource_id AND gn.genome_id=ds.genome_id AND m.method_id=ds.method_id AND o.organism_id=gn.organism_id AND ds.accession_no = :accession_no";
      //print $sql_dataset;  //print $acc;
      
      $result_dataset = db_query($sql_dataset, array(':accession_no' => $acc,));
              //print gettype($result_dataset);
              //$result_dataset_j = json_encode($result_dataset->fetchAllKeyed(1,3));
      $result_dataset_j = json_encode($result_dataset->fetchAllAssoc('ds_accession')); //
              //Gives assoc array: {"phavu1":{"datasetid":"2","datasetaccession":"phavu1","shortname":"Bean expression atlas Negro jamapa", ....}}.  `After JSON.parse`.phavu1['shortname']=Bean expression atlas Negro jamapa
              //$result_dataset_j = json_encode($result_dataset->fetchAll()); // gives array and can't get values by field name.
      
  //  Sample-json-data in this ajax call   
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
 *  Changed name from new_get_profile_neighbors_expression_json()
 */

  
  //function new_get_profile_neighbors_expression_json ($acc, $gene) {
  function get_profile_neighbors_expression_json ($acc, $gene) {
    
      //Construct ongenome profile neighbors table name
      $tablename_profile_neighbor = "ongenome.profileneighbors_" . $acc; 
       
      $sql_expr_neighb = "
          SELECT n.neighbor_uniquename, g.genemodel_name AS neighbor_name, n.correlation, json_agg(e.exp_value ORDER BY s.sample_id ASC) AS expression_values 
          FROM
          (SELECT pn.neighbor_uniquename, pn.correlation
          FROM $tablename_profile_neighbor AS pn
          WHERE pn.genemodel_uniquename = :querygene  AND pn.correlation > 0.7 ORDER BY pn.correlation DESC) AS n
          JOIN ongenome.genemodel AS g ON g.chado_uniquename=n.neighbor_uniquename
          JOIN ongenome.expressiondata AS e ON e.genemodel_id=g.genemodel_id
          JOIN ongenome.dataset AS d ON d.dataset_id=e.dataset_id
          JOIN ongenome.dataset_sample AS ds ON ds.dataset_sample_id=e.dataset_sample_id
          JOIN ongenome.sample AS s ON s.sample_id=ds.sample_id
          WHERE d.accession_no= :accession_no
          GROUP BY n.neighbor_uniquename, g.genemodel_name, n.correlation
          ORDER BY n.correlation DESC  ";
          
          // json_agg(e.exp_value ORDER BY s.sample_id ASC): gives an array [x, y, z, ...]
    
        
        $query_expr_neighb = db_query($sql_expr_neighb, array(':querygene' => $gene, ':accession_no' => $acc)); //->fetchCol(3);
        $neighbor_result_row_count  = $query_expr_neighb->rowCount();         
        $result_expr_neighb = $query_expr_neighb->fetchAll();
        
        $part_neighbors_hr["neighbors"] = $result_expr_neighb;
        
      
      // MUST USE 'ORDER BY sample_id'. The expr data comes in this order in an array
      $sql_sample_names = "SELECT ogsa.name FROM ongenome.sample AS ogsa, ongenome.dataset_sample AS ogdasa, ongenome.dataset AS ogda WHERE ogsa.sample_id = ogdasa.sample_id AND ogdasa.dataset_id = ogda.dataset_id AND ogda.accession_no = :acc_no ORDER BY ogsa.sample_id;";
      $query_sample_names = db_query($sql_sample_names, array(':acc_no' => $acc) );
      $result_samplenames = $query_sample_names->fetchCol(0);  // first col to an array
      
              //print_r($result_samplenames);
      foreach ($result_samplenames as $rec) {
        $each_samp["sample_name"] = $rec; // each sample into an assoc-array 
        $samples_r[] = $each_samp; //all samples into an array
      }
      $part_samples_hr["samples"] = $samples_r; //
      
      $sample_count = $query_sample_names->rowCount();
      $neighbor_count = $neighbor_result_row_count;
      
      $part_query_hr = array("query" => array("gene_uniquename" => $gene, "dataset" => $acc, "neighbor_count" => $neighbor_count));
      
          //Debug:
          //print json_encode($part_query_hr);
          //print json_encode(array_merge($part_query_hr, $part_samples_hr, $part_neighbors_hr));
          //print json_encode($part_samples_hr);
          //print json_encode($result_expr_neighb);
      print json_encode(array_merge($part_query_hr, $part_samples_hr, $part_neighbors_hr));
    
  }  //get_profile_neighbors_expression_json()
 
  
  
  
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
      // get the gene family members from Chado and their expression from ongenome
      // in json format.
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

      //NOT USING//$member_unique_names = "{"."\"FamMemberUniqueNames\":". "[" ."\"".implode("\",\"",$fam_members)."\"". "]" . "}";
      // Now a valid json structure of just the member list (no expression data)
      
      
      //Get expression of fam-members
      
      foreach ($fam_members as $member) {
          //print "<br>".$member."<br/>";
      
          //$sql_exp_fam = "select d.shortname as d_shortname,s.shortname as s_shortname,e.genemodel_id,e.exp_value  from ongenomesimple.dataset as d, ongenomesimple.sample as s ,ongenomesimple.expressiondata as e where d.dataset_id = s.dataset_id and d.dataset_id=e.dataset_id and e.dataset_sample_id=s.sample_id and e.genemodel_id = "."'".$member."'";
          $sql_exp_fam = "SELECT d.shortname AS d_shortname, s.sample_uniquename AS s_shortname, gm.genemodel_name, e.exp_value  FROM ongenome.dataset AS d, ongenome.sample AS s ,ongenome.expressiondata AS e, ongenome.dataset_sample AS ds, ongenome.genemodel AS gm WHERE d.dataset_id = ds.dataset_id and d.dataset_id=e.dataset_id and e.dataset_sample_id=ds.dataset_sample_id and s.sample_id=ds.sample_id and e.genemodel_id=gm.genemodel_id and d.accession_no = :accession_no and gm.chado_uniquename = :member";
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
  * 6. Function summarize_all_lis_expression_callback()
  * Just returns the theme for the lis_expression summary page.
  * From hook_menu  in 'lis_expression/all'
  */

  function summarize_all_lis_expression_callback() {
      
      return theme ('lis_expression_all', array());
      
  }   //function summarize_all_lis_expression()
  

  
  /*
  * 7. Function lis_expression_demo_callback ()
  *   Just returns the theme for the lis_expression demo page for workshop.
  *   From hook_menu in 'lis_expression/demo'
  */

  function lis_expression_demo_callback () {
      return theme ('lis_expression_demo', array());
  }











// End of function definations



                    ///////////////////////////////////////////////////////////
                    /////////////    SCRATCH & OBSOLETES     //////////////////
                    ///////////////////////////////////////////////////////////



