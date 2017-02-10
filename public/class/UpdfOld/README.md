Updf
==========
2017-02-08


A helper class to generate pdf documents.


Updf is part of the [universe framework](https://github.com/karayabin/universe-snapshot) and
uses the [tcpdf library](https://tcpdf.org/) (I used 6.2.13).



Features
============

- based on html templates
- use of themes
- stacked components approach



The basic idea
=================

Creating a pdf document can be a daunting task, especially if you don't know where to start. 

Well guess what, start from the top, and do it row by row.

Every time you create a row, save it as a component, so that you can re-use it later.

Then all the sudden, that pdf becomes a stack of re-usable rows, you just need to assemble
the rows in the order you want.


I first saw this idea using an online email editor, which allowed to compose an email 
just by clicking the components in a left menu.
Composing an email with this technique takes 5 clicks for 5 components.

And you can of course remove/recreate your components at will.

A huge time saver!

The perhaps greatest benefit of this technique, from a developer's perspective, is that 
you can work on a component on its own, at your own pace, and achieving a component is
a lot easier than achieving the whole pdf at once.



Which language?
-----------------
But then, how do you create the pdf components?

Do you use the tcpdf methods?

No, you don't have time to learn a new system (or if you have the time, go ahead and learn).

The good news is that tcpdf has a writeHTML method which interprets "some" html.

So, why not just use html and let tcpdf do the hard work?

You won't be able to use crazy things like the latest css3 techniques, but if you stick
to the basis that should do it.

That's the approach that Updf takes: code the pdf content using only html.



Html table is your friend
--------------------------

Html tables can stack very well, so well that you could pile different tables 
on top of each others and they would look like one big html table.

Also, if you set border=0, then you can use an html table as an alignment tool
for other elements like your company logo, and some text.

Personally, I like to design my pdf with html tables as long as I can.


Theming
------------

So if you divide your pdf in standalone stackable components (aka rows), then what if you decide
to change the background colors of all your tables from green to red for instance?

If you don't have a plan for it, chances are that you would have to re-open every component
and make the color update manually, that sucks...

Theming is spread out accross all components, and we should be able to change the theme only,
and it should feed all components automatically.

To do so, we need some convention.

The convention that every component's customizable part can be changed from an external theme object
that does not belong to the component.

Since we are doing html though, the implementation will be inside the html code.

Updf uses tags, which are html friendly, as general purpose variables.
A tag is wrapped with two underscores on each side (so four underscores total).
So for instance, __my_var__ is a reference to the my_var variable.

If you wonder why the double underscore is used instead of a traditional curly bracket,
that's because I had problem with my editor (phpstorm) when reformatting the css.

Basically: when I reformat such a code with phpstorm:

```html
#mydiv{
    color: {theme_color};
}
```

Then when I use my reformat shortcut, it breaks up the code and do something ugly with it.
Since I reformat my code every 10 seconds, it really annoyed me and I found that 
double underscores wrapping is reformatting friendly; here is how it looks like:

```html
#mydiv{
    color: __theme_color__;
}
```






Then, theming is deciding the name of the tags. 

A tag related to theme should start with the "theme_" prefix.

Then, we have to decide the names of the theme variables.

For instance, theme_bg_color represents the main background color, theme_color represents the 
main font color.

If you use a convention, then you will be able to use all the components that use this convention.

Here is my own convention, and if you use it too, then we will be able to re-use each others components!


With this convention, you have to decide for yourself what's main, and what's alternate,
based on common sense.

- colors
    - theme_bg_color: the main background color
    - theme_bg_color_alt: the alternate background color
    - theme_bg_color_alt2: the second alternate background color 
    - theme_color: the main text color
    - theme_color_alt: the alternate text color
    - theme_color_alt2: the second alternate text color
- font type
    - theme_font_family: the main font family
    - theme_font_family_alt: the alternate font family
- font size
    - theme_font_size: the default font size
    - theme_font_size_small: a smaller font size
    - theme_font_size_small2: an even smaller font size
    - theme_font_size_big: a bigger font size
    - theme_font_size_big2: an even bigger font size
    - (tables have their own system, see more in the table section)
- table
    - theme_cellpadding: the main cellpadding value    
    - theme_table_border_color: the border color of the tables 
    - theme_th_bg_color: the main background color for the table head (th)
    - theme_th_font_size: the main font size used for the table head (th) 
    - theme_td_font_size: the main font size used for the table content (td) 
    - theme_td_font_size_small: a small font size used inside the table content (td) 
- images
    - theme_logo: the content of the src attribute of the logo (see examples for concrete example)
    - theme_logo_width: if specified, the absolute width of the logo; otherwise the logo natural width will be used
    
    
- By default, all theme variables start with the "theme_" prefix.    
- By default, all text variables (variables belonging to the template) start with the "text_" prefix.    
    
    
    

Using tcpdf
===============
So at some point you have to create an html template for your component.
The problem is that you have to use tcpdf's html, which is 
a very small subset of what browsers can do.
It has its own rules.

Here are a few things that astonished me or caused me some troubles:

1. p#first   (not just #first) 
2. table#mytable tr td    (not just table#mytable td) 
3. Do not repeat your rules: rules replaces themselves instead of merging 
4. An inline style will override ALL the rules in a style tag 
5. Don't put whitespace after your opening tag 


Using styles
---------------
1. To target an element with an id, put the name of the tag before the 
sharp symbol (do p#first, and not #first), otherwise it won't work.

2. To target a td of a table, you need to write the intermediate tr.
In other words, "table td" won't work, while "table tr td" will.

3. If you write a rule, write it only once, because rules seems to replace themselves
rather than merging.


For instance if you write this:

```html
div#doo {
    color: red;
}
div#doo {
    font-weight: bold;
}
```

Then in the end, the #doo div will only have font weight to bold (the color will not be red).


4. An inline style will override ALL the rules in a style tag

So if you write this code, notice the border=0.5 set on the table tag...

```html
<style>
    table#invoice_summary_table {
        border: 1px solid black;
    }

    table#invoice_summary_table tr th {
        text-align: center;
        font-weight: bold;
        font-size: 9px;
        background-color: __theme_bg_color__;
        border: none;
    }
</style>

<table id="invoice_summary_table" border="0.5">
    <tr>
        <th>__text_invoice_number__</th>
        <th>__text_invoice_date__</th>
        <th>__text_order_reference__</th>
        <th>__text_order_date__</th>
    </tr>
    <tr>
        <td>__invoice_number__</td>
        <td>__invoice_date__</td>
        <td>__order_reference__</td>
        <td>__order_date__</td>
    </tr>
</table>
```

... then the th tags will have a border as well.




5. Don't put whitespace after your tags.

Whitespace after opening tags will mess up the indentation of the next line,
see the discussion here:
http://stackoverflow.com/questions/42129383/tcpdf-indentation-issue-after-line-break/42134914#42134914











