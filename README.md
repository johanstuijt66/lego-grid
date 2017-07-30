# lego-grid
php grid generator with margins like lego

Inspired by this article: [Learning from Lego](https://alistapart.com/article/learning-from-lego-a-step-forward-in-modular-web-design), and also because at work they wanted a webpage designed like the image below, i started a php implementation of a GRID system. 

![requested webpage layout](https://github.com/johanstuijt66/lego-grid/blob/master/grid.jpg)

The GRID consists of ROWS and COLUMNS, and also supports a GRID-IN-A-GRID with unlimited depth.  
GRID-IN-A-GRID in necessary to create the top part of the requested webpage layout:

![top row](https://github.com/johanstuijt66/lego-grid/blob/master/grid-depth.jpg)

The first ROW (red box) contains two COLUMNS (green boxes), and the second COLUMN contains a new GRID with two ROWS.

The grid generator creates the grid using DIV elements, and you supply the CSS classes which will make one DIV a ROW, and another a COLUMN. It depends on which CSS framework you will use (if any) to create the GRID.  
I like using Skeleton, and with Skeleton, a `<div class="row">` is a ROW, and a `<div class="three columns">` is a column.

The php class i came up with has these methods:

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

For getting the gaps between the elements as described in the AListApart article, the PUT method should wrap the given content in two nested DIV's like so:
```
<div class="element">
  <div>
    (content here)
  </div>
</div>
```
In the CSS, a padding is given to all `<div class="element">` DIV's to get the desired LEGO effect.  
Now the top-level grid still needs to be surrounded by a DIV that has the same padding.
Also, the gaps that Skeleton creates must be eliminated.

To make this happen, i wrap the top-level grid in a DIV with css class `no-skeleton-gaps`.

And in the style sheet i put:
```
.no-skeleton-gaps .column, .no-skeleton-gaps .columns { margin-left: 0; }

@media (min-width: 550px) {
  .no-skeleton-gaps .one.column     { width:   8.3333%; }
  .no-skeleton-gaps .two.columns    { width:  16.7777%; }
  .no-skeleton-gaps .three.columns  { width:  25.0000%; }
  .no-skeleton-gaps .four.columns   { width:  33.3333%; }
  .no-skeleton-gaps .five.columns   { width:  41.6666%; }
  .no-skeleton-gaps .six.columns    { width:  50.0000%; }
  .no-skeleton-gaps .seven.columns  { width:  58.3333%; }
  .no-skeleton-gaps .eight.columns  { width:  66.6666%; }
  .no-skeleton-gaps .nine.columns   { width:  75.0000%; }
  .no-skeleton-gaps .ten.columns    { width:  83.3333%; }
  .no-skeleton-gaps .eleven.columns { width:  91.6666%; }
  .no-skeleton-gaps .twelve.columns { width: 100.0000%; }
}

.element, .no-skeleton-gaps {
  padding: 10px;
  box-sizing: border-box;
}
```

And still we are not done yet!

The WIDTH's of the columns are OK now, but the HEIGHT's will vary, depending on the content that is put inside each column.   Somehow, i must force the content to have a certain apsect ratio (look back at the first image in this README, it contains contents with aspect ratio's 1:1, 2:1 and the yellow one at the bottom is 1:2).



