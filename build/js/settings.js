( function( $, undefined ) {

    var $tabsWrap = $( '#repoupdater-settings' ),
        $tabs = $( 'ul.tabs > li', $tabsWrap ),
        $currentTab,
        $panels = $( 'div.panels > *', $tabsWrap );

    $tabs.on( 'click', 'a', function( e ) {
        var $link = $( this ).blur(),
            sHash = $link.attr( 'href' ),
            $panel;

        e.preventDefault();

        // don't do anything if the click is for the tab already showing
        if ( $link.is( '.active a' ) )
            return false;

        // set hash
        window.location.hash = sHash;

        // links
        $( 'a.active', $tabs ).removeClass( 'active' );
        $link.addClass( 'active' );

        $panel = $( sHash );

        // panels
        $panels.not( $panel ).removeClass( 'active' ).hide();
        $panel.addClass( 'active' ).show();
    } );

    // current tab
    if ( window.location.hash ) $currentTab = $tabs.find( 'a[href="' + window.location.hash + '"]' );
    if ( !$currentTab || !$currentTab.length ) $currentTab = $tabs.first().find( 'a' );

    $currentTab.trigger( 'click' );

} )( jQuery );