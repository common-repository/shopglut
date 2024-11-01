jQuery(".shopglut__shortcode-selectable").click(function (e) {
  e.preventDefault();

  // Get the text from the span
  var copyText = jQuery(this).find(".shopglut_lcopy-text").text();

  copyText = copyText.replace(/\s+/g, " ").trim();

  // Create a hidden input to facilitate copying
  var input = jQuery("<input>");
  input.val(copyText).appendTo("body").select();
  document.execCommand("copy");
  input.remove();

  // Your animation code
  jQuery(".shopglut-after-copy-text").animate(
    {
      opacity: 1,
      bottom: 25,
    },
    300
  );
  setTimeout(function () {
    jQuery(".shopglut-after-copy-text").animate(
      {
        opacity: 0,
      },
      200
    );
    jQuery(".shopglut-after-copy-text").animate(
      {
        bottom: 0,
      },
      0
    );
  }, 2000);
});
