;(function($){
$.use('web-datalazyload', function(){
FE.util.datalazyload.register({
containers : $('#doc')
});
});
})(jQuery);

/*create by xuetao on 2014.02.26 for 盒子页面曝光统计 */
;(function($){
var lazyheight = 0,
windowHight = 0,
windowScrollTop = 0,
// 模块是否加载标示
moduleMap = {},
hightMap = {},
spma,
spmb,
offer_ids = new Array(),
member_ids = new Array(),
modelNodes,
// offer url规则正则
offerreg = new RegExp('^http:\/\/(detail|detailp4p)\.1688\.com\/offer\/\\d+\.htm(\l)?$'),
// 店铺 url规则正则
memberreg = new RegExp('^http:\/\/\\w+\.1688\.com\/page\/(\\w|\/)+\.htm(\l)?$'),
// 店铺 url规则正则
memberreg2 = new RegExp('^http:\/\/(\\w+)(\.cn)?\.1688\.com(\/)?$'),
// 店铺关键字
pinDaoKeyWord = ["nongye","fuzhuang","fushi","nengyuan","wanju","nanzhuang","xie","gangcai","neiyi","quan","jia","bao","tao","mei","you","yang","guanjia","qiao","ba","renzheng","fangzhi","zhaobu","POP","PF","MS","huagong","e","Steel","Down","Packaging","Home","Health","Food","Computer","Construction","www","industry","industrial","Shipping","textile","PC","PAI","Energy","Apparel"],
clock,
windowvar;
//domready预加载
$(function() {
if (window.exposureCrazyBox===true){
return;
}
window.exposureCrazyBox = true;
windowvar = $(window);
windowHight = windowvar.height();
windowScrollTop = windowvar.scrollTop();
modelNodes = $("div[data-spm]");
if($("meta[name='data-spm']").length<=0 || modelNodes.length <= 0){
return;
}
spma = $("meta[name='data-spm']")[0].content;
spmb = $("body").attr("data-spm");
// spma或者spmb为空不加载
if(!isNotNull(spma) || !isNotNull(spmb)){
return;
}
// 曝光第一屏并初始化预加载
showload(windowHight, windowScrollTop, true);
});
// 曝光
function showload(windowHight, windowScrollTop, isDomReady){
if(isNotNull(spma) && isNotNull(spmb)){
$.each(modelNodes,function(i,val){
var valvalue = $(val);
var valspm = valvalue.data('spm');
// 预加载 初始化
if(isDomReady){
moduleMap[valspm] = valspm;
}
if(isNotNull(valspm)){
lazyheight = parseFloat(windowHight) + parseFloat(windowScrollTop);
if(isNotNull(moduleMap[valspm])){
//确认在IE6下offsetTop也可以取到正确的值
hightMap[valspm] = val.offsetTop;
if(windowHight >= valvalue.height()){
hightMap[valspm] = val.offsetTop + valvalue.height() / 2;
}else{
hightMap[valspm] = val.offsetTop + windowHight / 2;
}
var offerIdsTemp = {};
var	memberIdsTemp = {};
// 当屏幕底部高度大于区块曝光临界点才开始曝光
if(lazyheight >= hightMap[valspm]){
// 获取当前区块下所有的a标签
valvalue.find("a").each(function(){
// 去除url内的参数
var urlNotParam = getUrlNotParam($(this).attr('href'));
// 正则判断链接属于店铺或者offer
var typeFlag = checkUrl(urlNotParam);
// 拼接统计出来的offerIds或者memberIds
if(isNotNull(typeFlag) ){
if(typeFlag === "offer"){
var offer_id = getOfferIdFromUrl(urlNotParam);
if(isNotNull(offer_id))  {
offerIdsTemp[offer_id] = offer_id;
}
}else if(typeFlag === "member") {
var member_id = removePinDaoKeyword(getMemberIdFromUrl(urlNotParam));
if(isNotNull(member_id))  {
memberIdsTemp[member_id] = member_id;
}
}
}
});
// 触发曝光js接口
var offerjsonpStr = getStrsFromJsonp(offerIdsTemp);
if(offerjsonpStr != ""){
clickStatPre("offer", offerjsonpStr, valspm, isDomReady);
}
var memberjsonpStr = getStrsFromJsonp(memberIdsTemp);
if(memberjsonpStr != ""){
clickStatPre("member", memberjsonpStr, valspm, isDomReady);
}
// 避免重复曝光
moduleMap[valspm] = null;
}
}
}
});
}
}
//调取js打点接口
function clickStatPre(cltype, objectIds, spmc, isDomReady){
var param = new StringBuffer();
param.append("spm=" + spma+ "." + spmb+ "." + spmc).append("&object_ids="+objectIds);
if(cltype==='offer'){
param.append("&object_type=offer");
}else{
param.append("&object_type=member");
}
// 只有预加载时 延迟5秒执行曝光
if(isDomReady){
setTimeout(function(){
if(isNotNull(window.dmtrack)){
window.dmtrack.clickstat("http://stat.1688.com/spmexp.html",param.toString());
}
},5000);
}else{
if(isNotNull(window.dmtrack)){
window.dmtrack.clickstat("http://stat.1688.com/spmexp.html",param.toString());
}
}
}
//正则校验url规则
function checkUrl(urlvalue) {
var typeFlag = "";
if (offerreg.test(urlvalue)) {
typeFlag = "offer";
}else if (memberreg.test(urlvalue) || memberreg2.test(urlvalue)) {
typeFlag = "member";
}
return typeFlag;
}
//获取url中"?"符前的字串
function getUrlNotParam(url) {
var theRequest = "";
if (isNotNull(url) && url.indexOf("?") != -1) {
theRequest = url.substring(0, url.indexOf("?"));
}else{
theRequest = url;
}
return theRequest;
}
//从url中获取offerId
function getOfferIdFromUrl(url) {
var regx = new RegExp('offer\/(\\d+)\.html$');
var r = regx.exec(url);
if (r!=null) {
return unescape(r[1]);
}
return null;
}
//从url中获取memberId
function getMemberIdFromUrl(url) {
var regx = new RegExp('^http:\/\/(\\w+)(\.cn)?\.1688');
var r = regx.exec(url);
if (r!=null) {
return unescape(r[1]);
}
return null;
}
//拼接objectIds参数
function getStrsFromJsonp(objectIds) {
var result = "";
var i = 0;
for(var o in objectIds){
if(i === 0 ){
result = o;
}else{
result += ","+ o;
}
i++;
}
return result;
}
//去除频道关键字
function removePinDaoKeyword(objectId) {
var result = "";
if(isNotNull(objectId) && arrayFindString(pinDaoKeyWord, objectId) === -1){
result = objectId;
}
return result;
}
// 查找数组包含的字符串
function arrayFindString(arr, string) {
var str = "," + arr.join(",") + ",";
return str.indexOf(","+string+",");
}
// 字符串处理函数
function StringBuffer() {
var arr = new Array;
this.append = function(str) {
arr[arr.length] = str;
return this;
};
this.toString = function() {
return arr.join("");   //把append进来的数组ping成一个字符串
};
}
// 判断是否为空
function isNotNull(string) {
if(typeof(string)==="undefined" || string===null || string===""){
return false;
}
return true;
}
//滚动条滚动事件触发
$(window).bind("scroll", function(){
// 清空定时器
if (clock){
clearTimeout(clock);
}
// 每次触发都重新获取各个模块高度值
clock = setTimeout(function(){
windowvar = $(window);
windowHight = windowvar.height();
windowScrollTop = windowvar.scrollTop();
showload(windowHight, windowScrollTop, false);
}, 200);
});
})(jQuery);
