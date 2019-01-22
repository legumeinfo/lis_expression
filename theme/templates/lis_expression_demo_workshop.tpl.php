<?php
/*
 *Gaoal: Starting Demo page for lis_expression in LegFed Worksop march 2018 ()
 *Started: 2018-03-21
 *Theme, 'lis_expression_demo' calls this template 'lis_expression_demo_workshop'.tpl.php.
 *hook_menu_alter at url, ''lis_expression/demo'' calls
 *     includes/lis_expression_helper_functions.inc::lis_expression_demo_callback ()::theme 'lis_expression_demo'::this template file.
 *
 *
 *
 *
 */
?>

<h2>Expression Data At LIS (LegFed workshop, Mar 2018)</h2>
<a target="_blank"  href="https://legumeinfo.org">https://legumeinfo.org</a>

<h3>How to reach the expression data:</h3>
<u>From Gene Search: </u><br>
At home page: Gene Search button (2nd row) --> Select a species (cajca, cicar.ICC4958, <b>phavu</b>, vigun) & Search  --> select a gene from the result table --> Click  'Expression' on the left panel. And, explore the expression data for this gene.<br>
OR<br>
straight go to <a href="/search/gene?abbreviation=phavu">'Gene Search' page</a> for common bean and click the 2nd gene, 'phavu.Phvul.001G000200'.
<br>
<br>
<u>From Expression Summary page:</u><br>
At home page: Expression button (4th row) --> Click one of the 4 datasets available for now. <br>
OR <br>
straight to <a href="/lis_expression/all">Expression Summary Page</a>


<h3>Examples to explore</h3>

<ol>
 <li>A <a  href="/feature/Phaseolus/vulgaris/gene/phavu.G19833.gnm1.ann1.Phvul.009G108100#pane=geneexpressionprofile">stress response related gene</a> highly expressed in root tip   </li>  
  <li> A chickpea gene predominantly expressed in the <a href="/feature/Cicer/arietinum_ICC4958/gene/cicar.ICC4958.gnm2.ann1.Ca_16436#pane=geneexpressionprofile">reproductive tissues</a> and another predominantly in the <a  href="/feature/Cicer/arietinum_ICC4958/gene/cicar.ICC4958.gnm2.ann1.Ca_01885#pane=geneexpressionprofile">vegetative tissues</a> with a family member also showing a similar expression profile.  </li>
   <li>A <a  href="/feature/Vigna/unguiculata/gene/vigun.IT97K-499-35.gnm1.ann1.Vigun01g004300#pane=geneexpressionprofile" >photosynthetic gene</a> expressed highly in leaves.</li>
  <li>Please note: its only other gene family member has a similar expression profile. </li>
  <li>A <a  href="/feature/Cajanus/cajan/gene/cajca.ICPL87119.gnm1.ann1.C.cajan_07765#pane=geneexpressionprofile"> transcription factor</a> expressed in reproductive parts in pigeonpea</li>
 <li>Finally send the list of profile neighbors to the LegumeMine for further analysis</li>
</ol>


<h3>Webservices examples (work in progress)</h3>

** Expression data of <a href="/lis_expression/cicar1/cicar.ICC4958.gnm2.ann1.Ca_01885/json">a gene</a> from a given dataset.
<br/>
** <a href="/lis_expression/dataset_metadata/cicar1/json">Metadata</a> for a dataset.
<br/>
** <a href="/lis_expression/profile_neighbors/cicar1/cicar.ICC4958.gnm2.ann1.Ca_01885/json"">Profile neighbors</a> expression data.




