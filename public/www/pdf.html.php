<style>

    .square {
        position: absolute;
        width: 30px;
        height: 30px;
        background-color: red;
        color: white;
    }

    #div1 {
        top: 0;
        left: 0;
    }

    #div2 {
        top: 0;
        right: 0;
    }

    #div3 {
        right: 0;
        bottom: 0;
    }

    #div4 {
        bottom: 0;
        left: 0;
    }

    .rectangle {
        left: 0px;
        position: absolute;
        height: 30px;
        background-color: green;
        color: white;
    }

    #div5 {
        width: 100%;
        top: 30px;
    }

    #div6 {
        width: 50%;
        top: 60px;
    }

    #div7 {
        position: static;
        width: 50%;
        color: black;
        background: yellow;
        margin: 0 auto; /* does not center the div as expected, how to center divs?*/
    }

    #div8 {
        width: 717px;
        top: 90px;
        background: violet;
    }

    #div9 {
        width: 600px;
        top: 120px;
        background: orange;
    }

    #div10 {
        width: 30px;
        height: 1167px;
        top: 0px;
        left: 30px;
        background: black;
        color: white;
    }

    table {
        border: 1px solid black;
        border-collapse: collapse;
        margin-left: 200px;
    }

    table tr,
    table td {
        border: 1px solid black;
    }

    table td {
        padding: 5px;
    }
</style>
<div id="div1" class="square">Coucou</div>
<div id="div2" class="square">Coucou</div>
<div id="div3" class="square">Coucou</div>
<div id="div4" class="square">Coucou</div>


<div id="div5" class="rectangle">Coucou</div>
<div id="div6" class="rectangle">Coucou</div>
<div id="div7" class="rectangle">Coucou</div>
<div id="div8" class="rectangle">Coucou</div>
<div id="div9" class="rectangle">Coucou</div>

<div id="div10" class="rectangle">Coucou</div>


<table>
    <tr>
        <td>Adresse facturation</td>
        <td>
            6 rue port feu hugon<br>
            37000 TOURS
        </td>
    </tr>
    <tr>
        <td>Adresse livraison</td>
        <td>
            6 rue port feu hugon<br>
            37000 TOURS
        </td>
    </tr>
</table>