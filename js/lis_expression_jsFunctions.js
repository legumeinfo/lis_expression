
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
    
    //Empties both containers at start
    document.getElementById("control_display_type_radios").innerHTML = "";
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
      
      //Empty control and display containers
      document.getElementById("control_display_type_radios").innerHTML = "";
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
 *Function:
 *profile_neighbors_data_presentation () 
 *  Given json data and container, present profile_neighbors data.
 *  Json data comes from get_profile_neighbors_expression_json () in _helper_functions.inc
 *
 */

  function get_profile_neighbors_data (container, dsetAcc, geneName, genus, species) {
      
      //jsonData should be object( should convert json string to object)
   
      // KEEP: This empties the div of previous content even without success in ajax call
      //var CONTAINER_NEIGHBORS = document.getElementById(container); //FAILS, WHY??
      //var CONTAINER_NEIGHBORS = document.getElementById("display_profile_neighbors_data");
      //CONTAINER_NEIGHBORS.innerHTML = ''; //empty the container first before drawing
      //container.innerHTML = '';
      
      //var CONTAINER_NEIGHBORS2 = document.getElementById("display_profile_neighbors_data2");
      //CONTAINER_NEIGHBORS2.innerHTML = ''; //empty the container first before drawing
      //var container2 = container + "2";
      //container2.innerHTML =''; //Empty the graphing container below 'display_profile_neighbors_data2'
      
      
      //AFTER TABS://Empty control and display containers
      document.getElementById("control_display_type_radios").innerHTML = "<br/>.....GETTING PROFILE NEIGHBORs DATA FROM SERVER.....<br/><br/>";
      document.getElementById("display_data").innerHTML = "";
      
      
    
      
      (function($){
                
          jQuery.ajax({ type: "GET",

              url: "/lis_expression/profile_neighbors/"+dsetAcc+"/"+geneName+"/json",
                             
              success : function(response)
              {
                  responseString = response; //captured into another var; typeof(responseString) is string
                                              //A string in json (like an assoc array)
                  jd_profNeighb = JSON.parse(responseString); // [Now an Obj]: JasonData-for-dataset
                  //console.log(responseString);
                  //dataset_metadata_presentation ("datasetMetadata", jd_dset);
                  //CONTAINER_NEIGHBORS.innerHTML = JSON.stringify(jd_profNeighb);
                  document.getElementById("control_display_type_radios").innerHTML = "";
                  present_profile_neighbors_data("display_data", jd_profNeighb, geneName, genus, species); 
                  
                  
                  //drawLinePlot ('display_gene_data', jd_gene); // in /js/lis_expression_jsFunctions.js'
                  //Ex:
                  //drawBarPlot ('div002', jd_gene);
                 
              },
              error : function()
              {
                  document.getElementById("display_data").innerHTML = "<br/>LIS_EXPRESSION_MODULE: <b>No profile neighbors data found !!</b><br/>";
                  //CONTAINER_NEIGHBORS.innerHTML = '<br/>LIS_EXPRESSION_MODULE: <b>No profile neighbors data found !!</b><br/>';
              },
              
          });   //jQ.ajax        
          
          //CONTAINER_NEIGHBORS.innerHTML = '<br/>Getting Data from Server !!</b><br/>'; //runs while getting data
          
    })(jQuery); // fn($)
    
  }  //get_profile_neighbors_data ()



    //to be used inside 'get_profile_neighbors_data (container, dsetAcc, geneName)'
  function present_profile_neighbors_data (container, jsonNeighbData, geneName, genus, species) {
      
      //var CONTAINER_NEIGHBORS = document.getElementById(container);
      //CONTAINER_NEIGHBORS.innerHTML = ''; //empty the container first before drawing    
      
      jsonData = jsonNeighbData[geneName]; //getting one level(this gene's name) deeper
      
      neighbUniqueNames=Object.keys(jsonData); 
      //console.log("XXXXXXXXXXXX: " + neighbUniqueNames);
      //["Phvul.006G167200.v1.0", "Phvul.002G017900.v1.0", "Phvul.002G113900.v1.0", ...]

      //Parsing the json data for each neighbor: Name-not-uniqueName, expr and corr
      neighbNames = []; // Array of neighbor names NOT Uniquenmaes
      neighbExpr = [];  //Assoc array of Expr(of assoc array) for each neighbor
      neighbCorr = [];  //Assoc array of corr for each neighbor
      neighbUniqueNamesString = [];
      
      for (var key in neighbUniqueNames) {
          if (neighbUniqueNames.hasOwnProperty(key)) {
              
              neighbUniqueNamesString.push(neighbUniqueNames[key]); //BOTH ARE SAME !!! may delete this expression //["Phvul.006G167200.v1.0", "Phvul.002G017900.v1.0", ...]
              
              //console.log(jsonData[neighbUniqueNames[key]]["name"]);
              neighbNames.push(jsonData[neighbUniqueNames[key]].name);
              //["phavu.Phvul.006G167200", "phavu.Phvul.002G017900", "phavu.Phvul.002G113900", ...]
              
              //console.log(jsonData[neighbUniqueNames[key]].expr);
              neighbExpr[neighbUniqueNames[key]] = jsonData[neighbUniqueNames[key]].expr;
              // [Phvul.006G167200.v1.0: {...}, Phvul.002G017900.v1.0: {...},...] 
              // Each has: {Leaf Young (SRR1569274): "21.480", Leaf 21 DAI (SRR1569385): "1.400", Stem (SRR1569432): "13.920", Shoot (SRR1569463): "79.480",...
              
              //console.log(jsonData[neighbUniqueNames[key]].corr);
              neighbCorr[neighbUniqueNames[key]] = jsonData[neighbUniqueNames[key]].corr;
              //[Phvul.006G167200.v1.0: "0.979", Phvul.002G017900.v1.0: "0.97",...]
          }
      }
      
      //Link of neighbors(Name-not-UniqueNmame) to Legume Mine
      urlStringToLegumeMine = "https://intermine.legumefederation.org/legumemine/bag.do?type=Gene&text="  +
              neighbNames.join("%0A");
      //"https://intermine.legumefederation.org/legumemine/bag.do?type=Gene&text=phavu.Phvul.006G167200%0A
      //phavu.Phvul.002G017900%0Aphavu.Phvul.002G113900%0A.....

      
      //<<<<< Parsing expr data
      
      //Get array of sample names
                  //Keys of jsonData; then, 1st key '[0]' (the first neighbor) of json data;
                  //  get its expr; then get its keys; should get array of sample-names.
                  //  Assuming if ajax call is sucess, there will be a first key
      sampleNames = Object.keys(jsonData[Object.keys(jsonData)[0]].expr);
      //["Leaf Young (SRR1569274)", "Leaf 21 DAI (SRR1569385)", "Stem (SRR1569432)", "Shoot (SRR1569463)",
      //"Flower (SRR1569464)", "Pod Young (SRR1569465)", ...., "Root 21 DAI (SRR1569477)"]

      //Neighbor uniq-name to anchored text
      G_anchorText = []; 
      for (var G in neighbUniqueNames) {
          //G.push(x);
          linkToGene = "<a  target=\"_blank\"  href=\"/feature/" + genus + "/" + species + "/gene/" + neighbUniqueNames[G] + "#pane=geneexpressionprofile" + "\"" + ">" + neighbUniqueNames[G] + "</a>" + " (" + neighbCorr[neighbUniqueNames[G]] + ")";
          G_anchorText.push(linkToGene);
      }
      
      V=[];  //array of each neighbor (with hash of sample=>value of all samples)
      for (var i=0; i < neighbUniqueNames.length; i++) {  // for each neighbor
          V.push(neighbExpr[neighbUniqueNames[i]]);       // push its expr(sample:value assoc array) into V
      }   // V is: array of expr of each neighbor
          // V = [{...}, {...}, {...}, {...}, {...}, {...}, ...]; array of all neighbors
          // Each neighbor {...} is = {Leaf Young (SRR1569274): "3.720", Leaf 21 DAI (SRR1569385): "0.220", ...}
      
      V2 = []; // Temp array of just-the-expr-value for a neighbor; no sample names; gets emptied every iteration
      Vall = [];  //Araay of arrays. expr value of each neighbor in the order of samples (just the expr values, no sample names)
      for (i = 0; i < V.length; i++) { //for each neighbor(=V.length)
          for (s=0; s < sampleNames.length; s++) {   //for each sample 's' within a neighbor
               // 'k'  IS MISSING HERE??? sample_names array
              //console.log(V[i][k[c]]);
              V2.push(V[i][sampleNames[s]]); //i-th neighbor's s-th sample, its value
          }
          Vall.push(V2);
          V2 = []; //
      }
      //Vall:
      //(50) [Array(15), Array(15), Array(15), ...] each neighbor is an array
      //each array: (15) ["3.720", "0.220", "2.000", "15.290", "5.900", ...],
      // 0 : (15) ["3.720", "0.220", "2.000", ...]
      //1 : (15) ["4.850", "0.420", "5.120", ...]
      
      //>>>>>>
      
      //Fieldset for choosing lineplot vs. heatmap
      var html = '';
      html += "<h3>Profile Neighbors</h3>";
      html += "(Genes with similar expression profile, with r &#8805 0.8; top 25 for now.)&nbsp;&nbsp;&nbsp;<br/>";
      html += "<fieldset style=\"display: inline-block; padding-left: 10px;\">";
      //html += "<input type=\"radio\" name=\"display_type_neighbors\" value=\"lineplot\"  onclick=\"draw_neighbors_linePlot ('display_profile_neighbors_data2', sampleNames, neighbNames, Vall);\"  checked > Line plot &nbsp;&nbsp;&nbsp;";
      html += "<input type=\"radio\" name=\"display_type_neighbors\" value=\"lineplot\"  onclick=\"draw_neighbors_linePlot ('display_data', sampleNames, neighbNames, Vall);\"  checked > Line plot &nbsp;&nbsp;&nbsp;";
      
      //html += "<input type=\"radio\" name=\"display_type_neighbors\" value=\"heatmap\"  onclick=\"draw_neighbors_heatmap ('display_profile_neighbors_data2', sampleNames, G_anchorText, Vall);\"> Heatmap** &nbsp;&nbsp;&nbsp;";
      html += "<input type=\"radio\" name=\"display_type_neighbors\" value=\"heatmap\"  onclick=\"draw_neighbors_heatmap ('display_data', sampleNames, G_anchorText, Vall);\"> Heatmap** &nbsp;&nbsp;&nbsp;";
      
      //html += "<input type=\"radio\" name=\"display_type_neighbors\" value=\"table\"  onclick=\"make_neighbors_table ('display_profile_neighbors_data2', sampleNames, G_anchorText, Vall);\"> Table &nbsp;&nbsp;&nbsp;";
      html += "<input type=\"radio\" name=\"display_type_neighbors\" value=\"table\"  onclick=\"make_neighbors_table ('display_data', sampleNames, G_anchorText, Vall);\"> Table** &nbsp;&nbsp;&nbsp;";
      
      html += "</fieldset>";
      html += "(**The <strong>heatmap & table have links</strong> to profile neighbors)";
      //link to legumemine
      html += "<br/>" + "<a target=\"_blank\"    href=\"" + urlStringToLegumeMine + "\">" + "<b>&#8694 &nbsp;&nbsp;Send the list of profile neighbors to LegumeMine for further analysis.</b></a>" + "<br/>"; //9758:hand pointing;
      
      
      
      
      //CONTAINER_NEIGHBORS.innerHTML += html;//before tab
      document.getElementById('control_display_type_radios').innerHTML += html; //after tab
      //CONTAINER_NEIGHBORS.innerHTML += JSON.stringify(neighbUniqueNames);

      //Initial display with draw_neighbors_linePlot / draw_neighbors_heatmap (container, xData, yData, zData);
      //draw_neighbors_heatmap ("display_profile_neighbors_data2", sampleNames, G_anchorText, Vall);
      //draw_neighbors_linePlot ("display_profile_neighbors_data2", sampleNames, G_anchorText, Vall);
      //after-tabs:
      draw_neighbors_linePlot ("display_data", sampleNames, G_anchorText, Vall);
      
      //Croll to bottom. The base of graph visible
      //$('html,body').animate({scrollTop: document.body.scrollHeight},"fast")(jQuery);
      window.scrollTo(0,document.body.scrollHeight);
     

//<<<<<<<<<<<<<<<<<<<<<<<<<< to a fn()
/*
    //ydata = G;
    ydata = G_anchorText;  //the gene names are now urls to the gene page.
    zdata = Vall;
    
    var data_neighbors_hmap = [
      {
        x:sampleNames,
        y: ydata,
        z: zdata,
        type: 'heatmap',
  colorbar: {title:'TPM', titleside:'right'},
        //colorscale: 'Picnic',
        colorscale: 'Rd',   //Hot,Jet,Greens*,Greys,Picnic*,Portland,RdBu,YIGnBu,YIOrRd,Bluered,Earth,Electric,Blackbody,Reds*(Rd),Blues
        ygap:0.15,
        xgap:0.15
      }
    ];
    graphicHeight = 130+(neighbUniqueNames.length)*30;
    //graphicHeight = 1000;
    //alert(graphicHeight);
    
    var layout = {  
      margin: { t: 0, l:220, b: 130},
      height: graphicHeight
    };
    
    //Plotly.newPlot(container, data_neighbors_hmap, layout);
    Plotly.newPlot("display_profile_neighbors_data2", data_neighbors_hmap, layout);//"display_profile_neighbors_data2"
*/
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>     

     
      //for (var key in neighbUniqueNames) {
      //    if (neighbUniqueNames.hasOwnProperty(key)) {
      //        //console.log(jsonData[neighbUniqueNames[key]]["name"];
      //        //console.log(jsonData[neighbUniqueNames[key]]["expr"]);
      //      //console.log(jsonData[neighbUniqueNames[key]]["expr"]["Stem (SRR1569432)"]);
      //      
      //    }
      //} //for
    
  } //present_profile_neighbors_data ()
    
    
    
    
  function draw_neighbors_heatmap (container, xData, yData, zData) {
      //xData: sampleNmaes, yData: G_anchorText;  //the gene names as urls to gene page., zData: expression values
      var CONTAINER = document.getElementById(container);
      CONTAINER.innerHTML = "";
      
    //ydata = G_anchorText;  //the gene names are now urls to the gene page.
    //zdata = Vall;
    
    var data_neighbors_hmap = [
      {
        x: xData,  //x:sampleNames,
        y: yData,  //y: ydata,
        z: zData,  //z: zdata,
        type: 'heatmap',
  colorbar: {title:'TPM', titleside:'right'},
        //colorscale: 'Picnic',
        colorscale: 'Rd',   //Hot,Jet,Greens*,Greys,Picnic*,Portland,RdBu,YIGnBu,YIOrRd,Bluered,Earth,Electric,Blackbody,Reds*(Rd),Blues
        ygap:0.15,
        xgap:0.15
      }
    ];
    //graphicHeight = 130+(neighbUniqueNames.length)*30;  //ACTUAL
    graphicHeight = 130+(yData.length)*30;
    //graphicHeight = 1000;
    //alert(graphicHeight);
    
    var layout = {  
      margin: { t: 0, l:220, b: 130},
      height: graphicHeight
    };
    
    //Plotly.newPlot(container, data_neighbors_hmap, layout);
    Plotly.newPlot(container, data_neighbors_hmap, layout);//"display_profile_neighbors_data2" 
          
  }  //fn draw_neighbors_heatmap ()
    
    

  function draw_neighbors_linePlot (container, xData, yData, zData) {
      //xData: sampleNmaes, yData: G_anchorText;  //the gene names as urls to gene page., zData: expression values
      var CONTAINER = document.getElementById(container);
      CONTAINER.innerHTML = "";
      
      //data_traces_for_scatter = []; //Constructing array of individual neighbor traces

      
      //var data_traces_for_scatter = [
      //    trace = {
      //        x: xData,  //x:sampleNames,
      //        y: yData,  //y: ydata,
      //        type: 'scatter'
      //    }
      //];
      
      data_traces_for_scatter = []; //Constructing array of individual neighbor traces
    for (var i=0; i < zData.length; i++) {
      trace = {x: xData, y: zData[i], type: 'scatter', name: yData[i]}; //To try name:'gene_name'
      data_traces_for_scatter.push(trace);
    }
    
    
    
    graphicHeight = 130+(yData.length)*25; //original

    var layout = {
        margin: { t: 0, l:50, b: 130},
        yaxis: {title: 'TPM'},
        height: graphicHeight
    };
    
    Plotly.newPlot(container, data_traces_for_scatter, layout);  // layout brings whole range into view
    
    ///Scroll to bottom of page for better visibility
    window.scrollTo(0,document.body.scrollHeight);
    
  } //fn draw_neighbors_linePlot 
   


  function make_neighbors_table (container, xData, yData, zData) {
      //xData: sampleNmaes, yData: G_anchorText;
      //the gene names as urls to gene page., zData: expression values
      
      var CONTAINER = document.getElementById(container);
      CONTAINER.innerHTML = "";
      
      table_col_names = xData; //sampleNames
      //table_row_names = G_anchorText; //neighbUniqueNames;
      
      var neighbTable = "";
      neighbTable += "<table border='1'>";
      neighbTable += "<tr>" + "<td><b>Profile neighbor (Correlation)</b></td>"; // + "<td><b>Name</b></td>";
      for (var s = 0; s < table_col_names.length; s++) {
          neighbTable += "<td><b>" + table_col_names[s] + "</b></td>";
        }                 //+ "<td></td><td></td><td></td><td></td>
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
      //IN DIV-id="display_family_members_data"
      
      //jsonData should be object( should convert json string to object)
   
      // KEEP: This empties the div of previous content even without success in ajax call
      //FAILS why? // var CONTAINER_NEIGHBORS = document.getElementById(container);
      //CONTAINER_FAM_MEMBERS = document.getElementById("display_family_members_data");
      //CONTAINER_FAM_MEMBERS.innerHTML = ''; //empty the container first before drawing
      //container.innerHTML = '';
      
      //var CONTAINER_FAM_MEMBERS2 = document.getElementById("display_family_members_data2");
      //CONTAINER_FAM_MEMBERS2.innerHTML = ''; //empty the container first before drawing
      //var containerFam2 = container + "2";
      //containerFam2.innerHTML =''; //Empty the graphing container below 'display_profile_neighbors_data2'
      
      
      //AFTER TABS://Empty control and display containers
      //document.getElementById("control_display_type_radios").innerHTML = "";
      document.getElementById("control_display_type_radios").innerHTML = "<br/>.....GETTING GENE FAMILY DATA FROM SERVER.....<br/>(Please wait !!! May take upto 40 secs!!!)<br/>";
      document.getElementById("display_data").innerHTML = "";
      
      //$("#wait").css("display", "block")(jQuery);

      
      (function($){
                
          jQuery.ajax({ type: "GET",
                      
              url: "/lis_expression/genefamily_members/"+dsetAcc+"/"+geneName+"/json",
                             
              success : function(responseFam)
              {
                  
                  responseStringFam = jQuery.trim(responseFam); //captured into another var; typeof(responseString) is string
                                              //A string in json (like an assoc array)
                                              //The .trim removes spaces, etc. from empty json when no family members present
                                              //(returns success in ajax call??)
        //DEL//jd_profNeighb = JSON.parse(responseString); // [Now an Obj]: JasonData-for-dataset
                      //If no json for family members error message and return
                  if (!responseStringFam) {
                    document.getElementById("control_display_type_radios").innerHTML = 'Error: No json data !!<br/>';
                    document.getElementById("display_data").innerHTML = '<br/>LIS_EXPRESSION_MODULE: <b>No gene family members found for this gene !!</b><br/>'; return;}                  
                  jd_famMembers = JSON.parse(responseStringFam); // [Now an Obj]: JasonData-for-dataset
                  //console.log(responseStringFam);
                  //dataset_metadata_presentation ("datasetMetadata", jd_dset);
                  //CONTAINER_FAM_MEMBERS.innerHTML = "***" + JSON.stringify(jd_famMembers) + "<hr style='width:400px'  />";
        //DEL//present_profile_neighbors_data("display_profile_neighbors_data", jd_profNeighb, geneName, genus, "species);
                  document.getElementById("control_display_type_radios").innerHTML = "";
                  document.getElementById("display_data").innerHTML = "";
                  
                  
                  present_family_members_data ("display_data", jd_famMembers, geneName, genus, species);
                  
                  
                  //drawLinePlot ('display_gene_data', jd_gene); // in /js/lis_expression_jsFunctions.js'
                  //Ex:
                  //drawBarPlot ('div002', jd_gene);
                  
                 
              },
              error : function()
              {
                  //CONTAINER_FAM_MEMBERS.innerHTML = '<br/>LIS_EXPRESSION_MODULE: <b>No gene family members found for this gene !!</b><br/>';
                  document.getElementById("display_data").innerHTML = 'ERROR: <br/>LIS_EXPRESSION_MODULE: <b>No gene family members found for this gene !!</b><br/>';
              },
              
          });   //jQ.ajax        
          
          //CONTAINER_FAM_MEMBERS.innerHTML = '<br/>Getting Data from Server !!</b><br/>'; //runs while getting data
          
    })(jQuery); // fn($)
      
      
  } //get_fanily_members_data ()
  


      //to be used inside 'get_family_members_data (container, dsetAcc, geneName, genus, species)'
  function present_family_members_data (container, jsonFamData, geneName, genus, species) {
      //TO DO
      //var CONTAINER_FAM_MEMBERS = document.getElementById(container);
      //CONTAINER_FAM_MEMBERS.innerHTML = ''; //empty the container first before drawing    
      
      
      
      
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
      
      
      //CONTAINER_FAM_MEMBERS.innerHTML += htmlFam; //before-tab
      //CONTAINER_NEIGHBORS.innerHTML += JSON.stringify(neighbUniqueNames);
      document.getElementById('control_display_type_radios').innerHTML += htmlFam; //after tab

      //Initial display with draw_neighbors_linePlot / draw_neighbors_heatmap (container, xData, yData, zData);
      //draw_neighbors_linePlot ("display_family_members_data2", sampleNamesFam, MG_anchorText, MVall); //before tab
      //draw_neighbors_heatmap ("display_profile_neighbors_data2", sampleNames, G_anchorText, Vall);
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