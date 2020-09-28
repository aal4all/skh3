<?php

  $addon = rex_addon::get('skh3') ;
  /* Addon Parameter */

  if(rex::isBackend())
  {
		rex_view::addCssFile($addon->getAssetsUrl('css/skh3.css'));
		rex_view::addCssFile($addon->getAssetsUrl('js/jquery-3.5.1.min.js'), [rex_view::JS_IMMUTABLE => true]);
		//rex_view::addJsFile($addon->getAssetsUrl('js/___.js'), [rex_view::JS_IMMUTABLE => true]);
  }
?>
