( function( $, undefined ) {

    $( document ).ready( function() {

        if ( repoupdaterData == undefined || !Object.keys( repoupdaterData ).length ) return;

        var sHook = 'plugin.php';
        if ( $( 'body' ).hasClass( 'update-core-php' ) ) sHook = 'update-core.php';

        var $pluginTr;

        $.each( repoupdaterData, function( sBasename, sURL) {
            if ( sHook == 'plugin.php' ) $pluginTr = $( 'tr.plugin-update-tr[data-plugin="' + sBasename + '"]');
            else $pluginTr = $( '#update-plugins-table').find( 'input[name="checked[]"][value="' + sBasename + '"]' )
                .closest( 'tr' );

            $pluginTr.find( 'a.thickbox' ).removeClass( 'thickbox' )
                .attr( 'href', sURL )
        } );

    } );

} )( jQuery );