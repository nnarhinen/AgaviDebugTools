<?php
/**
 * Filter for displaying couple of information :)
 * 
 * @author    Daniel Ancuta <daniel.ancuta@whisnet.pl>
 * @copyright Author
 * @version   0.1
 * 
 * @todo 
 * 1. Read information about childs
 * 2. Get information about callback class(?)
 * 3. Get information about output_type
 * 4. Add JavaScript using DOM, not str_replace
 * 5. Add CSS using DOM, not str_replace
 */
class AgaviDebugToolbarFilter extends AgaviFilter implements AgaviIGlobalFilter, AgaviIActionFilter 
{
  public function execute(AgaviFilterChain $filterChain, AgaviExecutionContainer $container) {
    $this->container = $container;
    
    $filterChain->execute($container);
    
    # We're checking if we can add AgaviDebugToolbar to response
    # If output type is one of our defined output types
    if ( !$container->getResponse()->isContentMutable() || 
        (is_array($this->getParameter('output_types')) && 
         !in_array($container->getResponse()->getOutputType()->getName(), $this->getParameter('output_types')) ) ) {
     return;
    }
    
    /**
     * Adding JS
     */
    # Mootools
    $mootoolsJs = '<script type="text/javascript" src="'.$this->getParameter('modpub').'/js/mootools-1.2.js'.'"></script>';
    $adtJs      = '<script type="text/javascript" src="'.$this->getParameter('modpub').'/js/AgaviDebugToolbar.js"></script>';
    $newContent = str_replace('</head>', $mootoolsJs."\n".$adtJs."\n</head>", $container->getResponse()->getContent() );
    
    # Load AgaviDebugToolbar template
    //TODO: handle relative and absolute paths
    $adtTemplate = file_get_contents(dirname(__FILE__) .'/'. $this->getParameter('template') );
    
    # Load routing block
    $adtTemplate = str_replace('{adtBlock_Routing}', $this->adtGetMatchedRoutesHtml(), $adtTemplate);
    
    # Load request parameters block
    $adtTemplate = str_replace('{adtBlock_Request}', $this->adtGetRequestHtml(), $adtTemplate);
    
    # Load view block
    $adtTemplate = str_replace('{adtBlock_View}', $this->adtGetViewHtml(), $adtTemplate);
    
    # Add AgaviDebugToolbar to response
    $newContent  = str_replace('</body>', $adtTemplate."\n</body>", $newContent);
    $container->getResponse()->setContent( $newContent );
    
    #echo '<pre>';
    #var_dump( $this->getContext()->getRouting()->getRoute('dupa') );
    #var_dump( $this->getContext()->getRouting()->getRoute('dupa.itsChild') );
  }
  
  /**
   * Get array with matched routes
   *
   * @return array
   * @since 0.1
   */
  private function adtGetMatchedRoutes() {
    # Array with information about matched routes, name of route is an index of array
    $matchedRoutesInformation = array();
    # Matched routes
    $matchedRoutes = $this->getContext()->getRequest()->getAttribute('matched_routes', 'org.agavi.routing');
    
    foreach( $matchedRoutes as $matchedRoute ) {
      $matchedRoutesInformation[$matchedRoute] = $this->getContext()->getRouting()->getRoute($matchedRoute);
    }
    
    return $matchedRoutesInformation;
  }
  
  /**
   * Generate HTML code for matched routes
   * 
   * @return string
   * @since 0.1
   */
  protected function adtGetMatchedRoutesHtml() {
    $matchedRoutesTemplate = '';
    
    foreach( $this->adtGetMatchedRoutes() as $matchedRouteName => $matchedRouteInfo ) {
       $matchedRoutesTemplate .= '<a id="adtMatchedRouteShow_'.md5($matchedRouteName).'" href="#"><strong>'.$matchedRouteName.'</strong></a>';
         $matchedRoutesTemplate .= '<div id="adtMatchedRouteInfo_'.md5($matchedRouteName).'" style="display: none;">';
           
           # Module and Action name
           $matchedRoutesTemplate .= '<strong>Module</strong>: '.$matchedRouteInfo['opt']['module'];
           $matchedRoutesTemplate .= '<br />';
           $matchedRoutesTemplate .= '<strong>Action</strong>: '.$matchedRouteInfo['opt']['action'];
           
           $matchedRoutesTemplate .= '<br />';
         
           # Stop
           $matchedRoutesTemplate .= '<strong>Stop</strong>: ';
           $matchedRoutesTemplate .= $matchedRouteInfo['opt']['stop']==true?'True':'False';

           $matchedRoutesTemplate .= '<br />';

           # Parameters 
           $matchedRoutesTemplate .= '<strong>Parameters</strong>: ';
           $matchedRoutesTemplate .= '<ul>';
           foreach( $matchedRouteInfo['par'] as $oneParameter ) {
              $matchedRoutesTemplate .= '<li>';
                $matchedRoutesTemplate .= '<strong>'.$oneParameter.'</strong>';
                $matchedRoutesTemplate .= '<br />';
                
                $matchedRoutesTemplate .= 'Default <br />';
                if ( isset($matchedRouteInfo['opt']['defaults'][$oneParameter]) ) {
                  $matchedRoutesTemplate .= '<ul>';
                    $matchedRoutesTemplate .= 'Pre: '.$matchedRouteInfo['opt']['defaults'][$oneParameter]['pre'];
                    $matchedRoutesTemplate .= '<br />';
                    $matchedRoutesTemplate .= 'Value: '.$matchedRouteInfo['opt']['defaults'][$oneParameter]['val'];
                    $matchedRoutesTemplate .= '<br />';
                   $matchedRoutesTemplate .= 'Post: '.$matchedRouteInfo['opt']['defaults'][$oneParameter]['post'];
                  $matchedRoutesTemplate .= '</ul>';
                } else {
                  $matchedRoutesTemplate .= ' No default ';
                }
                
                $matchedRoutesTemplate .= 'Required: ';

                if ( !isset($matchedRouteInfo['opt']['optional_parameters'][$oneParameter]) ) {
                  $matchedRoutesTemplate .= 'False';
                } else {
                  $matchedRoutesTemplate .= 'True';
                }
                
               $matchedRoutesTemplate .= '</li>';
           }
           $matchedRoutesTemplate .= '</ul>';
           
           # Output type for router
           $matchedRoutesTemplate .= '<strong>Output type</strong>: ';
           if ( empty( $matchedRouteInfo['opt']['output_type'] ) ) {
             $tmpOutputTypeName = $this->getContext()->getController()->getOutputType()->getName();
           } else {
             $tmpOutputTypeName = $matchedRouteInfo['opt']['output_type'];
           }
           $matchedRoutesTemplate .= '<a id="adtMatchedRouteOutputTypeShow_'.md5($matchedRouteName).'" href="#">'.$tmpOutputTypeName.'</a>';
           
           # Output type info
           $routeOutputType = $this->getContext()->getController()->getOutputType($tmpOutputTypeName);
           
           $matchedRoutesTemplate .= '<div id="adtMatchedRouteOutputTypeInfo_'.md5($matchedRouteName).'" style="display: none;">';
             $matchedRoutesTemplate .= 'Has renderers: ';
             $matchedRoutesTemplate .= $routeOutputType->hasRenderers()==true?'True':'False';
             $matchedRoutesTemplate .= '<br />';
             $matchedRoutesTemplate .= 'Default layout name: '.$routeOutputType->getDefaultLayoutName();
           $matchedRoutesTemplate .= '</div>';
           
           if ( strcmp($this->getContext()->getController()->getOutputType()->getName(), $matchedRouteInfo['opt']['output_type']) == 0 ) {
             $matchedRoutesTemplate .= ' ( default ) ';
           }

           $matchedRoutesTemplate .= '<br />';
           
           /*
           $matchedRoutesTemplate .= '<strong>Parameters</strong>: ';
           
           if ( count( $matchedRouteInfo['opt']['parameters'] ) != 0 ) {
             $matchedRoutesTemplate .= '<ul>';
             
             foreach( $matchedRouteInfo['opt']['parameters'] as $parameter ) {
              $matchedRoutesTemplate .= '<li>'.$parameter.'</li>';
             }
             $matchedRoutesTemplate .= '</ul>';
           } else {
            $matchedRoutesTemplate .= 'No parameters';
           }*/
           
           # Ignores
           $matchedRoutesTemplate .= 'Ignores: ';
           if ( count( $matchedRouteInfo['opt']['ignores'] ) != 0 ) {
             $matchedRoutesTemplate .= '<ul>';
             foreach( $matchedRouteInfo['opt']['ignores'] as $ignore ) {
               $matchedRoutesTemplate .= '<li>'.$ignore.'</li>';
             }
             $matchedRoutesTemplate .= '</ul>';
           } else {
             $matchedRoutesTemplate .= 'No ignores';
           }
           
           $matchedRoutesTemplate .= '<br />';
           
           # Childs of route
           $matchedRoutesTemplate .= 'Childs';
           if ( count($matchedRouteInfo['opt']['childs']) != null ) {
             $matchedRoutesTemplate .= '<ul>';
             foreach( $matchedRouteInfo['opt']['childs'] as $children ) {
               $matchedRoutesTemplate .= '<li>'.$children.'</li>';
             }
             $matchedRoutesTemplate .= '</ul>';
           }
           
           $matchedRoutesTemplate .= '<br />';
           
           # Callback class
           $matchedRoutesTemplate .= 'Callback: '.$matchedRouteInfo['opt']['callback'];
           
           $matchedRoutesTemplate .= '<br />';
           
           # Imply
           $matchedRoutesTemplate .= 'Imply: ';
           $matchedRoutesTemplate .= $matchedRouteInfo['opt']['imply']==true?'True':'False';
           
           $matchedRoutesTemplate .= '<br />';
           
           # Cut
           $matchedRoutesTemplate .= 'Cut: ';
           $matchedRoutesTemplate .= $matchedRouteInfo['opt']['cut']==true?'True':'False';
           
           $matchedRoutesTemplate .= '<br />';
           
           # RPX
           $matchedRoutesTemplate .= 'rpx: '.$matchedRouteInfo['rxp'];
         
       $matchedRoutesTemplate .= '</div>';
       $matchedRoutesTemplate .= '<br />';
    }
    
    return $matchedRoutesTemplate;
  }
  
  /**
   * Generate HTML code for request parameters
   * 
   * @return string
   * @since  0.1
   */
  protected function adtGetRequestHtml() {
    $requestParametersTemplate = '';
    
    $requestParametersTemplate .= '<ul>';
    foreach( $this->getContext()->getRequest()->getRequestData()->getParameters() as $parameter => $value ) {
      $requestParametersTemplate .= '<li>'.$parameter.': '.$value.'</li>';
    }
    $requestParametersTemplate .= '</ul>';
    
    return $requestParametersTemplate;
  }
  
  /**
   * Generate HTML code for View
   *
   * @return string 
   * @since 0.1
   */
  protected function adtGetViewHtml() {
    $viewTemplate = '';
    
    $outputType = $this->getContext()->getController()->getOutputType( $this->container->getOutputType()->getName() );

    
    $viewTemplate .= 'View name: '. $this->container->getViewName();
    $viewTemplate .= '<br />';
    $viewTemplate .= 'Output type: <a id="adtViewOutputTypeGet_'.md5($this->container->getOutputType()->getName()).'" href="#">'.$this->container->getOutputType()->getName().'</a>';
    if ( strcmp($this->getContext()->getController()->getOutputType()->getName(), $this->container->getOutputType()->getName()) == 0 ) {
      $viewTemplate .= ' ( default ) ';
    }
    $viewTemplate .= '<div id="adtViewOutputTypeInfo_'.md5($this->container->getOutputType()->getName()).'" style="display: none;">';
    $viewTemplate .= 'Has renderers: ';
    $viewTemplate .= $outputType->hasRenderers()==true?'True':'False';
    $viewTemplate .= '<br />';
    $viewTemplate .= 'Default layout name: '.$outputType->getDefaultLayoutName();
    $viewTemplate .= '</div>';
    return $viewTemplate;
  }
  
  /**
   * @var AgaviExecutionContainer
   */
  private $container = null;
}
?>