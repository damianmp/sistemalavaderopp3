$(document).ready(function(){
  $( "form" ).each( function () {
    if($(this).attr('id') == "cargarRopaSucia"){
        $( this ).bind( "submit", function (event) {
            var r = window.confirm("estas seguro que deseas borrar las cantidades de ropa hospitalaria");
            if(r == false){
                event.preventDefault();
            }
        });
    }
});
});