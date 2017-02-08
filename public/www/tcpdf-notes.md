Tcpdf notes
===============
2017-02-07



Tcpdf can interpret HTML, using the writeHTML method.
So why not creating your pdf using html?




Tables
============

Here is a list of attributes recognized by tcpdf (6.2.13),
they work on table, tr, th, td, unless otherwise specified.




- border: number, size of the border, can be less than 1
- cellspacing: number, the amount of space between adjacent cells. If 0, it's equivalent to border-collapse: collapse.
- cellpadding: number, the padding of the cell
- bgcolor: color, the background color
- color: color, the font color
- align: right|left|center, the alignment of the th or td                    
- colspan: same as html attribute                    
- rowspan: same as html attribute                    




 
 

<table data-bgcolor="TemplateBGColor" style="table-layout:fixed;margin:0 auto;" data-module="FinalCalculationsModule-4ROWS" cellspacing="0" cellpadding="0" border="0" bgcolor="#384855" align="center" width="100%">
	<td align="center">
				<table data-bgcolor="TemplateBGColor" class="table600Min" style="table-layout:fixed;margin:0 auto;min-width:668px;" cellspacing="0" cellpadding="0" border="0" bgcolor="#384855" align="center" width="668">
					<tr>
						<td class="table600st" style="min-width:668px;" align="center">
							<table class="table600Min" style="min-width:629px;" cellspacing="0" cellpadding="0" border="0" align="center" width="629">

