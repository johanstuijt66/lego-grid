# lego-grid
php grid generator with margins like lego

Inspired by this article: [Learning from Lego](https://alistapart.com/article/learning-from-lego-a-step-forward-in-modular-web-design), and also because at work they wanted a webpage designed like the image below, i started a php implementation of a GRID system. 

![requested webpage layout](https://github.com/johanstuijt66/lego-grid/blob/master/grid.jpg)

The GRID consists of ROWS and COLUMNS, and also supports a GRID-IN-A-GRID with unlimited depth. 
GRID-IN-A-GRID in necessary to create the top part of the requested webpage layout:

![requested webpage layout](https://github.com/johanstuijt66/lego-grid/blob/master/grid-depth.jpg)

The first ROW contains two COLUMNS, and the second COLUMN contains a new GRID with two ROWS.

The grid generator created the grid using DIV elements, and you supply the CSS classes which will make ome DIV a ROW, and another a COLUMN. I like using Skeleton, and with Skeleton, a `<div class="row">` is a ROW, and a `<div class="three columns">` is a column.

The API i came up with has these methods:

