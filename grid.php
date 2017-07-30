<?php

class Grid
{
  private $level;
  private $wrap_in_class;

  public function __construct($wrap_in_class = NULL)
  {
    $this->level = new Level(1);
    $this->wrap_in_class = $wrap_in_class;
  }

  /* Start a new ROW with optional ATTRIBS */
  public function row($class = array(), $id = NULL)
  {
    $attribs = $this->make_attribs($class);

    if($id)
    {
      $attribs["id"] = $id;
    }

    $this->level->row($attribs);

    return $this;
  }

  /* Start a new COLUMN with optional ATTRIBS */
  public function col($class = array())
  {
    $attribs = $this->make_attribs($class);

    $this->level->col($attribs);

    return $this;
  }

  /* Add content to current COLUMN */
  public function put($content = "")
  {
    $this->level->put($content);

    return $this;
  }

  /* Start new Level in current COLUMN */
  public function descend()
  {
    $this->level->descend();

    return $this;
  }

  /* End the current level */
  public function emerge()
  {
    $this->level->emerge();

    return $this;
  }

  /* Render this layout */
  public function render()
  {
    ob_start();

    $this->level->render();

    $content = ob_get_contents();

    ob_end_clean();

    if($this->wrap_in_class)
    {
      $content = "<div class=\"".$this->wrap_in_class."\">".$content."</div>";
    }

    return $content;
  }

  // make sure attribs will be an array
  private function make_attribs($class = array())
  {
    $attribs = array();
    if(is_array($class))
    {
      return $class;
    }

    if($class = strval($class))
    {
      return array("class" => $class);
    }

    return array();
  }
}

class Level
{
  private $rows = array();
  private $current_row = NULL;
  private $level;
  private $num;

  public function __construct($num)
  {
    $this->level = $this;
    $this->num = $num;
  }

  public function row($attribs = array())
  {
    if($this->level == $this)
    {
      $this->rows[] = ($this->current_row = new Row($attribs));
    }
    else
    {
      $this->level->row($attribs);
    }
  }

  public function col($attribs = array())
  {
    if($this->level == $this)
    {
      $this->current_row->col($attribs);
    }
    else
    {
      $this->level->col($attribs);
    }
  }

  public function put($content)
  {
    if($this->level == $this)
    {
      $this->current_row->put($content);
    }
    else
    {
      $this->level->put($content);
    }
  }

  public function descend()
  {
    if($this->level == $this)
    {
      $descendant = new Level($this->num + 1);

      $this->put_descendant($descendant);

      $this->level = $descendant;
    }
    else
    {
      $this->level->descend();
    }
  }

  public function put_descendant($descendant)
  {
    if($this->level == $this)
    {
      $this->current_row->put_descendant($descendant);
    }
    else
    {
      $this->level->put_descendant($descendant);
    }
  }

  public function emerge()
  {
    if($this->level != $this)
    {
      if($this->level->level == $this->level)
      {
        $this->level = $this;
      }
      else
      {
        $this->level->emerge();
      }
    }
  }

  public function render($indent = "  ")
  {
    foreach($this->rows as $row)
    {
      $row->render($indent);
    }
  }

  public function debug($indent = "")
  {
    echo $indent . "Level ".$this->num." \n";

    foreach($this->rows as $row)
    {
      $row->debug($indent . "  ");
    }
  }
}

class Row
{
  private $attribs = array();
  private $cols = array();
  private $current_col;

  public function __construct($attribs = array())
  {
    $this->attribs = $attribs;
  }

  public function col($attribs = array())
  {
    $this->cols[] = ($this->current_col = new Col($attribs));
  }

  public function put($content)
  {
    $this->current_col->put($content);
  }

  public function put_descendant($descendant)
  {
    $this->current_col->put_descendant($descendant);
  }

  public function render($indent = "")
  {
    echo $indent . '<div'.(new Compiledattribs($this->attribs)).'>' . "\n";

    foreach($this->cols as $col)
    {
      $col->render($indent . "  ");
    }

    echo $indent . "</div>\n";
  }

  public function debug($indent = "")
  {
    echo $indent . "Row(".$this->attribs.")\n";

    foreach($this->cols as $col)
    {
      $col->debug($indent . "  ");
    }

    return $this;
  }
}

class Col
{
  private $attribs;
  private $content;

  public function __construct($attribs = array())
  {
    $this->attribs = $attribs;
  }

  public function put($content)
  {
    $this->content = new Content($content);
  }

  public function put_descendant($descendant)
  {
    $this->content = $descendant;
  }

  public function render($indent = "")
  {
    echo $indent . '<div'.(new Compiledattribs($this->attribs)).'>' . "\n";

    $this->content->render($indent."  ");

    echo $indent . "</div>\n";
  }

  public function debug($indent = "")
  {
    echo $indent . "Col(".$this->attribs.")\n";

    $this->content->debug($indent . "  ");

    return $this;
  }
}

class Content
{
  private $content;

  public function __construct($content)
  {
    $this->content = $content;
  }

  public function render($indent = "")
  {
    echo $indent . $this->content . "\n";
  }

  public function debug($indent = "")
  {
    echo $indent . $this->content . "\n";
  }
}

// this one is copied from kohana framework
class Compiledattribs
{
  private $attribs = array();

  public function __construct($attribs = array())
  {
    $this->attribs = $attribs;
  }

  public function __toString()
  {
    $compiled = '';
    foreach ($this->attribs as $key => $value)
    {
      if ($value === NULL)
      {
        // Skip attributes that have NULL values
        continue;
      }

      if (is_int($key))
      {
        // Assume non-associative keys are mirrored attributes
        $key = $value;
      }

      // Prevent (XSS) attacks
      $value = htmlspecialchars( (string) $value, ENT_QUOTES, 'utf-8', TRUE);

      // Add the attribute value
      $compiled .= ' '.$key.'="'.$value.'"';
    }

    return $compiled;
  }
}
