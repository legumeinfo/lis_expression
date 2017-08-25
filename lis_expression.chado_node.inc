<?php

/**
 * Implements hook_node_view().
 *
 * @ingroup tripal_exp_profiles // MODIFY or REMOVE <<<<
 */
function lis_expression_node_view($node, $view_mode, $langcode) {
  switch ($node->type) {
    case 'chado_feature':
      // Show feature browser and counts
      if ($view_mode == 'full') {          
        
        $node->content['lis_expression'] = array(
          '#markup' => theme('lis_expression_profiles', array('node' => $node)),
          '#tripal_toc_id'    => 'geneexpressionprofile',
          '#tripal_toc_title' => 'Expression',
          '#weight' => -80,
        );
        /*For overriding the tripal_analysis_expression module's template
         *'tripal_analysis_expression/theme/templates/tripal_feature_expression.tpl.php' with
         * this module's 'tripal_exp_profiles/theme/templates/tripal_feature_expression.tpl.php' modified
         * by me to suit LIS needs.
         * 
        */
        //$node->content['tripal_feature_expression'] = array(
        //'#markup' => theme('tripal_feature_expression', array('node' => $node)),
        //'#tripal_toc_id' => 'figure',
        //'#tripal_toc_title' => 'Expression',
        //'#weight' => -99,
        //);
      } //if
      break;
  } //sw
} //f()


