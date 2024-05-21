<div class="bloque_central" >

    
    <div class="busquador arriba">
        <form>
            <input id="busquedaInput" type="text" placeholder="¿Qué cancion quieres buscar?">
        </form>

        <br>
        
        <div class="header_resultados">
            <span>#</span>
            <span>Titulo </span>
            <span>Album </span>
            <span>Duracion </span>
        </div>
        
        <hr class="separador">

    </div>

    <div>



        <?php
            require("resultados_busqueda.php");
        ?>

    </div>

</div>