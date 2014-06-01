$(function() {
  $(".f-tab-t").hover(function() {
    gr = $(this).parent().parent()
    gr.find('.f-tab-b').hide()
    $('.f-'+ $(this).attr('id')).show()
    $('.f-tab-t.current').removeClass("current")
    $(this).addClass("current")
  },
  function() {
  })
});
