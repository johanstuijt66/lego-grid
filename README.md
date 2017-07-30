# lego-grid
php grid generator with gaps like lego

Inspired by this article: [Learning from Lego](https://alistapart.com/article/learning-from-lego-a-step-forward-in-modular-web-design), and also because at work they wanted a webpage as shown below, i started a php implementation of a *grid*. 

![requested webpage layout](https://github.com/johanstuijt66/lego-grid/blob/master/grid.jpg)

The grid is made of *rows* containing *columns* containing *content*. Also a *grid inside a column* must be supported if we examine the top part of the webpage layout:

![top row](https://github.com/johanstuijt66/lego-grid/blob/master/grid-depth.jpg)

The first row (red) contains two columns (green), and the second column contains a new grid with two rows.

The implementation creates the grid using *div* elements, and you have to supply the css classes which will make one div a *row*, and another div a *column*. It depends on which css framework you use (if any) to create the grid.  
With Skeleton for instance, a `<div class="row">` is a row, and a `<div class="three columns">` is a column that spans a quarter of the total width of the row it sits in.

Grid.php offers the following methods:

* `public function row($class = array(), $id = "");`  
   adds a new row, with optional css-class (`"row"` for Skeleton) and optional id attribute.
* `public function col($class = array());`  
   adds a new column to the last added row, with optional css-class (`"xxx columns"` for Skeleton).
* `public function put($content = "");`  
   puts the content inside the last added column.
* `public function descend();`  
   adds a new grid inside the last added column (grid-in-a-grid).
* `public function emerge();`  
   returns back from the last grid that was descended into.
* `public function render();`  
   renders the grid (including all sub-grids inside it)

So for the top row of the requested layout, we should call:
`row,col,put,col,descend,row,col,put,col,put,row,col,put,col,put,emerge`

For getting the gaps between the elements as described in the AListApart article, the *put* method should wrap the given content in two nested div's like so:
```
<div class="element">
  <div>
    (content)
  </div>
</div>
```
A padding is given to all `<div class="element">` div's to get the desired LEGO effect.  
Only the top-level grid still needs to be surrounded by a div that has the same padding.
Also, the gaps that Skeleton creates by default must be eliminated.

To make this happen, i wrap the top-level grid in a div with css class `no-skeleton-gaps`, and re-define the column widths:
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
As you can see, below 550px the grid falls apart and all div's will render below each other at 100% width.

But we are not done yet!

The width's of the columns are OK now, but the *height's* may vary, depending on the content that is put inside each column.   Somehow, i must force the content to have a certain apsect ratio (look back at the first image above, it contains content blocks with aspect ratio's 1:1, 2:1 and the yellow one at the bottom is 1:2).

After some searching on the web i found that `padding` (again) can really be your friend:
```
<div class="aspect-ratio aspect-ratio-1-1">
  <div>
    (content)
  </div>
</div>

css:
.aspect-ratio {
  position: relative;
}

.aspect-ratio-1-1 {
  padding-bottom: 100%;
}

.aspect-ratio > div {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  overflow: hidden; /* or scroll if you like */
}
```
The `padding-bottom` forces the outer div to become as high as it is wide.  
The inner div is positioned absolute and laid over the outer div. `Overflow: hidden` prevents content sticking out.

So finally, Skeletongrid will wrap each piece of content like this:
```
<div class="element">
  <div class="$css">
    <div>
      (content)
    </div>
  </div>
</div>
```
The `$css` variable must be set before the call to `put()` using an extra `css()` method.  
Before calling `put()` to put some content inside a square column, you call `css("aspect-ratio aspect-ratio-1-1")`. 
I could also have added an extra (optional) argument to the `put()` method of course, but then we would repeat that argument lots of times (the webpage contains mostly square parts).

Aspect ratio 1:2 needs a little trick:
```
.aspect-ratio-1-2 {
  padding-top: 20px;
  padding-bottom: 200%;
}
```
This box is twice as tall as it is wide, because of `padding-bottom: 200%`, but we also need to make it a bit more tall because of the horizontal gap that also must be taken into account! And then `padding-top: 20px` gives us the exact extra height.

Apect ratio 2:1 is simpler:
```
.aspect-ratio-2-1 {
  padding-bottom: 50%;
}
```

That's it!
