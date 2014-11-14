$(function() {
  $(".mod-zhishu .f-tab-t").hover(function() {
    $('.mod-zhishu .f-tab-b').hide()
    $('.f-'+ $(this).attr('id')).show()
    $('.mod-zhishu .f-tab-t.current').removeClass("current")
    $(this).addClass("current")
  },
  function() {
  })
});
