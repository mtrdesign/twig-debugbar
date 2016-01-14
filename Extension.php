<?php
/*
* @Author: mark
* @Date:   2014-07-02 16:34:30
* @Last Modified by:   mtr-design
* @Last Modified time: 2015-08-19 16:44:00
*/

namespace Bearlikelion\TwigDebugBar;

class Extension extends \Twig_Extension
{
  private $debugbar;

  public function __construct()
  {
    $this->debugbar = new \DebugBar\StandardDebugBar();
    $this->renderer = $this->debugbar->getJavascriptRenderer();

    $this->renderer->disableVendor('fontawesome');
    $this->renderer->disableVendor('jquery');
    $this->renderer->disableVendor('highlightjs');

    $debugStack = new \Doctrine\DBAL\Logging\DebugStack();
    \Helpers\Database::getManager()->getConnection()->getConfiguration()->setSQLLogger($debugStack);
    $this->debugbar->addCollector(new \DebugBar\Bridge\DoctrineCollector($debugStack));
  }

  public function getFunctions()
  {
    return array(
      'dbg_render' => new \Twig_Function_Method($this, 'render',  array('is_safe' => array('html'))),
      'dbg_renderHead' => new \Twig_Function_Method($this, 'renderHead',  array('is_safe' => array('html'))),
      'dbg_jsAssets' => new \Twig_Function_Method($this, 'dumpJsAssets',  array('is_safe' => array('html'))),
      'dbg_cssAssets' => new \Twig_Function_Method($this, 'dumpCssAssets',  array('is_safe' => array('html')))
    );
  }

  public function render()
  {
  	return $this->renderer->render();
  }

  public function renderHead()
  {
  	return $this->renderer->renderHead();
  }

    public function dumpJsAssets()
  {
    return $this->renderer->dumpJsAssets();
  }

  public function dumpCssAssets()
  {
    return $this->renderer->dumpCssAssets();
  }

  public function getDebugBar() {
    return $this->debugbar;
  }

  public function getName()
  {
  	return 'debugbar_extension';
  }
}

