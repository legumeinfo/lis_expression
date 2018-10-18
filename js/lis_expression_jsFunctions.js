
//JS functions for lis_expression module

/*
 *List of functions:
 *  function drawLinePlot (container, jsonData)
 *  function drawBarPlot (container, jsonData)
 *  function drawHeatmap (container, jsonData, geneName)
 *  function makeTableWithJsonData (container, jsonData)
 *  function getExpressionData(dsetAcc, gene)
 *  
 *  function getDatasetMetadata(dsetAcc)
 *  function dataset_metadata_presentation (container, jsonMetadata, dsetAcc)
 *  
 *  function get_profile_neighbors_data (container, dsetAcc, geneName, genus, species)
 *  function present_profile_neighbors_data (container, jsonNeighbData, geneName, genus, species)
 *  function submit_neifhbors()
 *  
 *  function draw_neighbors_heatmap (container, xData, yData, zData)
 *  function draw_neighbors_linePlot (container, xData, yData, zData)
 *  function make_neighbors_table (container, xData, yData, zData)
 *
 *  function get_family_members_data (container, dsetAcc, geneName, genus, species)
 *  function present_family_members_data (container, jsonNeighbData, geneName, genus, species)
 *
 */




//Draw a line plot given container and json data
//container: div-id
//json data: sample and expression value for a gene in json

  function drawLinePlot (container, jsonData) {
      //jsonData should be object( should convert json string to object)
      
      var CONTAINER_GENE = document.getElementById(container);
      CONTAINER_GENE.innerHTML = '';  //empty the container first before drawing
            
      var k = Object.keys(jsonData); //k-keys
      
      //Don't draw if jsonData is empty
      if (!k.length) {CONTAINER_GENE.innerHTML = 'LIS_EXPRESSION MODULE says: No Data for this dataset, jsonObj is empty! <br/>'; return false;} //works; 'return false' exits fn execution.
      
      var vals = Object.keys(jsonData).map(function (key) {
          return jsonData[key];
      });
    
  
      var dataLinePlot =  [
          trace = {
              x: k, //x: ['abc1','abc2','abc3','abc4','abc5'],
              y: vals, //y: [1,3,5,7,9], }],
              
              type: 'scatter' //type: 'bar', orientation: 'h'
          }
      ];
    
      var layout = {
          margin: { t: 0, b: 130 },
          paper_bgcolor: '#f5f5f5',
          plot_bgcolor: '#c7c7c7',
          yaxis: {title: 'TPM'}
      };
    
      Plotly.newPlot(container, dataLinePlot, layout);
      //..........................
    
  } //fn: drawLinePlot


//------BAR
  function drawBarPlot (container, jsonData) {
    
      //jsonData should be object( should convert json string to object)
     
      var CONTAINER_GENE = document.getElementById(container);
      CONTAINER_GENE.innerHTML = ''; //empty the container first before drawing
      
      var k = Object.keys(jsonData); //k-keys
      
      //Don't draw if jsonData is empty
      if (!k.length) {CONTAINER_GENE.innerHTML = 'LIS_EXPRESSION MODULE says: No Data for this dataset, jsonObj is empty! <br/>'; return false;} //works; 'return false' exits fn execution.
      
      var vals = Object.keys(jsonData).map(function (key) {
          return jsonData[key];
      });
      
      var dataBar = [     // data for plotting between [ ]
          {
            x:k,
            y: vals,
            type: 'bar'
          }
      ];
      var layout = {  
          margin: { t: 0, b: 130 },
          height: 350,
          //xaxis: { side: 'bottom', gridwidth: 1 } // xaxis label at bottom (default)
      yaxis: {title: 'TPM'}
      };
      Plotly.newPlot(container, dataBar, layout);
  }



    //-----HEATMAP
  function drawHeatmap (container, jsonData, geneName) {
      //jsonData should be object( should convert json string to object)
      
      var CONTAINER_GENE = document.getElementById(container);
      CONTAINER_GENE.innerHTML = ''; //empty the container first before drawing
      
      var k = Object.keys(jsonData); //k-keys
      
      //Don't draw if jsonData is empty
        if (!k.length) {CONTAINER_GENE.innerHTML = 'LIS_EXPRESSION MODULE says: No Data for this dataset, jsonObj is empty! <br/>'; return false;} //works; 'return false' exits fn execution.
      
      var vals = Object.keys(jsonData).map(function (key) {
          return jsonData[key];
      });
            
      var dataHeatmap = [     // data for plotting between [ ]
          {
            x:k,
            y: [geneName],
            z: [vals],
            type: 'heatmap',
            colorscale: 'Rd',
      colorbar: {title:'TPM', titleside:'right'}
          }
      ];
      var layout = {  
          margin: { t: 0, l:250, b: 130 },
          height: 180,
          xaxis: { side: 'bottom', gridwidth: 1 } // xaxis label at bottom (default)
      };
      Plotly.newPlot(container, dataHeatmap, layout);
  }
  


    //------TABLE
  function makeTableWithJsonData (container, jsonData) {
      //jsonData should be object( should convert json string to object)
      
      var CONTAINER_GENE = document.getElementById(container);
      CONTAINER_GENE.innerHTML = ''; //empty the container first before drawing
      
      var k = Object.keys(jsonData); //k-keys
      
      //Don't draw if jsonData is empty
        if (!k.length) {CONTAINER_GENE.innerHTML = 'LIS_EXPRESSION MODULE says: No Data for this dataset, jsonObj is empty! <br/>'; return false;} //works; 'return false' exits fn execution.
      
      var vals = Object.keys(jsonData).map(function (key) {
          return jsonData[key];
      });
  
          
      var htmlTable = "";
      htmlTable += "<table border='1'>";
      htmlTable += "<tr style=''><td><b>Sample (shortname)</b></td><td><b>Expr. Value (TPM)</b></td></tr>";
      for (x in k) {
                  htmlTable += "<tr><td>" + k[x] + "</td><td>" + vals[x] +"</td></tr>";
      }
      htmlTable += "</table>"; 
      document.getElementById(container).innerHTML = htmlTable;
      
      //TODO:  Links in sample to sample metadata (Need ajax call to get data)  
  }






  //Need gene_uniquename, dataset_acc: Get data as json preferably
  
  function getExpressionData(dsetAcc, gene, genus, species) {
    
    /* Describe what exactly it does & where.
     *  Given datasetAcc and gene:
     *    Gets exprsn data for gene for the dtatset.
     *    Draws a linePlot in a specified div-ID
     */
    
    
    //Nedd a php script to query db and spit json
    //console.log(dsetAcc.value, " : for ", gene);
    console.log(dsetAcc, " : for ", gene);
    //dsetAcc = dsetAcc.value;
    gene = gene;
    
    //Empties control, message and data-display containers at start
    document.getElementById("control_display_type_radios").innerHTML = "";
    document.getElementById('message_for_display_data').innerHTML = ""; 
    document.getElementById("display_data").innerHTML = "....Getting Data from Server............<br/><br/>";
    
    
    
    //Generate radio button controls for display type
    var htmlGeneric = '';
    htmlGeneric += '<!--  Select display_type; radio buttons -->';
    htmlGeneric += '<fieldset style="display: inline-block; padding-left: 10px; position: relative; top:0px;">';
    htmlGeneric += '<input id=\'radioLinePlot\'  type="radio" name="display_type" value="plot"  onclick="drawLinePlot (\'display_data\', jd_gene);"  checked > Line plot &nbsp;&nbsp;&nbsp;';
    htmlGeneric += '<input type="radio" name="display_type" value="bar"  onclick="drawBarPlot(\'display_data\', jd_gene);"> Bar graph &nbsp;&nbsp;&nbsp;';
    htmlGeneric += '<input type="radio" name="display_type" value="heatmap"  onclick="drawHeatmap(\'display_data\', jd_gene, geneName)" > Heatmap &nbsp;&nbsp;&nbsp;';
    htmlGeneric += '<input type="radio" name="display_type" value="table" onclick="makeTableWithJsonData (\'display_data\', jd_gene);"> Table <br/>';
    htmlGeneric += '</fieldset>';
    document.getElementById("control_display_type_radios").innerHTML = htmlGeneric;

        //FAILS. Path is interpreted differently.  https://localhost:5800/feature/Phaseolus/vulgaris/gene/test.php 404 (Not Found)
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
                       //console.log(responseString);
                       document.getElementById("display_data").innerHTML = "";
                       drawLinePlot ('display_data', jd_gene); // in /js/lis_expression_jsFunctions.js' //was: 'display_gene_data'
                       //drawBarPlot ('div002', jd_gene);
                       
                       //after-tabs-commented
                       //getDatasetMetadata(datasetDropdown.value);  //Also change the content of metadata container
                       //get_profile_neighbors_data ("display_profile_neighbors_data", datasetDropdown.value, gene, genus, species);
                       //get_family_members_data ("display_family_members_data", datasetDropdown.value, gene, genus, species); 
                     
                   }
          });   //jQ.ajax        
      
    })(jQuery); // fn($)
    
    //Also Call these functions
    document.getElementById('radioLinePlot').checked = true; //Resets display type to line plot after dataset is selected.
    //getDatasetMetadata(datasetDropdown.value);  //Also change the content of metadata container
    //get_profile_neighbors_data ("display_profile_neighbors_data", datasetDropdown.value, gene, genus, species);
    //get_family_members_data ("display_family_members_data", datasetDropdown.value, gene, genus, species); 
    
        
  } //getExpressionData()


  
  
  //------------Dataset------------------
  
  function getDatasetMetadata(dsetAcc) {
      
    
    /* Describe what exactly it does & where.
     *  Given datasetAcc and gene:
     *    Gets exprsn data for gene for the dtatset.
     *    Draws a linePlot in a specified div-ID
     */
  
      //console.log("Metadata for: "+dsetAcc);
    
      //document.getElementById('radioLinePlot').checked = true; //Resets display type to line plot after dataset is selected.
      
      //Empty control, message and display containers
      document.getElementById("control_display_type_radios").innerHTML = "";
      document.getElementById('message_for_display_data').innerHTML = ""; 
      document.getElementById("display_data").innerHTML = "";
      
  
      (function($){
                  
            jQuery.ajax({ type: "GET",
                     //url: "includes/lis_expression_test.inc",    //XXX             
                     //url: "lis_expression/"+dsetAcc+"/"+gene+"/json", //XXX
                    //url: "/lis_expression/"+dsetAcc+"/"+gene+"/json", //WORKS
                    url: "/lis_expression/dataset_metadata/"+dsetAcc+"/json",
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
                                                    //A string in json (like an assoc array)
                         jd_dset = JSON.parse(responseString); // [Now an Obj]: JasonData-for-dataset
                         //console.log(responseString);
                         //dataset_metadata_presentation ("datasetMetadata", jd_dset);
                         //dataset_metadata_presentation ("datasetMetadata", jd_dset, dsetAcc);// for its own container
                         dataset_metadata_presentation ("display_data", jd_dset, dsetAcc); // for generic
                         
                         //Ex:
                         //drawLinePlot ('display_gene_data', jd_gene); // in /js/lis_expression_jsFunctions.js'
                         //drawBarPlot ('div002', jd_gene);
                       
                     }
            });   //jQ.ajax        
        
      })(jQuery); // fn($)
        
  } //getDatasetMetadata()



/*
 *Function: Present dataset metadata
 *    From json data to html or html table.
 *
 */

  function dataset_metadata_presentation (container, jsonMetadata, dsetAcc) {
      //Designed to work ONLY WITH jsonData='jd_dset' inside fn getDatasetMetadata().
      
      //TO DO MUST: LINKS TO BioProj, GEO, SRA, etc.
      // So, must go by each field from query. MUST Convert json to an assoc array first.
      
      jsonData = jsonMetadata.metadata[dsetAcc];
      jsonDset = jsonMetadata.metadata[dsetAcc];
      jsonSamp = jsonMetadata.metadata.samples;
      
      
      var CONTAINER_GENE = document.getElementById(container);
      CONTAINER_GENE.innerHTML = ''; //empty the container first before drawing
      
      var k = Object.keys(jsonDset); //k-keys
      
          //Exit fn-execn if jsonDset is empty
      if (!k.length) {CONTAINER_GENE.innerHTML = 'LIS_EXPRESSION MODULE: No Data for this dataset, jsonObj is empty! <br/>'; return false;}         //works; 'return false' exits fn execution.
      
      var vals = Object.keys(jsonDset).map(function (key) {
          return jsonDset[key];
      });
      
                  //As defined in query in $sql_dataset in .module
      AttributesMetadata = {"ds_datasetid":"Dataset ID", "ds_accession":"Dataset Accession No", "ds_shortname":"Dataset Shortname", "ds_name":"Dataset Name", "ds_description":"Dataset Description", "src_name":"Source Title", "src_origin":"Source", "src_description":"Source Description", "src_bioproj_acc":"BioProj Accession", "src_bioproj_title":"BioProj Title", "src_bioproj_description":"BioProj Description", "src_sra_proj_acc":"SRA Accession", "src_geo_series":"GEO Series", "gn_name":"Reference Genome", "gn_shortname":"Genome Abbrev.",  "gn_description":"Genome Description", "gn_source":"Genome Source", "gn_build":"Genome Build Version",  "gn_annotation":"Genome Annotation Version",  "m_name":"Data Analysis Method", "m_shortname":"Data Analysis Abbrev.", "m_version":"Method Version",  "m_description":"Method Description",  "m_details":"Method Details",  "o_name":"Organism Name", "o_genus":"Genus",  "o_species":"Species", "o_subspecies":"Subspecies", "o_cultivar_type":"Cultivar Type", "o_line":"Line/Genotype", "o_abbrev":"Organism Abbrev."};
              //ds_datasetid, ds_accession, ds_shortname, ds_name, ds_description, src_name, src_origin, src_description, src_bioproj_acc, src_bioproj_title, src_bioproj_description, src_sra_proj_acc, src_geo_series, gn_name, gn_shortname, gn_description, gn_source, gn_build, gn_annotation, m_name, m_shortname, m_version, m_description, m_details, o_name, o_genus, o_species, o_subspecies, o_cultivar_type, o_line, o_abbrev
      
      
      
      var html = "";
      html += "<h3>Dataset Metadata</h3>"; //html += "<br/>";
      html += "<div>";     
          //Attributes: Dispaly only required ones.
              //dataset at LIS
      html += "<b>"+jsonDset.ds_shortname+"</b>: " + "&nbsp;&nbsp;" + jsonDset.ds_name + "</br>";
      html += "<b>Dataset Accession</b>: " + "&nbsp;&nbsp;" + jsonDset.ds_accession + "</br>";
      html += "<b>Description</b>: " + "&nbsp;&nbsp;" + jsonDset.ds_description + "</br>";
              // Source
      html += "<ul>" + "<b>Source</b>: " + "&nbsp;&nbsp;" + jsonDset.src_origin;
        html += "<li>" + "<a target='_blank'  href=" + "'https://www.ncbi.nlm.nih.gov/sra/" + jsonDset.src_sra_proj_acc + "'" + ">"+ jsonDset.src_sra_proj_acc + "</a>" + ": " + jsonDset.src_name + "</li>";
        html +=  "<li>" + "BioProject: " + "<a target='_blank'  href=" + "'https://www.ncbi.nlm.nih.gov/bioproject/" + jsonDset.src_bioproj_acc + "'" + ">" + jsonDset.src_bioproj_acc + "</a>" + ": " + jsonDset.src_bioproj_title + "</li>";
      html += "</ul>";
              //Ref Genome and organism
      html += "<ul>" + "<b>Ref Genome</b>: ";
        html += "<li>" + jsonDset.gn_name + " (" + jsonDset.gn_source + ")" + "</li>";
        html += "<li>" + "Ref Genome Organism: " + jsonDset.o_genus + " " + jsonDset.o_species + " (" + jsonDset.o_name + ")"  + "</li>";
      html += "</ul>";
              //Method
      html += "<ul>" + "<b>Analysis Method</b>: " + jsonDset.m_shortname;
      html += "<li>" + jsonDset.m_name + ":&nbsp;&nbsp;" + jsonDset.m_details + "</li>";
      html += "</ul>";
      
  
  
  //  ---------------  NOW SAMPLES  ------
      html += "<b>SAMPLES</b>" + "<br>";
      //html += JSON.stringify(jsonSamp);
      sampleIDs = Object.keys(jsonSamp);
      sampleCount = sampleIDs.length;
      sampleData = sampleIDs.map( function (key) { return jsonSamp[key]; } );
      
      html += "No. of samples: " + sampleCount + "<br>";
      
            //display if not empty fn()
      function displayAttribIfNonEmpty(attributeName, attribValue) {
          if (attribValue) {
              html += "<dd>" + "<i>" + attributeName + "</i>: " + attribValue + "</dd>";
          }
      }  //displayAttribIfNonEmpty()
      
      
            //Parse and then display 'other_attributes' and sub-attribs
      
      function parseAndDisplayOtherAttributes (attribValue) {
          html += "<dd>" +"Other Attributes: " + "</dd>";
          attribList = attribValue.split(';');
          subAttribAssoc = [];
          for (var i=0; i<attribList.length; i++) {
              subAttribList = attribList[i].split(':');
              subAttribAssoc[subAttribList[0]] = subAttribList[1];
          }
          html += "<dd>";
          //conditional ternary to test null
          html += (subAttribAssoc.application) ? ('&bullet; Application: ' + subAttribAssoc.application + "&nbsp;&nbsp;&nbsp;&nbsp;"):'';
          html += (subAttribAssoc.tissue) ? ('&bullet; Tissue: ' + subAttribAssoc.tissue + "&nbsp;&nbsp;&nbsp;&nbsp;"):'';
          html += "</dd>";
          html += "<dd>";
          html += (subAttribAssoc.cultivar) ? ('&bullet; Cultivar: ' + subAttribAssoc.cultivar + "&nbsp;&nbsp;&nbsp;&nbsp;"):'';
          html += (subAttribAssoc.infraspecies) ? ('&bullet; Infraspecies: ' + subAttribAssoc.infraspecies + "&nbsp;&nbsp;&nbsp;&nbsp;"):'';
          html += (subAttribAssoc.organism) ? ('&bullet; Organism: ' + subAttribAssoc.organism + "&nbsp;&nbsp;&nbsp;&nbsp;"):'';
          html += "</dd>";
      }
      
          //Parse and display with links  each of the 'ncbi_accessions'
      function parseAndDisplayAccessions (attribValue) {
          html += "<dd>" +"Accessions: " + "</dd>";
          attribList = attribValue.split(';');
          subAttribAssoc = [];
          for (var i=0; i<attribList.length; i++) {
              subAttribList = attribList[i].split(':');
              subAttribAssoc[subAttribList[0]] = subAttribList[1];
          }
          html += "<dd>";
          //conditional ternary to test null `(...):''`
                      //SRS#: Couldn't find link
          //html += (subAttribAssoc.sra_accession) ? ('&bullet; ' + subAttribAssoc.sra_accession + "&nbsp;&nbsp;&nbsp;&nbsp;"):'';
                      //https://www.ncbi.nlm.nih.gov/biosample/SAMN02226083
          html += (subAttribAssoc.biosample_accession) ? ('&bullet; <a target=\'_blank\' href=\'https://www.ncbi.nlm.nih.gov/biosample/' + subAttribAssoc.biosample_accession + "\'>" + subAttribAssoc.biosample_accession + "</a>" + "&nbsp;&nbsp;&nbsp;&nbsp;"):'';
                      //https://www.ncbi.nlm.nih.gov/Traces/sra/?run=SRR1569490
          html += (subAttribAssoc.sra_run) ? ('&bullet; <a target=\'_blank\' href=\'https://www.ncbi.nlm.nih.gov/Traces/sra/?run=' + subAttribAssoc.sra_run + "\'>" + subAttribAssoc.sra_run + "</a>" + "&nbsp;&nbsp;&nbsp;&nbsp;"):'';
                      //https://www.ncbi.nlm.nih.gov/sra/    
          html += " (";  //The bioprojproj and SRP are separated within '()'
          html += (subAttribAssoc.sra_study) ? ('&bullet; <a target=\'_blank\' href=\'https://www.ncbi.nlm.nih.gov/sra/' + subAttribAssoc.sra_study + "\'>" + subAttribAssoc.sra_study + "</a>" + "&nbsp;&nbsp;&nbsp;&nbsp;"):'';
                      //https://www.ncbi.nlm.nih.gov/bioproject/PRJNA210619
          html += (subAttribAssoc.bioproject_accession) ? ('&bullet; <a target=\'_blank\' href=\'https://www.ncbi.nlm.nih.gov/bioproject/' + subAttribAssoc.bioproject_accession + "\'>"+ subAttribAssoc.bioproject_accession + "</a>" + "&nbsp;&nbsp;&nbsp;&nbsp;"):'';
          html += ")";
          html += "</dd>";
      }
      
      
            //Sample attributes into html: <dl> 
      for (var i=0; i < sampleCount; i++) {
          if (sampleData[i]) {
            html += "<dl>";
              html += "<dt><b>" + sampleData[i].name + "</b> : "  +"</dt>";
              displayAttribIfNonEmpty ('Description', sampleData[i].description);
              displayAttribIfNonEmpty ('Treatment', sampleData[i].treatment);
              displayAttribIfNonEmpty ('Age', sampleData[i].age);
              displayAttribIfNonEmpty ('Plant Part', sampleData[i].plant_part);
              displayAttribIfNonEmpty ('Dev. Stage', sampleData[i].dev_stage);
              //parseAttribAndDisplay ('OTHER-ATTRIBUTES', sampleData[i].other_attributes);
              //parseAttribAndDisplay ('RELATED ACCESIONS', sampleData[i].ncbi_accessions);
              parseAndDisplayOtherAttributes (sampleData[i].other_attributes);
              parseAndDisplayAccessions (sampleData[i].ncbi_accessions);
  
            html += "</dl>";  
          }
      } 
      
      html += "</div>";
      
      document.getElementById(container).innerHTML = html;
       
  
    
  }   //end- dataset_metadata_presentation()




/*
 *Function get_profile_neighbors_data ()
 *
 *
 */
      //Was: function new_get_profile_neighbors_data (container, dsetAcc, geneName, genus, species)
  function get_profile_neighbors_data (container, dsetAcc, geneName, genus, species) {
  
      //jsonData should be object( should convert json string to object)
      
      //AFTER TABS:
      //Empty control, print message and display containers
      document.getElementById("control_display_type_radios").innerHTML = "<br/>.....GETTING PROFILE NEIGHBORs DATA FROM SERVER.....<br/><br/>";
      document.getElementById('message_for_display_data').innerHTML = '';
      document.getElementById("display_data").innerHTML = "";
      
      
      
      (function($){
                
          jQuery.ajax({ type: "GET",
              
              url: "/lis_expression/profile_neighbors/"+dsetAcc+"/"+geneName+"/json", //was newjson
                             
              success : function(response)
              {
                  responseString = response; //captured into another var; typeof(responseString) is string
                                              //A string in json (like an assoc array)
                      //console.log(responseString);
                  jd_profNeighb = JSON.parse(responseString);  // [Now an Obj]: JasonData-for-dataset 
                  //container.innerHTML = JSON.stringify(jd_profNeighb);
                  
                  document.getElementById("control_display_type_radios").innerHTML = "";
                  present_profile_neighbors_data("display_data", jd_profNeighb, geneName, genus, species);
                 
              },
              error : function()
              {
                  document.getElementById("display_data").innerHTML = "<br/>LIS_EXPRESSION_MODULE: <b>No profile neighbors data found !!</b><br/>";
              },
              
          });   //jQ.ajax        
          
          //In future, perhaps, I can place the animated GIF here for loading !!!???
          //document.getElementById("display_data").innerHTML = '<br/>Getting Data from Server !!</b><br/>'; //runs while getting data
          //document.getElementById("wait").css("display", "block"); //(runs while getting data). Seems to work but needs more testing
          
    })(jQuery); // fn($)

 
  } // get_profile_neighbors_data ()
  
  
/*
 *  function present_profile_neighbors_data 
 *  (called from get_profile_neighbors_data())
 *  Was new_present_profile_neighbors_data ()
 *
 */
          
  function present_profile_neighbors_data (container, jsonNeighbData, geneName, genus, species) {         
      //console.log(jsonNeighbDataNew);
        
      //Query gene info
      queryGeneInfo = jsonNeighbData.query; //queryGeneInfo = jsonNeighbDataNew.query; //obj
              //Looks like:
              //{"gene_uniquename":"Phvul.001G011300.v1.0","dataset":"phavu1","neighbor_count":1241}
              //Extract by:
              //queryGeneInfo.gene_uniquename; queryGeneInfo.dataset; queryGeneInfo.neighbor_count
              
      //Sample Names LIst:
      sampleNamesList = jsonNeighbData.samples.map(x => x.sample_name);  //array

      //List of Neighbor Names:
      //(loop through or any straight forward method array.map)    
      Neighbors = jsonNeighbData.neighbors; //obj
     
      
      neighborUniquenamesList = Neighbors.map(x => x.neighbor_uniquename); //array
              //console.log(neighborUniquenamesList);              
      CorrelationsList = Neighbors.map(x => x.correlation);  //array
              //console.log(CorrelationsList);
      ExpressionValuesList = Neighbors.map(x => (x.expression_values).replace(/\]|\[/g, "").split(","));  //array of arrays
              //string.replace '[' or ']' before split() (the ajax comes with "[a,b,c,d,....,z]"; the [ and ] need to be removed)
              //.split(",")  to convert the string of expr-values from ajax to array; otherwise fails
              //console.log(ExpressionValuesList);

      //Neighbor unique-names to anchored text with correlation value (used for heatmap and table display)
      G_anchorText = []; 
      for (var G in neighborUniquenamesList) {
          linkToGene = "<a  target=\"_blank\"  href=\"/feature/" + genus + "/" + species + "/gene/" + neighborUniquenamesList[G] + "#pane=geneexpressionprofile" + "\"" + ">" + neighborUniquenamesList[G] + "</a>" + " (" + CorrelationsList[G] + ")"; 
          G_anchorText.push(linkToGene);
      }          
                      
      /*  DEBUG:
      document.getElementById("newgeneric").innerHTML=geneName + "<br>" + genus +  "<br>"+ species +  "<br>"
      + JSON.stringify(queryGeneInfo) + "<br><br>"
      + JSON.stringify(sampleNamesList) + "<br><br>"
      + JSON.stringify(neighborUniquenamesList);
      */
            
      //WHY ???  1092
      //draw_neighbors_heatmap ('newgeneric', sampleNamesList, neighborUniquenamesList.slice(0, 1092), newExpressionValuesList.slice(0, 1092)); //  >1092 fails to draw the plot WHY?????
 
    
      //Initial on load, lineplot: Top 20 neighbors
      messageForDisplayData = "Showing top 20 profile neighbors.";
      document.getElementById('message_for_display_data').innerHTML = messageForDisplayData;
      draw_neighbors_linePlot (container, sampleNamesList, neighborUniquenamesList.slice(0, 20), ExpressionValuesList.slice(0, 20)); 
      
      var html = '';  // for sub-heading, filter and plot types
      html += "<h3>Profile Neighbors</h3>";
      html += "(Genes with similar expression profile based on pearson correlation coefficient)&nbsp;&nbsp;&nbsp;<br/>";
      //Filter top neighbors or correlation
      html += "<b>Filter</b>:" + "&nbsp;&nbsp;&nbsp;" + "<br/>";
          //html += "<form>"; //to respond to 'Enter' key but has unwanted side effects
      html += "<input type='radio' id='top-neighb-rad'  name='filter-neighbors' checked >" + "&nbsp;&nbsp;&nbsp;";
      html += "Top" + "&nbsp;&nbsp;"; 
      html += "<input type='number' value=20  min=10 step=10 size=20  id='top-neighbs-box' /*onchange=\"submitNeighbors();\"*/  >";
      html += "&nbsp;&nbsp; neighbors";
      html += "&nbsp;&nbsp;&nbsp;" + "<b>OR</b>" + "&nbsp;&nbsp;&nbsp;";
      
      html += "<input type='radio' id='corr-rad' name='filter-neighbors' >" + "&nbsp;&nbsp;&nbsp;";
      html += "Correlation >= " + "&nbsp;&nbsp;&nbsp;";
      html += "<input type='number' value=0.95 placeholder=0.95  max=1.0  min=0.7 step=0.05  size=20  id='corr-box'>";
      html += "&nbsp;&nbsp;&nbsp;" + "<input type=\"submit\" value=\"Submit\" onclick=\"submitNeighbors();\">"; // submitNeighbors() defined in this script
          //html += "&nbsp;&nbsp;&nbsp;" + "<button type=\"submit\" value=\"Submit\" onclick=\"submitNeighbors();\" >Submit</button>";
          //html += "</form>";   
      
      html += "<br/>";
      
      html += "<fieldset style=\"display: inline-block; padding-left: 10px;\">";
      //html += "<legend>Draw Options: </legend>";

      html += "<input type=\"radio\" name=\"display_type_neighbors\" value=\"lineplot\"  onclick=\"submitNeighbors()\"  checked > Line plot &nbsp;&nbsp;&nbsp;";
      html += "<input type=\"radio\" name=\"display_type_neighbors\" value=\"heatmap\"  onclick=\"submitNeighbors()\"> Heatmap** &nbsp;&nbsp;&nbsp;";      
      html += "<input type=\"radio\" name=\"display_type_neighbors\" value=\"table\"  onclick=\"submitNeighbors()\"> Table** &nbsp;&nbsp;&nbsp;";

      html += "</fieldset>";
      html += "(**The <strong>heatmap & table have links</strong> to profile neighbors)";

      document.getElementById('control_display_type_radios').innerHTML = html; //after tab
            
  } //present_profile_neighbors_data ()

  
/*
 *function submitNeighbors()
 *
 *Called inside XXnew_XXpresent_profile_neighbors_data ()
 *
 */

  function submitNeighbors() {
    // Used inside present_profile_neighbors_data () to display only filtered neighbors

    //Empties message container
    document.getElementById('message_for_display_data').innerHTML = '';
    
    //User selection: top neighbors or by Correlation  
    filterTypeSelected = document.querySelector('input[name="filter-neighbors"]:checked').id; // either id='top-neighb-rad' or  id='corr-rad'
    console.log("filterType: " + filterTypeSelected);
    
    //1st switch-A: top-neighbor OR Corrln based
    switch (filterTypeSelected) {
        case 'top-neighb-rad':
            topNeighbSeleceted = document.getElementById("top-neighbs-box").value;
            displayNum = topNeighbSeleceted;
            plotTypeSelected = document.querySelector('input[name="display_type_neighbors"]:checked').value;
            
            messageForDisplayData = "Showing top " + topNeighbSeleceted + " profile neighbors.";
            document.getElementById('message_for_display_data').innerHTML = messageForDisplayData;
            
            //Inner switch: plot-type
            switch(plotTypeSelected) {
                case "lineplot":
                    draw_neighbors_linePlot ('display_data', sampleNamesList, neighborUniquenamesList.slice(0, displayNum), ExpressionValuesList.slice(0, displayNum));  
                    break;
                case "heatmap":
                    draw_neighbors_heatmap ('display_data', sampleNamesList, G_anchorText.slice(0, displayNum), ExpressionValuesList.slice(0, displayNum)); 
                    break;
                case "table":                    
                    make_neighbors_table ('display_data', sampleNamesList, G_anchorText.slice(0, displayNum), ExpressionValuesList.slice(0, displayNum)); 
                    break;
                default:
                    draw_neighbors_linePlot ('display_data', sampleNamesList, neighborUniquenamesList.slice(0, displayNum), ExpressionValuesList.slice(0, displayNum));  
            }
        break;  // for 1st switch-A
        
        //1st switch-B  
        case 'corr-rad':
            corrSelected = document.getElementById("corr-box").value;
            plotTypeSelected = document.querySelector('input[name="display_type_neighbors"]:checked').value;
            FilteredNeighborsByCorr = Neighbors.filter(item => {filtItems = item.correlation >= corrSelected;
                                                             return filtItems; });  
                console.log(FilteredNeighborsByCorr);
            FilteredNeighborsByCorrCount = FilteredNeighborsByCorr.length; 
                console.log("Number of neighbors with correlation >= " + corrSelected + ": " + FilteredNeighborsByCorrCount);
                
            messageForDisplayData = "Number of neighbors with correlation >= " + corrSelected + ": " + FilteredNeighborsByCorrCount;
            document.getElementById('message_for_display_data').innerHTML = messageForDisplayData; 
            
            neighborUniquenamesListCorr = FilteredNeighborsByCorr.map(x => x.neighbor_uniquename);  
                console.log(neighborUniquenamesListCorr);
            CorrelationsList = FilteredNeighborsByCorr.map(x => x.correlation);  //array
                console.log(CorrelationsList); //console.log(newCorrelationsList);
            ExpressionValuesListCorr = FilteredNeighborsByCorr.map(x => (x.expression_values).replace(/\]|\[/g, "").split(","));    //array of arrays
               //string.replace '[' or ']' before split() (the ajax comes with "[a,b,c,d,....,z]"; the [ and ] need to be removed)
              //.split(",")  to convert the string of expr-values from ajax to array; otherwise fails
                console.log(ExpressionValuesListCorr);  
            
            //Neighbor unique-names to anchored text with correlation value (used for heatmap and table display)
            G_anchorTextCorr = []; 
            for (var G in neighborUniquenamesListCorr) {
                linkToGeneCorr = "<a  target=\"_blank\"  href=\"/feature/" + genus + "/" + species + "/gene/" + neighborUniquenamesListCorr[G] + "#pane=geneexpressionprofile" + "\"" + ">" + neighborUniquenamesListCorr[G] + "</a>" + " (" + CorrelationsList[G] + ")"; 
                G_anchorTextCorr.push(linkToGeneCorr);
            }
                    
            plotTypeSelected = document.querySelector('input[name="display_type_neighbors"]:checked').value;
            switch(plotTypeSelected) {
                case "lineplot":
                    draw_neighbors_linePlot ('display_data', sampleNamesList, neighborUniquenamesListCorr, ExpressionValuesListCorr); 
                    break;
                case "heatmap":
                    draw_neighbors_heatmap ('display_data', sampleNamesList, G_anchorTextCorr, ExpressionValuesListCorr);  
                    break;
                case "table":
                    make_neighbors_table ('display_data', sampleNamesList, G_anchorTextCorr, ExpressionValuesListCorr);  
                    break;
                default:
                    draw_neighbors_linePlot ('display_data', sampleNamesList, neighborUniquenamesListCorr, ExpressionValuesListCorr); 
            }  //sw
     
        break; //for 1st-switch after B
          
        default:
          //code as in top-neighb-rad
    }  //1st-switch (filterTypeSelected)
                      
  }  // submitNeighbors() 

  
    
    
  function draw_neighbors_heatmap (container, xData, yData, zData) {
      //xData: sampleNmaes, yData: G_anchorText (gene names as urls to gene page), zData: expression values
      var CONTAINER = document.getElementById(container);
      CONTAINER.innerHTML = "";
    
      var data_neighbors_hmap = [
          {
              x: xData,  //x:sampleNames,
              y: yData,  //y: ydata,
              z: zData,  //z: zdata,
              type: 'heatmap',
              colorbar: {title:'TPM', titleside:'right'},
              colorscale: 'Rd', //Hot,Jet,Greens*,Greys,Picnic*,Portland,RdBu,YIGnBu,YIOrRd,
                                //Bluered,Earth,
                                //Electric,Blackbody,Reds*(Rd),Blues
              ygap:0.15,
              xgap:0.15
          }
      ];

      graphicHeight = 130+(yData.length)*30;  //graphicHeight = 1000;  //alert(graphicHeight);
      
      var layout = {  
          margin: { t: 0, l:220, b: 130}, 
          height: graphicHeight
      };
      
      Plotly.newPlot(container, data_neighbors_hmap, layout);
          
  }  //fn draw_neighbors_heatmap ()
    
    

  function draw_neighbors_linePlot (container, xData, yData, zData) {
      //xData: sampleNmaes, yData: G_anchorText (gene names as urls to gene page), zData: expression values
      var CONTAINER = document.getElementById(container);
      CONTAINER.innerHTML = "";
      
      data_traces_for_scatter = []; //Constructing array of individual neighbor traces
      for (var i=0; i < zData.length; i++) {
        trace = {x: xData, y: zData[i], type: 'scatter', name: yData[i]}; //To try name:'gene_name'
        data_traces_for_scatter.push(trace);
      }

      graphicHeight = 250+(yData.length)*25; //original

      var layout = {
          margin: { t: 0, l:50, b: 250, r: 80},
          yaxis: {title: 'TPM'},
          height: graphicHeight
      };
      
      Plotly.newPlot(container, data_traces_for_scatter, layout);  // layout brings whole range into view
      
      //Scroll to bottom of page for better comprehension
      window.scrollTo(0,document.body.scrollHeight);
    
  } //fn draw_neighbors_linePlot 
   


  function make_neighbors_table (container, xData, yData, zData) {
      //xData: sampleNmaes, yData: G_anchorText (gene names as urls to gene page), zData: expression values
      
      var CONTAINER = document.getElementById(container);
      CONTAINER.innerHTML = "";
      
      table_col_names = xData; //sampleNames
      //table_row_names = G_anchorText; //neighbUniqueNames;
      
      var neighbTable = "";
      neighbTable += "<table border='1'>";
      neighbTable += "<tr>" + "<td><b>Profile neighbor (Correlation)</b></td>"; // + "<td><b>Name</b></td>";
      for (var s = 0; s < table_col_names.length; s++) {
          neighbTable += "<td><b>" + table_col_names[s] + "</b></td>";
        }  
      neighbTable += "</tr>"; // end of header row
      
      //Each neighbor row
      for (var n=0; n < yData.length; n++) {
          neighbTable += '<tr>' + '<td>' + yData[n] + '</td>';
          for (var sm = 0; sm < table_col_names.length; sm++) {
              neighbTable += "<td>" + zData[n][sm] + "</td>";
          }  
      }
      
      neighbTable += "</table>";    
      CONTAINER.innerHTML = neighbTable;
      
  }  //fn make_neighbors_table



  function get_family_members_data (container, dsetAcc, geneName, genus, species) {
      
      //AFTER TABS://Empty control, message and display containers
      document.getElementById("control_display_type_radios").innerHTML = "<br/>.....GETTING GENE FAMILY DATA FROM SERVER.....<br/>(Please wait !!! May take upto 40 secs!!!)<br/>";
      document.getElementById('message_for_display_data').innerHTML = ""; 
      document.getElementById("display_data").innerHTML = "";
            
      (function($){
                
          jQuery.ajax({ type: "GET",
                      
              url: "/lis_expression/genefamily_members/"+dsetAcc+"/"+geneName+"/json",
              success : function(responseFam)
              {     
                  responseStringFam = jQuery.trim(responseFam); //captured into another var; typeof(responseString) is string
                        //A string in json (like an assoc array)
                        //The .trim removes spaces, etc. from empty json when no family members present
                        //(returns success in ajax call??)
                      //If no json for family members error message and return
                  if (!responseStringFam) {
                      document.getElementById("control_display_type_radios").innerHTML = 'Error: No json data !!<br/>';
                      document.getElementById("display_data").innerHTML = '<br/>LIS_EXPRESSION_MODULE: <b>No gene family members found for this gene !!</b><br/>';
                      return;
                  }                  
                  jd_famMembers = JSON.parse(responseStringFam); // [Now an Obj]: JasonData-for-dataset
                  //console.log(responseStringFam);
                  document.getElementById("control_display_type_radios").innerHTML = "";
                  document.getElementById("display_data").innerHTML = "";
                  present_family_members_data ("display_data", jd_famMembers, geneName, genus, species);
                 
              },
              error : function()
              {
                  document.getElementById("display_data").innerHTML = 'ERROR: <br/>LIS_EXPRESSION_MODULE: <b>No gene family members found for this gene !!</b><br/>';
              },
              
          });   //jQ.ajax        
          
          //container.innerHTML = '<br/>Getting Data from Server !!</b><br/>'; //runs while getting data
          
    })(jQuery); // fn($)
         
  } //get_fanily_members_data ()
  

/*
 *function present_family_members_data ()
 *to be used inside 'get_family_members_data (container, dsetAcc, geneName, genus, species)'
 */
      
  function present_family_members_data (container, jsonFamData, geneName, genus, species) {
      
      jsonDataFam = jsonFamData[geneName]; //getting one level(this gene's name) deeper
      
      memberUniqueNames=Object.keys(jsonDataFam); 
      //console.log("XXXXXXXXXXXX: " + neighbUniqueNames);
      //["Phvul.006G167200.v1.0", "Phvul.002G017900.v1.0", "Phvul.002G113900.v1.0", ...]
      memberCount = memberUniqueNames.length;
      if (memberCount < 2) {document.getElementById("display_data").innerHTML = "<br/>....NO OTHER MEMBERS....<br/><br/><br/>"; return;}
      
      //Parsing the json data for each neighbor: Name-not-uniqueName, expr and corr
      //memberNames = []; // Array of neighbor names NOT Uniquenmaes
      memberExpr = [];  //Assoc array of Expr(of assoc array) for each neighbor
      //memberCorr = [];  //Assoc array of corr for each neighbor
      memberUniqueNamesString = [];
      
      for (var key in memberUniqueNames) {
          if (memberUniqueNames.hasOwnProperty(key)) {
              
              //memberUniqueNamesString.push(memberUniqueNames[key]); //BOTH ARE SAME !!! may delete this expression //["Phvul.006G167200.v1.0", "Phvul.002G017900.v1.0", ...]
              
              console.log("@@@@@@@@@" + jsonDataFam[memberUniqueNames[key]]);
              //memberNames.push(jsonData[neighbUniqueNames[key]].name);
              //["phavu.Phvul.006G167200", "phavu.Phvul.002G017900", "phavu.Phvul.002G113900", ...]
              
              //console.log(jsonData[neighbUniqueNames[key]].expr);
              memberExpr[memberUniqueNames[key]] = jsonDataFam[memberUniqueNames[key]];
              // [Phvul.006G167200.v1.0: {...}, Phvul.002G017900.v1.0: {...},...] 
              // Each has: {Leaf Young (SRR1569274): "21.480", Leaf 21 DAI (SRR1569385): "1.400", Stem (SRR1569432): "13.920", Shoot (SRR1569463): "79.480",...
              
              //console.log(jsonData[neighbUniqueNames[key]].corr);
              //neighbCorr[neighbUniqueNames[key]] = jsonData[neighbUniqueNames[key]].corr;
              //[Phvul.006G167200.v1.0: "0.979", Phvul.002G017900.v1.0: "0.97",...]
          }  //if
      }  // for
      
      
      //<<<<< Parsing expr data
      
      //Get array of sample names
                  //Keys of jsonData; then, 1st key '[0]' (the first neighbor) of json data;
                  //  get its expr; then get its keys; should get array of sample-names.
                  //  Assuming if ajax call is sucess, there will be a first key
      //sampleNames = Object.keys(jsonData[Object.keys(jsonData)[0]].expr);
      sampleNamesFam = Object.keys(memberExpr[Object.keys(memberExpr)[0]]);
      //["Leaf Young (SRR1569274)", "Leaf 21 DAI (SRR1569385)", "Stem (SRR1569432)", "Shoot (SRR1569463)",
      //"Flower (SRR1569464)", "Pod Young (SRR1569465)", ...., "Root 21 DAI (SRR1569477)"]
      
      MG_anchorText = []; // MG for MemberGene
      for (var MG in memberUniqueNames) {
          //G.push(x);
          linkToMGene = "<a  target=\"_blank\"  href=\"/feature/" + genus + "/" + species + "/gene/" + memberUniqueNames[MG] + "#pane=geneexpressionprofile" + "\"" + ">" + memberUniqueNames[MG] + "</a>";
          MG_anchorText.push(linkToMGene);
      }  //for var MG
      
      
      MV=[];  //MemberValue:  array of each Member (with hash of sample=>Value of all samples)
      for (var i=0; i < memberUniqueNames.length; i++) {  // for each member
          MV.push(memberExpr[memberUniqueNames[i]]);       // push its expr(sample:value assoc array) into MV
      }   // MV is: array of expr of each member
          // MV = [{...}, {...}, {...}, {...}, {...}, {...}, ...]; array of all members
          // Each member {...} is = {Leaf Young (SRR1569274): "3.720", Leaf 21 DAI (SRR1569385): "0.220", ...}
      
      //@
      MV2 = []; // Temp array of just-the-expr-value for a member; no sample names; gets emptied every iteration
      MVall = [];  //Array of arrays. expr value of each member in the order of samples (just the expr values, no sample names)
      for (var x = 0; x < MV.length; x++) { //for each member(=MV.length)
          for (var s=0; s < sampleNamesFam.length; s++) {   //for each sample 's' within a member
              MV2.push(MV[x][sampleNamesFam[s]]); //x-th member's s-th sample, its value
          }
          MVall.push(MV2);
          MV2 = []; //
      }  //for var x
      //MVall:
      //(50) [Array(15), Array(15), Array(15), ...] each member is an array
      //each array: (15) ["3.720", "0.220", "2.000", "15.290", "5.900", ...],
      // 0 : (15) ["3.720", "0.220", "2.000", ...]
      //1 : (15) ["4.850", "0.420", "5.120", ...]
      

            //Fieldset for choosing lineplot vs. heatmap
      
      var htmlFam = '';
      
      htmlFam += "<h3>Family Members</h3>";
      htmlFam += "(No. of members in this species: " + memberCount + ")<br/>";
      htmlFam += "<fieldset style=\"display: inline-block; padding-left: 10px;\">";
      htmlFam += "<input type=\"radio\" name=\"display_type_family\" value=\"lineplot\"  onclick=\"draw_neighbors_linePlot ('display_data', sampleNamesFam, memberUniqueNames, MVall);\"  checked > Line plot &nbsp;&nbsp;&nbsp;";
      
      htmlFam += "<input type=\"radio\" name=\"display_type_family\" value=\"heatmap\"  onclick=\"draw_neighbors_heatmap ('display_data', sampleNamesFam, MG_anchorText, MVall);\"> Heatmap** &nbsp;&nbsp;&nbsp;";
          //draw_neighbors_linePlot (container, xData, yData)
          //draw_neighbors_linePlot (container, sampleNames, Vall);
      
      htmlFam += "<input type=\"radio\" name=\"display_type_family\" value=\"table\"  onclick=\"make_neighbors_table ('display_data', sampleNamesFam, MG_anchorText, MVall);\"> Table** &nbsp;&nbsp;&nbsp;";
      
      htmlFam += "</fieldset>";
      htmlFam += "(**The <strong>heatmap & table have links</strong> to family members)";

      //container.innerHTML += JSON.stringify(neighbUniqueNames);
      document.getElementById('control_display_type_radios').innerHTML += htmlFam; //after tab

      //Initial display with draw_neighbors_linePlot() / draw_neighbors_heatmap (container, xData, yData, zData);
      //draw_neighbors_heatmap ("display_data", sampleNames, G_anchorText, Vall);
      //after-tabs
      draw_neighbors_linePlot ("display_data", sampleNamesFam, MG_anchorText, MVall);

  }   //function present_family_members_data ()



///////////////////////////////////////////////////////////////////
// SCRATCH AREA

    /*
     *
     var htmlTable = "";
    htmlTable += "<table border='1'>";
    for (x in k) {
                htmlTable += "<tr><td>" + k[x] + "</td><td>" + vals[x] +"</td></tr>";
    }
    htmlTable += "</table>"; 
    document.getElementById(container).innerHTML = htmlTable;
    */
