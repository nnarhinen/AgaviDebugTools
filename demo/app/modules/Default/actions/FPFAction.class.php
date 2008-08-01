<?php
class Default_FPFAction extends AdtDemoDefaultBaseAction
{
  public function executeRead(AgaviRequestDataHolder $rd) {
    return 'Input';
  }
  
  public function executeWrite(AgaviRequestDataHolder $rd) {
    return 'Input';
  }
  
  public function handleError(AgaviRequestDataHolder $rd) {

    return 'Input';
  }
}
?>