<?php

if ( isset( $_GET[ "tel_to_test" ] ) ) {
    $tel_to_test = $_GET[ "tel_to_test" ];
    if ( empty( $tel_to_test ) ) {
        echo  "false";
    } $nigeria_tel_regex = "/^(70|80|81|90)\d{8}$/i";
		//The following IF blocks checks for the known phone number lengths, checks their prefixes which are: "0", none, "+234" and "234" accordingly and use the regex pattern above to validate the rest.
		if ( strlen( $tel_to_test ) == 11 ) {
			if ( substr( $tel_to_test, 0, 1 ) == 0 && preg_match( $nigeria_tel_regex, substr( $tel_to_test, 1 ) ) ) {
				echo "true1";
			}
		} else if ( strlen( $tel_to_test ) == 10 ) {
			if ( preg_match( $nigeria_tel_regex, $tel_to_test ) ) {
				echo "true2";
			}
		} else if ( strlen( $tel_to_test ) == 14 ) {
			if ( substr( $tel_to_test, 0, 4 ) == "+234" && preg_match( $nigeria_tel_regex, substr( $tel_to_test, 4 ) ) ) {
				echo "true3";
			}
		} else if ( strlen( $tel_to_test ) == 13 ) {
			if ( substr( $tel_to_test, 0, 3 ) == "234" && preg_match( $nigeria_tel_regex, substr( $tel_to_test, 3 ) ) ) {
				echo "true4";
			}
		}
    else {
        echo "false5";
    }
		
	}
?>