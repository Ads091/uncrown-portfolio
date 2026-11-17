/* Project editor: pick the demo video from the WordPress Media Library. */
(function ($) {
  'use strict';
  $(document).on('click', '.ctp-pick-video', function (event) {
    event.preventDefault();
    var target = document.getElementById($(this).data('target'));
    var frame = wp.media({ title: 'Choose a demo video', library: { type: 'video' }, button: { text: 'Use this video' }, multiple: false });
    frame.on('select', function () {
      var file = frame.state().get('selection').first().toJSON();
      if (target) target.value = file.url;
    });
    frame.open();
  });
}(jQuery));
