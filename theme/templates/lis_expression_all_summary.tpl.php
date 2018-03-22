<?php
/*
 *Gaoal: Summarize all expression data available at LIS
 *Started: 2018-03-20
 *Theme, ''lis_expression_all'' calls this template lis_expression_all_summary.tpl.php.
 *hook_menu_alter at url, 'lis_expression/all' calls
 *     lis_expression_helper_functions.inc::summarize_all_lis_expression()::theme 'lis_expression_all'::this template file.
 *
 *
 *
 *
 */
?>

<h2>Summary of Expression Data at LIS</h2>


<?php
  
  //psql -x -c "SELECT od.accession_no, od.shortname, od.name, od.description FROM ongenome.dataset AS od, ongenome.genome AS og, ongenome.organism AS oo  WHERE od.genome_id=og.genome_id AND og.organism_id=oo.organism_id AND oo.abbrev='cicar'"
  
  $sql_all = "SELECT oo.genus, oo.species, od.accession_no, od.shortname, od.name, od.description, og.name AS genome_name FROM ongenome.dataset AS od, ongenome.genome AS og, ongenome.organism AS oo  WHERE od.genome_id=og.genome_id AND og.organism_id=oo.organism_id  ORDER BY od.accession_no";
  $queried_all = db_query($sql_all);
      //print(gettype($queried_all));
  $ds_count = $queried_all->rowCount();
  print "Number of datasets at LIS (all species): " . $ds_count . "<br/>";
  
  $result_all = $queried_all->fetchAll();
  //print_r($result_all);
  
  $html1 = "";
  
  $html1 .= print "<table>";
  print "<tr>";
      print "<td>"."<b>Organism</b>"."</td>";
      print "<td style='width:5%'>"."<b>Acc No.</b>"."</td>";
      print "<td>"."<b>Mappet to</b>"."</td>";
      //print "<td style='width:15%'>"."<b>Abbreviated name</b>"."</td>";
      print "<td style='width:20%'>"."<b>Title</b>"."</td>";
      print "<td style='width:40%'>"."<b>Description</b>"."</td>";
  print "</tr>";
  
  foreach ($result_all as $rec) {
      // Do something with each $record
      //print(gettype($rec));
      $genus = $rec->genus;
      $species = $rec->species;
      $acc_no = $rec->accession_no;
      $shortname = $rec->shortname;
      $name = $rec->name;
      $description = $rec->description;
      $genome_name = $rec->genome_name;
      
      //Link to exemplar genemodel gene page.
      if (strpos($acc_no, 'cajca') !== false) {
          $exemplar = 'cajca.ICPL87119.gnm1.ann1.C.cajan_07765'; 
      } elseif (strpos($acc_no, 'cicar') !== false) {
          $exemplar = 'cicar.ICC4958.v2.0.Ca_01885';
          $species = 'arietinum_ICC4958';  //This is messy for Cicar
      } elseif (strpos($acc_no, 'phavu') !== false) {
          $exemplar = 'Phvul.001G011300.v1.0';  
      } elseif (strpos($acc_no, 'vigun') !== false) {
          $exemplar = 'vigun.IT97K-499-35.gnm1.ann1.Vigun01g004300';  
      }
      
      $acc_link = "<a href=\"/feature/$genus/$species/gene/$exemplar#pane=geneexpressionprofile\" >$acc_no</a>";
      
      print "<tr>";
        print "<td>".$genus." ".$species."</td>";
        
        //print "<td>" . "<b>" . $acc_no. "<b>" . "</td>";
        print "<td>" . "<b>" . $acc_link. "<b>" . "</td>";
        print "<td>".$genome_name."</td>";
        //print "<td>".$shortname."</td>";
        print "<td>".$name."</td>";
        print "<td>" . "<b>" . $shortname . ": </b>" . $description . "</td>";
      print "</tr>";
      
  }
  
  $html1 .= print "</table>";

?>