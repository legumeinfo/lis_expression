<<<ABC
    <!--  Select display_type; radio buttons -->
    <fieldset style="display: inline-block; padding-left: 10px;">
    <input type="radio" name="display_type" value="plot"  onclick="drawLinePlot (CONTAINER_GENE);"  checked > Line plot &nbsp;&nbsp;&nbsp;
    <input type="radio" name="display_type" value="bar"  onclick="drawBarPlot(CONTAINER_GENE);"> Bar graph &nbsp;&nbsp;&nbsp;
    <input type="radio" name="display_type" value="heatmap"  onclick="drawHeatmap(CONTAINER_GENE)" > Heatmap &nbsp;&nbsp;&nbsp;
    <input type="radio" name="display_type" value="table" onclick="document.getElementById('display_gene_data').innerHTML = div_content_table;"> Table <br/>
    </fieldset>
    <div>For gene model: <b><?php echo $gene_uniquename; ?></b></div> <!-- **May have to remove it -->
ABC;
