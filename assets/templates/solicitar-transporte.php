<?php 

function solicitar_transporte() { 

    $html = '

    <link rel="stylesheet" type="text/css" href="..\vendor\bootstrap\css\bootstrap.min.css" />
    <script src="..\vendor\bootstrap\js\bootstrap.min.js"></script>

    <div>
        <div class="container">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                <label class="form-check-label" for="inlineRadio1">1</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                <label class="form-check-label" for="inlineRadio2">2</label>
            </div>    
        </div>
    </div>
    ';
    return $html;
} 

add_shortcode( 'transporte', 'solicitar_transporte' );



//[foobar]
function foobar_func( $atts ){
	return "foo and bar";
}
add_shortcode( 'foobar', 'foobar_func' );


?>