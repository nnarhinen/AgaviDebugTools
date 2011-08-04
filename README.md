# Agavi Debug Tools

## About
Agavi Debug Tools is a project that aims to provide debugging tools for Agavi developers. The project got its start when Daniel Ancuta started to write a Debug Toolbar action filter for Agavi. The toolbar is still the main part of the project but the architecture has evolved a bit allowing us to keep the debug data gathering and rendering separate. This has made it possible to add other output types, namely FirePHP.

## Installation
 * Copy src/ into myproject/libs/adt/
 * Copy modpub/ into myproject/pub/modpub/adt/ (if you want to use the html output)
 * Add ADT classes to autoload.xml (adjust paths according to your project layout)

```xml
<ae:configuration environment="development.*">
 
	<!-- Required always -->
	<autoload name="AdtDebugFilter">%core.app_dir%/../libs/adt/filter/AdtDebugFilter.class.php</autoload>
 
	<!--
		for HTML output
	 -->
	<autoload name="AdtDebugToolbarFilter">%core.app_dir%/../libs/adt/filter/AdtDebugToolbarFilter.class.php</autoload>
 
 
	<!--
		for FirePHP output
	 -->
	<autoload name="AdtDebugFilter">%core.app_dir%/../libs/adt/filter/AdtDebugFilter.class.php</autoload>
	<autoload name="AdtDebugFirePhpFilter">%core.app_dir%/../libs/adt/filter/AdtDebugFirePhpFilter.class.php</autoload>
	<autoload name="AdtFirePhp">%core.app_dir%/../libs/adt/firephp/AdtFirePhp.class.php</autoload>
	<autoload name="FirePHP">%core.app_dir%/../libs/adt/vendor/FirePHPCore/FirePHP.class.php</autoload>
 
 
	<!-- optional if you want to use logging into the Log tab in the toolbar -->
	<autoload name="AdtRequestLogAppender">%core.app_dir%/../libs/adt/logging/AdtRequestLogAppender.class.php</autoload>
 
	<!-- optional if you want to use additional data sources (like propel logging)-->
	<autoload name="AdtDebugFilterDataSource">%core.app_dir%/../libs/adt/datasource/AdtDebugFilterDataSource.class.php</autoload>
 
	<!-- optional data source: Propel query logging -->
	<autoload name="AdtPropelDataSource">%core.app_dir%/../libs/adt/extras/AdtPropelDataSource.class.php</autoload>
 
	<!-- optional data source: Action timer-->
	<autoload name="AdtActionTimerDataSource">%core.app_dir%/../libs/adt/extras/AdtActionTimerDataSource.class.php</autoload>
</ae:configuration>

```

 * Register the filter in your global_filters.xml and action_filters.xml

global_filters.xml

```xml
<ae:configuration environment="development.*">
 
	<!-- FirePHP output -->
	<filter name="AdtDebugFirePhpFilter" class="AdtDebugFirePhpFilter" enabled="true">
 
		<!-- optional -->
		<ae:parameter name="datasources">
 
			<!-- Propel query logging -->
			<ae:parameter>
				<ae:parameter name="class">AdtPropelDataSource</ae:parameter>
				<ae:parameter name="parameters">
				</ae:parameter>
			</ae:parameter>
 
			<!-- Simple action execution timer-->
			<ae:parameter>
				<ae:parameter name="class">AdtActionTimerDataSource</ae:parameter>
			</ae:parameter>
		</ae:parameter>
 
	</filter>
 
 
	<!-- ENABLE ONLY ONE DEBUG FILTER -->
	<!-- Simultaneous use of both filters is not currently supported -->
 
	<!-- HTML output -->
	<filter name="AdtDebugToolbarFilter" class="AdtDebugToolbarFilter"  enabled="false">
		<!-- for what output types this filter is ran -->
		<ae:parameter name="output_types">
			<ae:parameter>html</ae:parameter>
		</ae:parameter>
 
		<!-- your modpub directory (relative to base href) -->
		<ae:parameter name="modpub">modpub/adt</ae:parameter>
 
		<ae:parameter name="template">templates/html.php</ae:parameter>
 
		<!-- for the HTML template, relative to parameter 'modpub' -->
		<ae:parameter name="css">
			<ae:parameter>css/adt.css</ae:parameter>
		</ae:parameter>
		<ae:parameter name="js">
			<ae:parameter>js/mootools-1.2.js</ae:parameter>
			<ae:parameter>js/SimpleTabs.js</ae:parameter>
			<ae:parameter>js/AgaviDebugToolbar.js</ae:parameter>
		</ae:parameter>
 
		<!-- optional -->
		<ae:parameter name="datasources">
 
			<!-- Propel query logging -->
			<ae:parameter>
				<ae:parameter name="class">AdtPropelDataSource</ae:parameter>
				<ae:parameter name="parameters">
				</ae:parameter>
			</ae:parameter>
 
			<!-- Simple action execution timer-->
			<ae:parameter>
				<ae:parameter name="class">AdtActionTimerDataSource</ae:parameter>
			</ae:parameter>
		</ae:parameter>
 
	</filter>
 
</ae:configuration>
```

action_filters.xml

```xml
<ae:configuration environment="development.*">
 
	<!-- FirePHP output -->
	<filter name="AdtDebugFirePhpFilter" class="AdtDebugFirePhpFilter" enabled="true" />
 
	<!-- ENABLE ONLY ONE DEBUG FILTER -->
	<!-- Simultaneous use of both filters is not currently supported -->
 
	<!-- HTML output -->
	<filter name="AdtDebugToolbarFilter" class="AdtDebugToolbarFilter" enabled="false" />
 
</ae:configuration>

```

This kind of dual-registering is necessary because most of the logging needs to be done by an action filter but rendering to output is best left to a global filter. If you only want to log, say, routing data or Propel queries, you can register the filter as global only. Then now action data is logged.

## Using the Logger

ADT ships with a logger appender that makes it possible pass arbitrary log lines to the debug filter.

To configure AdtRequestLogAppender add this to your logging.xml.

```xml
<loggers default="debug">
	<!-- logs only DEBUG messages -->
	<logger name="debug" class="AgaviLogger" level="AgaviLogger::DEBUG">
		<appenders>
		  <appender>AdtRequestLogAppender</appender>
		</appenders>
	</logger>
</loggers>
 
<appenders>
	<appender name="AdtRequestLogAppender" class="AdtRequestLogAppender" layout="PassthruLayout" />
</appenders>
 
<layouts>
	<layout name="PassthruLayout" class="AgaviPassthruLoggerLayout" />
</layouts>

```

... make sure logging is enabled in settings.xml ... 

```xml
<setting name="use_logging">true</setting>
```

... and start using it.

```php
<?php
	$this->context->getLoggerManager()->log('Look Ma! Me debugging!', AgaviLogger::DEBUG);
?>
```
