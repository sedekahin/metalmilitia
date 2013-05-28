( function( $ ) {

$( function() {
  // data is echoed out into meta elements at the top of the page
  var message = $( 'meta[property="exitpopup:message"]' ).attr( 'content' ),
      url = $( 'meta[property="exitpopup:url"]' ).attr( 'content' );

  if ( message && url ) {
    window.onbeforeunload = function( e ) {
      // remove self as a handler
      window.onbeforeunload = null;

      var e = e || window.event;

      // Safari does the redirect if the user stays on page
      // Firefox 4 does the redirect in the background whilst the user is choosing
      // Chrome never does the redirect
      window.location = url;

      if ( e ) {
        e.returnValue = message;
      }

      return message;
    };

    // don't show exit popup if user follows a link or submits a form
    $( 'form' ).submit( function() { window.onbeforeunload = null; } );
    $( 'a' ).click( function() { window.onbeforeunload = null; } );
  }
} );

} )( jQuery );
