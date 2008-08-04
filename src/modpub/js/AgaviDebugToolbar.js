/**
 *
 */

window.addEvent("domready", function(){

  var tabs = new SimpleTabs('sections', {
    selector: 'h2'
  });


  /**
   * Events: Routing
   *
   */
  // Show details about matched route
  $each( $$("a[id^=adtMatchedRouteShow_]"), function(el){
    el.addEvent("click", function(event){
      event.stop();

      elId = this.id.split("_");

      if ( $("adtMatchedRouteInfo_"+elId[1]).getStyle("display") == "none" ) {
        $("adtMatchedRouteInfo_"+elId[1]).setStyle("display", "block");
      } else {
        $("adtMatchedRouteInfo_"+elId[1]).setStyle("display", "none");
      }
    });
  });


  // Show details about route output type
  $each( $$("a[id^=adtMatchedRouteOutputTypeShow_]"), function(el){
    el.addEvent("click", function(event){
      event.stop();

      elId = this.id.split("_");

      if ( $("adtMatchedRouteOutputTypeInfo_"+elId[1]).getStyle("display") == "none" ) {
        $("adtMatchedRouteOutputTypeInfo_"+elId[1]).setStyle("display", "block");
      } else {
        $("adtMatchedRouteOutputTypeInfo_"+elId[1]).setStyle("display", "none");
      }
    });
  });

  /**
   * Events: View
   * /
   // Show details about output type
   $each( $$("a[id^=adtViewOutputTypeGet_]"), function(el){
    el.addEvent("click", function(event){
      event.stop();

      elId = this.id.split("_");

      if ( $("adtViewOutputTypeInfo_"+elId[1]).getStyle("display")=="none" ) {
        $("adtViewOutputTypeInfo_"+elId[1]).setStyle("display", "block");
      } else {
        $("adtViewOutputTypeInfo_"+elId[1]).setStyle("display", "none");
      }
    });
   });

   */

});
