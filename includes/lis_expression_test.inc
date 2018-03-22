<?php

$acc = $_GET["dset_acc"];
$gene = $_GET["gene"];

//function create_json_data_test ($gene, $acc) {
//function create_json_data_test ($acc, $gene) {
  //Given gene-uniquename and dataset-accession-no:
  //Generate html content for the current genel-evel expression data.
  //Should include table, bar chart, heatmap, line chart
  // $gene: ongenome.genemodel.chado_uniquename; $acc: ongenome.dataset.accession_no
  


    $sql_expression = "SELECT d.shortname as d_shortname, s.sample_uniquename as s_shortname, gm.genemodel_name,  e.exp_value  FROM  ongenome.dataset as d, ongenome.sample as s , ongenome.dataset_sample ds, ongenome.expressiondata as e, ongenome.genemodel as gm WHERE  d.dataset_id = ds.dataset_id and d.dataset_id=e.dataset_id and e.dataset_sample_id=ds.sample_id and ds.sample_id = s.sample_id and e.genemodel_id = gm.genemodel_id and gm.chado_uniquename = :gene_uniquename and d.accession_no = :accession_no";    
    $result_expression = db_query($sql_expression, array(':gene_uniquename' => $gene, ':accession_no' => $acc));
   


  
    //Expression data into JSON format in php
    //$result_expression 'HAD' expression data from db_query BUT NEEDS TO BE REPEATED
    //$result_expression = db_query($sql_expression, array(':gene_uniquename' => $gene_uniquename));
  
    //works, keep. 
    $result_expression_j = json_encode($result_expression->fetchAllKeyed(1,3));  //j2 in old code
    //json_encode($result_expression->fetchAllKeyed(1,3));  //j2 in old code
  
    //print "json k:v : ".$result_expression_j; //gets sample_uniquename(1) and exp_value(3)
    //return $result_expression_j;
    //return drupal_json_output($result_expression->fetchAllKeyed(1,3)); //FAILS: returns plain text in browser
    
    //testing
    //drupal_json_output($result_expression->fetchAllKeyed(1,3));
    
    //keep
    return $result_expression_j;
    
    //print $result_expression_j;
    //set_response_headers();
    //drupal_exit();
    //exit()
    //module_invoke('tripal_linkout', 'set_response_headers');
    
    drupal_exit();
  
//}  // fn create_html_main_div_genelevel_data

//echo $result_expression_j;
//create_json_data_test($acc, $gene);

//exit();

//if (!function_exists('set_response_headers')) {
//    function set_response_headers() {
//        drupal_add_http_header('Access-Control-Allow-Headers', 'Pragma');
//        drupal_add_http_header('Access-Control-Allow-Headers', 'Cache-Control', TRUE);
//        drupal_add_http_header('Access-Control-Allow-Headers', 'If-Modified-Since', TRUE);
//    }
//};

//return;
//if (module_exists('tripal_linkout')) { 
//    tripal_linkout_set_response_headers();
//  }
//drupal_exit();
