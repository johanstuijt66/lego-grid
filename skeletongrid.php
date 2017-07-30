<?php

class Skeletongrid
{
  private $grid;
  private $css = "";

  public function __construct($wrap_in_class = NULL)
  {
    $this->grid = new Grid($wrap_in_class);
  }

  public function row($class = array(), $id = NULL)
  {
    $attribs = array();
    if(is_array($class))
    {
      $attribs = $class;
    }
    else
    {
      if($class = strval($class))
      {
        $attribs["class"] = $class;
      }
    }

    $class = (isset($attribs['class']) ? $attribs['class'] : "");

    $attribs['class'] = trim($class . " row");

    $this->grid->row($attribs, $id);

    return $this;
  }

  public function css($css = "")
  {
    $this->css = $css;

    return $this;
  }

  public function one() { return $this->col("one column"); }
  public function two() { return $this->col("two columns"); }
  public function three() { return $this->col("three columns"); }
  public function four() { return $this->col("four columns"); }
  public function five() { return $this->col("five columns"); }
  public function six() { return $this->col("six columns"); }
  public function seven() { return $this->col("seven columns"); }
  public function eight() { return $this->col("eight columns"); }
  public function nine() { return $this->col("nine columns"); }
  public function ten() { return $this->col("ten columns"); }
  public function eleven() { return $this->col("eleven columns"); }
  public function twelve() { return $this->col("twelve columns"); }

  public function col($class = NULL)
  {
    $this->grid->col($class);

    return $this;
  }

  public function put($content = "")
  {
    $this->grid->put($this->lego($content));

    return $this;
  }

  public function descend()
  {
    $this->grid->descend();

    return $this;
  }

  public function emerge()
  {
    $this->grid->emerge();

    return $this;
  }

  public function render()
  {
    return $this->grid->render();
  }

  private function lego($content)
  {
    return '<div class="element">'
         . '<div class="'.$this->css.'">'
         . '<div>'
         . $content
         . '</div>'
         . '</div>'
         . '</div>';
  }
}
