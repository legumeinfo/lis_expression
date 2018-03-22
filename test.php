<?php

//return "Hello: Content returned from test.php"; //return fails but echo works
$dset_acc = $_GET["dset_acc"];
$gene = $_GET["gene"];
$return_str = "Content returned from test.php: ". $dset_acc . " |xyz| " . $gene . "."; //return fails but echo works
echo $return_str; //works
//include 'includes/lis_expression_helper_functions.inc';
//$html_text = create_html_main_div_genelevel_data ($gene, $dset_acc);
//echo $html_text;

//PROBLEM:  <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
// Drupal functions are not recognized in the context of this file.
//ERROR:
//Fatal error: Call to undefined function db_query() in /usr/local/www/drupal7/sites/all/modules/lis_expression/test.php on line 15
/*
$sql_expression = "SELECT d.shortname as d_shortname, s.sample_uniquename as s_shortname, gm.genemodel_name,  e.exp_value  FROM  ongenome.dataset as d, ongenome.sample as s , ongenome.dataset_sample ds, ongenome.expressiondata as e, ongenome.genemodel as gm WHERE  d.dataset_id = ds.dataset_id and d.dataset_id=e.dataset_id and e.dataset_sample_id=ds.sample_id and ds.sample_id = s.sample_id and e.genemodel_id = gm.genemodel_id and gm.chado_uniquename = :gene_uniquename and d.accession_no = :accession_no";    

$result_expression = db_query($sql_expression, array(':gene_uniquename' => $gene, ':accession_no' => $acc));

$result_expression_j = json_encode($result_expression->fetchAllKeyed(1,3));

echo $result_expression_j;
*/
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

//echo $return_str; //works

echo time(); //this function works

//PROBLEM:  "Fatal error: Call to undefined function pg_connect() in /usr/local/www/drupal7/sites/all/modules/lis_expression/test.php on line 33"

$sql_expression = "SELECT d.shortname as d_shortname, s.sample_uniquename as s_shortname, gm.genemodel_name,  e.exp_value  FROM  ongenome.dataset as d, ongenome.sample as s , ongenome.dataset_sample ds, ongenome.expressiondata as e, ongenome.genemodel as gm WHERE  d.dataset_id = ds.dataset_id and d.dataset_id=e.dataset_id and e.dataset_sample_id=ds.sample_id and ds.sample_id = s.sample_id and e.genemodel_id = gm.genemodel_id and gm.chado_uniquename = $gene and d.accession_no = $dset_acc";  

$conn = pg_connect("host=localhost  port=5800  dbname=drupal");
//$conn = pg_connect("dbname=drupal");
$result = pg_query($conn, $sql_expression);
if (!$result) {
  echo "An error occurred.\n";
  exit;
}

$allrows = '';
while ($row = pg_fetch_row($result)) {
  //echo "Dataset: $row[0]  Sample: $row[1]  Value: $row[3] \n";
  $allrows .= "Dataset: $row[0]  Sample: $row[1]  Value: $row[3] <br />\n";
  //echo "<br />\n";
}

echo json_encode($allrows);
//echo $allrows;



?>