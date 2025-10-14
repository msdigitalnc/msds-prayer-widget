(function($){
  $(document).ready(function(){

    function trapFocus($container){
      var $focusable = $container.find('a,button,input,select,textarea,[tabindex]:not([tabindex="-1"])').filter(':visible');
      var $first = $focusable.first();
      var $last  = $focusable.last();
      $container.on('keydown.trap', function(e){
        if (e.key === 'Tab') {
          if (e.shiftKey && document.activeElement === $first[0]) { e.preventDefault(); $last.trigger('focus'); }
          else if (!e.shiftKey && document.activeElement === $last[0]) { e.preventDefault(); $first.trigger('focus'); }
        }
      });
    }

    function releaseFocus($container){ $container.off('keydown.trap'); }

    function toggle(open){
      var $btn = $('#msds-pw-button'),
          $pop = $('#msds-pw-popup'),
          $ov  = $('#msds-pw-overlay');

      var isOpen = !$pop.attr('hidden');
      var shouldOpen = (typeof open === 'boolean') ? open : !isOpen;

      if (shouldOpen) {
        $pop.removeAttr('hidden').attr('aria-hidden','false');
        $ov.removeAttr('hidden');
        $btn.attr('aria-expanded','true');
        trapFocus($pop);
        setTimeout(function(){
          var $firstField = $pop.find('input,select,textarea,button,a').filter(':visible').first();
          if ($firstField.length) { $firstField.trigger('focus'); }
        }, 100);
      } else {
        $pop.attr('hidden', true).attr('aria-hidden','true');
        $ov.attr('hidden', true);
        $btn.attr('aria-expanded','false');
        releaseFocus($pop);
      }
    }

    $(document).on('click', '#msds-pw-button', function(e){ e.preventDefault(); toggle(); });
    $(document).on('click', '#msds-pw-close', function(e){ e.preventDefault(); toggle(false); });
    $(document).on('click', '#msds-pw-overlay', function(){ toggle(false); });
    $(document).on('keydown', function(e){ if (e.key === 'Escape') { toggle(false); } });
  });
})(jQuery);
