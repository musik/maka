;(function($){
$.use('web-datalazyload', function(){
FE.util.datalazyload.register({
containers : $('#doc')
});
});
})(jQuery);

/*create by xuetao on 2014.02.26 for ����ҳ���ع�ͳ�� */
;(function($){
var lazyheight = 0,
windowHight = 0,
windowScrollTop = 0,
// ģ���Ƿ���ر�ʾ
moduleMap = {},
hightMap = {},
spma,
spmb,
offer_ids = new Array(),
member_ids = new Array(),
modelNodes,
// offer url��������
offerreg = new RegExp('^http:\/\/(detail|detailp4p)\.1688\.com\/offer\/\\d+\.htm(\l)?$'),
// ���� url��������
memberreg = new RegExp('^http:\/\/\\w+\.1688\.com\/page\/(\\w|\/)+\.htm(\l)?$'),
// ���� url��������
memberreg2 = new RegExp('^http:\/\/(\\w+)(\.cn)?\.1688\.com(\/)?$'),
// ���̹ؼ���
pinDaoKeyWord = ["nongye","fuzhuang","fushi","nengyuan","wanju","nanzhuang","xie","gangcai","neiyi","quan","jia","bao","tao","mei","you","yang","guanjia","qiao","ba","renzheng","fangzhi","zhaobu","POP","PF","MS","huagong","e","Steel","Down","Packaging","Home","Health","Food","Computer","Construction","www","industry","industrial","Shipping","textile","PC","PAI","Energy","Apparel"],
clock,
windowvar;
//domreadyԤ����
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
// spma����spmbΪ�ղ�����
if(!isNotNull(spma) || !isNotNull(spmb)){
return;
}
// �ع��һ������ʼ��Ԥ����
showload(windowHight, windowScrollTop, true);
});
// �ع�
function showload(windowHight, windowScrollTop, isDomReady){
if(isNotNull(spma) && isNotNull(spmb)){
$.each(modelNodes,function(i,val){
var valvalue = $(val);
var valspm = valvalue.data('spm');
// Ԥ���� ��ʼ��
if(isDomReady){
moduleMap[valspm] = valspm;
}
if(isNotNull(valspm)){
lazyheight = parseFloat(windowHight) + parseFloat(windowScrollTop);
if(isNotNull(moduleMap[valspm])){
//ȷ����IE6��offsetTopҲ����ȡ����ȷ��ֵ
hightMap[valspm] = val.offsetTop;
if(windowHight >= valvalue.height()){
hightMap[valspm] = val.offsetTop + valvalue.height() / 2;
}else{
hightMap[valspm] = val.offsetTop + windowHight / 2;
}
var offerIdsTemp = {};
var	memberIdsTemp = {};
// ����Ļ�ײ��߶ȴ��������ع��ٽ��ſ�ʼ�ع�
if(lazyheight >= hightMap[valspm]){
// ��ȡ��ǰ���������е�a��ǩ
valvalue.find("a").each(function(){
// ȥ��url�ڵĲ���
var urlNotParam = getUrlNotParam($(this).attr('href'));
// �����ж��������ڵ��̻���offer
var typeFlag = checkUrl(urlNotParam);
// ƴ��ͳ�Ƴ�����offerIds����memberIds
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
// �����ع�js�ӿ�
var offerjsonpStr = getStrsFromJsonp(offerIdsTemp);
if(offerjsonpStr != ""){
clickStatPre("offer", offerjsonpStr, valspm, isDomReady);
}
var memberjsonpStr = getStrsFromJsonp(memberIdsTemp);
if(memberjsonpStr != ""){
clickStatPre("member", memberjsonpStr, valspm, isDomReady);
}
// �����ظ��ع�
moduleMap[valspm] = null;
}
}
}
});
}
}
//��ȡjs���ӿ�
function clickStatPre(cltype, objectIds, spmc, isDomReady){
var param = new StringBuffer();
param.append("spm=" + spma+ "." + spmb+ "." + spmc).append("&object_ids="+objectIds);
if(cltype==='offer'){
param.append("&object_type=offer");
}else{
param.append("&object_type=member");
}
// ֻ��Ԥ����ʱ �ӳ�5��ִ���ع�
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
//����У��url����
function checkUrl(urlvalue) {
var typeFlag = "";
if (offerreg.test(urlvalue)) {
typeFlag = "offer";
}else if (memberreg.test(urlvalue) || memberreg2.test(urlvalue)) {
typeFlag = "member";
}
return typeFlag;
}
//��ȡurl��"?"��ǰ���ִ�
function getUrlNotParam(url) {
var theRequest = "";
if (isNotNull(url) && url.indexOf("?") != -1) {
theRequest = url.substring(0, url.indexOf("?"));
}else{
theRequest = url;
}
return theRequest;
}
//��url�л�ȡofferId
function getOfferIdFromUrl(url) {
var regx = new RegExp('offer\/(\\d+)\.html$');
var r = regx.exec(url);
if (r!=null) {
return unescape(r[1]);
}
return null;
}
//��url�л�ȡmemberId
function getMemberIdFromUrl(url) {
var regx = new RegExp('^http:\/\/(\\w+)(\.cn)?\.1688');
var r = regx.exec(url);
if (r!=null) {
return unescape(r[1]);
}
return null;
}
//ƴ��objectIds����
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
//ȥ��Ƶ���ؼ���
function removePinDaoKeyword(objectId) {
var result = "";
if(isNotNull(objectId) && arrayFindString(pinDaoKeyWord, objectId) === -1){
result = objectId;
}
return result;
}
// ��������������ַ���
function arrayFindString(arr, string) {
var str = "," + arr.join(",") + ",";
return str.indexOf(","+string+",");
}
// �ַ���������
function StringBuffer() {
var arr = new Array;
this.append = function(str) {
arr[arr.length] = str;
return this;
};
this.toString = function() {
return arr.join("");   //��append����������ping��һ���ַ���
};
}
// �ж��Ƿ�Ϊ��
function isNotNull(string) {
if(typeof(string)==="undefined" || string===null || string===""){
return false;
}
return true;
}
//�����������¼�����
$(window).bind("scroll", function(){
// ��ն�ʱ��
if (clock){
clearTimeout(clock);
}
// ÿ�δ��������»�ȡ����ģ��߶�ֵ
clock = setTimeout(function(){
windowvar = $(window);
windowHight = windowvar.height();
windowScrollTop = windowvar.scrollTop();
showload(windowHight, windowScrollTop, false);
}, 200);
});
})(jQuery);
