# lego-grid
php grid generator with margins like lego

Inspired by this article: [Learning from Lego](https://alistapart.com/article/learning-from-lego-a-step-forward-in-modular-web-design), and also because at work they wanted a webpage designed like the image below, i started a php implementation of a GRID system. 

![requested webpage layout](https://github.com/johanstuijt66/lego-grid/blob/master/grid.jpg)

The GRID consists of ROWS and COLUMNS, and also supports a GRID-IN-A-GRID with unlimited depth. 
GRID-IN-A-GRID in necessary to create the top part of the requested webpage layout:

![top row](https://github.com/johanstuijt66/lego-grid/blob/master/grid-depth.jpg)

The first ROW (red box) contains two COLUMNS (green boxes), and the second COLUMN contains a new GRID with two ROWS.

The grid generator created the grid using DIV elements, and you supply the CSS classes which will make ome DIV a ROW, and another a COLUMN. I like using Skeleton, and with Skeleton, a `<div class="row">` is a ROW, and a `<div class="three columns">` is a column.

The API i came up with has these methods:

* `public function row($class = array(), $id = "");`  
   starts a new row, with optional css-class and optional id attribute.
* `public function col($class = array());`  
   starts a new column, with optional css-class.
* `public function put($content = "");`  
   puts the content inside the column.
* `public function descend();`  
   puts a new grid inside the column (grid-in-a-grid).
* `public function emerge();`  
   emerges back from the grid-in-a-grid.
* `public function render();`  
   renders the grid (with the sub-grids in it too)

So for the top row of the requested layout, we should call:
`row,col,put,col,descend,row,col,put,col,put,row,col,put,col,put,emerge`

Many CSS grid frameworks divide a row in 12 parts, and a column may have a width of 1,2,3 .. or 12 parts. The number 12 is handy because this will allow for 1 big column, 2 equal, 3 equal, 4 equal, 6 equal or 12 equal columns inside a row.




