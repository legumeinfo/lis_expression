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
<p>Explore expression data at LIS: <a  href="/lis_expression/demo">Demo page</a></p>

<?php

  /*    //ADD EXEMPLAR after a daset is added
      //Exemplar for each dataset in an Assoc array
  $exemplars_list = array();
      //$exemplar_list [''] = '';
  $exemplar_list ['cajca1'] = 'cajca.ICPL87119.gnm1.ann1.C.cajan_07765';
  $exemplar_list ['cicar1'] = 'cicar.ICC4958.gnm2.ann1.Ca_01885';
  $exemplar_list ['cicar2'] = 'cicar.CDCFrontier.gnm1.ann1.Ca_04638';  //expressed only in early-flower-bud, Ubiquitin like
  $exemplar_list ['phavu1'] = 'Phvul.001G011300.v1.0';
  $exemplar_list ['vigun1'] = 'vigun.IT97K-499-35.gnm1.ann1.Vigun01g004300';
      //$exemplar_list [''] = '';
  */
  
  //psql -x -c "SELECT od.accession_no, od.shortname, od.name, od.description FROM ongenome.dataset AS od, ongenome.genome AS og, ongenome.organism AS oo  WHERE od.genome_id=og.genome_id AND og.organism_id=oo.organism_id AND oo.abbrev='cicar'"
  
  $sql_all = "SELECT oo.genus, oo.species, oo.name AS org_name, oo.chado_organism_id, od.accession_no, od.shortname, od.name, od.description, od.exemplar, og.name AS genome_name FROM ongenome.dataset AS od, ongenome.genome AS og, ongenome.organism AS oo  WHERE od.genome_id=og.genome_id AND og.organism_id=oo.organism_id  ORDER BY od.accession_no";
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
      print "<td>"."<b>Mapped to</b>"."</td>";
      //print "<td style='width:15%'>"."<b>Abbreviated name</b>"."</td>";
      print "<td style='width:20%'>"."<b>Title</b>"."</td>";
      print "<td style='width:40%'>"."<b>Description</b>"."</td>";
  print "</tr>";
  
  foreach ($result_all as $rec) {
      // Do something with each $record
      //print(gettype($rec));
      $genus = $rec->genus;
      $species = $rec->species;
      $org_name = $rec->org_name;
      $chado_organism_id = $rec->chado_organism_id;
      $acc_no = $rec->accession_no;
      $shortname = $rec->shortname;
      $name = $rec->name;
      $description = $rec->description;
      $exemplar = $rec->exemplar;
      $genome_name = $rec->genome_name;
      
          //Get Chado species name for the link (arietinum_CDCFrontier, arietinum_ICC4958)
      $sql_chado_org = "SELECT species FROM chado.organism WHERE organism_id=".$chado_organism_id;
          //$species_chado = db_query($sql_chado_org)->fetchObject();
      $species_chado =db_query($sql_chado_org)->fetchColumn();
           
      //$exemplar = $exemplar_list[$acc_no];
      
          //Do not use $org_name. It creates confusion between cicar1-vs-cicar2
      $acc_link = "<a href=\"/feature/$genus/$species_chado/gene/$exemplar#pane=geneexpressionprofile\" >$acc_no</a>";
      
      print "<tr>";
        print "<td>".$genus." ". $species ."</td>";
        
        //print "<td>" . "<b>" . $acc_no. "<b>" . "</td>";
        print "<td id='$acc_no' >" . "<b>" . $acc_link. "<b>" . "</td>";
        print "<td>".$genome_name."</td>";
        //print "<td>".$shortname."</td>";
        print "<td>".$name."</td>";
        print "<td>" . "<b>" . $shortname . ": </b>" . $description . "</td>";
      print "</tr>";
      
  }
  
  $html1 .= print "</table>";

?>
