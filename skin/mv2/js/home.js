$(function() {
  $(".f-tab-t").hover(function() {
    $('.f-tab-c').hide()
    $('.f-'+ $(this).attr('id')).show()
    $('.f-tab-t.current').removeClass("current")
    $(this).addClass("current")
  },
  function() {
  })
});
