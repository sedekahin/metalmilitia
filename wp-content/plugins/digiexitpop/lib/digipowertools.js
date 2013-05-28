jQuery.fn.append_sidebar_ad = function( plugin ) {
  var self = this;

  jQuery.get( ajaxurl, { action: 'dpt_sidebar_ad', plugin: plugin }, function( ad ) {
    if ( !ad ) return;

    self.each( function() {
      var title = jQuery( '<h3>' ).html( ad.title ).css( {
            'background': '#e04', 'color': '#fff', 'text-shadow': '#900 0 1px 0' } ),
          content = jQuery( '<div>' ).addClass( 'submitbox' ).append(
            jQuery( '<div>' ).addClass( 'inside' ).html( ad.content ) );

      jQuery( '<div class="stuffbox digiresults-ad">' )
        .append( title )
        .append( content )
        .appendTo( this );
    } );
  } );

  return this;
}

jQuery.fn.keep_in_view = function( spacing ) {
  if ( spacing == null ) spacing = 30;

  this.each( function() {
    var $element = jQuery( this ),
        initial_offset = $element.offset().top;

    jQuery( window ).scroll( function() {
      var offset = Math.max( jQuery( window ).scrollTop() + spacing - initial_offset, 0 );
      $element.stop().animate( { marginTop: offset + 'px' }, 'fast' );
    } );
  } );

  return this;
}
